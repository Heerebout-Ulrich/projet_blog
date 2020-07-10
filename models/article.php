<?php

// Affichage sans pagination  //
function DisplayAllArticle(){
$bdd=dbconnect();
$query=$bdd->prepare("SELECT articles.id, titre, resume, articles.contenu, articles.date, articles.illustration, articles.alt_illustration, articles.alt_header_illustra, articles.id_auteur, nick, COUNT(case when statut = 1 then 1 END) AS nb_commentaires, nom_categ, categories.id as categories_id FROM commentaires RIGHT JOIN articles ON commentaires.id_article = articles.id INNER JOIN admin ON articles.id_auteur = admin.id INNER JOIN categories ON id_categ = categories.id GROUP BY articles.id ORDER BY articles.id DESC"); 
$query->execute();
$listearticles=$query->fetchAll();
return $listearticles;
}

function compteurs_all_articles(){
$bdd=dbconnect();
$query=$bdd->prepare("SELECT COUNT(*) AS total FROM articles"); 
$query->execute();
$nb_article=$query->fetch();
return $nb_article;
}

function Pagination_All_Articles($premiereEntree,$messagesParPage){
$bdd=dbconnect();
$query=$bdd->prepare("SELECT articles.id, titre, resume, articles.contenu, DATE_FORMAT(articles.date, '%d-%m-%Y') as date, articles.illustration, articles.alt_illustration, articles.header_illustra,articles.alt_header_illustra, articles.id_auteur, nick, COUNT(case when statut = 1 then 1 END) AS nb_commentaires, nom_categ, id_categ, categ FROM commentaires RIGHT JOIN articles ON commentaires.id_article = articles.id INNER JOIN admin ON articles.id_auteur = admin.id INNER JOIN categories ON id_categ = categories.id GROUP BY articles.id ORDER BY articles.id DESC LIMIT $premiereEntree, $messagesParPage"); 
$query->execute();
$triarticle=$query->fetchAll();
return $triarticle;
}

// Creation d'article  //
function addArticle($titrearticle, $resumearticle, $contenuarticle, $infos_sup, $map, $auteurarticle, $categarticle, $image, $alt_illustration, $header_illustra, $alt_header_illustra, $ref_titre, $ref_description){
$bdd=dbconnect();
$query=$bdd->prepare("INSERT INTO articles (titre, resume, contenu, map, infos_sup, date, id_auteur, id_categ, illustration, alt_illustration, header_illustra, alt_header_illustra, ref_titre, ref_description) VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, ?, ?, ?)"); 
$add_article = $query->execute(array($titrearticle, $resumearticle, $contenuarticle, $infos_sup, $map, $auteurarticle, $categarticle, $image, $alt_illustration, $alt_header_illustra, $header_illustra, $ref_titre, $ref_description));
;
return $add_article;
}

// edition d'article
function editArticle($titrearticle, $resumearticle, $contenuarticle, $infos_sup, $map, $id_auteur, $id_categ, $image, $alt_illustration, $header_illustration, $alt_header_illustra, $ref_titre, $ref_description, $id_article){
$bdd=dbconnect();
$query=$bdd->prepare("UPDATE articles SET titre = ?, resume = ?, contenu = ?, infos_sup = ?, map = ?, id_auteur = ?, id_categ = ?, illustration = ?, alt_illustration = ?, header_illustra = ?,alt_header_illustra = ?, ref_titre = ?, ref_description = ? WHERE id = ?");
$query->execute(array($titrearticle, $resumearticle, $contenuarticle, $infos_sup, $map, $id_auteur, $id_categ, $image, $alt_illustration, $header_illustration, $alt_header_illustra, $ref_titre, $ref_description, $id_article)); 
return $query;
}

// effacer article 
function DeleteArticle($id){
$bdd=dbconnect();
$query=$bdd->prepare("DELETE FROM articles WHERE id=?");
$query->execute(array($id));
return $query;
}

// affichage d'articles par id article  //
function displayArticleById($id){
$bdd=dbconnect();
$query=$bdd->prepare("SELECT articles.id,titre,resume,contenu, infos_sup, map, DATE_FORMAT(articles.date, '%d-%m-%Y') as date,id_auteur,id_categ,articles.illustration,articles.alt_illustration, articles.alt_header_illustra, articles.header_illustra, articles.ref_titre,articles.ref_description, nom_categ, nick, categ FROM articles INNER JOIN categories ON id_categ = categories.id INNER JOIN admin ON id_auteur = admin.id WHERE articles.id = ?"); 
$query->execute(array($id));
$article=$query->fetch();
return $article;
}

function Pagination_Count_by_Id($id){
$bdd=dbconnect();
$query=$bdd->prepare("SELECT COUNT(*) AS total FROM articles WHERE id_categ = ?"); 
$query->execute(array($id));
$nb_article=$query->fetch();
return $nb_article;
}

function Pagination_By_Id($id,$premiereEntree,$messagesParPage){
$bdd=dbconnect();
$query=$bdd->prepare("SELECT articles.id, titre, resume, articles.contenu, articles.date, articles.illustration, articles.alt_illustration, articles.id_auteur, nick,COUNT(case when statut = 1 then 1 END) AS nb_commentaires, nom_categ, id_categ FROM commentaires RIGHT JOIN articles ON commentaires.id_article = articles.id INNER JOIN admin ON articles.id_auteur = admin.id INNER JOIN categories ON id_categ = categories.id WHERE id_categ = ? GROUP BY articles.id ORDER BY articles.id DESC LIMIT $premiereEntree, $messagesParPage"); 
$query->execute(array($id));
$triarticle=$query->fetchAll();
return $triarticle;
}


function displayArticlesByCateg($id){
$bdd=dbconnect();
$query=$bdd->prepare("SELECT articles.id, titre, resume, articles.contenu, DATE_FORMAT(articles.date, '%d-%m-%Y') as date, articles.illustration, articles.alt_illustration, articles.id_auteur, nick, COUNT(case when statut = 1 then 1 END) AS nb_commentaires, nom_categ, id_categ, categ FROM commentaires RIGHT JOIN articles ON commentaires.id_article = articles.id INNER JOIN admin ON articles.id_auteur = admin.id INNER JOIN categories ON id_categ = categories.id WHERE id_categ = ? GROUP BY articles.id ORDER BY articles.id DESC"); 
$query->execute(array($id));
$articles=$query->fetchAll();
return $articles;
}


// Affichage des 3 derniers articles  //
function lastArticles(){
$bdd=dbconnect(); 
$query=$bdd->prepare("SELECT articles.id,articles.titre,articles.resume,articles.contenu,articles.date,articles.id_auteur,articles.id_categ,nom_categ,articles.illustration,articles.alt_illustration, articles.header_illustra,articles.alt_header_illustra, admin.nick FROM articles INNER JOIN admin ON id_auteur = admin.id INNER JOIN categories ON articles.id_categ = categories.id ORDER BY articles.id DESC LIMIT 6");
$query->execute();
$suggest = $query->fetchAll();
return $suggest;
}

// Slider en Ajax  //
function random_article_Ajax(){
$bdd=dbconnect(); 
$query=$bdd->prepare("SELECT articles.id, articles.titre,articles.resume,articles.id_categ,articles.illustration, articles.header_illustra,articles.alt_illustration, categories.nom_categ, date, categ FROM articles INNER JOIN categories ON id_categ = categories.id ORDER BY RAND() LIMIT 5");
$query->execute();
$slider = $query->fetchAll();
return $slider;
}

?>