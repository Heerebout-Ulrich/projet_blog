<?php

function getAdmin($mail){

$bdd=dbconnect();
$query=$bdd->prepare("SELECT * FROM admin WHERE email = ?");
$query->execute(array($mail));
$admin=$query->fetch();
return $admin;  

}

function getAdminByID($id){

$bdd=dbconnect();
$query=$bdd->prepare("SELECT * FROM admin WHERE id = ?");
$query->execute(array($id));
$admin=$query->fetch();
return $admin;  

}

function update_admin_profil($avatar,$nick,$email,$id){

$bdd=dbconnect();
$query=$bdd->prepare("UPDATE admin SET avatar = ?, nick = ?, email = ? WHERE id = ?");
$profil_admin = $query->execute(array($avatar,$nick,$email,$id));
return $profil_admin;  

}

function update_admin_password($pass,$id){

$bdd=dbconnect();
$query=$bdd->prepare("UPDATE admin SET password = ? WHERE id = ?");
$profil_admin_pass = $query->execute(array($pass,$id));
return $profil_admin_pass;  

}

?>