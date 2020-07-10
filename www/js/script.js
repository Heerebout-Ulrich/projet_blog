'use strict';

//****************** déclaration des fonctions 

// SITE - Chargement des pages avec un fondu.

function loading(){
    $('.loader').fadeOut("500");   
}

// SITE - Affichage des articles en mode grille ( Cache la sidebar )

function displayArticles(){

$(".all-blog-post div div.list").toggleClass('col-xs-12 col-sm-6 col-md-6 col-lg-6 list').toggleClass('col-xs-4 col-sm-4 col-md-4 col-lg-4 list');
$("#liste-articles").toggleClass('col-md-8').toggleClass('col-md-12');
$("aside").toggleClass('col-md-4').toggleClass('hidden');
$("#display-mode").html(($("#display-mode").html() == 'Cacher la sidebar <i class="fas fa-list"></i>') ? 'Afficher la sidebar <i class="fas fa-list"></i>' : 'Cacher la sidebar <i class="fas fa-list"></i>');
}

// SITE - Affichage des commentaires avec Ajax  

function getComments(){
// Récupération des commentaires
$.getJSON("index.php?action=ajax_commentaires",displayComments);
}

function displayComments(out){
 
$('aside #comments-bloc ul').empty();    
    for(var i=0;i<out.length;i++){
        $('aside #comments-bloc ul').append("<li class='animated fadeIn delay-800ms'><img src='"+out[i]['avatar']+"'><a href='"+out[i]['path']+"/"+out[i]['id_article']+"-"+out[i]['format_titre']+"#commentaire-"+out[i]['id']+"'><h3>" + out[i]['titre'] + "</h3><span class='texteCommentaire'>" + out[i]['contenu'] + "</span><span class='pseudoCommentaire'> de : " + out[i]['user'] + "</span></a></li>");
    } 
}  

// SITE - Affichage Aléatoire des articles avec Ajax.

function getArticleRandom(){
$.getJSON("index.php?action=ajax_random_article",RandomArticle);
}

function RandomArticle(article){
$('aside section#bloc-articles-aleatoires ul').empty();
    for(var i=0,nb=1;i<article.length;i++,nb++){
        $('aside section#bloc-articles-aleatoires ul').append("<li><img class='random-article text-center' src='"+article[i]['illustration']+"' alt='"+article[i]['alt_illustration']+"'><div class='inf-article'><a class='animated fadeIn delay-800ms' href='"+article[i]['path']+"/"+article[i]['id']+"-"+article[i]['format_titre']+"'><h3>"+article[i]['titre']+"</h3></a><span><a href='"+article[i]['path']+"/'>"+article[i]['nom_categ']+"</a></span></div></li>");
    }

 }

// SITE - Fonction qui génère un chiffre aléatoire ( pour la question de la newsletter )

function GetRandomIntegrer(min,max) {
  return Math.floor(Math.random() * (max-min) + 1) +min; 
}

// SITE - Gestion de la newsletter avec une question. 

function newsletter(){
var numb1 = GetRandomIntegrer(1,10);
var numb2 = GetRandomIntegrer(1,10);    
var question = window.prompt('Combien fait '+numb1+' + '+numb2+ ' ?');
var result = numb1 + numb2;

    if(result == question){
        $.post('index.php?action=article&id=52',"news="+$('#mail_news').val()); //données sous format de JSON
        alert('Merci, Votre inscription à la newsletter est prise en compte');
        $('#mail_news').empty();
    }
    else { window.prompt('error'); }
}

// SITE (ASIDE) - Génération d'un calendrier.

function calendrier(){

var date = new Date();
var jour = date.getDate();
var moi = date.getMonth();
var annee = date.getYear();
var mois = new Array('Janvier', 'F&eacute;vrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Ao&ucirc;t', 'Septembre', 'Octobre', 'Novembre', 'D&eacute;cembre');
var jours_dans_moi = new Array(31,28,31,30,31,30,31,31,30,31,30,31);
var total = jours_dans_moi[moi];
var date_aujourdui = jour+' '+mois[moi]+' '+annee;
var dep_j = date;

if(annee<=200){
  annee += 1900;
}

if(annee%4 == 0 && annee!=1900){
  jours_dans_moi[1]=29;
}

dep_j.setDate(1);
    
if(dep_j.getDate()==2){
 var dep_j=setDate(0);
}

dep_j = dep_j.getDay();
    
$('#calendrier').append('<table class="cal_calendrier"><tbody id="cal_body"><tr><th colspan="7">'+date_aujourdui+'</th></tr>');
$('#calendrier tbody').append('<tr class="cal_j_semaines"><th>Dim</th><th>Lun</th><th>Mar</th><th>Mer</th><th>Jeu</th><th>Ven</th><th>Sam</th></tr><tr>');
var sem = 0;
    
    for(var i=1;i<=dep_j;i++){
        $('#calendrier tbody').append('<td class="cal_jours_av_ap">'+(jours_dans_moi[moi-1]-dep_j+i)+'</td>');
        sem++;
    }
    for(i=1;i<=total;i++){
        if(sem==0){
            $('#calendrier tbody').append('<tr>'); }
        if(jour==i){
            $('#calendrier tbody').append('<td class="cal_aujourdhui">'+i+'</td>'); }
        else { $('#calendrier tbody').append('<td>'+i+'</td>'); }
    sem++;
        if(sem==7){
            $('#calendrier tbody').append('</tr>');
            sem=0;}
    }
    for(i=1;sem!=0;i++){
            $('#calendrier tbody').append('<td class="cal_jours_av_ap">'+i+'</td>');
    sem++;
        if(sem==7){
            $('#calendrier tbody').append('</tr>');
            sem=0;}
    }
    $('#calendrier').append('</tbody></table>');

    return true;
}  

