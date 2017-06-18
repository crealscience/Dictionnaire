<!-- Xavier-Laurent Salvador et Fabrice Issac, LDI UMR CNRS 7187, PARIS XIII -->
<?php
	setlocale (LC_TIME, 'fr_FR.utf8','fra'); 
	include "fonctions.php";
		include "modules.php";
	session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<script type="text/javascript" src="./JS/ie_xml.js"></script>
		<link rel="stylesheet" type="text/css" title="base" media="screen" href="CSS/dicobase.css"/>
		<link rel="stylesheet" type="text/css" title="base" media="screen" href="CSS/dicoreq.css"/>
		<link rel="stylesheet" type="text/css" title="base" media="screen" href="CSS/dicodefxml.css"/>
		<link rel="icon" type="image/png" href="<?echo $logos[1];?>" />

	</head>

<?
	if (empty($_SESSION['langue'])){$_SESSION['langue']='fr';}
	/*Mdp*/
	if (isset($_POST['login']) && isset($_POST['mdp'])) {
		$req = queryBasex(preg_replace('/<DBSECRET>/','utilisateur',preg_replace('/<MDP>/',$_POST['mdp'],preg_replace('/<NOM>/',$_POST['login'],$sec[0]))));		
		if ($req == 'OK') {
			session_start(); 
			$_SESSION['authentification'] = 'OK';
			$_SESSION['login'] = $_POST['login'];
			$_SESSION['groupe'] = queryBasex(preg_replace('/<NOM>/',$_POST['login'],preg_replace('/<DBSECRET>/','utilisateur',$sec[1])));
			//$_SESSION['mail'] = queryBasex(preg_replace('/<NOM>/',$_POST['login'],preg_replace('/<DBSECRET>/','utilisateur',$sec[4])));
			$_SESSION['mail']= $_SESSION['login'].'@crealscience.fr';
			header("Location:index.php?langue=".$_SESSION['langue']);} else {header("Location:login.php");
			}			
	}
?>

<body OnLoad=<?echo $bodyonload;?>>
		<div id='global'>
			<div id="top">
	<?	
		Affichtitre($titre[$_SESSION['langue']]);
	?>
				<div id="topcahier">
					<div id="alphabet">
						<form name="alphabet" action="<?echo $_SERVER["PHP_SELF"].'?langue='.$_SESSION['langue'];?>" method="post"> 
						<input type="hidden" name="indexlettreonglet" value=""> 
	<?
		//AffichAlphabet();
	?>
						</form>
					</div>
				</div>	
			</div><!-- Fin top -->	
					
				<div id="menugauche">
					<div id="boite"><!--La boîte des boutons-->
						<ul id="menucote">
	<? 
		AffichMenuGauche($BoutonGauche[$_SESSION['langue']]);
	?>
	
						</ul>
					</div>
				</div><!-- fin menu gauche-->
					
					<div id="contenu">
						<div id="machincahier">
						</div>
					 <div id='fondaffich'>
							<div id='biblio'>
								<?
									$bib = "for \$x in db:open('CREAL_Bibliographie')//entry return \$x ";
									$x = queryBasex($bib);
									echo $x;
								?>
							</div>
						</div>						
		</div>
	</body>
</html>
