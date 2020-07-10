<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

require "models/global.php";

function config_general(){

$article_par_page = getsetting('nb_article_home'); 
$article_par_categorie = getsetting('nb_article_categorie');
$title_ref_gen = getsetting('title_ref_gen');
$desc_ref_gen = getsetting('desc_ref_gen'); 
$nb_comments_admin = getsetting('nb_comments_admin');

if($_POST){

    $nb_article_home = $_POST['nb_article_home'];
    $nb_article_categ = $_POST['nb_article_categ'];
    $title_ref_gen = $_POST['title_ref_gen'];
    $desc_ref_gen = $_POST['desc_ref_gen'];
    $nb_comments_admin = $_POST['nb_comment_admin'];

    editsetting($nb_article_home,'nb_article_home');
    editsetting($nb_article_categ,'nb_article_categorie');
    editsetting($title_ref_gen,'title_ref_gen');
    editsetting($desc_ref_gen,'desc_ref_gen');
    editsetting($nb_comments_admin,'nb_comments_admin');

    header("location:index.php?action=config_general");

}

$template = "www/admin/config-general.phtml";
require "www/layout_admin.phtml";


}

function pagination($nb_articles,$messagesParPage){

$nombreDePages=ceil($nb_articles/$messagesParPage);

if(isset($_GET['page'])){
    // Si la variable $_GET['id'] existe...
 $pageActuelle=intval($_GET['page']);
 
     if($pageActuelle>$nombreDePages){
        // Si la valeur de $pageActuelle (le numéro de la page) est plus grande que $nombreDePages...
      $pageActuelle=$nombreDePages;
     }
}

else { $pageActuelle=1; } // La page actuelle est la n°1    

$premiereEntree=($pageActuelle-1)*$messagesParPage;

return array("pageActuelle" => $pageActuelle, "nombreDePages" => $nombreDePages, "premiereEntree" => $premiereEntree);
}



function PaginationDisplay($pagination,$url){

echo '<section id="pagination" class="clear"><h6>Pagination :</h6>';
echo '<ul>';

for($i=1; $i<=$pagination['nombreDePages']; $i++){

    if($i==$pagination['pageActuelle']){
    echo ' <li class="pagination-list-active">[ '.$i.' ]</li> '; 
    }
    else { echo '<li class="pagination-list"><a href="'.$url.$i.'/">'.$i.'</a> </li>'; }
}

echo '</ul>';    
echo'</section>';

}


function PaginationNoSQL($source,$total,$messagesParPage){

 //On récupère le total pour le placer dans la variable $total.
$page = !empty( $_GET['page'] ) ? (int) $_GET['page'] : 1;

/// NOMBRE PAR PAGE

$offset = ($page - 1) * $messagesParPage;
$cute = array_slice( $source, $offset, $messagesParPage); 

return $cute;
}

function DisplayPaginationNoSQL($source,$total,$messagesParPage){

$nombreDePages=ceil($total/$messagesParPage);


if(isset($_GET['page'])) // Si la variable $_GET['page'] existe...
{
     $page=intval($_GET['page']);
 
     if($page>$nombreDePages){ // Si la valeur de $page (le numéro de la page) est plus grande que $nombreDePages...    
      $page=$nombreDePages;
     }

$premiereEntree=($page-1)*$messagesParPage; // On calcul la première entrée à lire
     
}
else // Sinon
{
$page=1; // La page actuelle est la n°1    

$premiereEntree=0; // On calcul la première entrée à lire

} 

echo '<section id="pagination" class="clear"><h6> Pagination :</h6>';
echo '<ul>';
 
if($page !=1){

$pageprecedente = ($page-1);

echo'<li class="pagination-list"><a href="index.php?action=categorie&name='.$_GET['name'].'&page='.$pageprecedente.'">&laquo;</a></li> ';}

    for($i=1; $i<=$nombreDePages; $i++){ 
    echo '<li class="pagination-list-active">[ '.$i.' ]</li>';
    }

if($page<$nombreDePages){ // Si la valeur de $page (le numéro de la page) est plus grande que $nombreDePages...
        
$pagesuivante = ($page+1);

echo'<li class="pagination-list"><a href="index.php?action=categorie&name='.$_GET['name'].'&page='.$pagesuivante.'">&raquo;</a></li> ';
}
echo '</ul>';
echo '</section>';
}

