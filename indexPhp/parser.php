<? if ($_SESSION["authentification"] != "OK")
                header("Location:index.php");
        if (empty($_SESSION['langue'])){$_SESSION['langue']='fr';}
        $definition = $_POST['txtdef']; 
        $definition = cleanXML($definition);
        if (isset($_POST['mot']) && $_POST['mot'] != '') 
                {$mot = $_POST['mot'];}
        $definition = AjouteId($definition);
        preg_match('#<valid>([^<]*)</valid>#',$definition,$validherit); 
        if ($validherit[1]!='') {$valid='false';} else {$valid='newadd';}
        $definition = Reinitialise($definition,$valid);
?>
