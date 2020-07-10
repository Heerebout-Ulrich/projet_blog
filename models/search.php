<?php

function search($text){
$bdd=dbconnect();
$query=$bdd->prepare("SELECT articles.id, titre, resume, articles.contenu, articles.date, articles.illustration, articles.alt_illustration, articles.alt_header_illustra, articles.id_auteur, nick, COUNT(case when statut = 1 then 1 END) AS nb_commentaires, nom_categ, categories.id as categories_id FROM commentaires RIGHT JOIN articles ON commentaires.id_article = articles.id INNER JOIN admin ON articles.id_auteur = admin.id INNER JOIN categories ON id_categ = categories.id WHERE UCASE(articles.contenu) LIKE UCASE('%$text%') GROUP BY articles.id ORDER BY articles.id DESC"); 
$query->execute(array($text));
$search = $query->fetchAll();
return $search;
}

?>