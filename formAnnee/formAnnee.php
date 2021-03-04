<?php
	include ("../fonctionOutil/verifsession.php");
	include "formAnnee.html";
	include("../fonctionOutil/pdo_oracle.php");
	include("../fonctionOutil/util_chap11.php");
	include("../fonctionOutil/nom_prenom_function.php");

	$user="PPHP2A_02";
	$mdp="SUPPRIMER";
	$instance = "oci:dbname=kiutoracle18.unicaen.fr:1521/info.kiutoracle18.unicaen.fr;charset=AL32UTF8";
	$conn = OuvrirConnexionPDO($instance,$user,$mdp);

	//Declaration compte oracle
	$compte = "PPHP2A_02";

	//Recuperation date du jour
	date_default_timezone_set('Europe/Paris');
	$date = date("d/m/Y");


	if(isset($_POST["BtInsert"])){
	if(!empty($_POST["annee"])){
		$annee = $_POST['annee'];
		if(!empty($_POST["repos"])){
			$repos = $_POST['repos'];


			$req = "SELECT annee FROM tdf_annee where annee = '$annee'";
			$nb = LireDonneesPDO1($conn,$req,$tab);
			//Si l'année est déjà présente
			if($nb == 1) echo "Annee déjà présente dans la base de donnée";
			else{
	
			$req = "INSERT INTO TDF_ANNEE (ANNEE,JOUR_REPOS,COMPTE_ORACLE,DATE_INSERT) values ('$annee','$repos','$compte','$date')";
			$cur=preparerRequetePDO($conn,$req);
			$res=majDonneesPrepareesPDO($cur);

			echo "Date ajouté";
			}
		}
	}
}

?>