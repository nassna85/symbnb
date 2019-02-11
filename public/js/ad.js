$('#add-image').click(function(){
    //Je récupère le numero des futurs champs que je vais créer
    const index = +$('#widgets-counter').val(); //Le + sert a convertir en nombre
    //Je récupère le prototype des entrées
    const tmpl = $('#annonce_images').data('prototype').replace(/__name__/g, index);
    
    //J'injecte ce code au sein de la div

    $('#annonce_images').append(tmpl);

    $('#widgets-counter').val(index + 1);

    //Je gère le button supprimer
    handleDeleteButtons();
});

function handleDeleteButtons()
{
    $('button[data-action="delete"]').click(function(){
        const target = this.dataset.target;
        $(target).remove();
    });
}

function updateCounter()//Cette fonction sert pour la modification d'une annonce! sinon bug
{
    const count = +$('#annonce_images div.form-group').length;

    $('#widgets-counter').val(count);
}

updateCounter();

handleDeleteButtons();