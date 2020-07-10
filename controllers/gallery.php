<?php

require "models/gallery.php";

////////////////////////////////////
// Affichage de la Gallerie
////////////////////////////////////


function display_gallery(){

$btitre = "Photos - Instant Partage";
$bdesc = "Photos - Instant Partage"; 

$template = "www/pages/gallery.phtml";
require "www/layout.phtml"; 

}

////////////////////////////////////
// Affichage de la gallerie Image Admin
////////////////////////////////////

function display_gallery_admin(){

$nb_images = Count_Gallery();

$total_images = $nb_images['total'];
$messagesParPage = 10;

$pagination = pagination($total_images,$messagesParPage);
$premiereEntree = $pagination['premiereEntree'];

$gallerys = All_Images_Pagination($premiereEntree,$messagesParPage);


$template = "www/admin/gallery.phtml";
require "www/layout_admin.phtml"; 

}

////////////////////////////////////
// Ajouter une image dans la Gallerie
////////////////////////////////////

function add_image_gallery(){


$articles = DisplayAllArticle(); 

if (!empty($_FILES['fileUpload']['name'])){ 

$files_path = "www/images/gallerie";
$files_large = "www/images/gallerie/large";
$files_medium = "www/images/gallerie/medium";
$files_small = "www/images/gallerie/small";

$id_article = $_POST['article'];
$id_categ = displayArticleById($id_article);

for($i=0;$i<count($_FILES['fileUpload']['name']);$i++){

$tmp_name=$_FILES['fileUpload']['tmp_name'][$i];
$file_name=strtolower($_FILES['fileUpload']['name'][$i]);
$file_name = str_replace(' ', '_', $file_name);
$titre = $_POST['title'][$i];

move_uploaded_file($tmp_name,"$files_path/$file_name");  

$img_large = resize_img("$files_path/$file_name","$files_large/$file_name",900,0);
$img_medium = resize_img("$files_path/$file_name","$files_medium/$file_name",320,9);
$img_small = resize_img("$files_path/$file_name","$files_small/$file_name",200,9); 

  if($img_large == "no_need_to_resize"){ $files_large = $files_path; }

  $result = add_photo_gallery($titre, "$files_path/$file_name", "$files_large/$file_name", "$files_medium/$file_name", "$files_small/$file_name", $id_article);

     if($result){
        $message="<span class='messageOK'>L'image a été envoyée avec succès</span>";
      }
      else{ $message="<span class='messageError'>Erreur dans l'upload de l'image</span>"; }
  }

}


$template = "www/admin/gallery.phtml";
require "www/layout_admin.phtml"; 
 } 

 ////////////////////////////////////
// Effacer image de la Gallerie
////////////////////////////////////

function remove_image_gallery(){

if(!empty($_POST['check'])){

  for($i=0;$i<count($_POST['check']);$i++){
  $id =  $_POST['check'][$i];
  $infos = SelectGalleryByID($id);

  if (file_exists($infos['image_small'])){
  unlink($infos['image_small']);
  }
  if (file_exists($infos['image_large'])){
  unlink($infos['image_large']);
  }
  if (file_exists($infos['image_medium'])){
  unlink($infos['image_medium']);
  }
  if (file_exists($infos['source'])){
  unlink($infos['source']);
  }

  $result = DeleteImagesGalleryByID($id);

    if($result){
    $message="<span class='messageOK'>L'image a était supprimée</span>";
    header('Refresh: 3; URL=index.php?action=gallery_admin');
    }
    else{ $message="<span class='Error'>erreur</span>"; }

  }

}

$template = "www/admin/gallery.phtml";
require "www/layout_admin.phtml"; 

}

////////////////////////////////////
// Editer une image dans la Gallerie
////////////////////////////////////

