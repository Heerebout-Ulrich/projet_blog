<?php

function display_user(){
$bdd=dbconnect();
$query=$bdd->prepare("SELECT id,user_login,user_email,date,user_statut,avatar FROM users ORDER BY id DESC"); 
$query->execute();
$users=$query->fetchAll();
return $users;
}

function add_user($pseudo, $mdp, $email, $avatar){
$bdd=dbconnect();
$adduser=$bdd->prepare("INSERT INTO users (user_login, user_pass, user_email, date, user_statut, avatar) VALUES (?, ?, ?, CURRENT_TIMESTAMP, '0', ?)");
$adduser->execute(array($pseudo, $mdp, $email, $avatar));
return $adduser;
}

function delete_user($id){
$bdd=dbconnect();
$query=$bdd->prepare("DELETE FROM users WHERE id=?"); 
$delete_user = $query->execute(array($id));
return $delete_user;
}

function getUserByEmail($mail){

$bdd=dbconnect();
$query=$bdd->prepare("SELECT * FROM users WHERE user_email = ?");
$query->execute(array($mail));
$user=$query->fetch();
return $user;  

}

function getUserByPseudo($pseudo){

$bdd=dbconnect();
$query=$bdd->prepare("SELECT * FROM users WHERE user_login = ?");
$query->execute(array($pseudo));
$user=$query->fetch();
return $user;  

}

function getUserByID($id){

$bdd=dbconnect();
$query=$bdd->prepare("SELECT * FROM users WHERE id = ?");
$query->execute(array($id));
$user=$query->fetch();
return $user;  

}

function update_user_profil($avatar,$nick,$email,$id){

$bdd=dbconnect();
$query=$bdd->prepare("UPDATE users SET avatar = ?, user_login = ?, user_email = ? WHERE id = ?");
$profil_user = $query->execute(array($avatar,$nick,$email,$id));
return $profil_user;  

}

function update_user_password($pass,$id){

$bdd=dbconnect();
$query=$bdd->prepare("UPDATE users SET user_pass = ? WHERE id = ?");
$profil_user_pass = $query->execute(array($pass,$id));
return $profil_user_pass;  

}

?>