<!-- Xavier-Laurent Salvador, 2017 -->

<?php
	setlocale (LC_TIME, 'fr_FR.utf8','fra'); 
	header('Cache-Control: max-age=900');
	include "creal.class.php";
	include "fonctions.php";
	include "modules.php";
	//if ($_SESSION['login'] != "xavier") header('location:http://www.crealscience.fr');
	$page = new pageCreal;
	session_start();
	include "sessionTimer.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
       <head>
	 <meta name="google-site-verification" content="OLV7jXIFj2fKfVhvGbiuExcOTHAqJYtviKVl59l8_Og" />
	 <? echo $page -> createHeader(); 
	    echo $page -> createMathMl(); ?>
       </head>
	
	<? include "indexPhp/indexVar.php";?>

	<body OnLoad=<?echo $bodyonload;?>>
	
	<? include 'indexPhp/feuillure.php';?>
	
	<div id="masque"></div>
	
		<div id='global'>
			<div id="top">

	<? echo $page -> createTitre($_SESSION['langue']); ?>
	
			<div id="topcahier">
					<div id="alphabet">
	
	<? echo $page -> createAlphabet($_SESSION['authentification'], $_SESSION['groupe']);?> 
			
					</div>
				</div>	
			</div><!-- Fin top -->	
	
			<div id="menugauche">
				<div id="boite"><!--La boîte des boutons-->
	<?
		include 'indexPhp/formMot.php';
		echo $page->createMenuGauche($BoutonGauche[$_SESSION['langue']]); 
	?>
	
				</div>					
			</div><!-- fin menu gauche-->
					
			<div id="contenu">        
	<? include 'indexPhp/indexLogin.php'; ?>
	<? echo $stickyfocus; ?>
					
	<div id='<?if (preg_match('/<orth>/',$affichage)) 
			{echo "fondaffich2";} 
			else {echo "fondaffich";}?>'>

	<? 
		include 'indexPhp/indexContenu.php';
	?>	
				</div><!--Fin fondaffich --><!--</div>-->
			     </div> <!--Fin contenu-->
			<div id='footer' style="font-size: x-small; padding-bottom: 15px;"> 
		     </div>
		</div><!--Fin Global-->
	</body>
	<? include 'indexPhp/indexFilAriane.php'; ?>
	<? include 'indexPhp/indexFooter.php'; ?>
</html>
