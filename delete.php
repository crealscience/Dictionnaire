<!-- Xavier-Laurent Salvador, 2015 -->
<?php
	setlocale (LC_TIME, 'fr_FR.utf8','fra');
        header('Cache-Control: max-age=900');
        include "creal.class.php";
        include "fonctions.php";
        include "modules.php";
        $page = new pageCreal;
        session_start();
        include "sessionTimer.php";

?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
       <head>
         <? echo $page -> createHeader();
            echo $page -> createMathMl(); ?>
       </head>

<?php
if ($_SESSION["groupe"] != "admin")
	header("Location:index.php");



if (isset($_GET['id'])) {
	$delid=$_GET['id'];
	if ($delid > 1) {
	$orth = queryBasex('for $x in db:open("CREAL")//entry[.//id='.$delid.'] return data($x//orth)');
	if ($orth == '') {
		$affichage = 'Il n\'y a aucune fiche qui porte cet identifiant. C\'est un problème. Le mieux est de recommencer la procédure.';
		}
		else {
		$autres = queryBasex('
		let $p := count(for $x in db:open("CREAL")//entry[.//orth = "'.$orth.'"] return $x)
		return 
		if ($p >1) then (<p>Je compte <n>{$p - 1}</n> autre(s) fiches avec la même vedette</p>,
		<p>{for $x in db:open("CREAL")//entry[.//orth = "'.$orth.'"][not(.//id="'.$delid.'")]
	 	return  <p>id : {data($x//id)}.</p> }</p>,<p>Effacer la fiche actuelle provoquera le retour à un état plus ancien de la rédaction (Une des autres fiches encore stockées surnagera). Pour détruire l\'entrée définitivement, il faut d\'abord effacer la fiche actuelle tout en  relevant les autres identifiants qu\'on efface ensuite un par un grâce à l\'interface prévue à cet effet : <a href="http://www.crealscience.fr/Del.php" target="_blank">Ici</a>.</p>)
		else " Il n\'y a qu\'une fiche correspondant à ['.$orth.'] ('.$delid.')"');
		$affichage = "Vous souhaitez effacer la fiche [ $orth ] ($delid).$autres Confirmer en appuyant sur ok ou écrire 0 (zéro) ou non pour tout annuler) ? <input type='text' name='writeok' value='$delid' />";
		}
	    }
	else {}
}

	
if (isset($_GET['writeok'])) {
	if ($_GET['writeok'] > 1) {
		echo queryBasexBavard('for $x in db:open("CREAL")//entry[./id="'.$_GET['writeok'].'"] return delete node $x');
		echo queryBasexBavard('for $x in db:open("encours")//entry[./id="'.$_GET['writeok'].'"] return delete node $x');
		$affichage = "La fiche id='".$_GET['writeok']."' a été effacée.";
	}
}

?>

	<body>
		<!--<body>-->
		<div id='global'>
					<div id="contenu">
					<div id='fondaffich'>		
						<h2>Interface d'écrasement de fiches</h2>
						<div id='gestmot'>							
							<h3><?echo $Message[24];?></h3>
								<ul>
									<form id='delfiche' name='delfiche' action="Del.php" method="GET">
									<?
									if ($affichage=='') {echo '<ul>Effacer la fiche qui porte l\'identifiant:<input type="text" name="id" size="10"/> </ul>';}
									?><?echo $affichage;?>
									<ul>
										<li><input type="submit" name="choix" value="OK" class="button"></li>										
									</ul>
									</form>
			<div id="footerIdefx" style="z-index: 7000;width:100%; position:fixed; bottom:0px; left: 0px; text-align: center; color: #A0A0A0; font-size: xx-small; background-color:white; padding: 3px;">Edition numérique et Bases de données: Xavier-Laurent Salvador</div>
						</div>
					</div>
	</body>
</html>
