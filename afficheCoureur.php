<?php
	include("fonctionOutil/verifsession.php");
	include_once("fonctionOutil/pdo_oracle.php");
	include_once("fonctionOutil/util_chap11.php");
	include_once("fonctionOutil/nom_prenom_function.php");
	include_once("fonctionOutil/fonction_util.php");
	
	$user="PPHP2A_02";
	$mdp="SUPPRIMER";
	$instance = "oci:dbname=kiutoracle18.unicaen.fr:1521/info.kiutoracle18.unicaen.fr;charset=AL32UTF8";
	$conn = OuvrirConnexionPDO($instance,$user,$mdp);
	
	$req = 'SELECT * FROM tdf_nation order by nom';
	$nbLignes=LireDonneesPDO1($conn,$req,$tab);
	
	include_once("fonctionOutil/fonction_util.php");
	
	include("selectCoureur.htm");
	if(isset($_POST["BtAffiche"])){
		
		//début de la requête
		$sql_aff='select cou.n_coureur, cou.nom, cou.prenom, annee_naissance, annee_prem, nat.nom as nationalite, nat.code_cio from tdf_coureur cou
		join tdf_app_nation apt on cou.n_coureur=apt.n_coureur
		join tdf_nation nat on apt.code_cio=nat.code_cio';
		
		//modèle pour savoir si la requête à été modifiée
		$modele_sql='select cou.n_coureur, cou.nom, cou.prenom, annee_naissance, annee_prem, nat.nom as nationalite, nat.code_cio from tdf_coureur cou
		join tdf_app_nation apt on cou.n_coureur=apt.n_coureur
		join tdf_nation nat on apt.code_cio=nat.code_cio';
		
		//si nom est pas vide dans le form on l'ajoute dans la futur requete
		if(!empty($_POST["nom"])){
			$nom = $_POST['nom'];
			if($sql_aff==$modele_sql){
				$sql_aff=$sql_aff." where";
			}else{
				$sql_aff=$sql_aff." and";
			}
			$sql_aff=$sql_aff." cou.nom like '".nom($nom)."%'";
		}
		//si prenom est pas vide dans le form on l'ajoute dans la futur requete
		if(!empty($_POST["prenom"])){
			$prenom = $_POST['prenom'];
			if($sql_aff==$modele_sql){
				$sql_aff=$sql_aff." where";
			}else{
				$sql_aff=$sql_aff." and";
			}
			$sql_aff=$sql_aff." cou.prenom like '".prenom($prenom)."%'";
		}
		//si l'année de naissance est pas vide dans le form on l'ajoute dans la futur requete
		if(!empty($_POST["anneenaiss"])){
			$anneenaiss = intval($_POST["anneenaiss"]);
			if($sql_aff==$modele_sql){
				$sql_aff=$sql_aff." where";
			}else{
				$sql_aff=$sql_aff." and";
			}
			$sql_aff=$sql_aff." annee_naissance='".$anneenaiss."'";
		}
		//si l'année de première participation est pas vide dans le form on l'ajoute dans la futur requete
		if(!empty($_POST["anneeprem"]))	{
			$anneeprem = intval($_POST["anneeprem"]);
			if($sql_aff==$modele_sql){
				$sql_aff=$sql_aff." where";
			}else{
				$sql_aff=$sql_aff." and";
			}
			$sql_aff=$sql_aff." annee_prem='".$anneeprem."'";
		}
		
		//si la nationalité à été saisie (pas l'option par défaut)
		if(!empty($_POST["pays"])&&$_POST["pays"]!="---------------------------------")	{
			$pays = $_POST["pays"];
			if($sql_aff==$modele_sql){
				//s'il n'y a aucun paramètre de recherche dans la requête on ajoute un "where" 
				$sql_aff=$sql_aff." where";
			}else{
				//s'il y a déjà des paramètres, on ajoute ","
				$sql_aff=$sql_aff." and";
			}
			$sql_aff=$sql_aff." nat.code_cio='".$pays."'";
		}
		
		//on affiche les coureurs en fonction de leur introduction dans la base de données
		//du plus récent au plus ancien
		$sql_aff=$sql_aff." order by cou.n_coureur DESC";
		
		$nb_aff=LireDonneesPDO1($conn,$sql_aff,$tab_aff);
		if($nb_aff==0){
			echo "Aucun coureur ne correspond à votre sélection.";
		}else{
			echo '<table border="1">';
			$i=0;
			foreach($tab_aff as $coureur){
				//on affiche les informations du coureur, deux boutons et un élément caché dans un formulaire
				//chaque coureur a son propre formulaire
				//l'élémen tcaché sert à transmettre le numéro du coureur aux pages suivantes
				$sql_verif="SELECT * from tdf_parti_coureur where n_coureur=".$coureur["N_COUREUR"];
				$nb_verif=LireDonneesPDO1($conn,$sql_verif,$tab_verif);
				echo '<form name="cour'.$i.'" action = "modifsuppr.php" method="post" enctype="application/x-www-form-urlencoded">';
				echo '<input type="hidden" name="ID" value="'.$coureur["N_COUREUR"].'">';
				echo '<input type="hidden" name="NAT" value="'.$coureur["CODE_CIO"].'">';
				echo "<tr><td>Nom : ".$coureur["NOM"]."</td><td> Prenom : ".$coureur["PRENOM"]."</td><td> Date de naissance : ".$coureur["ANNEE_NAISSANCE"]."</td><td> Première date de participation : ".$coureur["ANNEE_PREM"]."</td><td> Nationalité : ".$coureur["NATIONALITE"]."</td>";
				echo '<td><input class="btn btn-primary" type="submit" name="BtModif" value="Modifier"></td>';
				if($nb_verif<=0){
					echo '<td><input class="btn btn-secondary" type="submit" name="BtSuppr" value="Supprimer" onclick="return confirm(\'Voulez-vous vraiment supprimer ?\')"><td></tr>';
				}
				else{
					echo "</tr>";
				}
				echo "</form>";
				$i++;
			}
			echo "</table>";
		}
		
		$conn=null;
	}
?>