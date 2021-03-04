<?php
	echo '<meta charset ="utf-8" />';
	mb_internal_encoding("UTF-8");
	
	/*Transformation des caractères spécials pour les prénoms*/
	function caraSpecialPrénom($chaine){
		$remplacement = array ( 'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'Á'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A',
                            'Ì'=>'I', 'Í'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O',
                            'Ú'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'á'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a',
                            'ì'=>'i', 'í'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'õ'=>'o',
                            'ö'=>'o', 'ø'=>'o', 'ú'=>'u', 'ý'=>'y', 'þ'=>'b', 'Ŭ'=>'U', 'Æ'=>'AE','œ'=>'oe', 'Œ'=>'OE', 'æ'=>'ae');
							
		$chaine = strtr( $chaine, $remplacement);
		return $chaine;
	}
	/*Transformation des caractères spécials pour les noms*/
	function caraSpecialNom($chaine){
		$remplacement = array ( 'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'AE', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'Ŭ'=>'U', 'œ'=>'oe', 'Œ'=>'OE', 'ü'=>'u', 'Ÿ'=>'Y');
		
		
		$chaine = strtr($chaine, $remplacement);
		return $chaine;
	}
	
	/*Vérifier si les caractères de la chaine sont français*/
	function alphabetFrancais($chaine){
			$tabPhrase = preg_split('//u', $chaine, -1, PREG_SPLIT_NO_EMPTY);
			
			
			$stop = false;
			$i = 0;
			while(!$stop&&$i<sizeof($tabPhrase)){
				$j = 0;
				$stopReg = false;
				$tab = array("[a-z]" ," ", "à", "ä", "â", "ç", "é", "è", "ê", "ë", "î", "ï", "ö", "ô", "ù", "û", "ü", "ÿ", "'", "-");
				do{
					if(preg_match("/$tab[$j]/i", mb_strtolower($tabPhrase[$i]))){
						$stopReg=true;
					}
					$j++;
				}while(!$stopReg&&$j<sizeof($tab));
				if(!$stopReg){
					$chaine = "interdit";
					$stop=true;
				}
				$i++;
			}
			return $chaine;
	}
	
	/*Mettre en majuscule la chaine*/
	function majuscule($chaine){
		$chaine = strtoupper($chaine);
		return $chaine;
	}
	
	/*Mettre en majuscule la chaine s'il y a une apostrophe*/
	function debMajusculeApostr($chaine){
		$exp = explode("'", $chaine);
		for($j = 0; $j<sizeof($exp); $j++){
		$exp[$j] = mb_strtoupper(mb_substr ($exp[$j],0,1)).mb_substr($exp[$j], 1);
		//$exp[$j] = ucwords($exp[$j]);
		}
		$chaine = implode("'", $exp);
		return $chaine;
	}
	
	/*Mettre en majuscule la chaine s'il y a un espace*/
	function debMajusculeEspace($chaine){
		$exp = explode(" ", $chaine);
		for($j = 0; $j<sizeof($exp); $j++){
		$exp[$j] = mb_strtoupper(mb_substr ($exp[$j],0,1)).mb_substr($exp[$j], 1);
		}
		$chaine = implode(" ", $exp);
		return $chaine;
	}
	
	/*Mettre en majuscule la chaine s'il y a un tiret*/
	function debMajusculeTiret($chaine){
		$exp = explode("-", $chaine);
		for($j = 0; $j<sizeof($exp); $j++){
			$exp[$j] = mb_strtoupper(mb_substr ($exp[$j],0,1)).mb_substr($exp[$j], 1);
		}
		$chaine = implode("-", $exp);
		return $chaine;
	}
	
	/*Mettre en majuscule la chaine s'il y a 2 tirets*/
	function debMajusculeTiret2($chaine){
		$exp = explode("--", $chaine);
		for($j = 0; $j<sizeof($exp); $j++){
			$exp[$j] = mb_strtoupper(mb_substr ($exp[$j],0,1)).mb_substr($exp[$j], 1);
		}
		$chaine = implode("--", $exp);
		return $chaine;
	}
	
	/*Si la chaine contient des espaces*/
	function contientEspace($chaine){
		$retour = false;
		if(preg_match("/ /i", $chaine)){
			$retour = true;
		}
		return $retour;
	}
	
	/*Si la chaine contient des apostrophes*/
	function contientApostr($chaine){
		$retour = false;
		if(preg_match("/'/i", $chaine)){
			$retour = true;
		}
		return $retour;
	}
	
	/*Si la chaine contient des tirets*/
	function contientTiret($chaine){
		$retour = false;
		if(preg_match("/-/i", $chaine)){
			$retour = true;
		}
		return $retour;
	}
	
	/*Si la chaine contient des espaces des doubles tirets*/
	function contientTiret2($chaine){
		$retour = false;
		if(preg_match("/--/i", $chaine)){
			$retour = true;
		}
		return $retour;
	}
	
	/*Mettre une majuscule juste après différents critères (espace, tirets, apostrophes...)*/
	function debMajuscule($chaine){
		$modele = "[-| |']";
		$chaine = mb_strtolower($chaine, 'UTF-8');
		if(contientApostr($chaine)){
			$chaine=debMajusculeApostr($chaine);
		}
		if(contientEspace($chaine)){
			$chaine=debMajusculeEspace($chaine);
		}
		if(contientTiret($chaine)){
			$chaine=debMajusculeTiret($chaine);
		}
		if(contientTiret2($chaine)){
			$chaine=debMajusculeTiret2($chaine);
		}
		$chaine = mb_strtoupper(mb_substr ($chaine,0,1)).mb_substr($chaine, 1);
		$remplacement = array ( 'Š'=>'S', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'AE', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y',
							'Ŭ'=>'U', 'Œ'=>'OE', 'Ÿ'=>'Y');
		$chaine = strtr($chaine, $remplacement);
		return $chaine;
	}
	
	/*Si la chaine contient des punctations interdites*/
	function interditPunct($chaine){
			$modele = "[[:punct:]]";
			$modele2 = "[-]";
			$modele3 = "[']( *)[']";
			$modele4 = "^['][[:alpha:]]";
			$modele5 = "[[:alpha:]][']";
			$modele6 = "(€|!)";
			if(preg_match("/$modele/i",$chaine)&&!preg_match("/$modele2/i", $chaine)){
				if(preg_match("/$modele3/i", $chaine)){
					$chaine="interdit";
				}
				else if(!preg_match("/$modele4/i", $chaine)&&!preg_match("/$modele5/i", $chaine)){
					$chaine="interdit";
				}
			}
			if(preg_match("/$modele6/", $chaine)){
				$chaine="interdit";
			}
			return $chaine;
	}
	
	/*Si la chaine contient plusieurs espaces on modifie pour avoir qu'une seule*/
	function plusieursEspaces($chaine){
			$modele = "[ ]{2,}";
			if(preg_match("/$modele/i", $chaine)){
				$chaine = preg_replace("/$modele/i", " ", $chaine);
				if(preg_match("/( *)[-]( *)/i", $chaine)){
					$chaine = preg_replace("/( *)[-]( *)/i", "-", $chaine);
				}
			}
			return $chaine;
	}
	
	/*Suppression des tirets et des espaces au début de la chaine*/
	function SupprimerTiretEtEspaceDebut($chaine){		
		$pattern = "/^[- ]/";
		if(preg_match($pattern,$chaine)){
			$chaine = preg_replace($pattern, '',$chaine);
		}
		return $chaine;
	}
	
	/*Suppression des tirets et des espaces à la fin de la chaine*/
	function SupprimerTiretEtEspaceFin($chaine){		
		$pattern = "/[- ]$/";
		if(preg_match($pattern,$chaine))
			$chaine = preg_replace($pattern, '',$chaine);
		return $chaine;
	}
	
	/*Interdiction de la chaine quand il y en a plus de 2 tirets à la suite*/
	function SupprimerTiretsALaSuite($chaine){
		$pattern = "/[-]{3,}/";
		if(preg_match($pattern,$chaine))
			$chaine = "interdit";
		return $chaine;
	}
	
	/*Interdiction de la chaine quand il y en a plus de 2 tirets à la suite, 2eme verif*/
	function SupprimerTiretsALaSuite2($chaine){
		if(substr_count($chaine, "--") >= 2 )
			$chaine = "interdit";
		return $chaine;
	}
	
	/*Interdiction de la chaine quand il y a 2 tirets dans un prénom*/
	function supprimer2TiretsPrenom($chaine){
		if(preg_match("/--/", $chaine)){
				$chaine="Interdit";
		}
		return $chaine;
	}
	
	/*Mettre une double apostrophe à la place de la simple apostrophe au lieu d'une seule,
	pour la requête SQL*/
	function mettreDoubleApost($chaine){
		$chaine = strtr($chaine, array("'" => "''"));
		return $chaine;
	}
	
	/*Transformation de toutes les apostrophes en une apostrophe classique*/
	function uniformiserApost($chaine){
		$apostList = array ("'"=>"'",  "’"=>"'", "ʾ"=>"'", "′"=>"'", "ˊ"=>"'", "ˈ"=>"'", "ꞌ"=>"'", "‘"=>"'",
							"ʿ"=>"'", "`"=>"'", "՜"=>"'", "΄"=>"'", "ʹ"=>"'", "՝"=>"'", "՛"=>"'", "՚"=>"'", "ՙ"=>"'"); 
		$chaine = strtr($chaine, $apostList);
		return $chaine;
	}
	
	//Source : https://www.linternaute.com/savoir/magazine/1106504-les-mots-les-plus-longs/1106507-noms-de-famille#:~:text=-En%20France%2C%20plusieurs%20noms%20de,de%20Quinsonas-Oudinot%20de%20Reggio.
	/*Interdire la chaine quand c'est supérieur à 30*/
	function taille($chaine){
		    //Compte bien la taille de la phrase et non la taille en octet de la phrase.
			$taille = count(preg_split('//u', $chaine, -1, PREG_SPLIT_NO_EMPTY));
			if($taille>30){
					$chaine = "interdit";
			}
			return $chaine;
	}
	
	/*Fonction qui applique toutes les fonctions des tirets*/
	function tiret($chaine){
		$chaine = SupprimerTiretEtEspaceDebut($chaine);
		$chaine = SupprimerTiretEtEspaceFin($chaine);
		$chaine = SupprimerTiretsALaSuite($chaine);
		$chaine = SupprimerTiretsALaSuite2($chaine);
		return $chaine;
	}
	
	/*Interdire la chaine si elle contient que des espaces*/
	function queEspace($chaine){
		$chaineT = str_replace(" ", "", $chaine);
		if(empty($chaineT)){
			$chaine="interdit";
		}
		return $chaine;
	}
	
	/*Application de certaines fonctions précédentes pour le transformer en une bonne chaine pour un nom*/
	function nom($chaine){
		$chaine = uniformiserApost($chaine);
		$chaine = caraSpecialNom($chaine);
		$chaine = majuscule($chaine);
		$chaine = tiret($chaine);
		$chaine = interditPunct($chaine);
		$chaine = plusieursEspaces($chaine);
		$chaine = alphabetFrancais($chaine);
		$chaine = mettreDoubleApost($chaine);
		$chaine = taille($chaine);
		$chaine = queEspace($chaine);
		return $chaine;
	}
	
	/*Application de certaines fonctions précédentes pour le transformer en une bonne chaine pour un prenom*/
	function prenom($chaine){
		    $chaine = uniformiserApost($chaine);
			$chaine = caraSpecialPrénom($chaine);
			$chaine = interditPunct($chaine);
			$chaine = tiret($chaine);
			$chaine = plusieursEspaces($chaine);
			$chaine = debMajuscule($chaine);
			$chaine = alphabetFrancais($chaine);
			$chaine = supprimer2TiretsPrenom($chaine);
			$chaine = mettreDoubleApost($chaine);
			$chaine = taille($chaine);
			if($chaine == "Interdit"){
					$chaine = "interdit";
			}
			$chaine = queEspace($chaine);
			return $chaine;
	}


 ?>