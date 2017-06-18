<!-- Xavier-Laurent Salvador et Fabrice Issac, 2017-->
<?php
	setlocale (LC_TIME, 'fr_FR.utf8','fra'); 
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

<?
        if (empty($_SESSION['langue'])){$_SESSION['langue']='fr';}
        /*Mdp*/
        if (isset($_POST['login']) && isset($_POST['mdp'])) {
                $req = queryBasex(preg_replace('/<DBSECRET>/','utilisateur',preg_replace('/<MDP>/',$_POST['mdp'],preg_replace('/<NOM>/',$_POST['login'],$sec[0]))));
                if ($req == 'OK') {
                        session_start();
                        $_SESSION['login'] = $_POST['login'];
                        $_SESSION['groupe'] = queryBasex(preg_replace('/<NOM>/',$_POST['login'],preg_replace('/<DBSECRET>/','utilisateur',$sec[1])));
                        $_SESSION['mail']= $_SESSION['login'].'@crealscience.fr';

                        //Test Lock In
                        if (testLockIn($_SESSION['login']) == "XXX") {
                                $_SESSION['authentification'] = 'XXX';
                                session_destroy();
                                header("Location:http://www.crealscience.fr/index.php?lockIn");
                        } else {
                                $_SESSION['authentification'] = 'OK';
                                resetUser($_SESSION['login']);
                        }

                        //On enregistre la date de connexion
                        registerDate('in');
                         foreach ($adminmail as $mail) {
                                courriel($mail,'[ Crealscience ] - Connexion au site '.$_SESSION['login'],$_SESSION['login'].' s\'est connecté au site. Ce message vous parvient pour vous informer d\'éventuels comportements suspects sur le site Crealscience.fr');
                        }
                         if (isset($_POST['phpFile'])) {
                         if ($_POST['phpFile'] == 'onto') {
                          header("Location:http://www.crealscience.fr/ontologie");
                          } else {
                           header("Location:http://www.crealscience.fr/laboratoire");
                          }

                        } else {
                        header("Location:index.php?langue=".$_SESSION['langue']);}}
                else {
                        echo '<script>alert("Utilisateur ou Mot de passe incorrect(s)."); document.location.href="inscription.php"; </script>';
                        }
        }
?>

<body OnLoad=<?echo $bodyonload;?>>
		<div id='global'>
			<div id="top">
	<? echo $page -> createTitre($_SESSION['langue']); ?>
				<div id="topcahier">
					<div id="alphabet">
						<form name="alphabet" action="<?echo $_SERVER["PHP_SELF"].'?langue='.$_SESSION['langue'];?>" method="post"> 
						<input type="hidden" name="indexlettreonglet" value=""> 
	<? echo $page -> createAlphabet($_SESSION['authentification'], $_SESSION['groupe']);?>
						</form>
					</div>
				</div>	
			</div><!-- Fin top -->	
					
				<div id="menugauche">
					<div id="boite"><!--La boîte des boutons-->
	<? 
	echo $page->createMenuGauche($BoutonGauche[$_SESSION['langue']]); 
	?>
					</div>
				</div><!-- fin menu gauche-->
					
					<div id="contenu">
						<div id="machincahier">
						</div>
					 <div id='fondaffich'>
						 <div class="texte">
 <h2>Se connecter</h2>
                <div id="creallogin_hype_container" style="margin:auto;margin-top: 25px;position:relative;width:188px;height:224px;overflow:hidden;float:left;" aria-live="polite">
                <script type="text/javascript" charset="utf-8" src="crealLogin/crealLogin.hyperesources/creallogin_hype_generated_script.js?54271"></script>
                </div>


							</div>
						</div>						
		</div>
	<? echo $page -> createFooter();?>
	</body>
</html>
