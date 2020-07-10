<?php

function DisplayAllCategorie(){
$bdd=dbconnect();
$query=$bdd->prepare("SELECT table1.id, table1.ordre, table1.cat_parent, table1.nom_categ, IFNULL(table2.nom_categ,0) as parent, COUNT(articles.id_categ) AS nb_article, table1.categ FROM categories as table1 LEFT OUTER JOIN categories as table2 ON table1.cat_parent = table2.id LEFT JOIN articles ON articles.id_categ = table1.id GROUP BY table1.nom_categ ORDER BY table1.cat_parent ASC, table1.ordre ASC");
$query->execute();
$allcateg = $query->fetchAll();
return $allcateg ;
}

function CategById($id){
$bdd=dbconnect();
$query=$bdd->prepare("SELECT id, nom_categ, illustration, ref_titre, ref_description, cat_parent, categ FROM categories WHERE id=?");
$query->execute(array($id));
$categ = $query->fetch();
return $categ;
}

function CategByCateg($categ){
$bdd=dbconnect();
$query=$bdd->prepare("SELECT id, nom_categ, illustration, ref_titre, ref_description, cat_parent, categ FROM categories WHERE categ=?");
$query->execute(array($categ));
$categ = $query->fetch();
return $categ;
}

function AddCategorie($nom_categ, $illustration, $ref_titre, $ref_description, $id_parent, $categ){
$bdd=dbconnect();
$query=$bdd->prepare("INSERT INTO categories (nom_categ, illustration, ref_titre, ref_description, cat_parent, categ) VALUES ( ?, ?, ?, ?, ?)");
$query->execute(array($nom_categ, $illustration, $ref_titre, $ref_description, $id_parent, $categ));
return $query;
}

function EditCategorie($newname, $illustration, $ref_titre, $ref_description, $id_parent, $categ, $id_categorie){
$bdd=dbconnect();
$query=$bdd->prepare("UPDATE categories SET nom_categ = ?, illustration = ?, ref_titre = ?, ref_description = ?, cat_parent = ?, categ = ? WHERE categories.id = ?");
$query->execute(array($newname, $illustration, $ref_titre, $ref_description, $id_parent, $categ, $id_categorie));
;
return $query;
}

function DeleteCategorie($id){
$bdd=dbconnect();
$query=$bdd->prepare("DELETE FROM categories WHERE id=?");
$query->execute(array($id));
return $query;
}

function SousCategByParent($id){
$bdd=dbconnect();
$query=$bdd->prepare("SELECT id,nom_categ, cat_parent, categ FROM categories WHERE cat_parent = ? ORDER BY ordre ASC");
$query->execute(array($id));
$submenu = $query->fetchAll();
return $submenu;
}

function OrderCategorie($order,$categ_id){
$bdd=dbconnect();
$query=$bdd->prepare("UPDATE categories SET ordre = ? WHERE categories.id = ?");
$query->execute(array($order,$categ_id));
return $query;
}

?>