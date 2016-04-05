// Fonction permettant de choisir un fichier uploadé basé sur l'année, à partir du click d'un bouton
function upload_au_click(annee)
{
    // On change l'année visée par l'upload
    $('#formulaire_upload_annee').val(annee);

    // Et on trigger un click
    $('#formulaire_upload_fichier').trigger('click');
}

// Détection du choix d'un fichier pour l'upload
$('#formulaire_upload_fichier').change(function()
{ 
    // On simule un click sur le bouton submit
    $('#formulaire_upload').submit(); 
});