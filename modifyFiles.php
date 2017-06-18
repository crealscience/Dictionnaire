<!-- Xavier-Laurent Salvador, 2017 -->
<?php
        setlocale (LC_TIME, 'fr_FR.utf8','fra');
	include "creal.class.php";
        $page = new pageCreal;
        include "fonctions.php";
	header('Location: http://www.crealscience.fr/deviation.php');
	 session_start();
	
	if ($_SESSION["authentification"] != "OK")
                header("Location:index.php");
        if ($_SESSION["groupe"] == "visiteur")
                header("Location:index.php");
        if (empty($_SESSION['langue'])){$_SESSION['langue']='fr';}

        if (isset($_GET['xmlmot'])) {
                $mot= $_GET['xmlmot'];
        } else { $mot = $_POST['proeditione'];}

        if (isset($_POST['txttest'])) {
                $return=$_POST['txttest'];
		/*$return = preg_replace_callback(
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
                        );*/
               	$return = cleanXML($return);
		/*$return = preg_replace('/^$/','',$return);*/
		$xq =  '
			declare namespace mml="http://www.w3.org/1998/Math/MathML";
			let $doc := '.$return.'
			let $dtdt := "/var/www/vhosts/crealscience.fr/httpdocs/DTD/creal.dtd"
			return (validate:dtd-info($doc,$dtdt))
		';
		$messageDTD = queryBasexBavard($xq);
		 if (preg_match('/Error/i',$messageDTD) || preg_match('/topped/i',$messageDTD)){
                   $messageDTD = preg_replace('/.CREAL./','','Cliquer pour effacer: '.$messageDTD);
		   registerIncidentMessage($_SESSION['login'],"Erreur en cours de Modification : ".preg_replace("#<([^>]*)>#","[$1]",$messageDTD));
		}
                else {$messageDTD=' Votre Fiche est valide.';}
        } else {
		if ($mot != '') //si ce n'est pas une nouvelle fiche 
		{
		//On va poser un verrou sur la fiche
		$xq = '
                if (not(db:open("encours")//entry[./orth="'.$mot.'"])) then
                insert node 
			<entry>
			<orth>'.$mot.'</orth>
			<auteur>'.$_SESSION['login'].'</auteur>
			<commentaire/>
			<date>{fn:current-dateTime()}</date>
			<valid>false</valid>
			</entry> 
			into root(db:open("encours")) 
                else 
			replace node db:open("encours")/entry[./orth="'.$mot.'"] with 
			<entry>
                        <orth>'.$mot.'</orth>
                        <auteur>'.$_SESSION['login'].'</auteur>
                        <commentaire/>
                        <date>{fn:current-dateTime()}</date>
                        <valid>false</valid>
                        </entry>
			';
		queryBasexBavard($xq);
		//////////// FIN VERROU \\\\\\\\\\\
		}

                $return=getDefMot($mot);
                $return = cleanXML($return);
        	$messageDTD = "Ok";
	}
        global $mot;

?>
<html>
        <head>
		<? echo $page -> createHeader(); ?>
		<? include 'indexPhp/codeMirrorHeader.php';?>
		<? include 'indexPhp/codeMirrorFonctions.php';?>
	</head>

<body onLoad="getInnerHtml();">
        <div id='global'>
	<div id="content" style="display:none;">
	<?php echo $return; ?>
	</div>
<form name="valid" method="post" action="Modifier.php">
<textarea name="txttest" id="txttest" style="display:none;"></textarea>
</form>
<div id='renvoidtd' style="margin-left: 20px; width: 250px; position: absolute; top:0; left:0;z-index:6000;padding: 10px; border: 10px #5083C0 solid;" onclick="document.getElementById('renvoidtd').style.display = 'none';"><? global $messageDTD; echo $messageDTD;?></div>


<div id="controle" style="overflow: auto; background: transparent;height: 98%; width: 45%; border-radius: 6px; border: 6px solid #5183C0;">
</div>

<div id="xml" style="height: 98%; width: 45%; float:left; border: 6px solid #5183C0;margin-left: 10px;">
<div id="contXml" style="height:70%;width: 100%;float:left;">
	<form id="formulaire" name='formulaire' method="post" action="ParseAndSave.php">
	<textarea name="txtdef" id="txtdef"><? echo $return; ?></textarea>
	</form>
</div>
<div id="tools" style="clear:both; width: 100%; margin-top: 15px;  margin-bottom: 10px;">
<img id="mesBoutons" src="http://www.crealscience.fr/images/vers-avant-icone-3791-64.png" onClick="Sauver();" width="40px"/>
<img id="mesBoutons" src="http://www.crealscience.fr/images/check-icon.png" onClick="validifier();" width="40px"/>
<img id="mesBoutons" src="http://www.crealscience.fr/images/34252.png" onClick="mute_width('controle');toggle_width('xml');" width="40px"/>
<a href="http://www.crealscience.fr/crealTuto/crealTuto.html"><img id="mesBoutons" src="http://www.crealscience.fr/images/Help.png" width="40px"/></a>
<a href="http://www.crealscience.fr"><img id="mesBoutons" src="http://www.crealscience.fr/images/c2c8465ce02042f2a5f915fbef5f1264.png" width="40px"/></a>
</div>

<div id="boutons" style="display:block; clear: both; margin-top: 10px;">

<div class="sample" style="float: left; margin-left: 3px;" id="sample-1"><input type="button" value="&lt;entry&gt;"><pre style="display:none;">&lt;entry&gt;
  &lt;form&gt; 
    &lt;orth&gt;VOTRE VEDETTE&lt;/orth&gt; 
  &lt;/form&gt; 
  &lt;gramgrp&gt;  
    &lt;gram type="pos"&gt;nom&lt;/gram&gt;
    &lt;gram type="gen"&gt;mas.&lt;/gram&gt;
  &lt;/gramgrp&gt; 
  &lt;sense med="XXX." mod="XXX."&gt; 
    &lt;def&gt;Texte de la définition&lt;/def&gt; 
    &lt;note id="Struct"&gt;    
      &lt;xr&gt;
        &lt;ref&gt;Ø&lt;/ref&gt;
      &lt;/xr&gt;
    &lt;/note&gt;    
    &lt;note id="encyclo"&gt;
      &lt;xr&gt;
        &lt;gloss&gt;texte encyclo&lt;/gloss&gt;
      &lt;/xr&gt;
    &lt;/note&gt;
    &lt;cit&gt;   
      &lt;quote&gt;texte de la citation&lt;/quote&gt;   
      &lt;bibl&gt;&lt;author&gt;auteur&lt;/author&gt; LIVRE&lt;/bibl&gt;  
    &lt;/cit&gt; 
  &lt;/sense&gt;
  &lt;id&gt;1000000&lt;/id&gt; 
&lt;/entry&gt;</pre>
</div>

<div class="sample" style="color: red; float: left; margin-left: 3px;" id="sample-1"><input type="button" style="color:red; text-decoration:underline; font-weight:bold;" value="&lt;RENVOI&gt;"><pre style="display:none;">
&lt;entry&gt;
  &lt;form&gt; 
    &lt;orth&gt;VOTRE VEDETTE&lt;/orth&gt; 
  &lt;/form&gt; 
&lt;xr type="V."&gt; 
&lt;ref&gt;Texte Renvoi&lt;/ref&gt;&lt;/xr&gt;
&lt;/entry&gt;
</div>

<div class="sample" style="float: left; margin-left: 3px;" id="sample-1"><input type="button" value="&lt;etym&gt;"><pre style="display:none;"> 
&lt;etym&gt;  
  &lt;bibl&gt;   
     &lt;etymosrc&gt;lat.?&lt;/etymosrc&gt;  
  &lt;/bibl&gt;  
  &lt;mentioned&gt;A CHERCHER&lt;/mentioned&gt; 
&lt;/etym&gt;</pre>
</div>

<div class="sample" style="float: left; margin-left: 3px;" id="sample-1"><input type="button" value="&lt;encyclo&gt;"><pre style="display:none;">&lt;note id="encyclo"&gt; 
  &lt;xr&gt;  
    &lt;gloss&gt;texte encyclo&lt;/gloss&gt; 
  &lt;/xr&gt;
&lt;/note&gt;</pre>
</div>

<div class="sample" style="float: left; margin-left: 3px;" id="sample-1"><input type="button" value="&lt;def&gt;"><pre style="display:none;">
&lt;def&gt;&lt;/def&gt;</pre>
</div>

<div class="sample" style="float: left; margin-left: 3px;" id="sample-1"><input type="button" value="&lt;orth&gt;"><pre style="display:none;">
&lt;form&gt;
&lt;orth&gt;VOTRE VEDETTE&lt;/orth&gt;
&lt;gramgrp&gt;
    &lt;gram type="pos"&gt;XXXX&lt;/gram&gt;
    &lt;gram type="gen"&gt;XXXX&lt;/gram&gt;
  &lt;/gramgrp&gt;
&lt;/form&gt;
</pre></div>

<div class="sample" style="float: left; margin-left: 3px;" id="sample-1"><input type="button" value="&lt;bibl&gt;"><pre style="display:none;">
&lt;bibl&gt;
    &lt;author&gt;auteur&lt;/author&gt; 
       LIVRE
    &lt;/bibl&gt;</pre></div>

<div class="sample" style="float: left; margin-left: 3px;" id="sample-1"><input type="button" value="&lt;sense&gt;"><pre style="display:none;">
&lt;sense med="XXX;" mod="XXX"&gt; 
  &lt;def&gt;Texte de la définition&lt;/def&gt; 
  &lt;cit&gt;  
    &lt;quote&gt;texte de la citation&lt;/quote&gt;  
    &lt;bibl&gt;
      &lt;author&gt;auteur&lt;/author&gt;
    &lt;/bibl&gt;  
  &lt;/cit&gt;
&lt;/sense&gt;</pre>
</div>

<div class="sample" style="float: left; margin-left: 3px;" id="sample-1"><input type="button" value="&lt;gramgrp&gt;"><pre style="display:none;">&lt;gramgrp&gt; 
  &lt;gram type="pos"&gt;nom&lt;/gram&gt; 
  &lt;gram type="gen"&gt;mas.&lt;/gram&gt;
&lt;/gramgrp&gt;</pre>
</div>

<div class="sample" style="float: left; margin-left: 3px;" id="sample-1"><input type="button" value="&lt;citation&gt;"><pre style="display:none;">
&lt;cit&gt; 
  &lt;quote&gt;texte citation&lt;/quote&gt; 
  &lt;bibl&gt;  
    &lt;author&gt;auteur&lt;/author&gt;  
  &lt;/bibl&gt;&lt;/cit&gt;</pre>
</div>

<div class="sample" style="float: left; margin-left: 3px;" id="sample-1"><input type="button" value="&lt;quote&gt;"><pre style="display:none;">
&lt;quote&gt;
  Texte citation
&lt;/quote&gt;</pre></div>

<div class="sample" style="float: left; margin-left: 3px;" id="sample-1"><input type="button" value="&lt;xr&gt;"><pre style="display:none;">
&lt;xr&gt;&lt;ref type="XXX"&gt;Texte Renvoi&lt;/ref&gt;&lt;/xr&gt;</pre></div>

<div class="sample" style="float: left; margin-left: 3px;" id="sample-1"><input type="button" value="ç"><pre style="display:none;">ç</pre></div>

<div class="sample" style="float: left; margin-left: 3px;" id="sample-1"><input type="button" value="Ç"><pre style="display:none;">Ç</pre></div>

<div class="sample" style="float: left; margin-left: 3px;" id="sample-1"><input type="button" value="∅"><pre style="display:none;">∅</pre></div>

<div class="sample" style="float: left; margin-left: 3px;" id="sample-1"><input type="button" value="Œ"><pre style="display:none;">Œ</pre></div>

<div id="biblio" style="float:left; width:100%; height: 130px; overflow:auto; background-color:#5183C0;">
<?
$xq ='
	for $x in db:open("CREAL_Bibliographie")//entry[not(.//titre="")] return 
	 <div class="sample" style="float: left; margin-left: 3px; padding: 3px; display:block; width: 100%; text-align: center;"  id="sample-1">
		<input type="button" style="width:80%;" value="&lt;{$x//titre} ({$x//authorbib})&gt;"/><pre style="display:none;">
			
 &lt;bibl&gt;
    &lt;author&gt;{data($x//authorbib)}&lt;/author&gt;
      {data($x//titre)} {if ($x//ed) then (", édition ",data($x//ed)) else ()} 
   &lt;bibxref&gt;{data($x/@id)}&lt;/bibxref&gt;
 &lt;/bibl&gt;
	</pre></div>
';
echo queryBasex($xq);
?>
</div>
</div>
</div>
<div id="footerIdefx" style="width:100%; position:fixed; bottom:0px; left: 0px; text-align: center; color: #A0A0A0; font-size: xx-small; background-color:white; padding: 3px;">Isilex par Xavier-Laurent Salvador</div>



<script>
	document.onreadystatechange = function () {
	if(document.readyState === "complete"){ 

	var dummy = {
        attrs: {
          gram: ["pos", "gen"],
          mod: ["MET.","MED.","MIN.","GEOL.","PHYS.","BOT.","ZOOL.","ING.","CHIM.","CHIR.","ANAT.","DIET.","PHARM.","OPT.","MAG.","DIVI.","MUSIQUE-THEORIE","ARITHM.","ALG.", "GEOM.","ASTR.","ASTRL.","AGR.","CONCEPT." ,"QUALITE.","ACTION.","AUTRE.","ARCHIT."],
          med: ["MET.","MED.","ARCH.","Sciences_Nat.","ASTR.","MATH.","MAG.","GEOM.","AGR.","METEO.","AGRIC.","AUTRE.","GENERAL.","ARTMEC."],
          id: ["Struct","encyclo"],
          type: ["syn","hyp","cohyp","var","V.","nomen"]
        },
        children: []
      };

      var tags = {
        "!entry": ["entry"],
        "!attrs": {
          id: null,
          class: ["A", "B", "C"]
        },
        sense: {
          attrs: {
            lang: ["en", "de", "fr", "nl"],
            freeform: null
          },
          children: ["animal", "plant"]
        },
        sense: dummy, note: dummy, xr: dummy, ref: dummy, gram: dummy,
        entry: dummy, bibl: dummy, quote: dummy
      };

      function completeAfter(cm, pred) {
        var cur = cm.getCursor();
        if (!pred || pred()) setTimeout(function() {
          if (!cm.state.completionActive)
            cm.showHint({completeSingle: false});
        }, 100);
        return CodeMirror.Pass;
      }

      function completeIfAfterLt(cm) {
        return completeAfter(cm, function() {
          var cur = cm.getCursor();
          return cm.getRange(CodeMirror.Pos(cur.line, cur.ch - 1), cur) == "<";
        });
      }

      function completeIfInTag(cm) {
        return completeAfter(cm, function() {
          var tok = cm.getTokenAt(cm.getCursor());
          if (tok.type == "string" && (!/['"]/.test(tok.string.charAt(tok.string.length - 1)) || tok.string.length == 1)) return false;
          var inner = CodeMirror.innerMode(cm.getMode(), tok.state).state;
          return inner.tagName;
        });
      }
	
	
	var editor = CodeMirror.fromTextArea(document.getElementById("txtdef"), {
        mode: "xml",
	lineWrapping: true,
        lineNumbers: true,
	extraKeys: {
          "'<'": completeAfter,
          "'/'": completeIfAfterLt,
          "' '": completeIfInTag,
          "'='": completeIfInTag,
          "Ctrl-Space": "autocomplete"
        },
	hintOptions: {schemaInfo: tags}
      });

	editor.setSize("100%", "95%");
	editor.on("change", function() {
    		document.getElementById('controle').innerHTML = editor.getValue();
		document.getElementById('txtdef').value = editor.getValue();
  });

	$(document).on('click', '.sample', function(){ 
	var text = $(this).find('pre').text();
	editor.replaceRange(text, { line: editor.getCursor().line, ch: editor.getCursor().ch });
});
	}}
    </script>
</body>
</html>
