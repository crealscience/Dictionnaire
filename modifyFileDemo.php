<!-- Xavier-Laurent Salvador, 2017 -->
<!--  MasterPro TILDE -->
<?php
	setlocale (LC_TIME, 'fr_FR.utf8','fra'); 
	include "fonctions.php";
	
	if (isset($_POST['txtcss']) || isset($_POST['txtdtd']) || isset($_POST['txtdtd'])) { 
		$css = $_POST['txtcss']; 
		$dtd = $_POST['txtdtd']; 
		$return = $_POST['txttest'];		
	} else {
		$css = file_get_contents('CSS/dicodefxml Exo.css');
		$dtd = file_get_contents('DTD/crealExo.dtd');
		$return=file_get_contents('TXT_SITE/Exo.xml');
		}
	$xq = preg_replace('/<DTD>/','<!ELEMENT root ANY>'.$dtd,preg_replace('/<XML>/','<root>'.$return.'</root>',$ReqXq[24]));
	$messageDTD = queryBasex($xq);
	if (!preg_match('/Error/',$messageDTD) && !preg_match('/Fatal/',$messageDTD) && $messageDTD != '') {
		//echo $messageDTD;
		$messageDTD='DTD valide !';
		$bouton = 'icone_voyant_vert.gif';
	} else {
		$bouton = 'boutonrouge.png';
	}
	$messageDTD = preg_replace('/CREAL/','Successfully',$messageDTD);
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<script type="text/javascript" src="./JS/ie_xml.js"></script>
		<script type="text/javascript" src="./JS/jsExo.js"></script>
		<link rel="stylesheet" type="text/css" title="base" media="screen" href="CSS/dicoExo.css"/>
		<!--<link rel="stylesheet" type="text/css" title="base" media="screen" href="CSS/dicodefxml.css"/>-->
		<style type="text/css" media="screen">
			<? echo $css;?>
		</style>
		<link rel="icon" type="image/png" href="<?echo $logos[1];?>"  />
		<link href='http://fonts.googleapis.com/css?family=Belleza|Nixie+One|Open+Sans|PT+Serif+Caption|Eagle+Lake&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Arbutus|Chango' rel='stylesheet' type='text/css'>
		<!--<script language="Javascript" type="text/javascript" src="JS/edit_area/edit_area_compressor.php?plugins"></script>
		<script type="text/javascript" src="JS/editarea_source.js" ></script >-->
	</head>
<?php

	if (empty($_SESSION['langue'])){$_SESSION['langue']='fr';}
	if (isset($_GET['xmlmot'])) {
		$mot= $_GET['xmlmot']; 
	} else { $mot = $_POST['proeditione'];}
	/*$return=TraitementTiny(getDefMot($mot));*/
	
