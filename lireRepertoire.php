<?php 

  /* Fonction qui renvoie sous forme de tableau
	 * les fichiers contenus dans le répertoire $repertoire
	 */
	function lireFichiersDuRepertoire($repertoire){
		
		$tableau = array();
		$leRepertoire = opendir($repertoire) or die('Erreur');
		while($entree = @readdir($leRepertoire)) 
			if(! is_dir($repertoire.'/'.$entree)&& $entree != '.' && $entree != '..') 
				$tableau[] = $entree;
			
		closedir($leRepertoire);
		return $tableau;
	}
?>
