<!-- Xavier-Laurent Salvador, 2009 - 2016 -->
<?php
	setlocale (LC_TIME, 'fr_FR.utf8','fra'); 
	include("BaseXClient2.php");
	include("queryBsx.php");
	session_start();
	
/********************    Variable   *****************/

$adminmail = split('#',file_get_contents('TXT_SITE/adm.islx'));

$choix_langue = array('en','it','fr');

$titre=array(
	'fr'=>file_get_contents('TXT_SITE/titre.fr.islx'),
	'en'=>file_get_contents('TXT_SITE/titre.en.islx'),
	'it'=>file_get_contents('TXT_SITE/titre.it.islx'),
);

$BoutonGauche=array(
	'fr'=>file_get_contents('TXT_SITE/boutons.fr.islx'),
	'en'=>file_get_contents('TXT_SITE/boutons.en.islx'),
	'it'=>file_get_contents('TXT_SITE/boutons.it.islx'),
);

/********************    Formulaire de validation **/


$form_Mot = file_get_contents('./TXT_SITE/formmot.xml');
/*********************    Les Petites Routines **/

$auteur = $_SESSION['login'];
$bdd = file_get_contents('TXT_SITE/bdd.islx');
$secrets = split(',',file_get_contents('TXT_SITE/secrets.islx'));
$ReqXq = split('#',file_get_contents('XQ/Site_Req.xq'));
$Message = split('#',file_get_contents('TXT_SITE/messages.'.$_SESSION['langue'].'.islx'));
$sec = split('#',file_get_contents('XQ/sec.xq'));
$logos = split('#',file_get_contents('TXT_SITE/logo.islx')); 
$bodyonload = '"document.forms[\'formmot\'].inputmot.focus();"';
$onfocus='"var val=this.value; this.value=\'\'; this.value= val;"';
$onkeyup='"document.forms[\'formmot\'].submit();"';
$stickyfocus = "<script type='text/javascript' language='JavaScript'> document.forms['formmot'].elements['inputmot'].focus();</script>";
$dtd = '<?xml version="1.0" encoding="utf-8" standalone="no"?>';
if (!empty($_SESSION["login"])) $auteur = $_SESSION["login"];
$jour = array("Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi");
$mois = array("","Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
$datefr = $jour[date("w")]." ".date("d")." ".$mois[date("n")]." ".date("Y");
$_SESSION['date'] = $datefr;

/********************    Fonctions  *****************/

function AffichXML($file) {
	$affichage = file_get_contents($file.'.xml');
	return $affichage;
}

function TraitementAffichage($x){
/*Barre d'outils*/
	global $ReqXq;
	preg_match('#<id>([^<]*)</id>#',$x,$idd);
	$favoid= $idd[0];
  	/*On affiche la barre*/
	if (preg_match('/<orth>/',$x)) {
		preg_match('/<orth>[^<]*<\/orth>/',$x,$orth);
		$mot = preg_replace('/<[^>]*>/','',$orth[0]);
		
		/*En cours de modification et par qui*/
		$wkinprogress = queryBasex(preg_replace('/<BASE>/','encours',preg_replace('/<VAR>/',$mot,$ReqXq[15])));
		$gold =	queryBasex(preg_replace('/<BASE>/','CREAL',preg_replace('/<VAR>/',$mot,$ReqXq[21])));
		
		//if ($gold != 'gold') {
		$grf = "";
		if ($_SESSION["login"] != "") {
			if ($_SESSION["groupe"] != 'visiteur' && $_SESSION["groupe"] != 'TIP') {
				$wiki = '<a style="display:none;" id="wikiGo" class="" href="/wiki/doku.php?id='.$mot.'">Wiki</a>';
				//if (file_exists("/var/www/vhosts/crealscience.fr/httpdocs/Graphes/graphe-".$mot.".html"))
				//	$grf = '<a class="boutonHype" style="text-decoration:none; background-color:#F0F0F0; color:black; border: 1px solid #A0A0A0; font-family: \'Helvetica\'; padding: 7px; font-size: small; text-align: center; display:block; margin-top:2px;" href="/Graphes/graphe-'.$mot.'.html">Graphe</a>';	
			//	else
			//		$grf = '<a class="ongl" href="'."/var/www/vhosts/crealscience.fr/httpdocs/Graphes/graphe-".mot.".html".'">Graphe</a>';
				$boutonnew = '<a class="boutonHype" style="text-decoration:none; background-color:#F0F0F0; color:black; border: 1px solid #A0A0A0; font-family: \'Helvetica\'; padding: 7px; font-size: small; text-align: center; display:block; margin-top:2px;" href="#" onclick="var isIE = /*@cc_on!@*/false || !!document.documentMode; if (!isIE) {document.getElementById(\'proeditione\').value=\'\';document.getElementById(\'edition\').submit();} else {alert(\'Pour éditer, utilisez Chrome, Safari ou firefox\')}">Nouveau</a>';
				
				if ($wkinprogress == $_SESSION['login']){ 
					
				  $modifier = '<a class="boutonHype" style="text-decoration:none; background-color:#F0F0F0; color:black; border: 1px solid #A0A0A0; font-family: \'Helvetica\'; padding: 7px; font-size: small; text-align: center;display:block; margin-top:2px;" href="#" onclick="var isIE = /*@cc_on!@*/false || !!document.documentMode; if (!isIE) {document.getElementById(\'proeditione\').value=\''.$mot.'\';document.getElementById(\'edition\').submit();} else {alert(\'Pour éditer, utilisez Chrome, Safari ou firefox\')}">Reprendre</a>';
				  
				} elseif ($wkinprogress==''){
					
					$modifier = '<a class="boutonHype" style="text-decoration:none; background-color:#F0F0F0; color:black; border: 1px solid #A0A0A0; font-family: \'Helvetica\'; padding: 7px; font-size: small; text-align: center;display:block; margin-top:2px;" href="#" onclick="var isIE = /*@cc_on!@*/false || !!document.documentMode; if (!isIE) {document.getElementById(\'proeditione\').value=\''.$mot.'\';document.getElementById(\'edition\').submit();} else {alert(\'Pour éditer, utilisez Chrome, Safari ou firefox\')}">Modifier la fiche</a>';
					
				} else {
					
					$modifier = '<a class="boutonHype" style="text-decoration:none; background-color:#F0F0F0; color:black; border: 1px solid #A0A0A0; font-family: \'Helvetica\'; padding: 7px; font-size: small; text-align: center;display:block; margin-top:2px;" href="#">Cf: '.$wkinprogress.'</a>';
					
				}
			}
		}
		else {
			$wiki = "";
			$boutonnew = "";
			$modifier = '<a class="" href="inscription.php">S\'inscrire</a>';
		}
			
		/*Récup mot avant, mot après*/
		$xq = preg_replace('/<VAR0>/',$mot[0],
				preg_replace('/<VAR>/',$mot,$ReqXq[10]));
		$motprecsqq = queryBasex($xq);
		$mottabsqq = preg_split("/,/",$motprecsqq);
		if ($mottabsqq[1]=='') {$mottabsqq[1]='DRAGON';}
		/*La variable pour l'éditabilité des fiches dans le dictionnaire*/
		if ($wkinprogress == $_SESSION['login'] || $wkinprogress=='' && $_SESSION['groupe'] != 'visiteur' && $_SESSION['groupe'] != 'TIP'){
			$editContent='false';
			$onclick='"copyContent(\'editEntry\',\'txtdef\');montrerDiv(\'formModifFly\');montrerDiv(\'renvoi\');"';
			$actutxtarea = 'actuTextarea();';
			}
		elseif  ($_SESSION['groupe'] == 'visiteur' || $_SESSION['groupe'] == 'TIP') {
			$editContent="false";			
			$onclick='"alert(\'Il ne vous est pas possible de modifier les fiches lexicographiques.\')"';
			$actutxtarea = '';
		}
		else {
			$editContent="false";
			$onclick ='';
			$actutxtarea = '';
			}
		$x = preg_replace('(<entry>)','
		<div id="onglets" style="display:none;">
				
				<!--<a href="index.php?motup='.preg_replace('/^ /','',$mottabsqq[1]).'"><img src="http://www.crealscience.fr/images/Preceding.png" class="fleches"></a>-->
				<a style="display:none;" id="myLinkBack" href="http://www.crealscience.fr/DFSM/consult/'.preg_replace('/^ /','',$mottabsqq[1]).'"><img src="http://www.crealscience.fr/images/Preceding.png" class="fleches"></a>
				<a class="boutonHype" style="text-decoration:none; background-color:#F0F0F0; color:black; border: 1px solid #A0A0A0; font-family: \'Helvetica\'; padding: 7px; font-size: small; text-align: center;display:block; margin-top:2px;" href="http://www.crealscience.fr/index.php?favoxq='.$favoid.'">Favoris</a>
				<a id="pdf" style="display:none;" class="" href="http://www.crealscience.fr/latex.php?inputxq='.$mot.'">PDF</a>
				<a style="display:none;" class="" href="#" onclick="var target = document.getElementById(\'editEntry\').innerHTML;
        alert(target);;">XML</a>
				
				'.$wiki.$grf.$modifier.$boutonnew.'
			
				<!--<a href="index.php?motup='.$mottabsqq[0].'"><img src="http://www.crealscience.fr/images/Next.png" class="fleches"></a>-->
				<a id="myLink" style="display:none;" href="http://www.crealscience.fr/DFSM/consult/'.$mottabsqq[0].'"><img src="http://www.crealscience.fr/images/Next.png" class="fleches"></a>
					
		</div>
		
		<div id="editEntry" style="display:none;" contentEditable="'.$editContent.'" onclick='.$onclick.' onblur="montrerDiv(\'boutonvalider\');" onkeyup="'.$actutxtarea.'"><entry>'
		,$x);
		$x= preg_replace('/(<sense )([^ ]* )([^>]*)(>)/','$1$2$3$4 <div id="attbalisessense"><div id="tagmed">$2</div><div id="tagmod">$3</div></div><br/>',$x);
		$x= preg_replace('/(<div id="tag...">)m.d=/','$1',$x);
		$x = preg_replace("/<ref>([^<]*)[^>]*>/","<ref><a href=\"http://www.crealscience.fr/DFSM/consult/$1\">$1</a></ref>",$x);
		$x = preg_replace("#(consult/) (.*)#","$1$2",$x);
		$x = preg_replace("/(<ref type=\"[^v][^>]*>)([^<]*)[^>]*>/","$1<a href=\"#\" onclick='post_form(\"$2\");'>$2</a></ref>",$x);
		$x = preg_replace("/(<ref type=\"[v]ar[^>]*>)([^<]*)[^>]*>/","$1 -- (Variante) -- <a href=\"#\" onclick='post_form(\"$2\");'>$2</a></ref>",$x);
		$x = str_replace("Synonyme","",$x);
		$x = str_replace("Hyponyme","",$x);
		$x = str_replace("Cohyponyme","",$x);
		$x = str_replace("Texte encyclopédique","",$x);
		$x= preg_replace('/\(<b>orig<\/b>\)/','',$x);
		$x=$x.'</div>';/*C'est là qu'on crée le formulaire java HTML5*/
		if ($wkinprogress == $_SESSION['login'] || $wkinprogress==''){ 
			$x=$x.'<div id="formModifFly" style="display:none;"><form name="formulaireDico" method="post" action="ParseAndSave.php">
			<div id="renvoi" style="display:none;">Vous entrez en zone de modifications. Modifier directement la fiche dans son contenu, vous ne risquez pas de détruire la structure XML. Appuyer sur <b>ENTREE</b> (clavier) pour valider le changement. Un message vous indiquera que les modifications sont enregistrées. Cliquer alors sur <b>Valider</b>. Pour annuler toutes vos modifications, appuyez sur <b>ESC</b> (clavier).</div><textarea style="display:none;" name="txtdef" id="txtdef" ></textarea>
			<div id="boutonvalider"><input class="buttonvalid" type="submit" value="Valider vos modifications"></div>
			<input type="hidden" name="mot" value="'.$mot.'"/>
			</form></div>';}		
	} elseif (preg_match('/class="texte">/',$x)) {
		if (!preg_match('/News/',$x)) {
			if ($_SESSION['authentification'] == 'OK' && $_SESSION['groupe'] == 'admin') {
				$x= preg_replace('/(class="texte">)/','$1'.file_get_contents('TXT_SITE/barre_modif.xml'),$x);
			} else if ($_SESSION['authentification'] == 'OK' && $_SESSION['groupe'] != 'visiteur') {
				$x= preg_replace('/(class="texte">)/','$1'.file_get_contents('TXT_SITE/barre_submit.xml'),$x);
			}
			else if ($_SESSION['authentification'] == 'OK' && $_SESSION['groupe'] == 'visiteur') {
				$x= preg_replace('/(class="texte">)/','$1'.file_get_contents('TXT_SITE/barre_submit_visi.xml'),$x);
			}
		}
		} else {
			$x= preg_replace('/(class="texte">)/','$1'.file_get_contents('TXT_SITE/barre_pequin.xml'),$x);
	}
	return $x;
}

function cleanXML($return){
		$return= preg_replace('#<div id=.stamp.>[^<]*</div>#','',$return);
		$return= preg_replace('/<div id=.attbalisessense.>/','',$return);
		$return= preg_replace('/<div id=.tagmed.>[^<]*<\/div>/','',$return);
		$return= preg_replace('/<div id=.tagmod.>[^<]*<\/div>/','',$return);		
		$return= preg_replace('/<\/div>/','',$return);	
		$return = preg_replace_callback(
                        '/(<orth>)([^<]*)(<\/orth>)/',
                        function ($matches) {
                        return $matches[1].strtoupper($matches[2]).$matches[3];
                        },
                $return
                        );
		$return = preg_replace_callback(
                        '/(<gram[^>]*>)([^<]*)(<\/gram>)/',
                        function ($matches) {
                        return $matches[1].strtolower($matches[2]).$matches[3];
                        },
                $return
                        );
		$return = preg_replace_callback(
                        '/(<ref>)([^<]*)(<\/ref>)/',
                        function ($matches) {
                        return $matches[1].strtoupper($matches[2]).$matches[3];
                        },
                $return
                        );
		$return = preg_replace_callback(
                        '/(<author>)([^<]*)(<\/author>)/',
                        function ($matches) {
                        return $matches[1].strtoupper($matches[2]).$matches[3];
                        },
                $return
                        );
		$return = preg_replace('/^$/','',$return);
		$return = preg_replace('/<id>[^<]*<\/id>/','',$return);
		return $return;
}

function Reinitialise($definition,$valid) {
	global $ReqXq;
	$today = queryBasex($ReqXq[11]);
	$definition = preg_replace("/<date>[^<]*<\/date>/","",$definition);
	$definition = preg_replace("/<auteur>[^<]*<\/auteur>/","",$definition);
	$definition = preg_replace("/<valid>[^<]*<\/valid>/","",$definition); 
	$definition = preg_replace("/<\/orth>/","</orth><date>".$today."</date><valid>".$valid."</valid><auteur>".$_SESSION['login']."</auteur>",$definition); 
	$definition = preg_replace("/<formula>/","<formula><![CDATA[",$definition);
	$definition = preg_replace("#</formula>#","]]></formula>",$definition);
	$definition = preg_replace("#</entry>.*#","</entry>",$definition);
	$definition = preg_replace("#.*<entry>#","<entry>",$definition);
	return $definition;
}

function AjouteId($x){
	$numid='(for $x in //id order by number($x) descending return $x)[1]+1';			 
	$ajoutid=queryBasex($numid);
	$chaineid='<id>'.$ajoutid.'</id></entry>';
	$x = preg_replace("#</entry>#",$chaineid,$x);
	$_SESSION['id'] = $chaineid;
	return $x;
}

function ParseIt($file) {
	$xmllint = array();
	exec('xmllint '.$file.' -dtdvalid DTD/creal.dtd 2>&1',$xmllint,$err);
	foreach ($xmllint as $lint) {
		if (preg_match('/error/', $lint)) {
		 $_SESSION["errorflag"] = 10;
		 $xmllint_error = $xmllint_error."<p>".$lint."</p>";
		 }
	}
	return $xmllint_error;
}

function courriel($mailto,$titre,$texte) {
	$mail = $mailto; // Déclaration de l'adresse de destination.
	if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)) // On filtre les serveurs qui rencontrent des bogues.
	{
		$passage_ligne = "\r\n";
	}
	else
	{
		$passage_ligne = "\n";
	}
	//=====Déclaration des messages au format texte et au format HTML.
	$message_txt = $texte;
	$message_html = "<html><head></head><body>".$texte."</body></html>";
	//==========
	//=====Création de la boundary
	$boundary = "-----=".md5(rand());
	//==========
	//=====Définition du sujet.
	$sujet = $titre;
	//=========
	//=====Création du header de l'e-mail.
	$header = "From: \"Crealscience\"<administrator@crealscience>".$passage_ligne;
	$header.= "Reply-to: \"Crealscience\" <administrator@crealscience>".$passage_ligne;
	$header.= "MIME-Version: 1.0".$passage_ligne;
	$header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
	//==========
	//=====Création du message.
	$message = $passage_ligne."--".$boundary.$passage_ligne;
	//=====Ajout du message au format texte.
	$message.= "Content-Type: text/plain; charset=\"UTF-8\"".$passage_ligne;
	$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
	$message.= $passage_ligne.$message_txt.$passage_ligne;
	//==========
	$message.= $passage_ligne."--".$boundary.$passage_ligne;
	//=====Ajout du message au format HTML
	$message.= "Content-Type: text/html; charset=\"UTF-8\"".$passage_ligne;
	$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
	$message.= $passage_ligne.$message_html.$passage_ligne;
	//==========
	$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
	$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
	//========== FI : pièce jointe
	/*file_put_contents("Latex/$mot.tex",$resultat);
	exec("cd Latex;xelatex $mot.tex",$output);
	$contenu = file_get_contents($fichier);
	$pj =  " ——=".'_parties_'.md5(uniqid (rand()));
	$pj .= "Content-Type: $typemime; name=\ »$nomPJ\" " ;
	$pj .= "Content-Transfer-Encoding: base64";
	$pj .= "Content-Disposition: attachment; filename=\ »$nomPJ\"";
	$pj .= chunk_split(base64_encode($contenu));*/
	//=====Envoi de l'e-mail.
	mail($mail,$sujet,$message,$header);
	//==========

}

