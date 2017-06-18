<!-- Xavier-Laurent Salvador, 2009-2017 -->
<?php
	setlocale (LC_TIME, 'fr_FR.utf8','fra'); 
	session_cache_limiter('private_no_expire');
	include "fonctions.php";
	include "modules.php";
	session_start();
	if (
	    isset($_POST["monMotifDeRecherche"]) && isset($_POST["motprems"]) && isset($_POST["countres"]) && isset($_POST["navig"])
	    ||
	    isset($_GET["monMotifDeRecherche"]) && isset($_GET["motprems"]) && isset($_GET["countres"]) && isset($_GET["navig"])
		) {

	     if (isset($_POST["monMotifDeRecherche"])) {$monMotifDeRecherche = $_POST["monMotifDeRecherche"];} elseif (isset($_GET["monMotifDeRecherche"])) {$monMotifDeRecherche = $_GET["monMotifDeRecherche"];}
	     if (isset($_POST["motprems"])) {$motprems = $_POST["motprems"];} elseif (isset($_GET["motprems"])) {$motprems = $_GET["motprems"];}
	     if (isset($_POST["navig"])) {$navig = $_POST["navig"];} elseif (isset($_GET["navig"])) {$navig = $_GET["navig"];}
	     //$pas = 10;
	     if (!isset($_POST["pas"]) || !isset($_GET["pas"])|| $_POST["countres"] = 0) {$pas = 10;}
	     if (isset($_POST["pas"])) {$pas = $_POST["pas"];} 
	     if (isset($_GET["pas"])) {$pas = $_GET["pas"];}  
	
  	     if ($motprems != "new") {	
		  if ($_POST["countres"] !='') { 
			if ($navig == "forward" || $navig="") {
			 $countres = $_POST["countres"]+ $pas - 1;
			} else {
			 if ($_POST["countres"]- $pas < 0) { 
			   $countres = 0;
			  } else {
			   $countres = $_POST["countres"]- $pas - 1 ;
			  }
			}	 
			//$pas = 10;
		  } else {
			$countres = 0;
			//$pas = 10;
		  }		
		} else {
		  $countres = 0;
		 // $pas = 10;
		}

		$seuilmax = $countres + $pas;
		if (preg_match('/[^ ] [^ ]/',$monMotifDeRecherche) && !preg_match('/[\?|\.|\*|\]]/',$monMotifDeRecherche)) 
		 {
		  $xqt='
		      all words ordered distance at most 5 words
		       '; }
		elseif (preg_match('/[\?|\.|\*|\]]/',$monMotifDeRecherche))
		 {
		  $xqt ='
			all words
			using wildcards
			using case insensitive
                        using diacritics insensitive
			';
		 } 
		else {
		  $xqt = '
			using stemming
                        using case insensitive
                        using diacritics insensitive
			';		
			 }
		$xq ='
		   let $google :=
		   (for $y score $sc in //entry
                    [./form/valid="true"]
                    [(.//text()) contains text {"'.$monMotifDeRecherche.'"} 
                        '.$xqt.' 
                     or 
                        (./sense/@mod,./sense/@med) contains text {"'.$monMotifDeRecherche.'"} 
		    ]

		    let $bonus := if ($y//orth contains text "'.$monMotifDeRecherche.'" using case insensitive) 
					then 5000
				  else if ($y//orth contains text "'.$monMotifDeRecherche.'" all words using case insensitive using wildcards) 
					then 4000
				  else if ($y//ref//text() contains text "'.$monMotifDeRecherche.'" using case insensitive)
					then 3500
				  else if ($y//def contains text "'.$monMotifDeRecherche.'" using case insensitive using stemming) 
					then 3000
				  else if ($y//quote contains text "'.$monMotifDeRecherche.'" using case insensitive using stemming)  
					then 2000
				  else 0 

                        
                        order by number($sc) + $bonus descending, $y/form/orth ascending 
                        
                        return
	 
                        <div id="res">
                         <div id="resres" score="{number($sc) + $bonus}" dom="">
			    {
				(
				 <a onclick="openInParent(this.href);" href="http://www.crealscience.fr/DFSM/consult/{data($y/form/orth)}">
				<div class="keyword dico {
					if (number($sc) + $bonus>4000)  
					then "gold"
					else ()
				}">{data($y/form/orth)}</div></a>
				 ,
				 for $i in distinct-values($y//sense/@med) return <div id="domax">{data($i)}</div>
				,
                                (:Les extraits:)
                                 if ($y//quote[.//text() contains text "'.$monMotifDeRecherche.'" using case insensitive]) 
                                    then 
                                     <div id="ext"> 
                                    {
                                    (
                                    for $xx in $y//quote
                                     [.//text() contains text "'.$monMotifDeRecherche.'" using case insensitive using wildcards] 
                                      return 
                                       ft:extract($xx[.//text() contains text "'.$monMotifDeRecherche.'" using case insensitive using wildcards],"mark",215)
                                    )[1]
                                    }
                                </div> 
                                else () 
				,
				if ($y//sense
                                 [.//text() contains text "'.$monMotifDeRecherche.'" using case insensitive using wildcards]//usg)
				then 
				<div id="mention">
				{
				for $yy in $y//sense
				 [.//text() contains text "'.$monMotifDeRecherche.'" using case insensitive using wildcards]//usg 
			         return 
				 (
				  <div id="sousent" class="keyword float">
				   <a href="http://www.crealscience.fr/monMotifDeRecherche.php?monMotifDeRecherche={replace(data($yy),"\.","")}&#38;motprems=vertu&#38;countres=1&#38;navig=1">
				   #{data($yy)}
				   </a> 
				 </div>
				)
				}</div>
				else ()
				)  
			   }
			   </div>
			   <div id="mentionsyno">
                           {
			     if (number($sc) + $bonus > 3500 and number($sc) + $bonus < 4000) 
                                then 
                                (
                                for $ii at $pp in distinct-values($y//ref//text()
                                [. contains text "'.$monMotifDeRecherche.'" using case insensitive using wildcards]) 
                                return  
                                <div id="resres">
				  <div id="ext">
				    <mark style="cursor:pointer; border: 5px dotted white ;" onClick="openInParent(href=\'http://www.crealscience.fr/monMotifDeRecherche.php?monMotifDeRecherche={data($ii)}&#38;motprems=vertu&#38;countres=1&#38;navig=1\')">#{data($ii)} ({$pp})
				    </mark>
				  </div>
				</div> 
                                ) 
                           else ()
                                }
			   </div>
             

                          {if (number($sc) + $bonus > 3500 and number($sc) + $bonus < 4000) 
				then () 
				else
				  <div id="resres" score="{number($sc) + $bonus}">
                          		<div id="ext">[...] 
				{for $p in ft:extract($y//text()[. contains text {"'.$monMotifDeRecherche.'"} '.$xqt.'],"mark",210) return $p}
                          		</div>
				  </div>}
                        </div>)
		      
		      let $total := count($google)
	
		      return

		      <div id="goo"> 
		       {	
		      for $res at $n in $google
		        where $n >= '.$countres.' and $n <= '.$seuilmax.'
			order by $res//orth collation "?lang=fr"	
		        return
			<div id="res">{ 
			 for $x in $res/div[1] 
			 return
			 if ($n=1 and number($x//@score) < 5000) 
				then 
				(
				<div id="resT" n="0"><div id="res"><div id="ext">Pas de fiche à cette vedette. <mark>{$total}</mark> résultats.</div></div></div>
				,
				<div id="resT" n="{$n}">{$x}{if (number($x//@score)>3500) then () else <a href="#">{$n}/{$total}</a>}
				</div>
				) 
				else 	
				<div id="resT" n="{$n}">
					<div id="footermonMotifDeRecherche">{$n}/{$total} - Résultat de la recherche {
					  if (data($x//@score) contains text {"500.*"} using wildcards)
					  then "sur les vedettes"
					  else if (data($x//@score) contains text {"^4.*"} using wildcards) 
					  then "sur les sous-entrées"
					  else "sur le corpus" 
						} &#171;'.$monMotifDeRecherche.'&#187; {for $t in $x//div[./@id="domax"] return <div id="dom">{data($t)}</div>}</div>
					{$x}
				</div>
		}	</div>
		       } 
		      </div>
		';
		$contenu =queryBasex($xq);
		
	} else {
	$contenu = "";
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<script type="text/javascript" src="./JS/ie_xml.js"></script>
		<script type="text/javascript" src="./JS/js.js"></script>
		<link rel="stylesheet" type="text/css" title="base" media="screen" href="http://www.crealscience.fr/CSS/dicobase.css"/>
		<link rel="stylesheet" type="text/css" title="base" media="screen" href="http://www.crealscience.fr/CSS/monMotifDeRecherchecss.css"/>
		<link rel="stylesheet" type="text/css" title="base" media="screen" href="http://www.crealscience.fr/CSS/dicoreq.css"/>
		<link rel="stylesheet" type="text/css" title="base" media="screen" href="http://www.crealscience.fr/CSS/dicodefxml.css"/>
		<link rel="icon" type="image/png" href="<?echo $logos[1];?>" />
		<link href='http://fonts.googleapis.com/css?family=Source Sans Pro|Syncopate|Belleza|Nixie+One|Open+Sans|PT+Serif+Caption|Eagle+Lake&subset=latin,latin-ext' rel='stylesheet' type='text/css'> 
		<link rel=”alternate” type=”application/rss+xml” title=”RSS” href=”/var/www/vhosts/i-def.fr/httpdocs/rss/rss.xml″ />
	</head>
	
	<body OnLoad=<?echo $bodyonload;?>>
	<div id="masque">.</div>
	<div id='global'>
			<div id="contenu">
        <div id="creallogin_hype_container" style="margin:auto;margin-top: 25px;position:relative;width:188px;height:224px;overflow:hidden;position:fixed; top: 30%;left:30%;z-index:5000;display:none;" aria-live="polite">
                <script type="text/javascript" charset="utf-8" src="http://www.crealscience.fr/crealLogin/crealLogin.hyperesources/creallogin_hype_generated_script.js?54271"></script>
                </div>
			 <div id="fondaffich">
			  <div class="texte">
			   <form id="monMotifDeRecherchex" action="monMotifDeRecherche.php" method="POST">
			    <input class="bg_form ok" id="monMotifDeRecherche" name="monMotifDeRecherche" type="text" onClick="document.getElementById('motprems').value='new';" value="<?php echo $monMotifDeRecherche;?>" list="vedette"/>
				<datalist id="vedette">
				<?	
				$liste = queryBasex('for $x in distinct-values(db:open("CREAL")//orth)
order by $x collation "?lang=fr" return <option label="{$x}" values="{upper-case($x)}"/>');
				echo "<option label='test' values='test'/>".$liste;
				?>
				</datalist>
			    <img src="http://www.i-def.fr/img/loupe.png" class="boutonR" onclick="document.forms['rech'].submit();" /><br/>
			    <label id="lab"><a href="http://www.i-def.fr/monMotifDeRecherche">monMotifDeRecherche</a> avec Crealscience</label>
       			    <input type="hidden" id="countres" name="countres" value="<?php global $pas; if ($countres=='1') {echo $pas - 1;} else {echo $countres + 1;} ?>" />
			    <input type="hidden" id="motprems" name="motprems" value="" />
			    <input type="hidden" id="navig" name="navig" value="" />
			   </form>
			<div id="" style="clear:both;border-bottom:1px solid black;">
			<a href="#" onClick="document.getElementById('navig').value='back';document.forms['monMotifDeRecherchex'].submit();"><img class="fleches loin" src="http://www.crealscience.fr/images/Preceding.png"/></a>   
			<a href="#" onClick="document.getElementById('navig').value='forward';document.forms['monMotifDeRecherchex'].submit();"><img class="fleches droite" src="http://www.crealscience.fr/images/Next.png"/></a></div>
			<div><?
				if ($contenu != '<div id="goo"/>') {
				  echo $contenu;
				} else {
				  echo "<p>Il n'y a plus de résultats.</p>";
				}
			?></div>
<textarea id="resultat" style="display:none;">COUCOUCOUCOUCOU</textarea>
			
			 <div id="" style="clear:both;border-bottom:1px solid black;margin-bottom:25px;">
                        <a href="#" onClick="document.getElementById('navig').value='back';document.forms['monMotifDeRecherchex'].submit();"><img class="fleches loin" src="http://www.crealscience.fr/images/Preceding.png"/></a>
                        <a href="#" onClick="document.getElementById('navig').value='forward';document.forms['monMotifDeRecherchex'].submit();"><img class="fleches droite" src="http://www.crealscience.fr/images/Next.png"/></a></div>

		     </div>
		    </div>
		  </div>
		<div id="footermonMotifDeRecherche" style="width:100%; position:fixed; bottom:1px; left: 0px; text-align: center; font-size: x-small; background-color:white; padding: 3px;">Moteur de Recherches :  Xavier-Laurent Salvador</div>
		</body>
	</html>

