<?php

// Creation d'article  //
function addpage($titrepage, $contenupage, $ref_titre, $ref_description, $page){
$bdd=dbconnect();
$query=$bdd->prepare("INSERT INTO pages (nom, contenu, titre_ref, desc_ref, page) VALUES (?, ?, ?, ?, ?)"); 
$add_page = $query->execute(array($titrepage, $contenupage, $ref_titre, $ref_description, $page));
return $add_page;
}

function editpage($titrepage, $contenupage, $ref_titre, $ref_description, $page, $id){
$bdd=dbconnect();
$query=$bdd->prepare("UPDATE pages SET nom = ?, contenu = ?, titre_ref = ?, desc_ref = ?, page = ? WHERE pages.id = ?");
$edit_page= $query->execute(array($titrepage, $contenupage, $ref_titre, $ref_description, $page, $id)); 
return $edit_page;
}

function displaypage(){
$bdd=dbconnect();
$query=$bdd->prepare("SELECT id, nom, contenu, titre_ref, desc_ref, page FROM pages"); 
$query->execute();
$listepages=$query->fetchAll();
return $listepages;
}

function page_by_id($id){
$bdd=dbconnect();
$query=$bdd->prepare("SELECT id, nom, contenu, titre_ref, desc_ref, page FROM pages WHERE id = ?"); 
$query->execute([$id]);
$listepages=$query->fetch();
return $listepages;
}

// effacer page  //
function DeletePage($id){
$bdd=dbconnect();
$query=$bdd->prepare("DELETE FROM pages WHERE id=?");
$query->execute(array($id));
return $query;
}

?>