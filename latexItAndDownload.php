<!-- Fabrice Issac, Xavier-Laurent Salvador 2017 -->
<?php
include_once("fonctions.php");
if (isset($_GET['inputxq'])) {
	$mot = $_GET['inputxq'];
	$requete = file_get_contents("XQ/fiche-latex.xq");
	$requete = preg_replace("/<ORTH>/",$mot,$requete);
	$mot = preg_replace("/ /","_",$mot);
	//echo $mot;
	//echo $requete;
	$resultat = queryBasex($requete);
	file_put_contents("Latex/$mot.tex",$resultat);
	exec("cd Latex;xelatex $mot.tex",$output);
	//header("Location:Latex/$mot.pdf");
	
//On va forcer le telechargement, hein
$size = filesize("Latex/$mot.pdf"); 
//echo "taille=$size";
header("Content-Type: application/force-download; name=\"Latex/$mot.pdf\"");
header("Content-Transfer-Encoding: binary");
header("Content-Length: $size");
header("Content-Disposition: attachment; filename=\"Latex/$mot.pdf\"");
header("Expires: 0");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
readfile("Latex/$mot.pdf");
exit();
}
?>
