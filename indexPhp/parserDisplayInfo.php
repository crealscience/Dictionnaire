<?
        global $definition;
        /*Sauvegarde Basex*/
        $validxq =preg_replace(
  '/<DOCXML>/',$definition,$ReqXq[22]
);
        $add = queryBasexBavard(
  $validxq
);
        global $mot;
        global $file;
        global $valid;
        $MesgBasexAdd = $add;
        if (
  preg_match(
    '/Error/i',$add
  ) || preg_match(
    '/Stopped/i',$add
  )
) {
                registerIncidentMessage(
    $_SESSION['login'],"Erreur Sauvegarde: ".$_SESSION['login']." :: ".preg_replace(
      "#<(
        [^>]*
      )>#","[$1]",preg_replace(
        '#CREAL#','',$add
      )
    )
  );
                /*On stocke le fichier erronné*/
                $nom_fichier = preg_match (
    "/(
      orth>
    )(
      [^<]*
    )(
      <.*
    )/", $definition, $nomfichierAAANEW
  );
                $mot = preg_replace(
    '/[ÏïÔôÛûüÜ]/','O',
                                        preg_replace(
      '/[çÇ]/','C',
                                                preg_replace(
        '/[ÉÈÊÂêâëËë]/','E',$nomfichierAAANEW[2]
      )
    )
  );
                $file = '/var/www/vhosts/crealscience.fr/httpdocs/fail/'.$mot.'.xml';
                $fp = fopen(
    $file,'w+'
  );
                fputs(
    $fp,'<fiche><message>'.$add.'</message>'.$definition.'</fiche>'
  );
                fclose(
    $fp
  );
                echo "<div class='texte'>";
                echo '<img src="images/kuba_information_icons_set_5.png" width="25px">'.$Message[2].'</h3><br/><span style="color:red;">'.preg_replace(
    '/..DICMODIF\/[^x]*xml:/','',$add
  ).'</span>';
                echo preg_replace(
    '/<FILE>/','fail',$Message[3]
  ).'<a class="ongl" href="#" onclick="document.getElementById(
    \'proeditione\'
  ).value=\''.$mot.'\';document.getElementById(
    \'edition\'
  ).submit();">Recommencer</a>'."</div>";
                echo '<div id="bla">'.AffichXML(
    'TXT_SITE/fr/errorXML'
  ).'</div>';
                }
        else {
		echo "XXXXXXXXXXXXXXXXXXXXXXXX id:   ";
                global $mot;
                preg_match(
    '#<orth>(
      [^<]*
    )</orth>#',$definition,$word
  ); // Pour je ne sais quelle obscure raison, la vedette saute.
                if (
    $mot==''
  ) {
    $mot=$word[1];
  }
                preg_match(
    '#<id>(
      [^<]*
    )</id>#',$definition,$idadded
  );
		echo $idadded[1];
                $formval = preg_replace(
    '/<ID>/',preg_replace(
      '/<[^>]*>/','',$idadded[1]
    ),file_get_contents(
      './TXT_SITE/formval.xml'
    )
  );
                echo "<div id='renvoi' style='width: 450px; height:80px;padding: 15px;'><img src='images/icone_voyant_vert.gif' width='15px' style='margin-right:10px;'>Parser: OK pout Insertion du Mot: ".$mot."<br/><p>Si la vedette n'apparaît pas au moment de la sauvegarde mais que le message du Parser est OK, contactez l'administrateur de toute urgence.</p></div>";
                echo ajouteMark(
    $mot
  );
                if (
    $valid!='newadd'
  )
                {
		  echo " XXXXX ";	
                  echo $messagexx.'.  '.$add.preg_replace(
      '/<TXT>/',$definition,preg_replace(
        '/<VAR>/',$mot,$formval
      )
    );
                } else {
        	  echo "YYYYYYYYYYY";      
	          echo '<br/><p>Votre fiche a été sauvée. <p><a href="index.php"  style="text-decoration:none; background-color:#F0F0F0; color:black; border: 1px solid #A0A0A0; font-family: \'Helvetica\'; padding: 7px; font-size: small; text-align: center; float:left; margin-top:2px;width: 150px; margin-left: 0px;">D\'accord</a></p></p>';
                }
               
		///////////////// VERROUR \\\\\\\\\\\\\\\\\\
		//queryBasexBavard(
    preg_replace(
      '/<ETAT>/','false',preg_replace(
        '/<AUTEUR>/',$_SESSION['login'],preg_replace(
          '/<BASE>/','encours',preg_replace(
            '/<ORTH>/',$mot,preg_replace(
              '/<ID>/',preg_replace(
                '/<[^>]*>/','',$_SESSION['id']
              ),$ReqXq[12]
            )
          )
        )
      )
    )
  );
		//queryBasex(
    'let $node := <entry><id>'.$_SESSION['id'].'</id><orth>'.$mot.'</orth><auteur>'.$_SESSION['login'].'</auteur><commentaire></commentaire><valid>false</valid></entry> return "YYYYYYY"||$node'
  );

		$xq = '
		let $id := string(
    '.preg_replace(
      '/<[^>]*>/','',$_SESSION['id']
    ).'
  )
		return
                if (
    not(
      db:open(
        "encours"
      )//entry[./orth="'.$mot.'"]
    )
  ) then
                insert node 
                        <entry>
                        <id>{
    $id
  }</id>
                        <orth>'.$mot.'</orth>
                        <auteur>'.$_SESSION['login'].'</auteur>
                        <commentaire/>
                        <date>{
    fn:current-dateTime()
  }</date>
                        <valid>false</valid>
                        </entry> 
                        into root(
    db:open(
      "encours"
    )
  ) 
                else 
                        replace node db:open(
    "encours"
  )/entry[./orth="'.$mot.'"] with 
                        <entry>
                        <id>{
    $id
  }</id>
                        <orth>'.$mot.'</orth>
                        <auteur>'.$_SESSION['login'].'</auteur>
                        <commentaire/>
                        <date>{
    fn:current-dateTime()
  }</date>
                        <valid>false</valid>
                        </entry>
                        ';
                queryBasex(
    $xq
  );



                if (
    $valid!='newadd'
  ){
                                courriel(
      $_SESSION['mail'],preg_replace(
        '/<MOT>/',$mot,$Message[9]
      ),preg_replace(
        '/<COMMENTAIRE>/',$txtx,preg_replace(
          '/<FILE>/',preg_replace(
            '#>#','&gt;',preg_replace(
              '#<#','&lt;',$definition
            )
          ),preg_replace(
            '/<MOT>/',$mot,$Message[10]
          )
        )
      )
    );
                        } else {
    MailAjout(
      $mot,preg_replace(
        '#>#','&gt;',preg_replace(
          '#<#','&lt;',$definition
        )
      )
    );
  }
                echo '<textarea id="deffinale">'.$definition.'</textarea>';
                }
?>
