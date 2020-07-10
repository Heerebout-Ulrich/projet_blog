<?php

require "models/page.php";

////////////////////////////////////
// Administration - Affichages des pages
////////////////////////////////////   

function gestion_pages_admin(){

$pages = displaypage(); 

$template = "www/admin/page.phtml";
require "www/layout_admin.phtml";

}

////////////////////////////////////
// Site - Affichages des pages
////////////////////////////////////   

function afficher_page(){

$page = page_by_id($_GET['id']);
$btitre = $page['titre_ref'];
$bdesc = $page['desc_ref'];

if (!empty($_POST['contact'])){

    $sujet = 'Message du formulaire Instant-Partage';
    $msg = "Nom :".htmlspecialchars($_POST['contact']['nom'])." <br>Message : ".htmlspecialchars($_POST['contact']['msg'])." <br>Email : ".htmlspecialchars($_POST['contact']['email']);
    $email = htmlspecialchars($_POST['contact']['email']);
    $destinataire = "contact@ulrich-web.fr";


    $headers = "From: \"expediteur moi\"<contact@ulrich-web.fr>\n";
    $headers .= "Reply-To: contact@ulrich-web.fr\n";
    $headers .= "Content-Type: text/html; charset=\"iso-8859-1\"";

    if(mail($destinataire,$sujet,$msg,$headers)){
       $message = "<span class='messageOK'>L'email a bien été envoyé.</span>"; }
    else { $message = "<span class='messageError'>Une erreur c'est produite lors de l'envois de l'email.</span>"; }

}

$template = "www/pages/page.phtml";
require "www/layout.phtml";

}

////////////////////////////////////
// Administration - Création d'une page.
////////////////////////////////////   

function ajouter_page(){

if(!empty($_POST)){ 

$titrepage = htmlspecialchars($_POST['titre']);
$contenupage = $_POST['contenu'];

$ref_titre = htmlspecialchars($_POST['titre-ref']);
$ref_description = htmlspecialchars($_POST['description-ref']);
$page = format_url($titrepage);

$result = addpage($titrepage, $contenupage, $ref_titre, $ref_description, $page);

if($result){
 $message="<span class='messageOK'>La page à été créer</span>";}
 else{ $message="<span>erreur lors de la création de la page</span>"; }
}

$template = "www/admin/page.phtml";
require "www/layout_admin.phtml";

}

////////////////////////////////////
// Administration - Edition d'une page.
////////////////////////////////////   

function editer_page(){

if(isset($_GET['id'])){

if(!empty($_POST)){ 

$id = $_GET['id'];

$titrepage = htmlspecialchars($_POST['titre']);
$contenupage = $_POST['contenu'];

$ref_titre = htmlspecialchars($_POST['titre-ref']);
$ref_description = htmlspecialchars($_POST['description-ref']); 
$page = format_url($_POST['page']);

$result = editpage($titrepage, $contenupage, $ref_titre, $ref_description, $page, $id);

 if($result){
    $message="<span class='messageOK'>La page à été modifier</span>";
    header('Refresh: 3; URL=index.php?action=gestion_pages_admin'); }
    else{ $message="<span class='messageError'>erreur lors de la modification de la page</span>";
    header('Refresh: 3; URL=index.php?action=gestion_pages_admin');    
    }
 }

}

else { $infosp = page_by_id($_GET['id_page']); }

$template = "www/admin/page.phtml";
require "www/layout_admin.phtml";

}

////////////////////////////////////
// Administration - Suppression des pages.
////////////////////////////////////   

function effacer_page(){

if(isset($_GET['delete'])){

$result = DeletePage($_GET['delete']);

 if($result){
    $message="<span class='messageOK'>La page à été supprimé</span>"; }
 else{ $message="<span class=Error'>erreur lors de la suppretion de la page</span>"; }
}

$template = "www/admin/page.phtml";
require "www/layout_admin.phtml";

}

?>