function MailAjout($motpournomdefichier,$definition){
	   global $adminmail;
	   $messagexx = 'Votre nouvelle fiche pour le mot: <span style="color:red;">"'.$motpournomdefichier.'"</span> a été insérée directement dans le dictionnaire. une entrée est créée pour l\'historique';
	   $messagexxmail='Bonjour,<br/>Votre nouvelle fiche pour le mot: <span style=\"color:red;\">'.
	   $messagexxmail2='Bonjour,<br/>Une nouvelle fiche pour le mot: <span style=\"color:red;\">"'.$motpournomdefichier.'"</span> a été insérée directement dans le dictionnaire. une entrée est créée pour l\'historique. <br/> La fiche: -----------<br/>'.$definition.'<br/>-----------------<br/>Merci, <br/> Le CrealMailBot';
	   courriel($_SESSION['mail'],'Insertion: '.$motpournomdefichier,$messagexxmail);
	   foreach ($adminmail as $mail) {
		   courriel($mail,'Insertion: '.$motpournomdefichier,$messagexxmail2);
	   }
}

/* pour  autoriser l'accès à certaines lettre
modification des tests dans la fonction:

if ($_SESSION['authentification'] != 'OK') 
-->
if ($_SESSION['authentification'] != 'OK' && !preg_match("/^[C]/",$mot)) 

et 3 lignes en dessous 

 if ($_SESSION['authentification'] == 'OK') 
-->
 if ($_SESSION['authentification'] == 'OK' || preg_match("/^[C]/i",$mot)) 

l'ajout de nouvelles lettres se fait dans la regexp
*/

