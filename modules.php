<!-- Xavier-Laurent Salvador, 2017 -->
<?
session_start();
include "TXT_SITE/modules/colonne.php";

/**/
function getInfoBase(){
	$reqnb = '
	let $orth := count(for $x in //orth return $x)
	let $usg := count(for $x in //usg return $x)
	return $orth+$usg
	';
	$nbdemots = queryBasex($reqnb);
	if ($nbdemots=="") {$nbdemots="Basex déconnecté";}
	$reqm = 'count(distinct-values(for $x in //orth return $x))';
	$reqn='(for $x in //id order  by number($x) descending return data($x))[1]';
	$nbdefiches = queryBasex($reqm);
	$nbdefichesredac = queryBasex($reqn);
	$reqdata = 'data((for $x in //entry[.//auteur="'.$_SESSION['login'].'"]//form/date order by $x descending return $x)[1])';
	if ($_SESSION['groupe']!='visiteur' && $_SESSION['groupe']!='') {$dernieracces = '<!--Dernier accès:<br/><span style="color:#2277bb">'.queryBasex($reqdata).'</span>-->';} else {$dernieracces = '';}
	$senses = '
	count(for $x in //sense return $x)
	';
	$nbsenses = queryBasex($senses);
	$xrref = '
	count(for $x in //ref return $x)
	';
	$nbxrref = queryBasex($xrref);
	if ($_SESSION['login'] != '') 
		{
		$who = '<p>Vous êtes: <a href="Configuration.php">'.$_SESSION['login'].'</a></p>
				<p>Du groupe: <a href="#">'.$_SESSION['groupe'].'</a></p>';
		} else 
		{
		$who = '<p>Vous n\'êtes pas connecté.</a>';
		}
	$info = "
		<p>".$who."</p>
		<p>Nombre d'unités simples ou polylexicales référencées: <span style='color:#2277bb'>$nbdemots</span>
		<br/>Nombre de fiches: <span style='color:#2277bb'>$nbdefiches</span>
		<br/>Nombre de fiches saisies: <span style='color:#2277bb'>$nbdefichesredac</span>
		<br/>Nombre de sens enregistrés (polysémie): <span style='color:#2277bb'>$nbsenses</span>
		<br/>Nombre de renvois internes: <span style='color:#2277bb'>$nbxrref</span>
		".$dernieracces;
	return $info;
}

function defDuJour() {
	$today= date(d) + date(m) + date(y);
	$reqrand = '
		(for $x in /entry
			[.//valid="true"]
			[not(./form/date contains text "1999")]
			[starts-with(.//orth,"C")]
			[.//def] 
			return 
			<div id="entryj">
			<div id="orthj">
			{(lower-case(data($x//orth)))}
			</div>
			<div id="gramj">{data($x//gramgrp)}</div>
			{for $y at $ind in $x//def 
			 return 
				<div id="defj">
				  {$ind}) 
				   {if ($y/preceding-sibling::*[1]) 
				    then 
					<div id="usgj">
					{data($y/preceding-sibling::*[1])}</div> 
					else ()} 
				  {data($y)} 
				</div>}
			</div>)['.$today.']';
	$motdujour = queryBasex($reqrand);
	$motdujour = "<br/><div class='textedessous' contentEditable='false'><h3>Mot du jour:</h3>".$motdujour."</div>";
	return $motdujour;
}


function highLight($paragraph,$mot) {
	if (strlen($mot)>4) {
		for ($i = 0; $i <= (strlen($mot)-4); $i++) {$motpresque = $motpresque.$mot[$i];}
	} else {
		$motpresque=$mot;
		}
    $paragraph= preg_replace('/(<quote>[^<]*)('.$motpresque.'[a-z|éèëêçà]*)([^<]*<\/quote>)/i','$1<span style="background-color:#FFFFCC;border:1px dotted #CCCCCC;padding:0.2em;display : inline ;">$2</span>$3',$paragraph);
    return $paragraph;
}

function motsEnCours() {
	if ($_SESSION['login']!=''){
		if ($_SESSION['login']!='jducos'){
			$xq = "<div id='waitValid'>Vos mots en attente de validation:".queryBasex('for $i in distinct-values(for $x in db:open("encours")/entry[./valid="false"][./auteur="'.$_SESSION['login'].'"]/orth return <a href="index.php?motup={$x}">{$x}</a>) return <a href="index.php?motup={$i}">{$i}</a>')."</div>";	
		} else {
			$xq = "<div id='waitValid'>Mots en attente:".queryBasex('for $i in distinct-values(for $x in db:open("encours")/entry[./valid="true"]/orth return <a href="index.php?motup={$x}">{$x}</a>) return <a href="index.php?motup={$i}">{$i}</a>')."</div>";	
		}
	} else {
		$xq = "<div id='waitValid'>Quelques mots en train d'être rédigés:".queryBasex('for $i in distinct-values(for $x in db:open("encours")/entry[./valid="true"]/orth return <a href="index.php?motup={$x}">{$x}</a>) return <a href="index.php?motup={$i}">{$i}</a>')."</div>";
	}
	return $xq;
}
?>
