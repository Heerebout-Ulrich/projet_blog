<?php

require "models/comments.php";


////////////////////////////////////
// Traitement des commentaires poster via un article
////////////////////////////////////

function traitement_comments_article(){

$listecommentaires = CommentsArticle($_GET['id']); 

$articles = displayArticleById($_GET['id']); 

$titre = format_url($articles['titre']);
$nom_categ = format_url($articles['nom_categ']);

if(!empty($_POST)){

$idarticle = $_GET['id'];
$usercomment = htmlspecialchars($_POST['user']);
$contenucomment = htmlspecialchars($_POST['contenu']);
$emailcomment = htmlspecialchars($_POST['email']);
$id_user = htmlspecialchars($_POST['id_user']);

$path = DisplayCategForArticle($articles['id']);

$result = add_comment($idarticle, $usercomment, $contenucomment, $emailcomment, $id_user);

    if($result){
      $message  = '<div class="messageOK"><i class="fas fa-check"></i>';
      $message .= '<p>Votre commentaire à été pris en compte</p>';
      $message .= '<p>il est en cours de modération</p>';
      $message .= '<img src="www/images/bx_loader.gif" alt="chargement de la page">';
      $message .= '</div>';
     }
    else{ 
      $message  = '<div class="messageError"><i class="fas fa-exclamation-triangle"></i>';
      $message .= '<p>Votre commentaire n\'a pas été prise en compte</p>';
      $message .= '<img src="www/images/bx_loader.gif" alt="chargement de la page">';
      $message .= '</div>';
    }
}

$template = "www/article/commentaires.phtml";
require "www/layout.phtml";

}

////////////////////////////////////
// Gestion / Modération des commentaires validé via l'admin
////////////////////////////////////

function gestion_commentaires_valide(){

$nb_comments = getsetting('nb_comments_admin');
$messagesParPage = $nb_comments['setting_value']; 
$nb_comments_valid = compteur_commentaires(1);
$total_comments_valid = $nb_comments_valid['nb_commentaires'];
$pagination_valid = pagination($total_comments_valid,$messagesParPage);
$premiereEntree_valid = $pagination_valid['premiereEntree'];
$commentsvalid = Comments_statut(1,$premiereEntree_valid,$messagesParPage);

if(isset($_GET['id_comment'])){

}


if(isset($_POST['statutb'])){
 for($i=0;$i<count($commentsvalid);$i++){
   if(isset($_POST['statutb'][$i] )){
  $result = CommentsUpdateStatut($_POST['statutb'][$i], $_POST['id_com'][$i]);
    if($result){
      $message  = '<div class="messageOK-admin"><i class="fas fa-check"></i>';
      $message .= '<p>Changement de statut réussi</p>';
      $message .= '<img src="www/images/bx_loader.gif" alt="chargement de la page">';
      $message .= '</div>';
      header("Refresh: 3; URL=index.php?action=gestion_commentaires_valide");
     }
    else{ 
      $message  = '<div class="messageError-admin"><i class="fas fa-exclamation-triangle"></i>';
      $message .= '<p>Error lors du changement du statut</p>';
      $message .= '<img src="www/images/bx_loader.gif" alt="chargement de la page">';
      $message .= '</div>';
      header("Refresh: 3; URL=index.php?action=gestion_commentaires_valide");
    }
  }
 }
}

if(!empty($_POST['check'])){
 for($i=0;$i<count($_POST['check']);$i++){
 $result = delete_comments($_POST['check'][$i]);
    if($result){
      $message  = '<div class="messageOK-admin"><i class="fas fa-trash-alt"></i>';
      $message .= '<p>Les commentaires ont été effacés</p>';
      $message .= '<img src="www/images/bx_loader.gif" alt="chargement de la page">';
      $message .= '</div>';
      header("Refresh: 3; URL=index.php?action=gestion_commentaires_valide");
     }
    else{ 
      $message  = '<div class="messageError-admin"><i class="fas fa-exclamation-triangle"></i>';
      $message .= '<p>Error lors de la suppression des commentaires</p>';
      $message .= '<img src="www/images/bx_loader.gif" alt="chargement de la page">';
      $message .= '</div>';
      header("Refresh: 3; URL=index.php?action=gestion_commentaires_valide");
    }
 }
}

$template = "www/admin/commentaire.phtml";
require "www/layout_admin.phtml";

}

////////////////////////////////////
// Gestion / Modération des commentaires en standby via l'admin
////////////////////////////////////


