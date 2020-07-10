<?php

function add_photo_gallery($titre, $image_original, $image_large, $image_medium, $image_small, $idarticle){
$bdd=dbconnect();
$query=$bdd->prepare("INSERT INTO gallery (desc_img, source, image_large, image_medium, image_small, id_article) VALUES (?, ?, ?, ?, ?, ?)");
$add_image = $query->execute(array($titre, $image_original, $image_large, $image_medium, $image_small, $idarticle));
return $add_image;
}

function SelectGalleryByArticle($idarticle){
$bdd=dbconnect();
$query=$bdd->prepare("SELECT id,desc_img,source,image_large,image_medium,image_small,id_article FROM gallery WHERE id_article = ?");
$query->execute(array($idarticle));
$gallery=$query->fetchAll();
return $gallery;
}

function SelectGalleryByID($id){
$bdd=dbconnect();
$query=$bdd->prepare("SELECT gallery.id,desc_img,gallery.source,image_large,image_medium,image_small,id_article,articles.titre FROM gallery INNER JOIN articles ON gallery.id_article = articles.id WHERE gallery.id = ?");
$query->execute(array($id));
$gallery=$query->fetch();
return $gallery;
}

function SelectAllImagesGallery(){
$bdd=dbconnect();
$query=$bdd->prepare("SELECT gallery.id,desc_img,articles.titre,source,image_large,image_medium,image_small,gallery.id_article FROM gallery INNER JOIN articles ON gallery.id_article = articles.id");
$query->execute();
$gallery=$query->fetchAll();
return $gallery;
}

function editImagesGallery($desc_img,$source,$image_large,$image_medium,$image_small,$id_article,$id){
$bdd=dbconnect();
$query=$bdd->prepare("UPDATE gallery SET desc_img = ?, source = ?, image_large = ?, image_medium = ?, image_small = ?, id_article = ? WHERE id = ?");
$gallery = $query->execute([$desc_img,$source,$image_large,$image_medium,$image_small,$id_article,$id]) or die(print_r($query->errorInfo(), TRUE));
;
return $gallery;
}

function DeleteImagesGalleryByID($id){
$bdd=dbconnect();
$query=$bdd->prepare("DELETE FROM gallery WHERE id=?");
$query->execute(array($id));
return $query;
}

function Count_Gallery(){
$bdd=dbconnect();
$query=$bdd->prepare("SELECT COUNT(*) AS total FROM gallery"); 
$query->execute();
$nb_images=$query->fetch();
return $nb_images;
}

function All_Images_Pagination($premiereEntree, $messagesParPage){
$bdd=dbconnect();
$query=$bdd->prepare("SELECT gallery.id,desc_img,articles.titre,source,image_large,image_medium,image_small,gallery.id_article FROM gallery INNER JOIN articles ON gallery.id_article = articles.id ORDER BY gallery.id DESC LIMIT $premiereEntree, $messagesParPage");
$query->execute();
$gallery=$query->fetchAll();
return $gallery;
}

?>