<!-- Xavier-Laurent Salvador, 2015 -->
<?php
	setlocale (LC_TIME, 'fr_FR.utf8','fra'); 
	include "fonctions.php";
	session_start();
	header('Location: http://www.crealscience.fr/deviation.php');
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
		<link href='http://fonts.googleapis.com/css?family=Belleza|Nixie+One|Open+Sans|PT+Serif+Caption|Eagle+Lake&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
		<link rel="icon" type="image/png" href="<?echo $logos[1];?>" />
		<script type="text/javascript" src="JS/tinymce/jscripts/tiny_mce/tiny_mce.js" ></script >
		<script type="text/javascript" src="JS/Tiny_NEWS.js" ></script >
		<script src="http://code.jquery.com/jquery-latest.js"></script>
		<script type="text/javascript" src="JS/js.js"></script>
	</head>
<?php
	if ($_SESSION["groupe"] != "admin")
		header("Location:index.php");
	
	if (isset($_POST["valid"]) && $_POST["valid"] != '') {
	/*Valider*/
		$id=$_POST["valid"];
		$orth=queryBasex('data(for $x in db:open("CREAL")/entry[./id="'.$id.'"]/form/orth return $x)');
		$return=$id." validé !";
		$orth = queryBasex(preg_replace('/<BASE>/','CREAL',preg_replace('/<ID>/',$id,$sec[8])));
		$auteur = queryBasex(preg_replace('/<BASE>/','CREAL',preg_replace('/<ID>/',$id,$sec[13])));
		/**/		
		courriel($auteur.'@crealscience.fr',preg_replace('/<MOT>/',$orth,$Message[23]) ,preg_replace('/<COMMENTAIRE>/',$_POST['comadmin'],preg_replace('/<MOT>/',$orth,$Message[22])));
		/**/
		$re1 = queryBasex(preg_replace('/<BASE>/','CREAL',preg_replace('/<ID>/',$id,$sec[9])));
		$re2 = queryBasex(preg_replace('/<BASE>/','encours',preg_replace('/<ID>/',$id,$sec[10])));
		$re3 = queryBasex(preg_replace('/<ORTH>/',$orth,preg_replace('/<BASE>/','CREAL',preg_replace('/<ID>/',$id,$sec[11]))));
		$re4 = queryBasex(preg_replace('/<ORTH>/',$orth,preg_replace('/<BASE>/','encours',preg_replace('/<ID>/',$id,$sec[11]))));
	}
	
	if (isset($_POST["cancelvalid"]) && $_POST["cancelvalid"] != '') {
	/*Effacer*/
		$id=$_POST["cancelvalid"];
		$orth = queryBasex(preg_replace('/<BASE>/','CREAL',preg_replace('/<ID>/',$id,$sec[8])));
		$auteur = queryBasex(preg_replace('/<BASE>/','CREAL',preg_replace('/<ID>/',$id,$sec[13])));
		$mess = preg_replace('/<COMMENTAIRE>/',$_POST['comadmin'],preg_replace('/MOT/',$orth,$Message[20]));
		courriel($auteur.'@crealscience.fr',$Message[21],$mess);
		$re1 = queryBasex(preg_replace('/<BASE>/','encours',preg_replace('/<ID>/',$id,$sec[7])));
		$re2 = queryBasex(preg_replace('/<BASE>/','CREAL',preg_replace('/<ID>/',$id,$sec[7])));
	}
	
	if (isset($_POST["affichmot"]) && $_POST["affichmot"] != '') {
		$xq = 'for $x in db:open("CREAL")/entry[./id="'.$_POST["affichmot"].'"] return $x';
		//$return= TraitementAffichage(queryBasexBavard($xq));
		//voir dans fonctions.php >> les eux fonctions...
		$return= (queryBasex($xq));
		$xqorth = queryBasex('data(for $x in db:open("CREAL")/entry[./id="'.$_POST["affichmot"].'"] return $x//orth)');		
		//header('location:#CUVE');
	}
	
	if (isset($_GET["sens"])){
		$count =queryBasex('count(for $x in db:open("encours")/entry[./valid="true"] return $x)');
		if ($_GET["sens"]=='plus') {
			if ($_SESSION["idvalid"]<($count-3)) {$_SESSION["idvalid"]=$_SESSION["idvalid"]+3;}
			else {$_SESSION["idvalid"]=($count-3);}
		}
		else {
			if ($_SESSION["idvalid"]>3) {$_SESSION["idvalid"]=$_SESSION["idvalid"]-3;}
			else {$_SESSION["idvalid"]=1;}
		}
	$return= '<entry><sense><def>Cliquez sur un mot.</def></sense></entry>';
	}
