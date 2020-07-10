<?php

function dbconnect(){

try
{
$bdd= new PDO('mysql:host=localhost;dbname=instant_p;charset=utf8','ulrichwi','RomannKeke31');
}
catch (Exception $e){
    die('Erreur:'. $e -> getMessage());
}

return $bdd;

}



?>