<?php
require "models/search.php";

////////////////////////////////////
// Site - Affichage Recherche
////////////////////////////////////   

function recherche(){
$btitre = "Page de recherche - Instant Partage";
$bdesc = "Page de recherche - Instant Partage";

if(!empty($_POST['recherche'])){
$text = htmlspecialchars($_POST['recherche']); 
$recherches = search($text);
}
$template = "www/pages/search.phtml";
require "www/layout.phtml";  
}

?>