function edit_image_gallery(){

$id = $_GET['id_img'];

$articles = DisplayAllArticle();
$infos = SelectGalleryByID($id);


if($_POST){

$id_article = $_POST['article'];
$categ = displayArticleById($id_article);
$id_categ = $categ['id_categ'];
$desc_img = $_POST['desc'];
$id = $_GET['id_img'];


$files_path = "www/images/gallerie";
$files_large = "www/images/gallerie/large";
$files_medium = "www/images/gallerie/medium";
$files_small = "www/images/gallerie/small";

    if (!empty($_FILES['img_source']['name'])){
        $tmp_name=$_FILES['img_source']['tmp_name'];
        $name=$_FILES['img_source']['name'];
        move_uploaded_file($tmp_name,"$files_path/$name"); 
        $source = "$files_path/$name";
    }  

    else { $source = htmlspecialchars($_POST['img_source_bis']); }

    if (!empty($_FILES['img_large']['name'])){
        $tmp_name=$_FILES['img_large']['tmp_name'];
        $name=$_FILES['img_large']['name'];
        move_uploaded_file($tmp_name,"$files_large/$name"); 
        $image_large = "$files_large/$name";
    }  

    else { $image_large = htmlspecialchars($_POST['img_large_bis']); }

     if (!empty($_FILES['img_medium']['name'])){
        $tmp_name=$_FILES['img_medium']['tmp_name'];
        $name=$_FILES['img_medium']['name'];
        move_uploaded_file($tmp_name,"$files_medium/$name"); 
        $image_medium = "$files_medium/$name";
    }  

    else { $image_medium = htmlspecialchars($_POST['img_medium_bis']); }
    
     if (!empty($_FILES['img_small']['name'])){
        $tmp_name=$_FILES['img_small']['tmp_name'];
        $name=$_FILES['img_small']['name'];
        move_uploaded_file($tmp_name,"$files_small/$name"); 
        $image_small = "$files_small/$name";
    }  

    else { $image_small = htmlspecialchars($_POST['img_small_bis']); }                      

    $result = editImagesGallery($desc_img,$source,$image_large,$image_medium,$image_small,$id_article,$id);

    if($result){
        $message="<span class='messageOK'>La modification de l'image a été réalisée avec succès</span>";
    }
    else{ $message="<span class='messageError'>Erreur</span>"; }
}

$template = "www/admin/gallery.phtml";
require "www/layout_admin.phtml";  

}

////////////////////////////////////
// Redimension image envoyé dans la gallerie : miniature / vignette / large
////////////////////////////////////   

function resize_img($image_path,$image_dest,$max_size,$qualite,$type = 'auto'){

  // Vérification que le fichier existe
  if(!file_exists($image_path)):
    return 'wrong_path';
  endif;

  if($image_dest == ""):
    $image_dest = $image_path;
  endif;
  // Extensions et mimes autorisés
  $extensions = array('jpg','jpeg','png','gif');
  $mimes = array('image/jpeg','image/gif','image/png');

  // Récupération de l'extension de l'image
  $tab_ext = explode('.', $image_path);
  $extension  = strtolower($tab_ext[count($tab_ext)-1]);

  // Récupération des informations de l'image
  $image_data = getimagesize($image_path);

  // Si c'est une image envoyé alors son extension est .tmp et on doit d'abord la copier avant de la redimentionner
  if($extension == 'tmp' && in_array($image_data['mime'],$mimes)):
    copy($image_path,$image_dest);
    $image_path = $image_dest;

    $tab_ext = explode('.', $image_path);
    $extension  = strtolower($tab_ext[count($tab_ext)-1]);
  endif;

  // Test si l'extension est autorisée
  if (in_array($extension,$extensions) && in_array($image_data['mime'],$mimes)):
    
    // On stocke les dimensions dans des variables
    $img_width = $image_data[0];
    $img_height = $image_data[1];

    // On vérifie quel coté est le plus grand
    if($img_width >= $img_height && $type != "height"):

      // Calcul des nouvelles dimensions à partir de la largeur
      if($max_size >= $img_width):
        return 'no_need_to_resize';
      endif;

      $new_width = $max_size;
      $reduction = ( ($new_width * 100) / $img_width );
      $new_height = round(( ($img_height * $reduction )/100 ),0);

    else:

      // Calcul des nouvelles dimensions à partir de la hauteur
      if($max_size >= $img_height):
        return 'no_need_to_resize';
      endif;

      $new_height = $max_size;
      $reduction = ( ($new_height * 100) / $img_height );
      $new_width = round(( ($img_width * $reduction )/100 ),0);

    endif;

    // Création de la ressource pour la nouvelle image
    $dest = imagecreatetruecolor($new_width, $new_height);

    // En fonction de l'extension on prépare l'iamge
    switch($extension){
      case 'jpg':
      case 'jpeg':
        $src = imagecreatefromjpeg($image_path); // Pour les jpg et jpeg
      break;

      case 'png':
        $src = imagecreatefrompng($image_path); // Pour les png
      break;

      case 'gif':
        $src = imagecreatefromgif($image_path); // Pour les gif
      break;
    }

    // Création de l'image redimentionnée
    if(imagecopyresampled($dest, $src, 0, 0, 0, 0, $new_width, $new_height, $img_width, $img_height)):

      // On remplace l'image en fonction de l'extension
        switch($extension){
        case 'jpg':
        case 'jpeg':
          imagejpeg($dest , $image_dest, 100); // Pour les jpg et jpeg
        break;

        case 'png':
          imagepng($dest , $image_dest, $qualite); // Pour les png
        break;

        case 'gif':
          imagegif($dest , $image_dest, $qualite); // Pour les gif
        break;
      }

      return 'success';
      
    else:
      return 'resize_error';
    endif;

  else:
    return 'no_img';
  endif;
}

?>