function gestion_commentaires_standby(){

$nb_comments = getsetting('nb_comments_admin');
$messagesParPage = $nb_comments['setting_value']; 

$nb_comments_standby = compteur_commentaires(0);

$total_comments_standby = $nb_comments_standby['nb_commentaires'];

$pagination_standby = pagination($total_comments_standby,$messagesParPage);

$premiereEntree_standby = $pagination_standby['premiereEntree'];

$commentsstandby = Comments_statut(0,$premiereEntree_standby,$messagesParPage);


if(isset($_POST['statut'])){
 for($i=0;$i<count($commentsstandby);$i++){
  if(isset($_POST['statut'][$i])){
  $result = CommentsUpdateStatut($_POST['statut'][$i], $_POST['id_com'][$i]);
    if($result){
      $message  = '<div class="messageOK-admin"><i class="fas fa-check"></i>';
      $message .= '<p>Le(s) commentaire(s) sont valide(s)</p>';
      $message .= '<img src="www/images/bx_loader.gif" alt="chargement de la page">';
      $message .= '</div>';
      header("Refresh: 3; URL=index.php?action=gestion_commentaires_standby");
     }
    else{ 
      $message  = '<div class="messageError-admin"><i class="fas fa-exclamation-triangle"></i>';
      $message .= '<p>Error lors du changement du statut</p>';
      $message .= '<img src="www/images/bx_loader.gif" alt="chargement de la page">';
      $message .= '</div>';
      header("Refresh: 3; URL=index.php?action=gestion_commentaires_standby");
    }
  }
 }
}


elseif(!empty($_POST['check'])){
 for($i=0;$i<count($_POST['check']);$i++){
 $result = delete_comments($_POST['check'][$i]);
    if($result){
      $message  = '<div class="messageOK-admin"><i class="fas fa-trash-alt"></i>';
      $message .= '<p>Les commentaires ont été effacés</p>';
      $message .= '<img src="www/images/bx_loader.gif" alt="chargement de la page">';
      $message .= '</div>';
      header("Refresh: 3; URL=index.php?action=gestion_commentaires_standby");
     }
    else{ 
      $message  = '<div class="messageError-admin"><i class="fas fa-exclamation-triangle"></i>';
      $message .= '<p>Error lors de la suppression des commentaires</p>';
      $message .= '<img src="www/images/bx_loader.gif" alt="chargement de la page">';
      $message .= '</div>';
      header("Refresh: 3; URL=index.php?action=gestion_commentaires_standby");
    }
 }
}

$template = "www/admin/commentaire.phtml";
require "www/layout_admin.phtml";

}

////////////////////////////////////
// Effacer commentaires via l'administration
////////////////////////////////////

function effacer_commentaires_admin(){

if(isset($_GET['delete'])){

$result = delete_comments($_GET['delete']);

    if($result){
      $message  = '<div class="messageOK-admin"><i class="fas fa-trash-alt"></i>';
      $message .= '<p>Le commentaire à été supprimé</p>';
      $message .= '<img src="www/images/bx_loader.gif" alt="chargement de la page">';
      $message .= '</div>';
      header("Refresh: 3; URL=index.php?action=gestion_commentaires_standby");
     }
    else{ 
      $message  = '<div class="messageError-admin"><i class="fas fa-exclamation-triangle"></i>';
      $message .= '<p>Error lors de la suppression des commentaires</p>';
      $message .= '<img src="www/images/bx_loader.gif" alt="chargement de la page">';
      $message .= '</div>';
      header("Refresh: 3; URL=index.php?action=gestion_commentaires_standby");
    }
}
$template = "www/admin/commentaire.phtml";
require "www/layout_admin.phtml";

}

////////////////////////////////////
// Administration - Affichage d'un commentaire dans une popup via Ajax
////////////////////////////////////

function commentaires_id(){

$id = $_GET['id']; 

$commentaire = CommentbyID($id);

$output = [];

$commentairepop["user"] = $commentaire["user"];
$commentairepop["contenu"] = $commentaire["contenu"];
$commentairepop["date"] = $commentaire["date"];
$output[] = $commentairepop;

echo json_encode($output);

}

////////////////////////////////////
// Affichage des commentaires via Ajax
////////////////////////////////////

function ajax_commentaires(){

$listecommentaires = last_comments();

$output = [];

foreach ($listecommentaires as $comment) {

$path = DisplayCategForArticle($comment["id_article"]);

$addtooutput["id"] = $comment["id"];
$addtooutput["user"] = $comment["user"];
$addtooutput["titre"] = $comment["titre"];
$addtooutput["contenu"] = substr($comment["contenu"], 0, 45).'...';
$addtooutput["format_titre"] = format_url($comment["titre"]);
$addtooutput["date"] = $comment["date"];
$addtooutput["id_article"] = $comment["id_article"];
$addtooutput["avatar"] = $comment["avatar"];
$addtooutput["path"] = $path;
$output[] = $addtooutput;

}

echo json_encode($output);

}

?>