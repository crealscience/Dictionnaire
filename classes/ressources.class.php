<!-- Xavier-Laurent Salvador, 2017 -->
<?php
/* Ressources.classe.php

   accès et manipulations des
   "ressources" d'affichage
*/

class Ressources{
	public $conf,$title,$rscdir,$lang;

	// constructeur : 
	function __construct($configuration) {
		$this->conf = $configuration;
		$this->rscdir = $configuration->getTabDir("resources");
		$this->lang = $configuration->getLang();
	}

	// récupération du titre
	function getTitle() {
		return file_get_contents($conf->getBaseDir()."/".$this->rscdir."/".$conf->lang."/title.islx");
	}
	// récupération du contenu du menu gauche
	function getMenu() {
		return file_get_contents($conf->getBaseDir()."/".$this->rscdir."/".$conf->lang."/menu.islx");
	}
}

?>
