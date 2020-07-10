<?php

function Comments(){
$bdd=dbconnect();
$query=$bdd->prepare("SELECT commentaires.id, id_article, titre, user, commentaires.contenu, commentaires.date, statut FROM commentaires INNER JOIN articles ON articles.id = commentaires.id_article ORDER BY DATE DESC");
$query->execute();
$comments=$query->fetchAll();
return $comments;
}

function CommentsArticle($id){
$bdd=dbconnect();
$query=$bdd->prepare("SELECT commentaires.id, user, contenu, commentaires.date, avatar FROM commentaires INNER JOIN users ON users.id = commentaires.id_user WHERE id_article = ? AND statut = 1");
$query->execute(array($id));
$comments=$query->fetchAll();
return $comments;
}

function CommentbyID($id){
$bdd=dbconnect();
$query=$bdd->prepare("SELECT user,contenu,date FROM commentaires WHERE id = ?"); 
$query->execute(array($id));
$comments=$query->fetch();
return $comments;
}

function compteur_commentaires($statut){
$bdd=dbconnect();
$query=$bdd->prepare("SELECT COUNT(statut) as nb_commentaires FROM commentaires WHERE statut = ?"); 
$query->execute(array($statut));
$count_comments=$query->fetch();
return $count_comments;
}


function Comments_statut($statut,$premiereEntree,$messagesParPage){
$bdd=dbconnect();
$query=$bdd->prepare("SELECT commentaires.id, id_article, titre, user, commentaires.contenu, commentaires.date, statut, illustration FROM commentaires INNER JOIN articles ON articles.id = commentaires.id_article WHERE statut = $statut ORDER BY DATE DESC LIMIT $premiereEntree, $messagesParPage");
$query->execute([$statut,$premiereEntree,$messagesParPage]);
$comments=$query->fetchAll();
return $comments;
}



function CommentsUpdateStatut($statut,$id){
$bdd=dbconnect();
$query=$bdd->prepare("UPDATE commentaires SET statut = ? WHERE id = ?");
$query->execute(array($statut,$id));
return $query;
}

function add_comment($idarticle, $usercomment, $contenucomment, $emailcomment, $id_user){
$bdd=dbconnect();
$query=$bdd->prepare("INSERT INTO commentaires (id_article, user, contenu, email, date, statut, id_user) VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP,0, ?)");
$add_comments = $query->execute(array($idarticle, $usercomment, $contenucomment, $emailcomment, $id_user));
return $add_comments;
}

function delete_comments($id){
$bdd=dbconnect();
$query=$bdd->prepare("DELETE FROM commentaires WHERE id=?");
$query->execute(array($id));
return $query;
}

function last_comments(){
$bdd=dbconnect();
$query=$bdd->prepare("SELECT commentaires.id, id_article, titre, user, commentaires.contenu, commentaires.date, statut, avatar, id_user FROM commentaires INNER JOIN articles ON articles.id = commentaires.id_article INNER JOIN users ON commentaires.id_user = users.id WHERE statut = 1 ORDER BY DATE DESC LIMIT 5");
$query->execute();
$comments=$query->fetchAll();
return $comments;
}

?>