?>
<body class='mod'>
	<div id='global'>
		<?
		global $messageDTD;
		if (preg_match('/valide/',$messageDTD)) {$marquee1 = '';$marquee2=''; $class='info';} else {$marquee1='';$marquee2='';$class='error';}
				?>
		<div class="<?echo $class;?> message"></div>
		<div id="message">
		
				<?echo $marquee1;?><div id='renvoidtd'><img class='blink' src="images/<?echo $bouton;?>" width="20" height="20" /><? global $messageDTD; echo $messageDTD;?></div><?echo $marquee2;?>
		</div>
		<div id='totop'>			
			<div class='ruban' id='top'>				
				<h2>Build your XML Database</h2>
			<div id='dessoustop'>
				
			XML2HTML5 Tryout Interface by <a href='http://google.com/+XavierLaurentSALVADOR'>Xavier-Laurent Salvador</a> for <a href='http://www-ldi.univ-paris13.fr/MasterPro/website/'>TILDE</a>. Click and Build.
			</div>
		</div>					
		</div>
		<div id='colgauche'>
				<p><input type='submit' class='gch' value='Salvador XML' onclick=' window.location = "http://www.humanitesnumeriques.fr/tilde/index.php";'></p>
				<p><input type='submit' class='gch' value='Isilex' onclick=' window.location = "http://www.isilex.fr";'></p>
				<p><input type='submit' class='gch' value='C.science' onclick=' window.location = "http://www.crealscience.fr";'></p>
				<p><input type='submit' class='gch' value='Tilde' onclick=' window.location = "http://www-ldi.univ-paris13.fr/MasterPro/website/";'></p>
		</div>
					<div id="contenuEdit">
						<div id='fondaffichExo'>
							<div id="edit">								
							<form name='formulaire' method="post" action="ModifierTilde.php">	
								<h2><div class='blinksoft'>X</div>ML Branch Input</h2>
									<div id='renvoi'><img class='blink' src="images/<?echo $bouton;?>" width="20" height="20" />  Taper le texte actualise la fiche. 
								</div>																
									<textarea placeholder="XML vide!" wrap='on' spellcheck="false" style="display:block; color:blue; font-size:13px;" onkeypress="" onkeyup="controleDiv('controle');scanTouche();" onfocus="controleDiv('controle');" id="txtdef" name="txttest"><?echo $return;?>
									</textarea>
									
									<textarea name="txtcss" id="txtcsstst" spellcheck="false" style='display:none;'></textarea>
									<textarea name="txtdtd" id="txtdtdtst" spellcheck="false" style='display:none;'></textarea>							
								<input type="hidden" name="mot" value="<?echo $mot;?>"/>
								<ul><p><input type='submit' class='ongl' value='Validate' onclick="copyContent('txtdtd','txtdtdtst');copyContent('txtcss','txtcsstst');"></p>
									<p><input class='button' type="button" value="::Ex::" onClick="insertion('<entry>\r <form>\r  <orth>VOTRE VEDETTE</orth>\r </form>\r <gramgrp>\r  <gram type=&quot;pos&quot;>subst.</gram><gram type=&quot;gen&quot;>mas.</gram>\r </gramgrp>\r <sense med=&quot;XXX&quot; mod=&quot;XXX&quot;>\r  <def>Texte de la définition</def>\r  <note id=&quot;Struct&quot;>\r    <xr>\r     <ref>Ø</ref>\r   </xr>\r    </note>\r   <cit>\r   <quote>texte de la citation</quote>\r   <bibl><author>auteur</author> LIVRE</bibl>\r  </cit>\r  <note id=&quot;encyclo&quot;><xr><gloss>texte encyclo</gloss>\r  </xr></note>\r </sense>', '<id></id></entry>');controleDiv('controle');">									</p>
									 <p><select class='ongl2' name="boite1" width="30px">
											<option onClick="insertion('È', '');controleDiv('controle');">È</option>
											<option onClick="insertion('∅', '');controleDiv('controle');">∅</option>
											<option onClick="insertion('É', '');controleDiv('controle');">É</option>
											<option onClick="insertion('Ê', '');controleDiv('controle');">Ê</option>
											<option onClick="insertion('Ë', '');controleDiv('controle');">Ë</option>
											<option onClick="insertion('Â', '');controleDiv('controle');">Â</option>
											<option onClick="insertion('Œ', '');controleDiv('controle');">Œ</option>
											<option onClick="insertion('Ç', '');controleDiv('controle');">Ç</option>
											<option onClick="insertion('Ü', '');controleDiv('controle');">Ü</option>
									</select>	</p>
									<p><input class='buttonhelp' type="button" value="help" onClick="alert('Les deux zones sont modifiables. Pour corriger une fiche, saisissez le texte qui actualisera la fenêtre automatiquement. Pour insérer un bloc xml, placez-vous en un endroit idoine et cliquez sur le bouton. Quand vous avez fini, cliquez sur le bouton de validation (Envoyer la fiche en base).');">								</p>
								</ul>								
					         </form>								
							</div>
							
							<div id='editdtd'>
							<form name="validcss" method="post" action="ModifierTilde.php"><h2><div class='blinksoft'>D</div>TD Building Window</h2>
								<textarea placeholder="DTD vide!" name="txtdtd" id="txtdtd" spellcheck="false" ><?echo $dtd;?></textarea>
								<textarea name="txttest" id="txthidden" spellcheck="false" style='display:none;'></textarea>
								<textarea name="txtcss" id="txtcssdtd" spellcheck="false" style='display:none;'></textarea>
								<ul><p><input type='submit' class='ongl' onclick="copyContent('controle','txthidden');copyContent('txtcss','txtcssdtd');" value='Validate'>
								</p>
								<p><input type='submit' class='ongl' onclick=" window.location = ModifierTilde.php;" value='Cancel But'></p></ul>
							</form>
						</div>
						</div>
						
						<div id='editcss'>
							<form name="validcss" method="post" action="ModifierTilde.php"><h2><div class='blinksoft'>C</div>SS Input Window</h2>
								<textarea placeholder="CSS vide!" name="txtcss" id="txtcss" spellcheck="false"><?echo $css;?></textarea>
								<textarea name="txttest" id="txthidden2" spellcheck="false" style='display:none;'></textarea>
								<textarea name="txtdtd" id="txtdtdcss" spellcheck="false" style='display:none;'></textarea>
								<ul><p><input type='submit' class='ongl' onclick="copyContent('controle','txthidden2');copyContent('txtdtd','txtdtdcss');" value='Link'>
								</p><p><input type='submit' class='ongl' onclick=" window.location = ModifierTilde.php;" value='Cancel But'></p></ul>
							</form>
						</div>
					</div>
					<div id='result'>
						<div id='editdroite'><h2><div class='blinksoft'>R</div>esponsive Control Window</h2>
							<div id='renvoidroite'><img class='blink' src="images/<?echo $bouton;?>" width="20" height="20" />  En attente de données XML.</div>	
							<div id='controle' contentEditable='true' spellcheck="false" onkeyup="actuTextarea();"><?echo $return;?></div>
							</div>							
						</div>
												
				</div>				
	</body>
</html>