function getDefMot($mot,$page=1) {
	$nombre_de_caractères = strlen($mot);
	global $ReqXq;
	if ($nombre_de_caractères == 0) 
	{
		$ret = ""; 
	} elseif ($nombre_de_caractères == 1) 
	{
		if ($mot=='*') { //Les favoris
			 $fxq = '
				for $y in distinct-values(for $x in db:open("favoris")/entry[./nom="'.$_SESSION["login"].'"] return $x/id), $z in db:open("CREAL")/entry[./id=$y] return 
				<li>
					<a class="favo" href="http://www.crealscience.fr/DFSM/consult/{data($z/form/orth)}">{data($z/form/orth)}<span>{(for $x in db:open("CREAL")/entry[./id=$y] return 
					if ($x/sense/def) 
					then for $y at $p in $x/sense/def return ($p,") ",data($y)) else data($x))}</span></a>
					<a class="favo bin" href="#"  onclick="document.forms[\'deleteFav\'].delfavo.value=\'{$y}\' ;document.forms[\'deleteFav\'].submit();">
						<img src="http://www.crealscience.fr/images/recycle-bin-icons.png" height="20px"/>						
					</a>
				</li>
			 ';
			 $fav = queryBasex($fxq);
			 if ($fav != '') {
				$ret=  "
				<div id=\"roomliste\"><h2>Votre liste de mots favoris</h2><p>Passez la souris sur une vedette</p><div id=\"listevedette\" >
				<ul>".$fav."</ul>
				<form id='deleteFav' name='deleteFav' action='index.php' method='get'>
				<input type='hidden' name='delfavo' value=''>
				</form>
				</div>
				</div>";
			} else {
				$ret = '<div class="texte"><h2>Message d\'information</h2> Vous n\'avez pas encore constitué de liste de favoris. Pour ajouter un mot, cliquez sur le bouton "favori" puis naviguez dans vos favoris avec l\'étoile de l\'alphabet.</div>';
			}
		} else {
			$req1lettre = preg_replace('/<PAGE>/',$page,preg_replace('/<AUTEUR>/',$_SESSION['login'],preg_replace('/<VAR>/',$mot,$ReqXq[0])));
			$ret = queryBasex($req1lettre);
			if ($_SESSION['authentification'] != 'OK' && !preg_match("/^9[C]/i",$mot)) {
				$ret = preg_replace('/href="[^"]*"/','href="#" onclick="alert(\'Vous devez vous connecter. Pour ce faire, cliquer sur Login ou rendez-vous sur http://www.crealscience.fr/inscription.php\')"',$ret);				
			}
			if ($_SESSION['groupe'] == 'visiteur' && $mot != 'C') {
				header("Location:http://www.crealscience.fr/DFSM/fr/failAccess");				
			}
			$c = count(split('<li>',$ret));
			if ($c<25) {$colon='class="colonun"';}
			else if ($c>24 && $c<150) {$colon='class="colondeux"';}
			else {$colon='class="colontrois"';}
			preg_match_all('#>(.{2})[^<]*<#u',$ret,$indexdouble);
			//print_r(array_unique($indexdouble[1]));
			$anc = '<a href="#top"><img height="20px" src="http://www.crealscience.fr/images/2uparrow.png"/></a>';
			foreach (array_unique($indexdouble[1]) as $x){
				$anc = $anc.'<a href="#'.$x.'">'.strtoupper($x).'</a>';
				$ret = preg_replace('/(<[^>]*[^x]">)('.$x.')([^<]*)(<)/','<div id="'.$x.'" class="lettrineindex">$2</div>$1$2$3$4',$ret,1);
			}
			$anc = $anc.'<a href="#footer"><img height="20px" src="http://www.crealscience.fr/images/2downarrow.png"/></a>';
			$ret = "<div id=\"roomliste\"><div id='ancres'>".$anc."</div><div id=\"listevedette\" $colon><ul>".$ret."</ul></div></div>";
		}
	} else 
	{
		if ($_SESSION['authentification'] == 'OK' || preg_match("/^9[C]/i",$mot)) {
		/*Les champs vitaux sont orth / valid / date / auteur (?) */
			$xq1 = preg_replace('/<VAR>/',$mot,$ReqXq[1]);
			$xq11 = preg_replace('/<AUTEUR>/',$_SESSION['login'],preg_replace('/<VAR>/',$mot,$ReqXq[4]));
			$xq2 = preg_replace('/<VAR>/',$mot,$ReqXq[5]);	
			$xq3 = preg_replace('/<VAR>/',$mot,$ReqXq[6]);
			$xq4 = preg_replace('/<AUTEUR>/',$_SESSION['login'],(preg_replace('/<VAR>/',$mot,$ReqXq[0])));
			/*On revient à l'index*/		
		/*On prévoit*/
			$xqetym= preg_replace('/<VAR>/',$mot,$ReqXq[2]);
			$xqtrueaut = preg_replace('/<VAR>/',$mot,$ReqXq[3]);						
		/*On s'informe*/		
			$auteur = queryBasex(preg_replace('/<VAR>/',$mot,$ReqXq[7]));						
			$etat = queryBasex(preg_replace('/<VAR>/',$mot,$ReqXq[8]));
		/*On affiche*/
		if ($_SESSION["login"] != $auteur) {
			$cas="1";
			$ret = queryBasex($xq1);
		} else {
			$cas="2";
			$ret = queryBasex($xq11);
		}			
			if ($ret == "") 
			{
				$cas="3ii";
				$ret = queryBasex($xq2);
				if ($ret == "") {
					$cas = "5";
					$ret = queryBasex($xq3);
					if ($ret=="") {$ret = queryBasex($xq4);}
					$ret = "<div id=\"roomliste\"><div id=\"listevedette\" class=\"colons\"><ul>".$ret."</ul></div></div>";
					}
				}		
			} else {//Si pas connecté
				$ret = "<div id=\"roomliste\"><div id=\"listevedette\"><ul>Vous devez vous connecter. Cliquer sur Login ou se rendre sur <a href='http://www.crealscience.fr/inscription.php'>http://www.crealscience.fr/inscription.php</a></ul></div></div>";				
			}
		}
	//$ret = $ret."SESSION LOGIN = ".$_SESSION['login']." et AUTEUR=".$auteur."<p>Cas".$cas."</p><p>auteur:".$auteur."</p><p>login:".$_SESSION["login"]."</p><p>dernier etat: ".$etat."</p>";	
	//return "drapeau:".$cas.$ret;
	$ret = (preg_replace('#(<date>([^\-]*)-([^\-]*)-([^T]*)T.*</date>)#','<div id="stamp">Etat du cycle de la vedette au $4/$3/$2. La citation mentionne la date. Données libres pour la recherche et l\'enseignement.</div>$1',$ret));
	$ret = preg_replace('#mml:#','',$ret);
	$ret = preg_replace('#:mml#','',$ret);
	return $ret;
}

