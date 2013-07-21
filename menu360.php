<?php
	/* 360 Menu
	 * menu360.php
	 * Auteur : Medhi Hanai
	 * 2013
	 * v20130721
	 * Creative Commons License
	 * contact@mhanai.fr 
	 */
?>
<?php 

	require "./fonctions/lireRepertoire.php";
	function creerMenu($dossierImages,$dossierTextes){
		echo"
		<style>
			
			#interrupteur{	
				
				position:fixed;top:0%;left:50%;
				height:6%;width:300px;
				background-color:#323232;
				border-bottom: 1px solid white;
				margin-left:-150px;
				margin-top:-1%;
				border-bottom-left-radius:130%;
				border-bottom-right-radius:130%;
			}
			#interrupteur:hover{
				
				border-bottom: 1px solid #26f;
				background-color:#323243;
			}
			#mainDiv{
				
				transition-duration:0.3s;
			}
			#mainDiv:hover{
				
				cursor:pointer;
			}
			.main{
				
				position:fixed;
				top:50%;
				left:50%;
				background-color:#050505;
				box-shadow:0px 0px 10px white;
			}
			.main0{
				
				position:absolute;
				z-index:-5;
				/* avec z-index à -5, la zone n'est plus cliquable
				lorsqu'elle est cachée */
				top:50%;
				left:50%;
			}
			
			.hid{	
				border-bottom:1px solid white;	
				border-top:1px solid white;	
				padding:0;	
			}
			.hid:hover{box-shadow:0px 0px 10px #2CF;} 
			
			#droite{
				overflow:auto;
				font-family:Sans-serif;
				letter-spacing:2px;
				font-weight:lighter;
			}
			
			#gauche{overflow:hidden;}
			#gauche>div{overflow:hidden;border-radius:10px;}
			
		</style>

		<!-- Cette balise affiche le fond transparent lorsque l'on clique sur l'interrupteur -->
		<div id='transp'></div>
		<!-- L'interrupteur en haut de la page -->
		<div id='interrupteur' onclick='allumerEteindre();' ></div>
		<!-- Le panneau de gauche qui contient l'image agrandie -->
		<div id='gauche' class='hid2'><div style='width:inherit;height:inherit;'></div></div>
		<!-- Le panneau central rond -->
		<div onclick='allumerEteindre();' id='mainDiv' class='main0' ></div>
		<!-- Le panneau de droite qui contient le texte -->
		<div id='droite' class='hid2'><div></div></div>
		
		<script>
			
			var on=1;	/* 	Quand cette valeur vaut 1, le menu est visible
							et quand elle vaut 0, le menu est caché. La fonction 
							allumerEteindre() est appelée une première fois
							dès que la page est chargée. C'est pourquoi 
							le menu n'est pas visible tout de suite. Cette valeur passe immédiatement à 0. 
						*/
			
			/*	afficherLesPanneaux(lUrl,texte)
				cette fonction affiche le contenu dans les panneaux.
				L'Url permet d'afficher le background du panneau gauche
				texte est le nom du fichier qui contient le texte/HTML
				à mettre dans le panneau droit.
			*/
			function afficherLesPanneaux(lUrl,texte){
								
				jQuery.get('$dossierTextes'+texte+'.html', function(data) {
					
					$('#droite>div').html(data);
				});
									
				$('#gauche>div').css({'background-image':'url(\''+lUrl+'\')','background-size':'cover'});
				$('#gauche>div,#droite>div').hide();
				$('#gauche>div,#droite>div').fadeIn(400);
			}
			
			/*  genererItemsDuMenu(xml)
				Cette fonction crée les items du menu
				sous forme de div de classe hid 
			*/
			
			function genererItemsDuMenu(xml){
				
				var result = '';
				var bordure = -1*window.innerWidth/30; 
				
				$('IMAGE', xml).each(function(){
					
					result += '<div onmouseover=\"afficherLesPanneaux(\''+$(this).find('BACK').text()+'\',\''+$(this).find('FIC').text()+'\');\" ';
					result += 'class=\"hid\" id=\"'+$(this).attr('id')+'\" style=\"width:'+$(this).find('WIDTH').text()+'px; ';
					result += 'height:'+$(this).find('HEIGHT').text()+'px; ';
					result += 'background-image:url(\''+$(this).find('BACK').text()+'\'); ';
					result += 'background-size:cover;position:absolute; top:50%;left:50%;margin-top:'+bordure+'px;margin-left:'+bordure+'px ;';
					result += 'border-radius:'+$(this).find('WIDTH').text()+'px; opacity:0 ; \"></div>';
				});
				return result;
			}
			
			/* 	Cette fonction est la plus important et la plus compliquée
				Elle utilise l'Ajax pour faire calculer les dimensions et
				positions des éléments du menu par le script PHP imgs.php
			*/
			
			function calculerDimensions(){
				
				var facteurDeReduction = 6;
				var taille = parseInt(window.innerWidth/facteurDeReduction);
				var largeurPanneaux = parseInt(taille * 1.5); // width for both panels
				var panneauDroitMargeGauche = parseInt(taille * 1.8) -5; 	
				var panneauGaucheMargeGauche = panneauDroitMargeGauche - largeurPanneaux;
				var hauteurPanneaux = 2*taille; // for the height of both panels
				var facteurDeReductionItems = 2.5;
				var tailleItemMenu = parseInt(taille/facteurDeReductionItems)	;
				var border = parseInt(taille/2);
				var borderNeg = -1*border;	// for the mainDiv to be centered
				$('#mainDiv').css({
					'border-radius':border+'px', 
					height:taille+'px',width:taille+'px',
					'margin-left':borderNeg+'px',
					'margin-top':borderNeg+'px',
				});
				
				$('#gauche').css({'overflow':'hidden',width:largeurPanneaux+'px',height:hauteurPanneaux+'px','border-radius':'10px','border-left':'1px solid #555','border-right':'1px solid #CCC',position:'fixed',left:'0%',top:'50%','margin-top':'-'+taille+'px','margin-left':panneauGaucheMargeGauche+'px'});
				$('#droite').css({width:largeurPanneaux+'px',height:hauteurPanneaux+'px','border-radius':'10px','border-left':'1px solid #555','border-right':'1px solid #CCC',position:'fixed',left:'100%',top:'50%','margin-top':'-'+taille+'px','margin-left':'-'+panneauDroitMargeGauche+'px'});
				
				
				if(on){
						
					$.ajax({
						type: 'GET',
						url: 'imgs.php',
						data: 	{
									facteur:facteurDeReductionItems+'',
									laTaille: tailleItemMenu+'',
									leDossier:\"".$dossierImages."\"+''	
								},
						dataType: 'xml',
						success: function(msg){
							
							var temp = msg;
							$('#mainDiv').html(genererItemsDuMenu(msg));	
							$('IMAGE', temp).each(function(){
									
								var me = $(this);
								$('#'+(me.attr('id'))).animate({
									opacity:1,
									margin:0,
									top:me.find('TOP').text()+'px',
									left:me.find('LEFT').text()+'px'
								},400);	
							});
						}
					});	
				}
			}
			
			function gererLeFond(){
				
				var w = window.innerWidth;
				var h = window.innerHeight;
				
				if(!on)
					$('#transp').animate({'background-color':'black',width:w,height:h,position:'fixed',top:'0px',left:'0px',opacity:0.8	},300);
				else
					$('#transp').css({'background-color':'black',width:w,height:h,position:'fixed',top:'0px',left:'0px',opacity:0});
			}

			function style1(){
				
					$('#mainDiv').removeClass('main0');
					$('#mainDiv').addClass('main');
			}

			function style2(){
				
				
					$('#mainDiv').removeClass('main');
					$('#mainDiv').addClass('main0');	
			}	
			
			/* 	Cette fonction fait apparaître ou disparaître
				le menu et positionne la variable 'on' sur 1 ou 0
			*/		
			function allumerEteindre(){
				
				if(on){
					
					$('#interrupteur').delay(400).show('slow');
					$('.hid2').hide();
					gererLeFond();
					style2();
					var border = -1*window.innerWidth/30;
					on =0;
					
					$('.hid').animate({position:'relative',top:'50%',left:'50%','z-index':50,
					'margin-left':border+'px',
					'margin-top':border+'px',
					opacity:0},800);
				}
				else{
					
					$('.hid2').delay(400).show('slow');
					$('#interrupteur').delay(400).hide('slow');
					gererLeFond();
					style1();
					on =1;
					calculerDimensions();
				}
			}
	
			allumerEteindre();
			calculerDimensions();							
			var fontSize = parseInt(100*(window.innerWidth)/(screen.width));
			$('.hid2').css({'font-size':fontSize+'%'});
			
			// Pour gérer le redimensionnement
			$(window).resize(function(){ 
							
							var w = window.innerWidth;
							var h = window.innerHeight;
							//Adjust the size of the transparent background
							$('#transp').css({width:w,height:h});
							// Réajuste les éléments
							calculerDimensions();
							var fontSize = parseInt(100*(window.innerWidth)/(screen.width));
							// Ajuste le texte dans le panneau 
							$('.hid2').css({'font-size':fontSize+'%'});
							return false ;		
			});
			
			";
			
			/* 
			 * Ce code génère du Javascript qui permet
			 * de précharger les images
			*/
				
			$images = lireFichiersDuRepertoire($dossierImages);
			$compteur=0;
			
			echo "var lesImages = new Array();";
			foreach($images as $fic){

				echo "lesImages[$compteur] = \"$dossierImages"."$fic\" ; ";
				$compteur++;
			}
			
			echo"
				function precharger(images) {
					
					$(images).each(function(){

						$('<img/>').attr('src',this).appendTo('body').css('display','none');
					});
				}
				precharger(lesImages);
				";
		echo"</script>";
	}//Fin creerMenu()	
?>
	


