<!-- Xavier-Laurent Salvador, 2017 -->
(:0 L'index pour le formulaire input mot:) 
import module namespace creal = 'http://www.crealscience.fr';           
for $i in creal:index(
  '<VAR>','<PAGE>'
) 
	return 
		if (
  ends-with(
    data(
      $i
    ),"(
      AND
    )"
  )
)
		then
		<li><a href="http://www.crealscience.fr/DFSM/AND/{
  data(
    $i
  )
}"><div class="listingAND">{
  data(
    $i
  )
}</div></a></li>			
		else
		<li><a href="http://www.crealscience.fr/DFSM/consult/{
  data(
    replace(
      $i,' ','%20'
    )
  )
}"><div class="listing {
  creal:encours(
    '<AUTEUR>',$i
  )
}">{
  data(
    $i
  )
}</div></a></li>			
#
(:1:)
import module namespace creal = 'http://www.crealscience.fr';    
	creal:getmot(
  '<VAR>'
)
#
(
  for $i in //entry  where $i[.//orth contains text "<VAR>" using wildcards ]//valid ="true" order by (
    $i[.//orth contains text "<VAR>"]//date
  ) descending return $i
)[1]
#
(
  for $i in //entry[.//orth contains text "<VAR>" using wildcards ][.//valid = "true"][.//auteur = "<AUTEUR>"]  order by (
    $i[.//orth contains text "<VAR>"]//date[1]
  ) descending return $i
)[1]
#
(:4:)
import module namespace creal = 'http://www.crealscience.fr';    
	creal:getmotaut(
  '<VAR>','<AUTEUR>'
)
#
(:5:)   (
  for $i in /entry where $i[./form/orth="<VAR>"][not(
    ./form/valid="rejected"
  )]  return for $j in $i order by (
    $i[./form/orth contains text "<VAR>"]/form/date
  ) return $j
)[1]
#
(:6:)
(
  for $i in distinct-values(
    /entry/form/orth
  )  where $i[. contains text "^<VAR>.+" using wildcards] order by upper-case(
    $i
  ) ascending return <li><a href=\"index.php?mot={
    data(
      replace(
        $i,' ','%20'
      )
    )
  }" onclick="document.getElementById(
    'inputmot'
  ).value='{
    upper-case(
      data(
        translate(
          $i,"Éé","Ee"
        )
      )
    )
  }' ">{
    upper-case(
      data(
        $i
      )
    )
  }</a></li>
)
#
(:7:)
data(
  for $j in (
    for $i in db:open(
      "CREAL"
    )/entry[./form/orth contains text "<VAR>"] order by (
      $i/form/date[1]
    ) descending return $i/form/auteur
  )[1] return $j
)
#
data(
  for $j in (
    for $i in //orth[. contains text "<VAR>"] order by (
      $i/parent::*/date[1]
    ) descending return $i/parent::*/valid
  )[1] return $j
)
#
(:8:)
let $veds := distinct-values(
  for $x in //entry[.//orth contains text "^<VAR>.*?" using wildcards][starts-with(
    .//orth,'<VAR>'
  )]//orth return $x
)
	 for $x in $veds order by upper-case(
  data(
    upper-case(
      translate(
        $x,"ÇçŒÎÜÏÔÂÉËÊÈé","CcOIUIOAEEEEe"
      )
    )
  )
) return <x>{
  upper-case(
    data(
      upper-case(
        $x
      )
    )
  )
}</x>
#
(:9:)
let $liste := for $u at $v in distinct-values(
  for $x at $y in /entry[./form/orth contains text "$<VAR0>.*" using wildcards] order by upper-case(
    data(
      translate(
        $x,"ÇçŒÎÜÏÔÂÉËÊÈé","CcOIUIOAEEEEe"
      )
    )
  ) ascending return $x/form/orth
) return <p><orth>{
  $u
}</orth><rg>{
  $v
}</rg></p> let $indice := (
  $liste[./orth="<VAR>"]//rg
)[1] let $suivant := if (
  $indice !="" 
) then (
  $liste[./rg=$indice+1]//orth
)[1] else (
  $liste[./rg=1]//orth
) let $prec := if (
  $indice !="" 
) then (
  $liste[./rg=$indice - 1]//orth
)[1] else (
  $liste[./rg=1]//orth
) return (
  data(
    $suivant
  ),",",data(
    $prec
  )
)
#
(:10:)
	import module namespace creal = "http://www.crealscience.fr"; 
	creal:date()
