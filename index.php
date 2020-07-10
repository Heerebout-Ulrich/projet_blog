<?php
session_start();
require "config/database.php";
require "config/global.php";
require "controllers/admin.php";
require "controllers/search.php";
require "controllers/user.php";
require "controllers/comments.php";
require "controllers/article.php";
require "controllers/categories.php";
require "controllers/page.php";
require "controllers/gallery.php";

if(isset($_GET['action'])){   
switch($_GET['action']){

case 'creer_compte':
	create_account_user();
break;
case 'login':
	login_user();
break;
case 'profil':
	profil_user();
break;
case 'deconnect':
	deconnect();
break;
case 'article':
	Affichage_Article();
break;
case 'categorie':
	Affichage_Categ();
break;
case 'login_admin':
	login_admin();
break;
case 'edit_profil_admin':
	edit_profil_admin();
break;
case 'administration':
	page_administration();
break;
case 'ajouter_article':
	ajouter_article_admin();
break;
case 'effacer_article':
	effacer_article_admin();
break;
case 'editer_article':
	editer_article_admin();
break;
case 'gestion_articles':
	gestion_article_admin();
break;
case 'gestion_categories':
	gestion_categ_admin();
break;
case 'ajouter_categorie':
	creer_categ_admin();
break;
case 'effacer_categorie':
	effacer_categ_admin();
break;
case 'editer_categorie':
	editer_categ_admin();
break;
case 'commentaires':
	traitement_comments_article();
break;
case 'gestion_commentaires_valide':
	gestion_commentaires_valide();
break;
case 'gestion_commentaires_standby':
	gestion_commentaires_standby();
break;
case 'effacer_commentaires':
	effacer_commentaires_admin();
break;
case 'editer_commentaires':
	editer_commentaires_admin();
break;
case 'gestion_membres':
	gestion_membres_admin();
break;
case 'effacer_membre':
	effacer_membre_admin();
break;
case 'page':
	afficher_page();
break;
case 'gestion_pages_admin':
	gestion_pages_admin();
break;
case 'ajouter_page':
	ajouter_page();
break;
case 'editer_page':
	editer_page();
break;      
case 'effacer_page':
	effacer_page();
break;          
case 'config_general':
	config_general();
break;
case 'photos': 
	display_gallery();  
break;
case 'gallery_admin': 
	display_gallery_admin();  
break;
case 'add_image_admin': 
	add_image_gallery();  
break;
case 'edit_image_admin': 
	edit_image_gallery();  
break;
case 'remove_image_admin': 
	remove_image_gallery();  
break;
case 'contact': 
	form_contact();  
break;
case 'recherche': 
	recherche();  
break;
case 'commentaire_id': 
	commentaires_id();  
break;
case 'ajax_commentaires': 
	ajax_commentaires();  
break;
case 'ajax_random_article': 
	ajax_random_article();  
break;
}
}
else { All_Articles(); } 
?>