function transcribe($string) {
        $string = strtr($string,"\xA1\xAA\xBA\xBF\xC0\xC1\xC2\xC3\xC5\xC7\xC8\xC9\xCA\xCB\xCC\xCD\xCE\xCF\xD0\xD1\xD2\xD3\xD4\xD5\xD8\xD9\xDA\xDB\xDD\xE0\xE1\xE2\xE3\xE5\xE7\xE8\xE9\xEA\xEB\xEC\xED\xEE\xEF\xF0\xF1\xF2\xF3\xF4\xF5\xF8\xF9\xFA\xFB\xFD\xFF","!ao?AAAEACEEEEIIIIDNOOOOOUUUYaaaaaceeeeiiiidnooooouuuyy");
		$string = strtr($string, array("\xC8" => "E", "\xC4"=>"Ae", "\xC6"=>"AE", "\xD6"=>"Oe", "\xDC"=>"Ue", "\xDE"=>"TH", "\xDF"=>"ss", "\xE4"=>"ae", "\xE6"=>"ae", "\xF6"=>"oe", "\xFC"=>"ue", "\xFE"=>"th"));
		return($string);
}

function getUtilisateurs() {
	$req = queryBasex('data(for $x in db:open("utilisateur")//entry return <x>{data($x/nom)},</x>)');
	return preg_replace('/admin,/','',$req);
}

