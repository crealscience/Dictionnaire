<!-- Xavier-Laurent Salvador -->
<?
class pageCreal {
	private $_header;
	private $_titre;
	private $_colonne;
	private $_contenu;
	private $_menuGauche;
	private $_alphabet;
	private $_footer;
	private $_ariane;

	public function createHeader() {
		$this -> _header = file_get_contents('indexPhp/header.php');
		return $this -> _header;
	}

	public function createFilAriane() {
		 $this -> _ariane = file_get_contents('indexPhp/indexFilAriane.php');
		 return $this -> _ariane;
	}

	public function createFooter() {
		$this -> _footer = file_get_contents('indexPhp/indexFooter.php');
		return $this -> _footer;
	}
	
	public function createAlphabet($connexion, $groupe) {
	  if ($groupe == "visiteur") 
		{$message = '<div id="xx">Cher Visiteur, cliquez ici la lettre </div id="xx">';}
	  $preAlpha = '
		<form name="alphabet" action="'.$_SERVER["PHP_SELF"].'?langue="'.$_SESSION['langue'].'" method="post">
	        <input type="hidden" name="indexlettreonglet" value="">'.$message;
	  if ($groupe == "visiteur") {$messageFin = '<div id="xx"> (en cours). Les autres lettres seront prochainement accessibles.</div>';}
	  $postAlpha = $messageFin.'</form>';

	 if ($connexion == 'OK') {
          if ($groupe == 'visiteur')
           {
                 $this->_alphabet='<div id="xx"><a href="http://www.crealscience.fr/DFSM/consult/CAAB">C</a></div>';
           } else {
                 $this->_alphabet=file_get_contents('/var/www/vhosts/crealscience.fr/httpdocs/TXT_SITE/modules/alphabet.xml');
           }
         }
	else {
	 if ($groupe == 'visiteur') 
	   { 
		 $alphabet='C';
	   }  else { 
		 $alphabet= split(',',file_get_contents('TXT_SITE/alphabet'));
	   }
		for($i = 0; $i < sizeof($alphabet)+1; ++$i)
		if ($alphabet[$i] != '') {
			$this->_alphabet = $this->_alphabet.'<div id="xx"><a href="http://www.crealscience.fr/DFSM/consult/'.chop($alphabet[$i]).'">'.chop($alphabet[$i]).'</a></div>';
			}
		if ($_SESSION["login"]=='coucou') {
		$this->_alphabet = '<div id="xx"><a class="etoile" href="#" onclick="document.forms[\'alphabet\'].indexlettreonglet.value=\'*\';
   		 document.forms[\'formmot\'].elements[\'inputmot\'].value=\'*\';
		 document.forms[\'alphabet\'].submit();"><img src="http://www.crealscience.fr/images/Etoile V2.png" style="height:15px; text-align:center;"/></a></div>';}
		
	      }
	return $preAlpha.$this->_alphabet.$postAlpha; 
	}

	public function createMenuGauche($texte) {
	$preMenu = ' <ul id="menucote">';
	$postMenu = ' </ul>
                <div id="crealpub_hype_container" style="margin:auto;position:relative;width:80px;height:125px;overflow:hidden;background-color: white; border-radius:20px;border: 6px solid #D9CEAC; display:block; clear:both;" aria-live="polite">
                <script type="text/javascript" charset="utf-8" src="http://www.crealscience.fr/crealPub/crealPub.hyperesources/crealpub_hype_generated_script.js?73963"></script>
        </div>	 
		';
	$bouton = split(',',$texte);
	$bouton = split(',',$texte);
	array_shift($bouton); 
	foreach ($bouton as $b) {
		if (!preg_match('/Login/',$b)) {
			if (!preg_match('/Isilex/',$b)) {
				if (preg_match('/Chercher/',$b) || preg_match('/Find/',$b)) {
                                	$b = '<li><a href="idefx.php">'.$b.'</a></li>';
                        	} else {
					if (!preg_match('/olloque/',$b)) 
						{$b = '<li><a href="http://www.crealscience.fr/DFSM/'.$_SESSION['langue'].'/'.$b.'">'.$b.'</a></li>';}
					else {$b = '<li><a href="http://www.crealscience.fr/Colloque">Colloque</a></li>';}
}
			} else {$b='<li><a href="http://www.isilex.fr">'.$b.'</a></li>'; }
		} else {
			if (!$_SESSION['authentification'] == 'OK') {
				$b = '<li><a class="login" href="#" onClick="document.getElementById(\'creallogin_hype_container\').style.display = \'block\';document.getElementById(\'masque\').style.display = \'block\';">'.$b.'</a></li>'; 
			}else {
				$b = '<li><a class="exit" href="http://www.crealscience.fr/index.php?key=exit">Exit</a></li>';
		}
	    }
	 $this->_menuGauche = $this->_menuGauche.$b;
	 }
	 global $form_Mot;
	 return $preMenu.
		$this->_menuGauche.
		preg_replace('<MOT>',$mot,$form_Mot).
		Drapeaux($BoutonGauche[$_SESSION['langue']]).
		$postMenu;
	}

	public function createMathMl() {
		$this -> _header = file_get_contents('indexPhp/indexMathMl.php');
		return $this -> _header;
	}

	public function createTitre($lg) {
        	global $logos;
		$titreX=array(
  	      	'fr'=>file_get_contents('TXT_SITE/titre.fr.islx'),
        	'en'=>file_get_contents('TXT_SITE/titre.en.islx'),
        	'it'=>file_get_contents('TXT_SITE/titre.it.islx'),
		);
                $this -> _titre = '<div id="hautcote">
                       <a href="http://www.isilex.fr/"><img class="perso5" src="'.$logos[0].'" height="70" width="40"/></a>
                                    </div>
                        <div id="titrepos1">
                                 '.$titreX[$lg].'
				</div>
                                                ';
                return $this -> _titre;
	}

	public function createColonne() {
		$this -> _colonne = "coucou";
		if ($_SESSION['groupe'] == "admin") {
        $barre = '
        <a href="http://www.crealscience.fr/Admin.php"><img src="http://www.crealscience.fr/images/Office-Girl-icon.png" width="20px"/> Admin </a>||
        <a href="http://www.crealscience.fr/Modifier.php"><img src="http://www.crealscience.fr/images/26096.png" width="20px"/> Nouveau </a>||
        <a href="http://www.crealscience.fr/Configuration.php"><img src="http://www.crealscience.fr/images/key_PNG1174.png" width="20px"/> Configurer </a>||
        <a href="http://www.crealscience.fr/DFSM/consult/*"><img src="http://www.crealscience.fr/images/Etoile V2.png" width="20px"/></a>  
        ';
        }
        if ($_SESSION['groupe'] == "user") {
        $barre = '
        <a href="http://www.crealscience.fr/Modifier.php"><img src="http://www.crealscience.fr/images/26096.png" width="20px"/> Nouveau </a>||
        <a href="http://www.crealscience.fr/Configuration.php"><img src="http://www.crealscience.fr/images/key_PNG1174.png" width="20px"/> Configurer </a>||
        <a href="http://www.crealscience.fr/DFSM/consult/*"><img src="http://www.crealscience.fr/images/Etoile V2.png" width="20px"/>Favoris</a>  
        ';
        }
        if ($_SESSION['groupe'] == "visiteur") {
        $barre = '
        <a href="http://www.crealscience.fr/Configuration.php"><img src="http://www.crealscience.fr/images/key_PNG1174.png" width="20px"/> Configurer </a>||
        <a href="http://www.crealscience.fr/DFSM/consult/*"><img src="http://www.crealscience.fr/images/Etoile V2.png" width="20px"/>Favoris</a>  
        ';
        }
        if ($_SESSION['authentification'] != "OK") {
        $barre = '
        <a href="http://www.crealscience.fr/inscription.php"><img src="http://www.crealscience.fr/images/key_PNG1174.png" width="20px"/> Inscription </a>
';
        }
        global $datefr;
         $info = getInfoBase();
         $motdj = defDuJour();
         $nomenclature = queryBasexBavard('for $x in doc("/var/www/vhosts/crealscience.fr/httpdocs/TXT_SITE/fr/nomenclature.xml")/p/option return $x');
         $colonne = file_get_contents('TXT_SITE/modules/colonne.xml');
         preg_match('/<orth>([^<]*)<\/orth>/',$motdj,$orth);
         $this -> _colonne = '
           <form id="transidef" action="http://www.crealscience.fr/idefx.php#resT" method="post" onSubmit="target_popup(this);">
    <!--a href="http://www.crealscience.fr/ontologie">Le Lab "Synonymie"</a-->
    <input class="bg_form ok" placeholder="Recherche avec troncatures" width="20px" id="idefx" type="text" name="idefx" list="vedette"  />
        <datalist id="vedette">'.$nomenclature.'</datalist>
        <input type="hidden" name="countres" value="1">
        <input type="hidden" id="countres" name="countres" value="" />
                            <input type="hidden" id="motprems" name="motprems" value="" />
        <input type="hidden" id="navig" name="navig" value="" />
  </form>
        <div id="crealnews_hype_container" style="margin:auto;position:relative;width:184px;height:80px;overflow:hidden;border-radius:20px;" aria-live="polite">
                <script type="text/javascript" charset="utf-8" src="http://www.crealscience.fr/crealNews/crealNews.hyperesources/crealnews_hype_generated_script.js?12036"></script>
        </div><!--
        '.$datefr.'-->
        '.highLight($motdj,$orth[1]).
        "<div id='ariane' style='display:none;'>".$barre."</div>"
        .$colonne.$info.' <!--'.motsEnCours().'--> Aucune reproduction, même partielle, autres que celles prévues à l\'article L 122-5 du code de la propriété intellectuelle, ne peut être faite de ce site sans l\'autorisation expresse de l\'auteur.<a href="//plus.google.com/115962454725173392900?prsrc=3"
        rel="publisher" target="_top" style="text-decoration:none;display:inline-block;color:#333;text-align:center; 
        font:13px/16px arial,sans-serif;white-space:nowrap;">
        <img src="//ssl.gstatic.com/images/icons/gplus-32.png" alt="Google+" style="border:0;width:16px;height:16px;"/>
        </a>
        ';
		return $this -> _colonne;
	}

	public function createContenu($affichage, $mot) {
	if (preg_match('/crealtexte/',$affichage) || preg_match('/<orth>/',$affichage) || preg_match('/roomliste/',$affichage)) {
                        $this -> _contenu = TraitementAffichage($affichage);
                        if (preg_match('/<orth>/',$affichage)) {
                        $dom = preg_match('#<med="([^"]*)"#',$affichage);
                        $domaine = $dom[1];
                        $xq = ('(for $x in distinct-values(for $p in db:open("CREAL")//entry[starts-with(.//orth,"'.$mot[0].'")] order by $p//orth collation "?lang=fr-FR" return $p//orth) return <a style="text-decoration:none; background-color:#F0F0F0; color:black; border: 1px solid #A0A0A0; font-family: \'Helvetica\'; padding: 7px; font-size: small; text-align: center; margin-top: 2px; display:block;" href="http://www.crealscience.fr/DFSM/consult/{data($x)}">{data($x)}</a>)');
                        $this -> _contenu = $this -> _contenu.'<div id="motUp" style="display:none;">'.$mot.'</div>';

        if ($_SESSION["groupe"] == "visiteur") {
        $xqq = ('
        (let $liste := (distinct-values(for $x in db:open("CREAL")//entry[.//text() contains text "'.$mot.'" using stemming]//orth order by $x collation "?lang=fr-FR" return $x), db:open("CREAL")//entry[./form/orth="'.$mot.'"]//ref[.=db:open("CREAL")]//orth) 
        return for $x in distinct-values($liste)  order by $x collation "?lang=fr-FR" return 
        <a style="text-decoration:none; background-color:#F0F0F0; color:black; border: 1px solid #A0A0A0; font-family: \'Helvetica\'; padding: 7px; 
font-size: small; text-align: center; margin-top: 2px; display:block;" href="{if (starts-with($x,\''.$mot[0].'\')) then \'http://www.crealscience.fr/DFSM/consult/\'||$x else \'http://www.crealscience.fr/DFSM/consult/'.$mot[0].'\'}">{data($x)}</a>)');
        }
        else {
         $xqq = ('
        (let $liste := (distinct-values(for $x in db:open("CREAL")//entry[.//text() contains text "'.$mot.'" using stemming]//orth order by $x collation "?lang=fr-FR" return $x), db:open("CREAL")//entry[./form/orth="'.$mot.'"]//ref[.=db:open("CREAL")]//orth) 
        return for $x in $liste  order by $x collation "?lang=fr-FR" return 
        <a style="text-decoration:none; background-color:#F0F0F0; color:black; border: 1px solid #A0A0A0; font-family: \'Helvetica\'; padding: 7px; 
font-size: small; text-align: center; margin-top: 2px; display:block;" href="http://www.crealscience.fr/DFSM/consult/{data($x)}">{data($x)}</a>)');
        }
        
                       	$this -> _contenu = $this -> _contenu.'<div id="hideIndex" style="display:none;"><br/>'.queryBasex($xqq).'</div>';
                    	$this -> _contenu = $this -> _contenu.'<div id="hideIndex2" style="display:none;"><br/>'.queryBasex($xq).'</div>';

                        $nomenclature = queryBasexBavard('for $x in doc("/var/www/vhosts/crealscience.fr/httpdocs/TXT_SITE/fr/nomenclature.xml")/p/option return $x');
                        $this -> _contenu = $this -> _contenu.'<form id="transidef" style="z-index: 10000; position:absolute; left: 50px; top: 290px;" action="http://www.crealscience.fr/idefx.php#resT" method="post" onSubmit="target_popup(this);">
    <input class="bg_form2 ok" style="width: 90px;" width="20px" placeholder="Recherche" id="idefx" type="text" name="idefx" list="vedette"  />
        <input type="Submit" Value="OK"/>
        <input type="hidden" name="countres" value="1">
        <input type="hidden" name="motUpIdefx" value="'.$mot.'">
        <input type="hidden" id="countres" name="countres" value="" />
                            <input type="hidden" id="motprems" name="motprems" value="" />
        <input type="hidden" id="navig" name="navig" value="" />
  </form>
                        ';

                        $this -> _contenu = $this -> _contenu.'<div id="crealconsult_hype_container" style="margin:auto;position:relative;width:1000px;height:800px;overflow:hidden;position:absolute; top:0px; left:60px;" aria-live="polite">
                <script type="text/javascript" charset="utf-8" src="http://www.crealscience.fr/crealConsult/crealConsult.hyperesources/crealconsult_hype_generated_script.js?50399"></script>
        </div>';}
                } else {
                        $this -> _contenu = $this -> _contenu.'<div id="centrehype">'.TraitementAffichage($affichage).'</div>';
                }
                if (preg_match('/<orth>/',$affichage) || preg_match('/roomliste/',$affichage) || preg_match('/News/',$affichage) ){
                } else {
                $this -> _contenu = $this -> _contenu.'<div id="colonne">';
                        echo $page -> createColonne();
                        echo '</div>';
                 }
                 return $this -> _contenu;	
	}
}
?>
