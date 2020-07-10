<?php

require "models/categories.php";

////////////////////////////////////
// Affichage des categories
////////////////////////////////////

function Affichage_Categ(){

$ref_categ = CategByCateg($_GET['name']);

$btitre = $ref_categ['ref_titre']." - Instant Partage";
$bdesc = $ref_categ['ref_description'];

$article_par_categ = getsetting('nb_article_categorie');
$messagesParPage = $article_par_categ['setting_value'];

$source = MuxCateg($ref_categ['id']);
$total=count($source);
$listearticles = PaginationNoSQL($source,$total,$messagesParPage);

$template = "www/categorie/categories.phtml";
require "www/layout.phtml";

}

////////////////////////////////////
// Gestion des categories via l'administration
////////////////////////////////////

function gestion_categ_admin(){

$listecategories = DisplayAllCategorie();

if($_POST){

$order = $_POST['order'];

    for($i=0;$i<count($order);$i++){
    $orders =$_POST['order'][$i];
    $categ_id = $_POST['id'][$i];
    $result = OrderCategorie($orders,$categ_id);

     if($result){
        $message="<span class='messageOK'>L'ordre des categories ont été modifiés</span>";
      }
      else{ $message="<span class='messageError'>Erreur, Verifiez les informations transmises</span>"; }
    }

}
 
$template = "www/admin/categorie.phtml";
require "www/layout_admin.phtml";  
}   

////////////////////////////////////
// Creer une categorie via l'administration
////////////////////////////////////

function creer_categ_admin(){

if(isset($_POST['nom_categ'])){

    $uploads_dir="www/images/categorie/";   

    $nom_categ = htmlspecialchars($_POST['nom_categ']);
    $ref_titre = htmlspecialchars($_POST['reftitre']);
    $ref_description = htmlspecialchars($_POST['refdescription']);
    $categ = format_url(htmlspecialchars($_POST['reftitre']));
    $illustration = $_FILES['illustration']['name'];
    $id_parent = 0;

    $result = AddCategorie($nom_categ, $illustration, $ref_titre, $ref_description, $id_parent, $categ); 

     if($result){
       $message="<span class='messageOK'>La categorie à été ajouté</span>";
     }
     else{ $message="<span class='messageError'>La categorie n'a pas été ajouté</span>"; }
}

if (!empty($_FILES['illustration']['name'])){
    $tmp_name=$_FILES['illustration']['tmp_name'];
    $name=$_FILES['illustration']['name'];
    move_uploaded_file($tmp_name,"$uploads_dir/$name");    
}     

$template = "www/admin/categorie.phtml";
require "www/layout_admin.phtml";   

}    

////////////////////////////////////
// Edition des categories via l'administration
////////////////////////////////////

function editer_categ_admin(){


if(isset($_GET['edit_categ'])){

$cat = CategById($_GET['edit_categ']); 
$sous_categ = CategById($cat['cat_parent']);

if ($sous_categ['cat_parent'] == null){
    $sous_categ['nom_categ'] = "vide";
}


$listecategories = DisplayAllCategorie(); 

$template = "www/admin/categorie.phtml";
require "www/layout_admin.phtml"; 
}


if(isset($_POST['newname'])){

$uploads_dir="www/images/categorie/";      

$newname = htmlspecialchars($_POST['newname']);
$id_categorie = htmlspecialchars($_POST['id_cat']);
$ref_titre = htmlspecialchars($_POST['reftitre']);
$ref_description = htmlspecialchars($_POST['refdescription']);
$categ = format_url(htmlspecialchars($_POST['categ']));
$illustration = $_FILES['illustration']['name'];
$id_parent= htmlspecialchars($_POST['sous_categ']);

    if (!empty($_FILES['illustration']['name'])){
        $tmp_name=$_FILES['illustration']['tmp_name'];
        $name=$_FILES['illustration']['name'];
        move_uploaded_file($tmp_name,"$uploads_dir/$name");    
    }  

    $result = EditCategorie($newname, $illustration, $ref_titre, $ref_description, $id_parent, $categ, $id_categorie);

    if($result){
        $message="<span class='messageOK'>La categorie à été modifié</span>";
        header('Refresh: 3; URL=index.php?action=gestion_categories');
    }
    else{ $message="<span class='messageError'>La categorie n'a pas été modifié</span>"; }
     
$template = "www/admin/categorie.phtml";
require "www/layout_admin.phtml";  
}

}

////////////////////////////////////
// Suppression d'une categorie via l'administration
////////////////////////////////////

function effacer_categ_admin(){

if(isset($_GET['delete'])){
$result = DeleteCategorie($_GET['delete']);

     if($result){
        $message="<span class='messageOK'>La categorie à été supprimé</span>";
     }
     else{ $message="<span class='messageError'>La categorie n'a pas été supprimé</span>"; }
} 

$template = "www/admin/categorie.phtml";
require "www/layout_admin.phtml";         

}


function sous_categ_exist($id){

$allmenu = DisplayAllCategorie();  

$categ = array_column($allmenu, "cat_parent");

$test = in_array($id, $categ);

return $test;
}

////////////////////////////////////
// Liste toutes les id des sous categuories d'une categorie
////////////////////////////////////

function AllSousCateg($id_categ){


$tabsniveau0 = [];
$tabsniveau1 = [];
$tabsniveau2 = [];

array_push($tabsniveau0, $id_categ); 

if(!empty(SousCategByParent($id_categ))){

    for($a=0;$a<count(SousCategByParent($id_categ));$a++){
                                
    $niveau0 = SousCategByParent($id_categ);

    array_push($tabsniveau1, $niveau0[$a]['id']);
    }

    if(!empty($tabsniveau1)){  
                            
        for($b=0;$b<count($tabsniveau1);$b++){

        $niveau1 = SousCategByParent($tabsniveau1[$b]);
                                
        for($c=0;$c<count($niveau1);$c++){

        array_push($tabsniveau2,$niveau1[$c]['id']);
        }
                            
        }
    }
}

$result = array_merge($tabsniveau0, $tabsniveau1, $tabsniveau2);

return $result;

}

////////////////////////////////////
// Remonte la Liste toutes les id des categuories supérieure d'une categorie
////////////////////////////////////

function DisplayCategForArticle($id_article){

$global = [];

$article = displayArticleById($id_article);

$categ0 = $article['id_categ'];

$name0 = CategById($categ0);

$catprincip = $name0['categ'];

array_push($global,$catprincip);

$parent = CategByCateg($catprincip);

$catsup = CategById($parent['cat_parent']);

$name2 = $catsup['categ'];

array_push($global,$name2);

$parentcateg1 = CategByCateg($name2);

$catsupsup = CategById($parentcateg1['cat_parent']);

$categ2 = $catsupsup['categ'];

if($categ2!= NULL){

array_push($global,$categ2);

}

$globals = array_reverse($global);

$ph = null;

for($i=1;$i<count($globals);$i++){
    if($i>1){ $ph .= '/'; }
        $ph .= $globals[$i];
}

return $ph;

}

////////////////////////////////////
// Liste toutes les id des sous categories d'une categorie
////////////////////////////////////

function MuxCateg($id_categ){

$categs = AllSousCateg("$id_categ");

$global = [];

for($i=0;$i<count($categs);$i++){

$tab = displayArticlesByCateg("$categs[$i]");

foreach ($tab as $vv) {
    array_push($global,$vv);
}


}

return $global;

}

?>