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
 	if ($_SESSION["groupe"] != "admin")
                header("Location:index.php");	
	if (isset($_POST['ip'])) 
	 {
	  $xq = 'for $x in db:open("bannis")//entry[./ip="'.$_POST['ip'].'"] return delete node $x';
	  $mess = queryBasex($xq);
	  $message = "Opération effectuée ";
	 }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
       <head>
	 <? echo $page -> createHeader(); 
	    echo $page -> createMathMl(); ?>
       </head>
	

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
  		 global $message;
		 echo $message;
		?>
		<h2 style="float:left; width: 50%">Incidents relevés</h2><h2 style="float:left;">Liste des IP bannies</h2>
		<div style="float:left; width: 49%; height:550px; overflow:scroll;">
		 <?
		  $xq = 'for $x in db:open("incident")//entry order by xs:dateTime($x/desc/date) descending return $x';
		  echo preg_replace("#<ip>[^<]*</ip>#","",preg_replace("#<date>([^<]*)</date>#","<datefr>$1</datefr>",queryBasex($xq)));
		 ?>
		</div>
		<div style="float:left;width: 47%; height:550px; overflow:scroll;">
                 <?
                  $xq = 'for $x in db:open("bannis")//entry return <entry>{$x/*} 
			   <a href="#" onClick="document.forms[\'freeIp\'].ip.value=\'{data($x/ip)}\';document.forms[\'freeIp\'].submit();">
			   libérer</a></entry>';
                  echo preg_replace("#<date>([^<]*)</date>#","<datefr>$1</datefr>",queryBasex($xq));
                 ?>
                </div>
	</div><!--Fin fondaffich --><!--</div>-->
			</div> <!--Fin contenu-->
		       <div id='footer' style="font-size: x-small; padding-bottom: 15px;"> 
		     </div>
		</div><!--Fin Global-->
	</body>
	<? include 'indexPhp/indexFooter.php'; ?>
	<form name="freeIp" action="Ban.php" method="post">
 	  <input type="hidden" name="ip" value=""/>	
	</form>
</html>
