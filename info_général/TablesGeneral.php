<?php
	include("../fonctionOutil/verifsession.php");
	include_once "../fonctionOutil/fonction_util.php";
	include "InfoGeneral.htm";
	include_once "../fonctionOutil/pdo_oracle.php";
	include_once "../fonctionOutil/util_chap11.php";
	include_once "fonction_general.php";
	
	$res=false;
	$db_username = "PPHP2A_02";
	$db_password = "SUPPRIMER";
	
	$db = "oci:dbname=kiutoracle18.unicaen.fr:1521/info.kiutoracle18.unicaen.fr;charset=AL32UTF8"; // fonctionne si tnsname.ora est complet (base UTF8)
	$conn = OuvrirConnexionPDO($db,$db_username,$db_password);
	
	/*Si on pousse le formulaire qu'on a selectionné dans le select*/
	if ( isset($_POST["BtSub"])&&isset($_POST["general"])  ){
		/*Si on a selectionné le palmarès*/
		if($_POST["general"]=="palmares"){
			if (!empty($_POST["nom_coureur"])&&!empty($_POST["prenom_coureur"])){
					$nom = $_POST["nom_coureur"];
					$prenom = $_POST["prenom_coureur"];
					echo "<hr/>";
					echo "AFFICHAGE : Palmares (étapes et Tours Participés)";
					echo "<hr/>";
					echo "<br><b>Informations du coureur</b><br>";
					afficheTDFCoureur($conn, $prenom, $nom);
					echo "<br><b>Etapes participées et être sur le podium</b><br>";
					afficheEtapeParti($conn, $prenom, $nom);
					echo "<br><b>Classement dans les Tours de France</b><br>";
					afficheAnneesParti($conn, $prenom, $nom);
					echo "<br><b>Les abandons</b><br>";
					afficheAbandons($conn, $prenom, $nom);
			}
		} /*Si on a selectionné les pays qui ont visités*/
		else if($_POST["general"]=="paysVisit"){
			echo "AFFICHAGE : Pays qui ont participés au Tour de France";
			echo "<hr/>";
			affichePaysVisite($conn);
		} /*Si on a selectionné les villes étapes des TDF*/
		else if($_POST["general"]=="villeEtape"){
			echo "AFFICHAGE : Villes étapes des Tours de France";
			echo "<hr/>";
			afficheVilleEtapes($conn);
			
		} /*Si on a selectionné les pays qui ont participés au TDF*/
		else if($_POST["general"]=="nationParti"){
			echo "AFFICHAGE : Nations ayant participés à des Tours de France";
			echo "<hr/>";
			afficheNationsParti($conn);
		} /*Si on a selectionné toutes les équipes du TDF*/
		else if($_POST["general"]=="équipes"){
			echo "AFFICHAGE : Toutes les équipes du Tour de France";
			echo "<hr/>";
			afficheEquipeHisto($conn);
		} /*Si on a selectionné les statistiques*/
		else if($_POST["general"]=="stat"){
			echo "AFFICHAGE : Des statistiques";
			echo "<hr/>";
			afficheStat($conn);
		} /*Sinon*/
		else{
				echo "Erreur : rien à afficher <br>";
		}
	}
 ?>