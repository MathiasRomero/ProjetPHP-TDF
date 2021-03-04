<?php
	include_once("fonctionOutil/pdo_oracle.php");
	include_once("fonctionOutil/util_chap11.php");
	include_once("fonctionOutil/nom_prenom_function.php");
	
	$user="PPHP2A_02";
	$mdp="SUPPRIMER";
	$instance = "oci:dbname=kiutoracle18.unicaen.fr:1521/info.kiutoracle18.unicaen.fr;charset=AL32UTF8";
	$conn = OuvrirConnexionPDO($instance,$user,$mdp);
	
	$req = 'SELECT * FROM tdf_nation order by nom';
	$nbLignes=LireDonneesPDO1($conn,$req,$tab);
	
	include_once("fonctionOutil/fonction_util.php");
	
	//si l'utilisateur choisit de supprimer le coureur 
	if(isset($_POST["BtSuppr"])){
		$val = intval($_POST["ID"]);
	
		
		$valN = $_POST["NAT"];
		
		$sql = "select * from tdf_app_nation where n_coureur=".$val;
		$nb = LireDonneesPDO1($conn,$sql,$donnee);
			
		$req_suppression = "delete from tdf_app_nation 
		where n_coureur = :n_coureur and code_cio= :code";
		$cur = preparerRequetePDO($conn, $req_suppression);

		$tab = array (
			':n_coureur'=>$val,
			':code' => $valN,
		);
		majDonneesPrepareesTabPDO($cur, $tab);
		
		if(sizeof($donnee)<2){
			$req_suppression = "delete from tdf_coureur 
		where n_coureur = :n_coureur";
			$cur = preparerRequetePDO($conn, $req_suppression);
		
			$tab = array (
				':n_coureur'=>$val,
			);
			majDonneesPrepareesTabPDO($cur, $tab);
		}
		
		
		$sql_del = "commit";
		majDonneesPDO($conn, $sql_del);
		
		//Rafraichissement de la page pour aller vers afficheCoureur.php
		echo '<meta http-equiv="refresh" content="0; afficheCoureur.php">';
	}
	
	//s'i l'utilisateur choisit de modifier le coureur
	if(isset($_POST["BtModif"])){
		include_once("confirmemodif.php");
		$val = $_POST["ID"];
		echo '<input type="hidden" name="ID" value="'.$val.'">';
		echo '</form></body></html>';
	}
?>