<!-- Xavier-Laurent Salvador -->
<?php
/* Conf.classe.php

   accès et manipulations du
   fichier de configuration
*/

class Conf{
	protected $tabDir= array(),$lang,$baseDir,$logoName;

	// constructeur : lecture du fichier de configuration
	function __construct($baseDir) {
		$this->baseDir = $baseDir;
		$tabdir = array("templates","images","css","xquery","resources","javascript");
		$totFile = file($baseDir."/conf/isilex.conf");
		foreach ($totFile as $ligne) {
			if ($ligne[0]!="#") {
				$tabconf = preg_split("/[\t ]*=[\t ]*/",rtrim($ligne));
				if ($tabconf[0] == "locale") $this->setLocales($tabconf[1]);
				elseif ($tabconf[0] == "lang") $this->setLang($tabconf[1]);
				elseif ($tabconf[0] == "logo") $this->setLogoName($tabconf[1]);
				elseif (in_array($tabconf[0],$tabdir)) $this->setTabDir($tabconf[1],$tabconf[0]);
			}
		}
	}

	// wakeup et sleep
	function __sleep() {
		return Array("tabDir","lang","baseDir","logoName");	
	}

	function __wakeup() {
		//	
	}

	// dossier de base
	function getBaseDir() {
		return $this->baseDir;	
	}

	// gestion des locales
	function setLocales($conf) {
		$tmp = preg_split("/[\t ]*,[\t ]*/",$conf);
		setlocale (LC_TIME,$tmp[0],$tmp[1]); 
	}

	// dossier templates
	function setTabDir($dir,$name) {
		$this->tabDir[$name] = $dir;
	}

	function getTabDir($name) {
		return $this->tabDir[$name];
	}
	// langue
	function setLang($rep) {
		$this->lang = $rep;
	}

	function getLang() {
		return $this->lang;
	}

	// logo
	function setLogoName($name) {
		$this->logoName = $name;
	}

	function getLogoName() {
		return $this->getTabDir("images")."/".$this->logoName;	
	}
}

?>
