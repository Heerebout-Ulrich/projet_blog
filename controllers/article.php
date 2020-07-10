<?php

require "models/article.php";

////////////////////////////////////
// Affichage de tous les articles
////////////////////////////////////

function All_Articles(){

$titre_ref = getsetting('title_ref_gen');
$desc_ref = getsetting('desc_ref_gen');

$btitre = $titre_ref['setting_value'];
$bdesc = $desc_ref['setting_value'];

$nb_articles = compteurs_all_articles();

$total_article = $nb_articles['total'];

$article_par_page = getsetting('nb_article_home');
$messagesParPage = $article_par_page['setting_value'];

$pagination = pagination($total_article,$messagesParPage);
$premiereEntree = $pagination['premiereEntree'];

$articles = Pagination_All_Articles($premiereEntree,$messagesParPage);

$last_articles = lastArticles();

$menuright = SousCategByParent(0);

$template = "www/pages/home.phtml";
require "www/layout.phtml";

}


////////////////////////////////////
// Affichage complet d'une page article
////////////////////////////////////

function Affichage_Article(){

$id = $_GET['id'];

$article = displayArticleById($id);
$listecommentaires = CommentsArticle($id); 
$articlessuggest = lastArticles();

$ref_article = displayArticleById($_GET['id']);
$listecategories = DisplayAllCategorie();

$menuright = SousCategByParent(0);

$gallery = SelectGalleryByArticle($id);

$btitre = $ref_article['ref_titre']." - Instant Partage";
$bdesc = $ref_article['ref_description'];

$template = "www/article/article.phtml";
require "www/layout.phtml";

}


////////////////////////////////////
// gestion article via l'administration
////////////////////////////////////


function gestion_article_admin(){

$nb_articles = compteurs_all_articles();

$total_article = $nb_articles['total'];
$messagesParPage = 50;

$pagination = pagination($total_article,$messagesParPage);
$premiereEntree = $pagination['premiereEntree'];

$listearticles = Pagination_All_Articles($premiereEntree,$messagesParPage);

$template = "www/admin/article.phtml";
require "www/layout_admin.phtml";

}


////////////////////////////////////
// Ajouter article via l'administration
////////////////////////////////////


function ajouter_article_admin(){

$listecategories = DisplayAllCategorie();

if(isset($_POST['contenu'])){ 

$titrearticle = $_POST['titre'];
$resumearticle = $_POST['resume'];
$contenuarticle = $_POST['contenu'];
$infos_sup = $_POST['infos_sup'];
$map = $_POST['map'];
$image = $_FILES['image']['name'];
$header_illustration = $_FILES['header_illustration']['name'];
$alt_illustration = $_POST['alt_illustration'];
$auteurarticle = $_SESSION['admin']['id'];
$categarticle = $_POST['categ'];
$ref_titre = $_POST['titre-ref'];
$ref_description = $_POST['description-ref'];
$alt_header_illustration = $_POST['alt_header_illustra'];
$uploads_dir="www/images/article/";    

    if (!empty($_FILES['header_illustration']['name'])){
        $tmp_name=$_FILES['header_illustration']['tmp_name'];
        $name=$_FILES['header_illustration']['name'];
        move_uploaded_file($tmp_name,"$uploads_dir/$name");    
    }  

    if (!empty($_FILES['image']['name'])){
        $tmp_name=$_FILES['image']['tmp_name'];
        $name=$_FILES['image']['name'];
        move_uploaded_file($tmp_name,"$uploads_dir/$name");    
    } 

    $result = addArticle($titrearticle, $resumearticle, $contenuarticle, $infos_sup, $map, $auteurarticle, $categarticle, $image, $alt_illustration, $header_illustration, $alt_header_illustration, $ref_titre, $ref_description);

     if($result){
        $message="<span class='messageOK'>L'article a été pris en compte</span>";
                /*header('Refresh: 3; URL=index.php?action=gestion_articles'); */  
      }
      else{ $message="<span class='messageError'>L'article n'a pas été pris en compte</span>"; }
}

$template = "www/admin/article.phtml";
require "www/layout_admin.phtml";

}