?>
<div id="fondAppliXX" style="position: fixed; top:0px; left:0px; width: 100%; height: 100%;background-color:rgba(64, 110, 182, 0.3)"></div>
<body>
	<div id="ongletH" style="display:none;"><?php 
 if ($_SESSION["idvalid"]!='') {$base=$_SESSION["idvalid"];} else {$base='0';}
 echo $base.' à '.($_SESSION["idvalid"]+3).' / '.queryBasex('count(for $x in db:open("encours")/entry[./valid="true"] return $x)');
	?></div>

<div id='adminmot' style="display:none;">	

<form id='validmot' name='validmot' action="<?echo $_SERVER["PHP_SELF"].'?langue='.$_SESSION['langue'];?>" method="POST">
<textarea id="comadmin" style="display:none;" name="comadmin" onFocus="if(this.value=='Commentaire pour le mot <?echo $xqorth?>')this.value=''" row="5">Commentaire pour le mot <?echo $xqorth?></textarea>					
	<input name="valid" type="hidden" value="">			
	<input name="cancelvalid" type="hidden" value="">
	<input name="affichmot" type="hidden" value="">				
</form>
	<?echo "<a href='Ban.php'>Voir Incidents</a>".getValidMots2($_SESSION['idvalid']);?>

</div>
<div id='adminmotaff' style="display:none;">
<div id="controle" spellcheck="false"><?echo $return;?>
</div>	
</div>	
					
<!-- /////////////// HYPE \\\\\\\\\\\\\\\\\\\ -->
	<div id="crealadmin_hype_container" style="margin:auto;position:relative;width:800px;height:800px;overflow:hidden;margin-top: 25px;" aria-live="polite">
	<script type="text/javascript" charset="utf-8" src="crealAdmin/crealAdmin.hyperesources/crealadmin_hype_generated_script.js?95259"></script>
	</div>
	<form id="idvalid" action="<?echo $_SERVER["PHP_SELF"].'?langue='.$_SESSION['langue'];?>" method="get">
				<input type="hidden" name="sens" value="" />
			</form>
	<div id="mailsInscrits" style="display:none;font-size: small;">
		<?
	$xq = ' let $liste := for $x in db:open("utilisateur")//entry[matches(./mail,"@")][not(matches(./mail,"salvador"))][not(matches(./mail,"coucou"))][matches(./groupe, "visiteur") or matches(./groupe,"TIP")] return <p style="font-size:small;" class="cool {if ($x/groupe = "visiteur") then \'vert\' else ()}">{data($x/nom)} :: <d>{data($x/mail)}</d></p>
	return (<a href="mailto:xavier-laurent.salvador@univ-paris13.fr?subject=[ Dictionnaire du Français Scientifique Médiéval ]&amp;cc=joelle.ducos@paris-sorbonne.fr&amp;bcc={for $x in $liste//d return ($x,";")}toutlemonde@crealscience.fr&amp;body=(Vous recevez ce courriel parce que vous êtes abonné au DFSM - http://www.crealscience.fr)">Ecrire à tous</a>,$liste)
';
	echo queryBasex($xq);
		?>
	</div>

	<div id="demandeDels" style="display:none;">
                <?
        $xq = '
		if (db:open("CREAL")//entry[.//def contains text {"fiche", "supprimer"} all using case insensitive ordered window 4 words]) then
		for $x in db:open("CREAL")//entry[.//def contains text {"fiche", "supprimer"} all using case insensitive ordered window 4 words] return <p style="font-size: xx-small;"> fiche <a style="text-decoration: none; color: black; padding: 3px; background-color:#A0A0A0; border: 1px solid black; text-align: center; margin: 2px; margin-top: 10px; " href="#" onClick="open(\'http://www.crealscience.fr/Del.php?id={data($x/id)}&amp;choix=OK#gestmot\', \'new\', \'width=800,height=360,toolbar=no,location=no, directories=no,status=no,menubar=no,scrollbars=yes,resizable=no\')">id: {data($x/id)}</a> "{data($x//orth)}"</p>
		else "Pas de demande de suppression en cours"
	
	';
        echo queryBasex($xq);
                ?>
        </div>

	</body>
	<script>
	 document.onreadystatechange = function () {
         if(document.readyState === "complete"){ 
	 var t = document.getElementById('mailsInscrits').innerHTML;
	 document.getElementById('lesMails').innerHTML = t;

	var t = document.getElementById('demandeDels').innerHTML;
         document.getElementById('lesDels').innerHTML = t;
	}
	}
	</script>
<div id="footerIdefx" style="width:100%; position:fixed; bottom:0px; left: 0px; text-align: center; color: #A0A0A0; font-size: xx-small; background-color:white; padding: 3px;">Isilex par Xavier-Laurent Salvador</div>
</html>



