<?php

require "models/user.php";

////////////////////////////////////
// Vérification si le membre est connecté.
////////////////////////////////////

function is_connect(){
    
if(!empty($_SESSION['user']['email'])){
    return true;
}

else { return false; }

}

////////////////////////////////////
// Site - Page identification User
////////////////////////////////////

function login_user(){

$btitre = "Page d'identification - Instant Partage";
$bdesc = "Page d'identification - Instant Partage";

if(!empty($_POST)){

$mail = htmlspecialchars($_POST['mail']);
$password = htmlspecialchars($_POST['password']);
$infouser = getUserByEmail($mail);

if($mail = $infouser['user_email'] and password_verify($password, $infouser['user_pass']) ){

$_SESSION['user']['email'] = $infouser['user_email']; 
$_SESSION['user']['login'] = $infouser['user_login'];
$_SESSION['user']['id'] = $infouser['id']; 

  if(isset($_POST['url_courante'])){
    header("Location: ".$_POST['url_courante']);
  }
  else { header('Location: index.php'); }

}
else { 
    $message  = "<span class='messageError'>Problème identifiant ou mot de passe</span>";
    $template = "www/login.phtml";
    }
}

$template = "www/user/login.phtml";
require "www/layout.phtml";

}

////////////////////////////////////
// Site - Création d'un membre.
////////////////////////////////////


function create_account_user(){

$btitre = "Creation d'un compte Membre - Instant Partage";
$bdesc = "Creation d'un compte Membre - Instant Partage"; 

if(!empty($_POST)){
 
 $pseudo = htmlspecialchars($_POST['pseudo']);
 $password = $_POST['password'];
 $password2 = $_POST['password-bis'];
 $email = htmlspecialchars($_POST['email']);
 $avatar = 'www/images/avatar.png';
 $users = getUserByPseudo($pseudo);

// Verifie si le pseudo existe déja.
if($users == FALSE){
      // Verifie si le format du mail est correct.
      if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

              // Verifie si les mots de pass sont identiques.
              if($password == $password2){
                    $mdp = password_hash($password, PASSWORD_DEFAULT);                        
                    if( getUserByEmail($email) == FALSE ){
                      add_user($pseudo, $mdp, $email, $avatar);
                      header('Location: index.php?action=login');
                     }                  
                     else { $message  = "<span class='messageError'>L'adresse mail existe deja</span>"; }
                    }
              else { $message  = "<span class='messageError'>Le mot de passe ne correspond pas</span>"; }
          }
      else { $message  = "<span class='messageError'>Le format du mail n'est pas valide</span>"; }
      }

else { $message  = "<span class='messageError'>Le pseudo existe déja</span>"; }
}

$template = "www/user/register.phtml";
require "www/layout.phtml";

}

////////////////////////////////////
// Site - Edition d'un profil membre
////////////////////////////////////

function profil_user(){

if(is_connect() == false){
header("location:index.php?action=login");
}

$btitre = "Modifier Compte - Instant Partage";
$bdesc = "Modifier Compte - Instant Partage"; 

$id = $_SESSION['user']['id']; 
$profil = getUserByID($id);

if(!empty($_POST['pseudo'])){

$email = htmlspecialchars($_POST['email']);
$nick = htmlspecialchars($_POST['pseudo']);    

        if(!empty($_POST['password'] || $_POST['password-bis'] )){

        $password = htmlspecialchars($_POST['password']);
        $password2 = htmlspecialchars($_POST['password-bis']);

            if($password == $password2){
            $pass = password_hash($password, PASSWORD_DEFAULT); 
            $result = update_user_password($pass,$id);

                if($result){
                $message = "Le mot de passe à été modifié";
                }

                else { $message = "<span class='messageError'>Erreur lors de la modification</span>"; }

            }

            else { $message = "<span class='messageError'>Les mots de passe ne sont pas identiques</span>";}

        }

if(empty($_FILES['avatar']['name']) ){

$avatar = htmlspecialchars($_POST['avatar_bis']);
$result = update_user_profil($avatar,$nick,$email,$id);

if($result){
/*$message = "<script type='text/javascript'> document.location = 'index.php?action=edit_profil_admin'; </script> ";*/
$message = "<span class='messageOK'>les modifications ont été effectuées</span>";
}

else { $message = "<span class='messageError'>Erreur lors de la modification</span>"; }

}
       
    // On verifie si le champ est rempli
    if( !empty($_FILES['avatar']['name']) )
    {

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
      if(in_array(strtolower($extension),$tabExt))
      {
        // On recupere les dimensions du fichier
        $infosImg = getimagesize($_FILES['avatar']['tmp_name']);
   
        // On verifie le type de l'image
        if($infosImg[2] >= 1 && $infosImg[2] <= 14)
        {
          // On verifie les dimensions et taille de l'image
          if(($infosImg[0] <= WIDTH_MAX) && ($infosImg[1] <= HEIGHT_MAX) && (filesize($_FILES['avatar']['tmp_name']) <= MAX_SIZE))
          {
            // Parcours du tableau d'erreurs
            if(isset($_FILES['avatar']['error']) 
              && UPLOAD_ERR_OK === $_FILES['avatar']['error'])
            {
               
              // On renomme le fichier
              $nomImage = $_SESSION['user']['login'] .'_'.$rand.'.'.$extension;
  
              // Si c'est OK
              if(move_uploaded_file($_FILES['avatar']['tmp_name'], TARGET.$nomImage))
              {
               $avatar = TARGET.$nomImage;

               $result = update_user_profil($avatar,$nick,$email,$id);

              if($result){
              $message = "<script type='text/javascript'> document.location = 'index.php?action=profil'; </script> ";
              }

              else { $message = "<span class='messageError'>Erreur lors de la modification</span>"; }


              }
              else
              {
                // Sinon on affiche une erreur systeme
                $message = '<span class="messageError">Problème lors de l\'upload !</span>';
              }
            }
            else
            {
              $message = '<span class="messageError">Une erreur interne a emp&ecirc;ché l\'uplaod de l\'image</span>';
            }
          }
          else
          {
            // Sinon erreur sur les dimensions et taille de l'image
            $message = '<span class="messageError">Erreur dans les dimensions ou le poids de l\'image !</span>';
          }
        }
        else
        {
          // Sinon erreur sur le type de l'image
          $message = '<span class="messageError">Le fichier &agrave; uploader n\'est pas une image !</span>';
        }
      }
      else
      {
        // Sinon on affiche une erreur pour l'extension
        $message = '<span class="messageError">L\'extension du fichier est incorrecte !</span>';
      }
    } 
    else { $avatar = htmlspecialchars($_POST['avatar_bis']); }


}

$template = "www/user/profil.phtml";
require "www/layout.phtml";

}

////////////////////////////////////
// Administration - Listing de tous les membres inscrit.
////////////////////////////////////

function gestion_membres_admin(){

$listeusers = display_user();

$template = "www/admin/user.phtml";
require "www/layout_admin.phtml";

}

////////////////////////////////////
// Administration - Effacer un membre.
////////////////////////////////////

function effacer_membre_admin(){

if(isset($_GET['delete'])){

$result = delete_user($_GET['delete']);

   if($result){
      $message="<span class='messageOK'>Le membre à été supprimé</span>";
      header('Refresh: 3; URL=index.php?action=gestion_membres');
   }
   else{ $message="<span class='messageError'>Le membre n'a pas été supprimé</span>"; }
}

$template = "www/admin/user.phtml";
require "www/layout_admin.phtml";

}

?>