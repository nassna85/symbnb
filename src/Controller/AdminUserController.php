<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminUserType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminUserController extends AbstractController
{
    /**
     * Permet d'afficher tous les utilisateurs
     * @Route("/admin/users", name="admin_user_index")
     * @param UserRepository $repo
     * 
     * @return Response
     */
    public function index(UserRepository $repo)
    {
        return $this->render('admin/user/index.html.twig', [
            'users' => $repo->findAll()
        ]);
    }

    /**
     * Permet de modifier un utilisateur
     * 
     * @Route("/admin/user/{id}/edit", name="admin_user_edit")
     * 
     * @param User $user
     * @param Request $request
     * @param ObjectManager $manager
     *
     * @return Response
     */
    public function edit(User $user, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(AdminUserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted()  && $form->isValid())
        {
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications pour l'utilisateur <strong>{$user->getFullName()}</strong> ont bien été modifiées"
            );

            return $this->redirectToRoute("admin_user_index");
        }

        
        return $this->render('admin/user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * Permet de supprimer un utilisateur
     * 
     * @Route("/admin/user/{id}/delete", name="admin_user_delete")
     * 
     * @param User $user
     * @param ObjectManager $manager
     * 
     * @return Response
     */
    public function delete(User $user, ObjectManager $manager)
    {
        if(count($user->getBookings()) > 0)
        {
            $this->addFlash(
                'warning',
                "Vous ne pouvez pas supprimer un utilisateur qui possède des réservations !"
            );
        }
        else
        {
            $manager->remove($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'utilisateur a bien été supprimé !"
            );
        }

        return $this->redirectToRoute("admin_user_index");
    }
}
