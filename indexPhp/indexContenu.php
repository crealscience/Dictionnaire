<!-- Xavier-Laurent Salvador, 2017 -->
<?
                if (
  preg_match(
    '/crealtexte/',$affichage
  ) || preg_match(
    '/<orth>/',$affichage
  ) || preg_match(
    '/roomliste/',$affichage
  )
) {
                        echo TraitementAffichage(
    $affichage
  );
                        if (
    preg_match(
      '/<orth>/',$affichage
    )
  ) {
                        $dom = preg_match(
      '#<med="(
        [^"]*
      )"#',$affichage
    );
                        $domaine = $dom[1];
                        $xq = (
      '(
        for $x in distinct-values(
          for $p in db:open(
            "CREAL"
          )//entry[starts-with(
            .//orth,"'.$mot[0].'"
          )] order by $p//orth collation "?lang=fr-FR" return $p//orth
        ) return <a style="text-decoration:none; background-color:#F0F0F0; color:black; border: 1px solid #A0A0A0; font-family: \'Helvetica\'; padding: 7px; font-size: small; text-align: center; margin-top: 2px; display:block;" href="http://www.crealscience.fr/DFSM/consult/{
          data(
            $x
          )
        }">{
          data(
            $x
          )
        }</a>
      )'
    );
                        echo '<div id="motUp" style="display:none;">'.$mot.'</div>';

        if (
      $_SESSION["groupe"] == "visiteur"
    ) {
        $xqq = (
        '
        (
          let $liste := (
            distinct-values(
              for $x in db:open(
                "CREAL"
              )//entry[.//text() contains text "'.$mot.'" using stemming]//orth order by $x collation "?lang=fr-FR" return $x
            ), db:open(
              "CREAL"
            )//entry[./form/orth="'.$mot.'"]//ref[.=db:open(
              "CREAL"
            )]//orth
          ) 
        return for $x in distinct-values(
            $liste
          )  order by $x collation "?lang=fr-FR" return 
        <a style="text-decoration:none; background-color:#F0F0F0; color:black; border: 1px solid #A0A0A0; font-family: \'Helvetica\'; padding: 7px; 
font-size: small; text-align: center; margin-top: 2px; display:block;" href="{
            if (
              starts-with(
                $x,\''.$mot[0].'\'
              )
            ) then \'http://www.crealscience.fr/DFSM/consult/\'||$x else \'http://www.crealscience.fr/DFSM/consult/'.$mot[0].'\'
          }">{
            data(
              $x
            )
          }</a>
        )'
      );
        }
        else {
         $xqq = (
        '
        (
          let $liste := (
            distinct-values(
              for $x in db:open(
                "CREAL"
              )//entry[.//text() contains text "'.$mot.'" using stemming]//orth order by $x collation "?lang=fr-FR" return $x
            ), db:open(
              "CREAL"
            )//entry[./form/orth="'.$mot.'"]//ref[.=db:open(
              "CREAL"
            )]//orth
          ) 
        return for $x in $liste  order by $x collation "?lang=fr-FR" return 
        <a style="text-decoration:none; background-color:#F0F0F0; color:black; border: 1px solid #A0A0A0; font-family: \'Helvetica\'; padding: 7px; 
font-size: small; text-align: center; margin-top: 2px; display:block;" href="http://www.crealscience.fr/DFSM/consult/{
            data(
              $x
            )
          }">{
            data(
              $x
            )
          }</a>
        )'
      );
        }
        
                        echo '<div id="hideIndex" style="display:none;"><br/>'.queryBasex(
      $xqq
    ).'</div>';
                        echo '<div id="hideIndex2" style="display:none;"><br/>'.queryBasex(
      $xq
    ).'</div>';

                        $nomenclature = queryBasexBavard(
      'for $x in doc(
        "/var/www/vhosts/crealscience.fr/httpdocs/TXT_SITE/fr/nomenclature.xml"
      )/p/option return $x'
    );
                        echo '<form id="transidef" style="z-index: 10000; position:absolute; left: 50px; top: 290px;" action="http://www.crealscience.fr/idefx.php#resT" method="post" onSubmit="target_popup(
      this
    );">
    <input class="bg_form2 ok" style="width: 90px;" width="20px" placeholder="Recherche" id="idefx" type="text" name="idefx" list="vedette"  />
        <input type="Submit" Value="OK"/>
        <input type="hidden" name="countres" value="1">
        <input type="hidden" name="motUpIdefx" value="'.$mot.'">
        <input type="hidden" id="countres" name="countres" value="" />
                            <input type="hidden" id="motprems" name="motprems" value="" />
        <input type="hidden" id="navig" name="navig" value="" />
  </form>
                        ';

                        echo '<div id="crealconsult_hype_container" style="margin:auto;position:relative;width:1000px;height:800px;overflow:hidden;position:absolute; top:0px; left:60px;" aria-live="polite">
                <script type="text/javascript" charset="utf-8" src="http://www.crealscience.fr/crealConsult/crealConsult.hyperesources/crealconsult_hype_generated_script.js?50399"></script>
        </div>';
  }
                } else {
                        echo '<div id="centrehype">'.TraitementAffichage(
    $affichage
  ).'</div>';
                }
                if (
  preg_match(
    '/<orth>/',$affichage
  ) || preg_match(
    '/roomliste/',$affichage
  ) || preg_match(
    '/News/',$affichage
  ) 
){
                } else {
                echo '<div id="colonne">';
                        echo $page -> createColonne();
                        echo '</div>';
                }
        ?>