////////////////////////////////////
// effacer article via l'adminsitration
////////////////////////////////////

function effacer_article_admin(){

if(isset($_GET['delete'])){

$result = DeleteArticle($_GET['delete']);

 if($result){
    $message="<span class='messageOK'>L'article à été supprimé</span>";
 }
 else{ $message="<span class='messageError'>L'article n'a pas été supprimé</span>"; }

header('Refresh: 3; URL=index.php?action=gestion_articles');     
}   

$template = "www/admin/article.phtml";
require "www/layout_admin.phtml";

}

////////////////////////////////////
// modifier article via l'adminsitration
////////////////////////////////////


function editer_article_admin(){

    $listecategories = DisplayAllCategorie();
    $articles = displayArticleById($_GET['edit_id']);

    if(!empty($_POST)){

        $titrearticle = $_POST['titre'];
        $resumearticle = $_POST['resume'];
        $contenuarticle = $_POST['contenu'];
        $infos_sup = $_POST['infos_sup'];
        $map = $_POST['map'];
        $id_auteur = $_SESSION['admin']['id'];
        $image = $_FILES['image']['name'];
        $alt_illustration = $_POST['alt_illustration'];
        $header_illustration = $_FILES['header_illustration']['name'];
        $id_article = $_GET['edit_id'];
        $id_categ = $_POST['categ'];
        $ref_titre = $_POST['titre-ref'];
        $ref_description = $_POST['description-ref'];
        $alt_header_illustration = $_POST['alt_header_illustra'];

        $uploads_dir="www/images/article/";  

        if (!empty($_FILES['header_illustration']['name'])){
            $tmp_name=$_FILES['header_illustration']['tmp_name'];
            $name=$_FILES['header_illustration']['name'];
            move_uploaded_file($tmp_name,"$uploads_dir/slider/$name");
            $header_illustration = "$uploads_dir/slider/$name";   
        }  

        else { $header_illustration = $_POST['header_illustration_bis']; }

        if (!empty($_FILES['image']['name'])){
            $tmp_name=$_FILES['image']['tmp_name'];
            $name=$_FILES['image']['name'];
            move_uploaded_file($tmp_name,"$uploads_dir/vignette/$name");
            $image = "$uploads_dir/vignette/$name";      
        }  

        else {  $image = $_POST['image_bis']; }

        $result = editArticle($titrearticle, $resumearticle, $contenuarticle, $infos_sup, $map, $id_auteur, $id_categ, $image, $alt_illustration, $header_illustration, $alt_header_illustration, $ref_titre, $ref_description, $id_article);


//Affiche d'un message de l'état de la variable $result;
         if($result){
            $message="<span class='messageOK'>L'article à été modifié avec succes</";
            header('Refresh: 3; URL=index.php?action=gestion_articles');   
          }
          else{ $message="<span class='messageError'>L'article n'a pas été modifié</span>"; }

    }

$template = "www/admin/article.phtml";
require "www/layout_admin.phtml";


}

////////////////////////////////////
// Affichage en Ajax du slider
////////////////////////////////////

                          
function ajax_random_article(){

$slider = random_article_Ajax();

$output = [];

foreach ($slider as $article) {

$path = DisplayCategForArticle($article['id']);

$addtooutput["id"] = $article["id"];
$addtooutput["format_titre"] = format_url($article["titre"]);
$addtooutput["titre"] = $article["titre"];
$addtooutput["date"] = $article["date"];
$addtooutput["id_categ"] = $article["id_categ"];
$addtooutput["illustration"] = $article["illustration"];
$addtooutput["alt_illustration"] = $article["alt_illustration"];
$addtooutput["nom_categ"] = $article["nom_categ"];
$addtooutput["categ"] = $article["categ"];
$addtooutput["path"] = $path;

$output[] = $addtooutput;
}

echo json_encode($output);

}

?>