#
(:11:)
let $test := distinct-values(
  for $x in db:open(
    "<BASE>"
  )//entry[.//id="<ID>"] return 1
) let $x := <entry><id><ID></id><orth><ORTH></orth><auteur><AUTEUR></auteur><commentaire></commentaire><valid><ETAT></valid></entry> return if (
  $test=1
) then () else insert node $x into root(
  db:open(
    "<BASE>"
  )
)
#
(:12:)
for $x in db:open(
  "<BASE>"
)//entry[.//id="<ID>"]//orth return data(
  $x
)
#
(:13:)
(
  for $x in db:open(
    "<BASE>"
  )//entry[.//id="<ID>"]//valid return replace value of node $x with "true",for $x in db:open(
    "<BASE>"
  )//entry[.//id="<ID>"]//commentaire return replace value of node $x with "<COMMENTAIRE>"
)
#
(:14:)
(
  for $x in db:open(
    "<BASE>"
  )//entry[.//orth="<VAR>"][starts-with(
    .//orth,"<VAR>"
  )] order by $x//id descending return data(
    $x//auteur
  )
)[1]
#
(:15:)
import module namespace creal = "http://www.crealscience.fr"; 
	creal:addid()
#
(:16:)
for $i in /entry[./sense/def contains text "<MOT>"  using stemming using fuzzy]			
			return
			<resultat> 
				<a href="http://index.php?motup={
  data(
    ft:mark(
      $i[.//def contains text "<MOT>"   using stemming using fuzzy]//orth
    )
  )
}">				
				{
  ft:mark(
    $i[.//def contains text "<MOT>"   using stemming using fuzzy]//orth
  )
}				
				</a> 
		(
  {
    data(
      ft:mark(
        $i[.//def contains text "<MOT>" using stemming using fuzzy]//gram[@type="pos"]
      )
    )
  }
):				
		{
  ft:mark(
    $i[.//def contains text "<MOT>"  using stemming using fuzzy]//def[.contains text "<MOT>"  using stemming using fuzzy]
  )
}
			</resultat>
#
(:18:)
for $i in /entry[./sense/@mod contains text "<DOM>" using stemming using fuzzy]
			return 
			<resultat><span style="text-transform:uppercase;">
			<a href="http://index.php?motup={
  data(
    $i//orth
  )
}">{
  $i//orth
}</a>
			</span> (
  ATTRIBAL: {
    data(
      $i/@mod
    )
  }, nature: {
    data(
      $i//gram
    )
  }
) ---> {
  $i//sense[./@mod="<DOM>"]/def
}</resultat>
#
(:19:)
let $count := count(
  distinct-values(
    for $x in //entry[./form/auteur contains text "<AUT>"]//orth return data(
      $x
    )
  )
)
		return
		<resultat>
		Nombre total de mots traités: {
  data(
    $count
  )
}
		{
  for $x in //entry[./form/auteur contains text "<AUT>"] return
		<p>
		<nom>{
    data(
      $x/form/auteur
    )
  }</nom>
		<mot><a href="index.php?motup={
    data(
      $x//orth
    )
  }">{
    data(
      $x//orth
    )
  }</a></mot>
		--- Id: <id>{
    $x//id
  }</id>
		</p>
		}
		</resultat>
#
(:20:)
for $x in db:open(
  "CREAL"
)/entry[./form/orth="<VAR>"]
	let $term := data(
  $x/form/orth
)
	let $quote := $x//quote[not(
  exists(
    ./mark
  )
)]
	return 	
	for $q in $quote return
	if (
  $q contains text {
     $term 
  } using fuzzy using stemming
) then
	replace node $q with ft:mark(
  $q[text() contains text {
     $term 
  } using fuzzy using stemming]
)
	else ()
#
(:21:)
for $x in db:open(
  "<BASE>"
)/entry[./form/orth="<VAR>"] return data(
  $x/form/auteur
)
#
(:22:)
declare namespace mml="http://www.w3.org/1998/Math/MathML";
import module namespace creal = "http://www.crealscience.fr"; 
	let $doc :=<DOCXML>
	let $dtd := creal:validate(
  $doc
)
	return
		if (
  matches(
    $dtd,"Erreur"
  )
) then
			(
  db:output(
    $dtd
  ),db:output(
    $doc
  )
)
		else
			(
  db:output(
    $dtd
  ),creal:sauver(
    $doc
  )
)
#
(:23:)
import module namespace creal = "http://www.crealscience.fr";
	creal:validate(
  <XML>
)
#
(:24 Pour TILDE:)
let $dtd := file:read-text(
  "creal.dtd"
)
let $dtd2 := '<DTD>'
let $x:= validate:dtd-info(
  <XML>, $dtd2
)
	return <erreur>{
  $x
}</erreur>
#
(:25: le diff:)
import module namespace creal = 'http://www.crealscience.fr';    
	creal:diff(
  '<MOT>'
)


