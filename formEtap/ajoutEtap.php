<?php
	include("../fonctionOutil/verifsession.php");
	include("../fonctionOutil/pdo_oracle.php");
	include("../fonctionOutil/util_chap11.php");
	include("../fonctionOutil/nom_prenom_function.php");

	$user="SUPPRIMER";
	$mdp="SUPPRIMER";
	$instance = "SUPPRIMER";
	$conn = OuvrirConnexionPDO($instance,$user,$mdp);
	
	/*Requete qui va être utiliser pour afficher les nations dans les selects*/
	$req = 'SELECT * FROM tdf_nation order by nom';
	$nbLignes=LireDonneesPDO1($conn,$req,$tab);
	
	/*Requete qui va être utiliser pour rechercher toutes les années dans la BDD*/
	$reqA = 'SELECT * FROM tdf_annee order by annee';
	$nbLignes=LireDonneesPDO1($conn,$reqA,$tabAnnee);

	/*Variable boollénne pour savoir si oui ou non
	on conserve les infos dans les inputs (dans le cas d'un echec)*/
	$conserver=false;
	
	if(isset($_POST["BtInsert"])){
		/*Verifie toutes les valeurs entrées dans le formulaire*/
		if(!empty($_POST["numE"])){$numE = $_POST["numE"];}
		if(!empty($_POST["numC"])){$numC = $_POST["numC"];}else{$numC = null;}
		if(isset($_POST["paysD"])&&$_POST["paysD"]!="---------------------------------"){$paysD = $_POST["paysD"];}else{$paysD = null; $conserver=true;}
		if(isset($_POST["paysA"])&&$_POST["paysA"]!="---------------------------------"){$paysA = $_POST["paysA"];}else{$paysA = null; $conserver=true;}
		if(!empty($_POST["villeD"]))	{$villeD = $_POST["villeD"];}
		if(!empty($_POST["villeA"]))	{$villeA = $_POST["villeA"];}
		if(!empty($_POST["dist"]))	{$dist = $_POST["dist"];}
		if(!empty($_POST["moy"]))	{$moy = $_POST["moy"];}else{$moy=null;}
		if(!empty($_POST["date"])){$date = date('d/m/y', strtotime($_POST['date'])); $annee=intval(date('Y', strtotime($_POST['date'])));} //On récupère l'année dans le date
		if(isset($_POST["typeE"])&&$_POST["typeE"]!="---------------------------------"){$typeE = $_POST["typeE"];}else{$typeE = null; $conserver=true;}
		
		$bon=false;
		/*Voir si l'année entrée dans le formulaire est dans tdf_annee*/
		foreach($tabAnnee as $ligneA){
				if(intval($ligneA["ANNEE"])==$annee){
					$bon=true;
				}
		}
		
		/*Si l'annee est pas dans le tdf, message d'erreur et on conserve les valeurs dans les inputs*/
		if($bon==false){
				echo '<script>alert("Erreur : cette année n\'existe pas dans la BDD. Veuillez l\'ajouter dans la BDD avec le formulaire année");</script>';
				$conserver=true;
		}
		
		/*Si on veut entré une étape dans une année inférieur à 2020, message d'alerte*/
		if($annee<2020&&!$conserver){
				$conserver=true;
				echo '<script>alert("Erreur : vous pouvez pas ajouter des étapes dans un TDF terminé");</script>';
		}
		
		/*Si tous les précédents test sont ok, on peut continuer.
		Autrement, on arrête*/
		if(!$conserver){
			//Les villes sont verifier avec la fonction nom() de nom_prenom_function.php
			$villeD=nom($villeD);
			$villeA=nom($villeA);
			/*Si la ville entrée est mal écrit, on l'interdit*/
			if($villeD=="interdit"){
					$conserver=true;
					echo '<script>alert("Erreur : le nom de la ville d\'arrivée n\'est pas écris de façon correcte en français");</script>';
			}
			/*Si la ville entrée est mal écrit, on l'interdit*/
			if($villeA=="interdit"){
					$conserver=true;
					echo '<script>alert("Erreur : le nom de la ville de départ n\'est pas écris de façon correcte en français");</script>';
			}
			
			/*Requete qui va être utilisé pour vérifier le n_etape, DATETAPE et le annnee entré*/
			$req = 'SELECT n_etape, n_comp, DATETAPE, annee FROM tdf_etape order by cat_code';
			$nbLignes=LireDonneesPDO1($conn,$req,$tabE);
		
			
			foreach($tabE as $ligne){
				$i=0;
				foreach($ligne as $valeur){
					/*Si la $valeur est une DATETAPE*/
					if($i%3==2){
							/*Si la date est déjà existante*/
							if($date==$valeur){
									/*Si le numéro de l'étape n'est pas la même que dans cette date*/
									if($numE!=$ligne["N_ETAPE"]){
										$conserver=true;
										echo '<script>alert("Erreur : la date est déjà prise et vous n\'avez pas le bon numéro d\'étape");</script>';
										break;
									}
									/*si le num complémentaire est nul ou égal à un existant*/
									if(empty($numC)||$ligne["N_COMP"]==$numC){
											$conserver=true;
											echo '<script>alert("Erreur : le numéro étape est déjà pris, veuillez mettre un num complémentaire ou un différent");</script>';
											break;
									}
							}
							else{ // Si la date n'est pas existante
								/*Si le numéro étape correspond à la valeur entrée et à l'année qui correspond à celle qui est dans la date entrée*/
								if($numE==$ligne["N_ETAPE"]&&$ligne["ANNEE"]==$annee){
									/*si le num complémentaire est nul ou égal à un existant*/
									if(empty($numC)||$ligne["N_COMP"]==$numC){
											$conserver=true;
											echo '<script>alert("Erreur : le numéro étape est déjà pris, veuillez mettre un num complémentaire ou un différent");</script>';
											break;
									}
								}
							}
					}
					$i++;
				}
				if($conserver){
						break;
				}
			}
			
			/*Si tous les précédents test sont ok, on peut continuer.
			Autrement, on arrête*/
			if(!$conserver){
				/*Si le num complémentaire est vide*/
				if(empty($numC)){
					$dateT = 'to_date('.$date.', \'dd/mm/yy\')'; 
					$req2 = "INSERT into tdf_etape (annee,n_etape, n_comp,code_cio_d, code_cio_a,ville_d,ville_a,distance,moyenne,DATETAPE,cat_code,compte_oracle,date_insert) values ('$annee','$numE',' ','$paysD','$paysA','$villeD','$villeA','$dist','$moy',to_date('$date', 'dd/mm/yy'),'$typeE','PPHP2A_02',sysdate)";
				}
				else if(empty($moy)){ //Si la moyenne est vide
					$req2 = "INSERT into tdf_etape (annee,n_etape,n_comp,code_cio_d, code_cio_a,ville_d,ville_a,distance,DATETAPE,cat_code,compte_oracle,date_insert) values ('$annee','$numE','$numC','$paysD','$paysA','$villeD','$villeA','$dist',to_date('$date', 'dd/mm/yy'),'$typeE','PPHP2A_02',sysdate)";
				}
				else if(empty($numC)&&empty($moy)){ //Si la moyenne est vide et le num complémentaire est vide
					$req2 = "INSERT into tdf_etape (annee,n_etape,n_comp,code_cio_d, code_cio_a,ville_d,ville_a,distance,DATETAPE,cat_code,compte_oracle,date_insert) values ('$annee','$numE',' ','$paysD','$paysA','$villeD','$villeA','$dist',to_date('$date', 'dd/mm/yy'),'$typeE','PPHP2A_02',sysdate)";
				}
				else{ //Sinon
					$req2 = "INSERT into tdf_etape (annee,n_etape,n_comp,code_cio_d, code_cio_a,ville_d,ville_a,distance,moyenne,DATETAPE,cat_code,compte_oracle,date_insert) values ('$annee','$numE','$numC','$paysD','$paysA','$villeD','$villeA','$dist','$moy',to_date('$date', 'dd/mm/yy'),'$typeE','PPHP2A_02',sysdate)";
				}
				//Insertion de la requête et succès
				$cur=preparerRequetePDO($conn,$req2);
				$res=majDonneesPrepareesPDO($cur);
				echo '<script>alert("Succès : étape ajoutée");</script>';
				
			}
		}
	}
	
	/*Ajout du select si il y a eu une section et que $conserver est true*/
	function verifierSelect($_tab, $_val, $conserver){	
		if(isset($_POST[$_tab])&&$conserver){
			if($_POST[$_tab]==$_val){
				return "selected";
			}
		}
	}
	
	/*Remplir le select des pays d'arrivé dans le html*/
	function remplirOptionA($tab,$nbLignes, $conserver)	{
		for ($i=0;$i<$nbLignes;$i++){
			$code=$tab[$i]["CODE_CIO"];
			
			$verif = verifierSelect("paysA","$code", $conserver);
			echo '<option value="'.$code.'" '.$verif.'>'.$tab[$i]['NOM'];
			echo '</option>';
		}
	}
	
	/*Remplir le select des pays de départ dans le html*/
	function remplirOptionD($tab,$nbLignes, $conserver)	{
		for ($i=0;$i<$nbLignes;$i++){
			$code=$tab[$i]["CODE_CIO"];
			
			$verif = verifierSelect("paysD","$code", $conserver);
			echo '<option value="'.$code.'" '.$verif.'>'.$tab[$i]['NOM'];
			echo '</option>';
		}
	}
	
	/*Remplir le input text si une valeur a été entré et que $conserver est true*/
	function verifierText($n, $conserver)
	{  
		if (!empty($_POST[$n])&&$conserver)
		{
		  $var = $_POST[$n];
		  if ($var <> "")
			echo $var; 
		}
		else 
		  echo ""; 
	}
	
	include ("ajoutEtap.htm");
?>