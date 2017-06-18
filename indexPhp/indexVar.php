<!-- Xavier-Laurent Salvador -->
<? /*La langue, get et langue=fr ou en*/
        if (
  isset(
    $_GET["langue"]
  )
) {
                $_SESSION['langue'] = $_GET["langue"];
                } else {
                        if (
    empty(
      $_SESSION['langue']
    )
  ) $_SESSION['langue'] = 'fr';
                }

        /*Destroy*/
        if (
  isset(
    $_GET["key"]
  )
) {
                if (
    $_GET["key"]== 'exit'
  ) {
                        registerDate(
      'out'
    );
                        session_destroy();
                        header(
      "Location:http://www.crealscience.fr"
    );
                }
        }

        /* Les fichiers XML */
        if (
  isset(
    $_GET["type"]
  )
) {
                $txt = 'TXT_SITE/'.$_SESSION['langue'].'/'.$_GET["type"];
                $affichage = AffichXML(
    $txt
  );
        } else {
                $affichage=AffichXML(
    'TXT_SITE/'.$_SESSION['langue'].'/Accueil'
  );
        }

        /*form alphabet, INDEX*/
        if (
  isset(
    $_GET['indexlettreonglet']
  )
) {
          if (
    isset(
      $_GET['page']
    )
  ) {
    $page=$_GET['page'];
  } else {
    $page="1";
  }
                $mot=$_POST['indexlettreonglet'];
                $affichage  = getDefMot(
    $mot,$page
  );
        }
       /*Les cahiers*/
        if (
  isset(
    $_GET["cahier"]
  )
) {
            $cahier = $_GET["cahier"];
             if (
    $cahier=="agronomie"
  ) {
    $_SESSION['cahier']="AGRONOMIE"; $dom="AGR";
  }
             elseif (
    $cahier=="nature"
  ){
    $_SESSION['cahier']="NATURE"; $dom="Sciences_Nat";
  }
             elseif (
    $cahier=="medecine"
  ){
    $_SESSION['cahier']="MEDECINE"; $dom="MED";
  }
             elseif (
    $cahier=="astronomie"
  ){
    $_SESSION['cahier']="ASTRONOMIE"; $dom="ASTR";
  }
             elseif (
    $cahier=="mathematique"
  ){
    $_SESSION['cahier']="MATHEMATIQUE"; $dom="MATH";
  }
             elseif (
    $cahier=="geometrie"
  ){
    $_SESSION['cahier']="GEOMETRIE"; $dom="GEO";
  }
             elseif (
    $cahier=="zool"
  ){
    $_SESSION['cahier']="ZOOLOGIE"; $dom="zool";
  }
            $affichage = indexCahier(
    $dom
  );
        }

        /*formmot*/
        if (
  isset(
    $GET["mot"]
  ) || isset(
    $_GET["mot"]
  )
) {
                $mot = strtoupper(
    $_GET["mot"]
  );
                if (
    $_SESSION['groupe'] != "visiteur" || $mot[0] == "C"
  ){
                $affichage = getDefMot(
      $mot
    );
  }
                $affichage =preg_replace(
    '#<bibxref>(
      [^<]*
    )</bibxref>#','<br/><a style="text-decoration:none; padding: 3px; background-color: #F0F0F0; margin-top: 3px;" href="#" onClick="open(
      \'http://www.crealscience.fr/biblio.php#$1\', \'new\', \'width=600,height=150,toolbar=no,location=no, directories=no,status=no,menubar=no,scrollbars=no,resizable=no\'
    )">Voir Référence</a>',$affichage
  );

        }

        /*Insertion d'un favori*/
        if (
  isset(
    $_GET["favoxq"]
  )
) {
                $fxq = '
                let $entry:=<entry><nom>'.$_SESSION["login"].'</nom><id>'.$_GET["favoxq"].'</id></entry>
                return insert node $entry into root(
    db:open(
      "favoris"
    )
  )         
                ';
                queryBasex(
    $fxq
  );
                $fxqq = '
                for $x in db:open(
    "CREAL"
  )/entry[./id="'.preg_replace(
    '#<[^>]*>#','',$_GET["favoxq"]
  ).'"] return $x
              ';
                $affichage='<div class="texte"><h2>Favoris</h2><p>Le mot a été ajouté à vos favoris.</p></div>'.queryBasex(
    $fxqq
  );
        }

        /*Cancellation d'un favori*/
        if (
  isset(
    $_GET["delfavo"]
  )
) {
                $delfav = $_GET["delfavo"];
                $xq = 'for $x in db:open(
    "favoris"
  )/entry[./nom="'.$_SESSION["login"].'"][./id="'.$delfav.'"] return delete node $x';
                queryBasexBavard(
    $xq
  );
                header(
    "Location:http://www.crealscience.fr/index.php?indexlettreonglet=*"
  );
        }

        /*Le mot*/
        if (
  isset(
    $_GET["motup"]
  )
) {
                $mot=$_GET["motup"];
                if (
    $_SESSION['groupe'] != "visiteur" || $mot{0} == "C"
  ){
                 $affichage = getDefMot(
      strtoupper(
        $mot
      )
    );
                 }
                }

        /* mot AND */
        if (
  isset(
    $_GET["motand"]
  )
) {
                if (
    $_SESSION['groupe'] != "visiteur" || $mot{0} == "C"
  ){
                        $affichage = afficheAND(
      $_GET["motand"]
    );
                }
        }
        /*Le formulaire de recherche dans le dictionnaire*/
        if (
  isset(
    $_GET["xqnotion"]
  ) || isset(
    $_GET["xqdomaine"]
  ) || isset(
    $_GET["xqauteur"]
  )
) {
                if (
    $_SESSION['authentification'] == 'OK'
  ) {
                        if (
      $_GET["xqnotion"]!=''
    ) {
                                $affichage = highLight(
        queryBasex(
          preg_replace(
            '/<MOT>/',$_GET["xqnotion"],$ReqXq[17]
          )
        ),$_GET["xqnotion"]
      );
} elseif (
      $_GET["xqdomaine"] != ''
    ) {
                                $affichage = queryBasex(
        preg_replace(
          '/<DOM>/',$_GET["xqdomaine"],$ReqXq[18]
        )
      );
                        } else {
                                $affichage = queryBasex(
        preg_replace(
          '/<AUT>/',$_GET["xqauteur"],$ReqXq[19]
        )
      );
                        }
                } else {
     $affichage = '<div class="texte">'.$Message[40].'</div>';
  };
        }

        If (
  isset(
    $_POST['validid']
  ) && isset(
    $_POST['commentaires']
  )
) {
                //Le formulaire de validation
                queryBasexBavard(
    preg_replace(
      '/<BASE>/','CREAL',preg_replace(
        '/<ID>/',preg_replace(
          '/<[^>]*>/','',$_POST['validid']
        ),$ReqXq[13]
      )
    )
  );
                queryBasexBavard(
    preg_replace(
      '/<COMMENTAIRE>/',$_POST['commentaires'],preg_replace(
        '/<BASE>/','encours',preg_replace(
          '/<ID>/',preg_replace(
            '/<[^>]*>/','',$_POST['validid']
          ),$ReqXq[14]
        )
      )
    )
  );
          //      queryBasexBavard(
    preg_replace(
      '/<ORTH>/',$orth,preg_replace(
        '/<BASE>/','encours',preg_replace(
          '/<ID>/',$_POST['validid'],$sec[11]
        )
      )
    )
  );
		$xq = '
		let $orth := db:open(
    "CREAL"
  )//entry[.//id="'.$_POST['validid'].'"]//orth
		return
                if (
    not(
      db:open(
        "encours"
      )//entry[./orth=$orth]
    )
  ) then
                insert node 
                        <entry>
			<id>'.$_POST['validid'].'</id>
                        <orth>{
    data(
      $orth
    )
  }</orth>
                        <auteur>'.$_SESSION['login'].'</auteur>
                        <commentaire/>
                        <date>{
    fn:current-dateTime()
  }</date>
                        <valid>true</valid>
                        </entry> 
                        into root(
    db:open(
      "encours"
    )
  ) 
                else 
                        replace node db:open(
    "encours"
  )/entry[./orth=$orth] with 
                        <entry>
			<id>'.$_POST['validid'].'</id>
                        <orth>{
    data(
      $orth
    )
  }</orth>
                        <auteur>'.$_SESSION['login'].'</auteur>
                        <commentaire/>
                        <date>{
    fn:current-dateTime()
  }</date>
                        <valid>true</valid>
                        </entry>
                        ';
                queryBasexBavard(
    $xq
  );
		$messageMailValidation = queryBasex(
    '
			let $orth := db:open(
      "CREAL"
    )//entry[.//id="'.$_POST['validid'].'"]//orth 
			let $com := "'.$_POST['commentaires'].'"	
			return "Une demande de validation a été insérée pour le mot " || upper-case(
      data(
        $orth
      )
    ) || " avec le commentaire: " || $com || " par '.$_SESSION['login'].'" 
			'
  );
		$motOrth = queryBasex(
    'db:open(
      "CREAL"
    )//entry[.//id="'.$_POST['validid'].'"]//orth/text()'
  );
                        foreach (
    $adminmail as $mail
  ) {
                                courriel(
      $mail,'[ Crealscience ] - Demande de Validation: '.$motOrth,$messageMailValidation
    );
                        }
                $affichage='<div class="texte"><h2>Sauvegarde</h2><p> XXXX Votre fiche avec l\'identifiant '.$_POST['validid'].' a été correctement enregistrée et votre demande de validation avec le commentaire: '.$_POST['commentaires'].' a été transmise pour modération. Un mail vous a été envoyé.</p><p><a href="index.php" class="ongl">Accueil</a></p><p><a href="http://www.crealscience.fr/laboratoire/index.php" class="ongl">Laboratoire</a></p></div>';
        }
         // La Suppression du menu de gauche    
         $st = '<style>#menugauche {
  display:none;
} #top {
  display:none;
}
}</style>';
        if (
preg_match(
  '/<orth>/',$affichage
)
) {
                echo $st;
        }
?>
