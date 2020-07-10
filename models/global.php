<?php

function getsetting($option){

$bdd=dbconnect();
$query=$bdd->prepare("SELECT setting_name, setting_value FROM settings WHERE setting_name = ?");
$query->execute(array($option));
$setting=$query->fetch();
return $setting;  

}

function editsetting($value,$option){
$bdd=dbconnect();
$query=$bdd->prepare("UPDATE settings SET setting_value = ? WHERE setting_name = ?");
$edit_setting= $query->execute(array($value,$option)); 
return $edit_setting;
}

?>