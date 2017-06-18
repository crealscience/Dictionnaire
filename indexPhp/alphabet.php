<!-- Xavier-Laurent Salvador -->
<form name="alphabet" action="<?echo $_SERVER["PHP_SELF"].'?langue='.$_SESSION['langue'];?>" method="post">
       <input type="hidden" name="indexlettreonglet" value="">
        <?
                if (
  $_SESSION['groupe'] == "visiteur"
) {
  echo '<div id="xx">Cher Visiteur, cliquez ici la lettre </div id="xx">';
}
		echo $page->createAlphabet(
  'OK'
);
                if (
  $_SESSION['groupe'] == "visiteur"
) {
  echo '<div id="xx"> (
    en cours
  ). Les autres lettres seront prochainement accessibles.</div>';
}
        ?>
</form>