// SITE (Accueil) - Affichage de la date/heure actuel.

function date_heure(id){

var date = new Date;
var annee = date.getFullYear();
var moi = date.getMonth();
var mois = new Array('Janvier', 'F&eacute;vrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Ao&ucirc;t', 'Septembre', 'Octobre', 'Novembre', 'D&eacute;cembre');
var j = date.getDate();
var jour = date.getDay();
var jours = new Array('Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi');
var h = date.getHours();

    if(h<10){
    h = "0"+h;
    }
    var m = date.getMinutes();
    if(m<10){
    m = "0"+m;
    }
    var s = date.getSeconds();
    
    if(s<10){
    s = "0"+s;
    }

    var resultat = 'Nous sommes le '+jours[jour]+' '+j+' '+mois[moi]+' '+annee+' il est '+h+':'+m+':'+s;
    $('#date_heure').empty().append(resultat);
    setTimeout('date_heure("'+id+'");','1000');
    return true;
}

// Administration - Commentaires ( changement des statuts standby/valide )

function checkboxAll(){
 $(".checkbox").prop('checked', $(this).prop("checked"));
}

function checkbox(){
    if(false == $(this).prop("checked")){ 
        $("#all_select").prop('checked', false); 
    }
	if ($('.checkbox:checked').length == $('.checkbox').length ){
		$("#all_select").prop('checked', true);
	}
}

function switchBox(){
	if($(this).is(':checked')){
	$(this).val(1);
	}
	else {$(this).val(0);}     
}

// Administration - Gallerie Images 

function addlineIMG(){
    $('.gestion-photos').append("<tr class='line'><td><input type = 'file' name = 'fileUpload[]' ></td><td><input type = 'text' name = 'title[]' placeholder = 'Title' ></td><tr>");
}

function removelineIMG(){
	$(".line").last().remove();
}

// Administration - recupération de l'id d'un commentaire afin de l'afficher dans une popup.

function popup_commentaire(){
var href = $(this).attr('href').replace('#', '');
$.getJSON("index.php?action=commentaire_id&id="+href,AffichageCom);
}

// Administration - Affichage du commentaire dans une popup.

function AffichageCom(commentaire){
$('#popup-content').empty(); 
$('#popup-content').append("<h6>Posté par : "+commentaire[0]['user'] + "</h6><p>"+commentaire[0]['contenu']+"</p>");
}


// Administration - ONGLETS PLUGIN JQUERY.

function tabs(){
    $('#tabs').tabs();     
}  

// Chargement des fonctions avec un interval.

function refreshCMD(){
setInterval(getComments, 10000); /* rappel après 10 secondes */
setInterval(getArticleRandom, 10000); /* rappel après 10 secondes */
}



// Gestion des POPUPS.
// SITE - ( Bouton "se connecter" ( HEADER )
// ADMINISTRATION - ( Visualiser commentaire )

function popups(){

var bloc_popup = document.getElementById("popup");
var btn = document.getElementById("myBtn");
var btn2 = document.getElementById("myBtn-2");
var close = document.getElementsByClassName("close")[0];
var nb_popup_commentaire = document.getElementsByClassName("popup_commentaire").length;

for(var i=0;i<nb_popup_commentaire;i++){
var pop = document.getElementsByClassName("popup_commentaire")[i];
    pop.onclick = function() {
      bloc_popup.style.display = "block";
    }
}   

if(!!btn){
btn.onclick = function() {
  bloc_popup.style.display = "block";
}}

if(!!btn2){
btn2.onclick = function() {
  bloc_popup.style.display = "block";
}}

if(!!close){
close.onclick = function() {
  bloc_popup.style.display = "none";
}}


window.onclick = function(event) {
  if (event.target == bloc_popup) {
    bloc_popup.style.display = "none";
  }
}    

}

// SITE - Bouton qui permet de remonter en haut de page.
    
function button_top(){
var btn = $('#button-top');
  
  $(window).scroll(function() {
    if ($(window).scrollTop() > 100) {
      btn.addClass('show'); } 
    else { btn.removeClass('show'); }
  });

  btn.on('click', function(e) {
    e.preventDefault();
    $('html, body').animate({scrollTop:0}, '100');
  });  
}

// le code principal 

$(document).ready(function() {
    $(window).load(loading);
    $(window).load(date_heure);		
    $('.fancybox').fancybox();
    $('#display-mode').on('click',displayArticles);
    $('#addline').on('click',addlineIMG);
    $('#removeline').on('click',removelineIMG);
    $('.switch input').click(switchBox);
    $('#all_select').change(checkboxAll);
    $('.checkbox').change(checkbox);
    $(tabs);
    $(getComments);
    $(calendrier);
    $(refreshCMD);
    $(button_top);
    $(getArticleRandom);
    $(Slider);
    $('#submit_news').on('click',newsletter);
    $(popups);
    $('.popup_commentaire').click(popup_commentaire);
});












