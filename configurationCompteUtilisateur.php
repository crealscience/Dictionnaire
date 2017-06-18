<!-- Xavier-Laurent Salvador et Fabrice Issac -->
<?php
	setlocale (LC_TIME, 'fr_FR.utf8','fra'); 
	header('Cache-Control: max-age=900');
	include "creal.class.php";
	include "fonctions.php";
	include "modules.php";
	$page = new pageCreal;
	session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	 <head>
	 <? echo $page -> createHeader(); 
	    echo $page -> createMathMl(); ?>
       </head>
<?php
	if ($_SESSION["authentification"] != "OK")
		header("Location:index.php");
		if (isset($_POST['oldmdp']) && isset($_POST['newmdp']) && isset($_POST['newmdp2'])) {
			if($_POST['newmdp2'] == $_POST['newmdp']) {
			$re = queryBasex(preg_replace('/<NMDP>/',$_POST['newmdp'],preg_replace('/<MDP>/',$_POST['oldmdp'],preg_replace('/<NOM>/',$_SESSION['login'],preg_replace('/<BASE>/','utilisateur',$sec[12])))));
			$rez = 'Mot de passe modifié.';
		} else {$rez = 'Vous avez mal saisi la confirmation.';}
	}
?>
<body>
		<div id='global'>
			<div id="top">
		<? echo $page -> createTitre($_SESSION['langue']); ?>
				  <div id="topcahier">
					<div id="alphabet">
		<? echo $page -> createAlphabet($_SESSION['authentification'], $_SESSION['groupe']); ?>	
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
						<div id="machincahier">
						</div>		
						<div id='fondaffich'>		
							<h2><?echo $Message[13];?></h2>
						<p><?echo $Message[14];?> : <?echo $_SESSION['login'];?> </p> 
						<p><?echo $Message[15];?> : <?echo $_SESSION['groupe'];?> </p>
						<form name="chgt" action="Configuration.php" method="POST">
							<ul>
								<li><?echo $Message[16];?></li>
								<li><input type="password" name="oldmdp"/></li>
								<li><?echo $Message[17];?></li>
								<li><input type="password" name="newmdp"/></li>
								<li><?echo $Message[18];?></li>
								<li><input type="password" name="newmdp2"/></li>
								<li><input type="submit" class="button" value="<?echo $Message[19];?>"/></li>
							</ul>
						</form>
						<? echo $rez; ?>
						</div>
					</div>
				</div> <!--Fin contenu-->
		</body>
</html>
