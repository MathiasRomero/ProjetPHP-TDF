<?php
	$test = true;
	
	if(isset($_POST["BtModifier"])){
		
		//les includes et les connexions pdo sont dans le if car quand on
		//entre la première fois dans le .php c'est dans un include de modifsuppr.php
		//et dans ce fichier il y a déjà ces includes et la connexion pdo
		//C'est qu'après avoir include le html et l'envoi du submit de modif.htm
		//qu'on va réinclure les includes et réinitialiser les variables pour se connecter
		include("fonctionOutil/pdo_oracle.php");
		include("fonctionOutil/nom_prenom_function.php");
		
		$user="PPHP2A_02";
		$mdp="SUPPRIMER";
		$instance = "oci:dbname=kiutoracle18.unicaen.fr:1521/info.kiutoracle18.unicaen.fr;charset=AL32UTF8";
		$conn = OuvrirConnexionPDO($instance,$user,$mdp);
		
		$req = 'SELECT * FROM tdf_nation order by nom';
		$nbLignes=LireDonneesPDO1($conn,$req,$tab);
		
		
		include("fonctionOutil/fonction_util.php");
		
		
		$sql_modif='UPDATE tdf_coureur set';
		$modele_sql='UPDATE tdf_coureur set';
		
		if(!empty($_POST["nom"])){
			$nom = $_POST['nom'];
			$nom = nom($nom);
			if($nom!="interdit"){
				if($sql_modif!=$modele_sql){// si un paramètre a déjà été ajouté dans le set, on rajoute une virgule
					$sql_modif=$sql_modif." ,";
				}
				$sql_modif=$sql_modif." nom='".$nom."'";
			}else{
					echo '<script>alert("Le nom est écris de façon ou avec des caractères non-autorisés")</script>';
					$test=false;
			}
		}  
		if(!empty($_POST["prenom"])){
			$prenom = $_POST['prenom'];
			$prenom = prenom($prenom);
			if($prenom!="interdit"){
				if($sql_modif!=$modele_sql){
					$sql_modif=$sql_modif." ,";
				}
				$sql_modif=$sql_modif." prenom='".$prenom."'";
			}else{
				echo '<script>alert("Le prénom est écris de façon ou avec des caractères non-autorisés")</script>';
				$test=false;
			}
		}
		
		if($test){
			//on cherche à tester si le nom du coureur (avec modification) est le même que celui d'un autre coureur
			$req2 = 'SELECT nom,prenom from tdf_coureur';
			$nbLignes2=LireDonneesPDO1($conn,$req2,$tabPreNom2);
			$req3 = 'SELECT nom,prenom from tdf_coureur where n_coureur='.intval($_POST["ID"]).'';
			$nbLignes3=LireDonneesPDO1($conn,$req3,$tabPreNom3);
			foreach($tabPreNom2 as $val){
				if(!empty($_POST["nom"]) && !empty($_POST["prenom"])){//le nom et le prenom ont été saisis
					if($val["NOM"]==$nom && $val["PRENOM"]==$prenom){
						$test=false;
						echo '<script>alert("Un coureur portant ce nom et prénom existe déjà")</script>';
					}
				}
				else if(!empty($_POST["nom"])){//seul le nom a été saisi
					if($val["NOM"]==$nom && $val["PRENOM"]==$tabPreNom3[0]["PRENOM"]){
						$test=false;
						echo '<script>alert("Un coureur portant ce nom existe déjà")</script>';
					}
				}
				else if(!empty($_POST["prenom"])){//seul le prenom a été saisi
					if($val["PRENOM"]==$prenom && $val["NOM"]==$tabPreNom3[0]["NOM"]){
						$test=false;
						echo '<script>alert("Un coureur portant ce nom existe déjà")</script>';
					}
				}
				
			}
			
			
			if(!empty($_POST["anneenaiss"])){
				$anneenaiss = intval($_POST["anneenaiss"]);
				if($sql_modif!=$modele_sql){
					$sql_modif=$sql_modif." ,";
				}
				$sql_modif=$sql_modif." annee_naissance='".$anneenaiss."'";
			}
			if(!empty($_POST["anneeprem"]))	{
				$anneeprem = intval($_POST["anneeprem"]);
				if($sql_modif!=$modele_sql){
					$sql_modif=$sql_modif." ,";
				}
				$sql_modif=$sql_modif." annee_prem='".$anneeprem."'";
			}
			
			// " --------------------------------- " correspond à l'option par défaut de la liste déroulante
			// elle ne doit pas pouvoir être interprétée comme une nation valide
			if(!empty($_POST["pays"]) && $_POST["pays"]!="---------------------------------")	{
				$pays = $_POST["pays"];
				$id=$_POST["ID"];
				
				if(!empty($_POST["choix"]) && $test==true)	{
					
					if($_POST["choix"]=="nouv"){//nouvelle nationalité
						
						//on met à jour la nationalité actuelle en rajoutant une année de fin égale à l'année actuelle
						//on donne ensuite au coureur une nouvelle nationalité dont l'année de début est l'année actuelle et l'année de fin est nulle
						
						$sql_modif_pays="UPDATE tdf_app_nation set annee_fin=to_number(to_char(sysdate, 'YYYY'), '9999') where n_coureur=".intval($id)." and annee_fin is null";

						$sql_pays="INSERT into tdf_app_nation (code_cio,n_coureur,annee_debut, compte_oracle, date_insert) values ('".$pays."',".intval($id).",to_number(TO_CHAR(SYSDATE, 'YYYY'), '9999'), 'PPHP2A_02', sysdate)";
						
						$sql_modif_pays_prep=preparerRequetePDO($conn,$sql_modif_pays);
						$res=majDonneesPrepareesPDO($sql_modif_pays_prep);
						
						$sql_del = "commit";
						majDonneesPDO($conn, $sql_del);
						
						$sql_pays_prep=preparerRequetePDO($conn,$sql_pays);
						$res=majDonneesPrepareesPDO($sql_pays_prep);
						
						$sql_del = "commit";
						majDonneesPDO($conn, $sql_del);
					}
					if($_POST["choix"]=="correc"){//correction de nationalité
						
						//on change juste la nationalité actuelle du coureur
						
						$sql_modif_pays="UPDATE tdf_app_nation set code_cio='".$pays."' where annee_fin is null and n_coureur=".intval($id); //changer le pays (correction)
						$sql_modif_pays_prep=preparerRequetePDO($conn,$sql_modif_pays);
						$res=majDonneesPrepareesPDO($sql_modif_pays_prep);
						
						$sql_del = "commit";
						majDonneesPDO($conn, $sql_del);
					}
				}
			}
		}
		
		if($test == true){
			$sql_modif=$sql_modif." where n_coureur='".$_POST["ID"]."'";
			$sql_modif_prep=preparerRequetePDO($conn,$sql_modif);
			$res=majDonneesPrepareesPDO($sql_modif_prep);
		
			$sql_del = "commit";
			majDonneesPDO($conn, $sql_del);
		
			echo '<script>alert("Le coureur a été modifié")</script>';
			echo '<meta http-equiv="refresh" content="0; afficheCoureur.php">';
			echo $tabPreNom3[0]["PRENOM"];
		}
		else{
				include("modif.htm");
				$val = $_POST["ID"];
				echo '<input type="hidden" name="ID" value="'.$val.'">';
				echo '</form></body></html>';
		}
		$conn=null;
	}
	
	if($test){
		include("modif.htm");
	}
?>