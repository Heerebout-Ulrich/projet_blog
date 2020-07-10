<?php
require "models/admin.php";

////////////////////////////////////
// Vérification si c'est un administrateur
////////////////////////////////////

function is_admin(){
    
if(!empty($_SESSION['admin'])){
    return true;
}

else { return false; }
}

////////////////////////////////////
// Page Identifiction Administration
////////////////////////////////////

function login_admin(){

if(is_admin() == true){
header("location:index.php?action=administration");
}
   
if(!empty($_POST)){

$mail = htmlspecialchars($_POST['mail']);
$password = htmlspecialchars($_POST['password']);

$infoadmin = getAdmin($mail);

  if($mail = $infoadmin['email'] and password_verify($password, $infoadmin['password']) ){
  	$_SESSION['admin']['email'] = $infoadmin['email']; 
  	$_SESSION['admin']['id'] = $infoadmin['id']; 
  	$_SESSION['admin']['nick'] = $infoadmin['nick']; 

  header("location:index.php?action=administration");    
  }

  else { $message  = "<span class='messageError'><i class='fas fa-exclamation-triangle'></i> Problème identifiant ou mot de passe</span>"; }

  }

$template = "www/admin/login.phtml";
require "www/layout_admin.phtml";
}

////////////////////////////////////
// Accueil - Adminstration
////////////////////////////////////


function page_administration(){

$btitre = "Administration - Instant Partage";
$bdesc = "Administration - Instant Partage";

if(is_admin() == false){
header("location:index.php?action=login_admin");
}    

$template = "www/admin/index.phtml";
require "www/layout_admin.phtml";

}

////////////////////////////////////
// Edition profil Administration
////////////////////////////////////

function edit_profil_admin(){

if(is_admin() == false){
header("location:index.php?action=administration");
}

$id = $_SESSION['admin']['id'];  
$profil = getAdminByID($id);

    
if(!empty($_POST['pseudo'])){

$email = htmlspecialchars($_POST['email']);
$nick = htmlspecialchars($_POST['pseudo']);    

    if(!empty($_POST['password'] || $_POST['password-bis'] )){

    $password = htmlspecialchars($_POST['password']);
    $password2 = htmlspecialchars($_POST['password-bis']);

        if($password == $password2){
        $pass = password_hash($password, PASSWORD_DEFAULT); 
        $result = update_admin_password($pass,$id);
          if($result){
          $message = "<span class='messageOK'>Le mot de passe à été modifié</span>";
          }
          else { $message = "<span class='messageError'>Erreur lors de la modification</span>"; }
        }

        else { $message = "<span class='messageError'>Les mots de passe ne sont pas identiques</span>"; }
    }

if(empty($_FILES['avatar']['name']) ){

$avatar = htmlspecialchars($_POST['avatar_bis']);
$result = update_admin_profil($avatar,$nick,$email,$id);

  if($result){
    $message = "<span class='messageOK'>les modifications ont été effectuées</span>";
  }

  else { $message = "<span class='messageError'>Erreur lors de la modification</span>"; }

}
       
    // Gestion de l'image du profil
    if( !empty($_FILES['avatar']['name']) ){

      unlink($profil['avatar']);

      // Constantes
      define('TARGET', 'www/images/avatar/');    // Repertoire cible
      define('MAX_SIZE', 100000);    // Taille max en octets du fichier
      define('WIDTH_MAX', 90);    // Largeur max de l'image en pixels
      define('HEIGHT_MAX', 90);    // Hauteur max de l'image en pixels
       
      // Tableaux de donnees
      $tabExt = array('jpg','png','jpeg');    // Extensions autorisees
      $infosImg = array();
       
      // Variables
      $extension = '';
      $message = '';
      $nomImage = '';
      $rand = rand(0,100000);

      // Recuperation de l'extension du fichier
      $extension  = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
      // On verifie l'extension du fichier
      if(in_array(strtolower($extension),$tabExt)){
        // On recupere les dimensions du fichier
        $infosImg = getimagesize($_FILES['avatar']['tmp_name']);        
        // On verifie le type de l'image
        if($infosImg[2] >= 1 && $infosImg[2] <= 14)
        {
          // On verifie les dimensions et taille de l'image
          if(($infosImg[0] <= WIDTH_MAX) && ($infosImg[1] <= HEIGHT_MAX) && (filesize($_FILES['avatar']['tmp_name']) <= MAX_SIZE)){
            // Parcours du tableau d'erreurs
            if(isset($_FILES['avatar']['error'])  && UPLOAD_ERR_OK === $_FILES['avatar']['error']){                    
              // On renomme le fichier
              $nomImage = $_SESSION['admin']['nick'] .'_'.$rand.'.'.$extension;       
              // Si c'est OK
              if(move_uploaded_file($_FILES['avatar']['tmp_name'], TARGET.$nomImage)){
               $avatar = TARGET.$nomImage;
               $result = update_admin_profil($avatar,$nick,$email,$id);

              if($result){
              $message = "<script type='text/javascript'>document.location='index.php?action=edit_profil_admin';</script>";
              }
              else { $message = "<span class='messageError'>Erreur lors de la modification</span>"; }
              }
              // Sinon on affiche une erreur upload
              else { $message = '<span class="messageError">Problème lors de l\'upload !</span>'; }
            }
            else { $message = '<span class="messageError">Une erreur interne a emp&ecirc;ché l\'uplaod de l\'image</span>'; }
          }
          // Sinon erreur sur les dimensions et taille de l'image
          else { $message = '<span class="messageError">Erreur dans les dimensions ou le poids de l\'image !</span>'; }
                                      
        }
        // Sinon erreur sur le type de l'image
        else { $message = '<span class="messageError">Le fichier &agrave; uploader n\'est pas une image !</span>'; }
      }
      // Sinon on affiche une erreur pour l'extension
      else { $message = '<span class="messageError">L\'extension du fichier est incorrecte !</span>'; }
    } 
    else { $avatar = htmlspecialchars($_POST['avatar_bis']); }
}

$template = "www/admin/profil.phtml";
require "www/layout_admin.phtml";

}

////////////////////////////////////
// Fermeture de la session Administrateur / Utilisateur
////////////////////////////////////

function deconnect(){
    
if(isset($_SESSION['admin'])){
    $_SESSION=array();
    session_unset();
    session_destroy();
}  

if(isset($_SESSION['user'])){
    $_SESSION=array();
    session_unset();
    session_destroy();
}  

header ('Location: index.php');
}  

?>