function affichage_nav_pages(){

$pages = displaypage();

echo "<ul>";

foreach ($pages as $page) {
 echo "<li><a href='".$page['page']."'>".$page['nom']."</a></li>";
}

echo "</ul>";

}

////////////////////////////////////
// Prise en charge global de la newsletter
////////////////////////////////////
if (isset($_POST["news"])){
$suscribe = $_POST["news"];
add_NewsLetter($suscribe);
}
////////////////////////////////////
// Creer un fichier csv avec les mails saisis.
////////////////////////////////////

function add_NewsLetter($mail){
        
$file = fopen("www/admin/newsletter/list_newsletter.csv","a");

fputcsv($file,array($mail));
fclose($file);

}

function format_url($str){
    $str = mb_strtolower($str);
    $str = utf8_decode($str);
    $str = strtr($str, utf8_decode('àâäãáåçéèêëíìîïñóòôöõøùúûüýÿ'), 'aaaaaaceeeeiiiinoooooouuuuyy');
    $str = preg_replace('`[^a-z0-9]+`', '-', $str);
    $str = trim($str, '-');
    return $str;
}

// MENU HORIZONTALE
function menu(){

$menu = SousCategByParent(0);
    $tab = []; 
    $tab2 = [];
    $tab3 = [];

    foreach ($menu as $nom_categ){

        if ($nom_categ['cat_parent'] == "0" and  sous_categ_exist($nom_categ['id']) == FALSE){
        echo '<li><a href="'.$nom_categ['categ'].'/">'.$nom_categ['nom_categ'].'</a></li>';
        array_push($tab, $nom_categ['id']); 
        }

        elseif ($nom_categ['cat_parent'] == "0" and sous_categ_exist($nom_categ['id']) == TRUE){
            array_push($tab, $nom_categ['id']);

            echo '<li class="dropdown"><a href="'.$nom_categ['categ'].'/">'.$nom_categ['nom_categ'].'</a>';  
            echo '<ul class="dropdown-menu">';

            $sous_menus = SousCategByParent($nom_categ['id']); 

            foreach ($sous_menus as $sous_menu){
                
                if (sous_categ_exist($sous_menu['id']) == FALSE){

                echo '<li><a href="'.format_url($nom_categ['nom_categ']).'/'.$sous_menu['categ'].'/">'.$sous_menu['nom_categ'].'</a></li>';
                array_push($tab2, $sous_menu['id']);

                }
                
                elseif (sous_categ_exist($sous_menu['id']) == TRUE){
                    array_push($tab2, $sous_menu['id']); 

                    echo '<li class="dropdown"><a href="'.format_url($nom_categ["nom_categ"]).'/'.$sous_menu['categ'].'/">'.$sous_menu['nom_categ'].'</a>';

                    echo '<ul class="dropdown-menu">';

                    $sous_menus2 = SousCategByParent($sous_menu['id']);

                    foreach ($sous_menus2 as $sous_menu2){
                        echo '<li><a href="'.format_url($nom_categ['nom_categ']).'/'.$sous_menu['categ'].'/'.$sous_menu2['categ'].'/">'.$sous_menu2['nom_categ'].'</a></li>';
                        array_push($tab3, $sous_menu2['id']);
                    }   

                    echo '</ul>';
                    echo '</li>';       
                }   
            }       
        echo '</ul>';
        echo '</li>';       
        }

    }    

}

function currentURL() {
    $host     = $_SERVER['SERVER_NAME'];
    $query    = $_SERVER['REQUEST_URI'];
    return 'https://'.$host.$query;
}

function SeoTitre() {

if (empty($btitre)){
$title_ref_gen = getsetting('title_ref_gen'); 
$btitre = $title_ref_gen['setting_value']; 
echo '<title>'.$btitre.'</title>';
}
else{ echo '<title>'.$btitre.'</title>'; }
}

function SeoDescription() {
// j'ai trouvé que cette indentation pour mettre la balise meta description a la ligne.
if (empty($bdesc)){
$desc_ref_gen = getsetting('desc_ref_gen');
$bdesc = $desc_ref_gen['setting_value'];
echo '
    <meta name="description" content="'.$bdesc.'" />
'; 

}
else{ echo '
    <meta name="description" content="'.$bdesc.'" />
'; }

}

?>