function getValidMots() {
	$rex = queryBasex('distinct-values(for $x in db:open("encours")//entry[.//valid="true"] return <x>{data($x/id)},{data($x/orth)},{data($x/auteur)},{data($x/commentaire)}#</x>)');
	return $rex;
}

function getValidMots2($x) {
	if ($x=='') $x=1;
	$rex = queryBasex('
	for $x in ('.$x.' to '.$x.'+2) return (
	let $ved := distinct-values(for $x in db:open("encours")//entry[.//valid="true"]/orth return $x)
	for $t in $ved, $x in db:open("encours")/entry[./orth=$t][.//valid="true"][1] order by $x/id descending return 
	<p id="resadmin">
		<a href="#" id="{data($x/orth)}" style="text-decoration:none;margin:0px;" onclick="document.forms[\'validmot\'].affichmot.value=\'{$x/id}\' ;document.forms[\'validmot\'].submit();">{data($x/orth)}</a>	
		<a href="#" onclick="document.forms[\'validmot\'].valid.value=\'{$x/id}\' ;document.forms[\'validmot\'].submit();"><img style="vertical-align:text-bottom;" height="15px" src="images/dagobert83_apply.png"/></a>
		<a href="#" style="vertical-align:-2px" onclick="document.forms[\'validmot\'].cancelvalid.value=\'{$x/id}\' ;document.forms[\'validmot\'].submit();"><img height="15px" src="images/kuba_information_icons_set_5.png"/></a>
		<div id="comment">Commentaire de {data($x/auteur)}:<br/>{$x/commentaire}</div>
	</p>)[$x]
	');
	$rex2 = queryBasex('for $x in (1 to 42) return $x');	
	return $rex;
}

function WriteNews($text) {
	$file= './TXT_SITE/fr/Actus.xml';
	$fp = fopen($file,'w+');
	fputs($fp,$text);
	fclose($fp);
	return 'OK';
}

function Drapeaux($texte) {
	global $choix_langue;
	$r = '<div id="colpropreleft">';
	foreach ($choix_langue as $l) {
		//$r = $r. '<li class="drapeaux">
		//	  <a href="index.php?langue='.$l.'"><img width=30 height=19 src="http://www.crealscience.fr/TXT_SITE/'.$l.'/flag.png"></a>			
		//	 </li>';		
		$r = $r. '<li class="drapeaux"><a href="http://www.crealscience.fr/DFSM/'.$l.'/Accueil"><img width=30 height=19 src="http://www.crealscience.fr/TXT_SITE/'.$l.'/flag.png"></a> </li>';
		}	
		$r = $r. '</div>';
		return $r;
}

function ajouteMark($mot) {
	global $ReqXq;
	$req= preg_replace('/<VAR>/',$mot,$ReqXq[20]);
	queryBasex($req);
	return 'GRAAL ON: Marquage effectué';
}


function lit_rss($fichier,$objets) {
 
	// on lit tout le fichier
	if($chaine = @implode("",@file($fichier))) {
 
		// on découpe la chaine obtenue en items
		$tmp = preg_split("/<\/?"."item".">/",$chaine);
 
		// pour chaque item
		for($i=1;$i<sizeof($tmp)-1;$i+=2)
 
			// on lit chaque objet de l'item
			foreach($objets as $objet) {
 
				// on découpe la chaine pour obtenir le contenu de l'objet
				$tmp2 = preg_split("/<\/?".$objet.">/",$tmp[$i]);
 
				// on ajoute le contenu de l'objet au tableau resultat
				$resultat[$i-1][] = @$tmp2[1];
			}
 
		// on retourne le tableau resultat
		return $resultat;
	}
}

function indexCahier($dom) {
        $xq = '
          import module namespace creal = "http://www.crealscience.fr";
          for $x in creal:indexCahier("'.$dom.'") return $x
        ';
        $res = queryBasexBavard($xq);
        return $res;
}
				
/********************************
affichage AND
********************************/
function afficheAND($mot) {
	$mot = strtolower(preg_replace("/\(AND\)/","",$mot));
	$urldep = "http://www.anglo-norman.net/D/".$mot;
	$txt = $mot;
	$txt = file_get_contents($urldep);
	$txt = preg_replace("/<a href.*?><img.*?><.a>/"," ",$txt);
	$txt = preg_replace("#<span[^>]*>#","",$txt);
	$txt = preg_replace("#</span>#","",$txt);
	$txt = preg_replace("#<script>.*</script>#","",$txt);
	$txt = "<entry><orth>$mot</orth>".$txt."</entry>";
	return($txt);
}

?>
