<?php
 
  /* imgs.php
	 * Medhi Hanai
	 * 2013
	 * v20130721
	*/
	
	/* Ce code génère 
	 * la réponse XML  envoyée par AJAX 
	 * Et les informations sur les items du menu
	 * FIC est le nom du fichier sans extension
	 * BACK c'est 'background-image',
	 * qui sera visible comme fond du panneau gauche
	 * et des items du menu
	 * (style='background-image:url('')...etc')
	 * TOP et LEFT sont les positions
	 * des éléments à afficher en cercle.
	 * WIDTH et HEIGHT, les dimensions
	*/ 
	/////////////////////////////////////////////////	
	/* scanDirectory contient une fonction qui
	 * renvoie un tableau des noms de fichiers
	*/
	require "./fonctions/lireRepertoire.php";
		
	$PI = 3.1416;
	
	if(!isset($_GET['laTaille']) || !isset($_GET['leDossier']) || !isset($_GET['facteur']))
		exit(1);
	
	$dossier = $_GET['leDossier'];// Le dossier qui contient les images
	$size = $_GET['laTaille'];// La taille des items du menu
	$images = lireFichiersDuRepertoire($dossier);//$images est un tableau
	$nombre = count($images);
	$compteur=0;
	$facteur = $_GET['facteur'];
	$correctionDeDecalage = 0.5*$facteur - 0.50;
	
	echo "<IMAGES>";
	foreach($images as $fic){
		
		echo "
		<IMAGE id=\"d".$compteur."\">				
			<FIC>".substr($fic,0,strpos($fic,"."))."</FIC>
			<WIDTH>".round($size)."</WIDTH>
			<HEIGHT>".round($size)."</HEIGHT>
			<BACK>".$dossier.$fic."</BACK>
			<TOP>".round($size*($correctionDeDecalage + $facteur*0.90*(sin($PI*(2.0/$nombre)*(1+$compteur)))))."</TOP>
			<LEFT>".round($size*($correctionDeDecalage + $facteur*0.90*(cos($PI*(2.0/$nombre)*(1+$compteur)))))."</LEFT>
		</IMAGE>";
		$compteur++;
	}
	
	echo "</IMAGES>";
?>
