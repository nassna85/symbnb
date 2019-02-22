<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Form\BookingType;
use App\Form\CommentType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookingController extends AbstractController
{
    /**
     * Permet de gerer et d'afficher le formulaire de réservation
     * @Route("/ads/{slug}/book", name="booking_create")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function book(Ad $ad, Request $request, ObjectManager $manager)
    {
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            //On chope l'user connecté
            $user = $this->getUser();

            //On relie la reservation avec l'user connecté et a l'annonce
            $booking->setBooker($user)
                    ->setAd($ad);

            //Si les dates ne sont pas disponible => message d'erreur
            if(!$booking->isBookableDates())
            {
                $this->addFlash(
                    'warning',
                    "Les dates que vous avez choisi ne peuvent être réservés : Elles sont déjà prises !"
                );
            }

            //Sinon enregistrement et redirection
            else
            {
                $manager->persist($booking);
                $manager->flush();

                return $this->redirectToRoute('booking_show', [
                    'id' => $booking->getId(),
                    'withAlert' => true
                ]);
            }
        }

        return $this->render('booking/book.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher la page d'une réservation
     * 
     * @Route("/booking/{id}", name="booking_show")
     * @Security("is_granted('ROLE_USER') and user === booking.getBooker()", message="Vous n'avez pas le droit à regarder les réservations qui ne vous appartiennent pas !")
     *
     * @param Booking $booking
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function show(Booking $booking, Request $request, ObjectManager $manager)
    {

        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $comment->setAd($booking->getAd())
                    ->setAuthor($this->getUser());

            $manager->persist($comment);

            $manager->flush();

            $this->addFlash(
                'success',
                "Votre commentaire a bien été pris en compte !"
            );
        }


        return $this->render('booking/show.html.twig', [
            'booking' => $booking,
            'form' => $form->createView()
        ]);
    }
}
