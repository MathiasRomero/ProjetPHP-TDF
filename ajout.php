<?php
	include ("fonctionOutil/verifsession.php");
	include("fonctionOutil/pdo_oracle.php");
	include("fonctionOutil/util_chap11.php");
	include("fonctionOutil/nom_prenom_function.php");

	$user="PPHP2A_02";
	$mdp="SUPPRIMER";
	$instance = "oci:dbname=kiutoracle18.unicaen.fr:1521/info.kiutoracle18.unicaen.fr;charset=AL32UTF8";
	$conn = OuvrirConnexionPDO($instance,$user,$mdp); //connection a la basse de données

	$req = 'SELECT * FROM tdf_nation order by nom'; //requête pour obtenir les nations pour le menu déroulant 
	$reqAnnee = 'SELECT COUNT(total_seconde) as NBTEMPS from tdf_temps where annee in (select MAX(annee) FROM tdf_temps)'; //on regarde si il y a des temps dans les épreuves de la dernière année pour laquel on a rentré des coureurs
	$reqAnneeMax = 'SELECT MAX(annee) as MAX FROM tdf_temps';//on recupère cette année
	$nbLignes=LireDonneesPDO1($conn,$req,$tab);
	$debug=LireDonneesPDO1($conn,$reqAnneeMax,$tabAnneeMax);
	$debug=LireDonneesPDO1($conn,$reqAnnee,$tabAnnee);

	if ($tabAnnee[0]["NBTEMPS"]==0) {
		$anneeBonne=$tabAnneeMax[0]["MAX"];//si il n'y a pas de temps dans cette année on peux a nouveau ajouté des coureurs
	}else{
		$anneeBonne=$tabAnneeMax[0]["MAX"]+1;//sinon on passe a l'année suivante
	}
	
	function remplirOption($tab,$nbLignes)	{
		for ($i=0;$i<$nbLignes;$i++){
			$code=$tab[$i]["CODE_CIO"];
			$verif = verifierSelect("pays","$code");
			echo '<option value="'.$code.'" '.$verif.'>'.$tab[$i]['NOM'];
			echo '</option>';
		}
	}
	function verifierSelect($_tab, $_val){	
			if(isset($_POST[$_tab])&&$_POST['affiche']==true){
				if($_POST[$_tab]==$_val){
					return "selected";
				}
			}
		}
	function initAffiche(){
		if (isset($_POST['affiche'])) //on initialise nôtre variable de contrôle de l'affichage des données déja remplis
			$val = $_POST['affiche']; //si elle existe déja on recupère sa valeur que l'on redonne au HTM
		else{
			$val = false;//sinon on l'initialise a false
		}
		return $val;
	}

	function verifierSaisie($_string){
			 if (isset($_POST[$_string])&&$_POST['affiche']==true){ 
			 	echo $_POST[$_string]; //on verifie si on réaffiche ou non les données déja saisie si le formulaire a échoué 
			 }
	}
	function verifierSaisieNationnalité($anneepays,$anneenaiss){
			 if (isset($_POST[$anneepays])&&$_POST['affiche']==true){ 
			 	echo $_POST[$anneepays];
			 }else{
			 	echo $anneenaiss;
			 }
	}

	function verifierDatePartipation($_string,$anneeBonne){
			 if (isset($_POST[$_string])&& $_POST['affiche']==true) {
				return $_POST[$_string]; //on verifie si on réaffiche ou non les données déja saisie si le formulaire a échoué 
			 }else{
				return $anneeBonne;// sinon on affiche l'année obtenu dans les requetes précedente
			 }
	}

	
	if(isset($_POST["BtInsert"])){ //une fois le formulaire envoié
		if(!empty($_POST["nom"])){$nom = $_POST['nom'];}  // on met les valeurs dans des variables
		if(!empty($_POST["prenom"])){$prenom = $_POST['prenom'];}
		//et les valeurs facultatives sont initialisé a NULL si elle n'ont pas été remplis
		if(!empty($_POST["anneenaiss"])){$anneenaiss = intval($_POST["anneenaiss"]);}else{$anneenaiss = NULL;} 
		if(!empty($_POST["anneeprem"]))	{$anneeprem = intval($_POST["anneeprem"]);}else{$anneeprem= NULL;}
		if(!empty($_POST["anneepays"]))	{$anneepays = intval($_POST["anneepays"]);}else{$anneepays=$anneenaiss;}
		if(!empty($_POST["pays"]))	{$pays = $_POST["pays"];}

		$reqNextCoureur = 'SELECT MAX(n_coureur)+1 as MAXCODE from tdf_coureur'; //on obtient un nouveau numéro de coureur 
		$nbLignes2=LireDonneesPDO1($conn,$reqNextCoureur,$tab2);
		$code=intval($tab2[0]["MAXCODE"]); 
		$nom=nom($nom); //on utilise les fonctions de vérification des nom et prénom
		$prenom=prenom($prenom);
		if($prenom!="interdit"&&$nom!="interdit"){ //si le couple nom prénom est conforme ou corrigé conforme
			$test = true;
			$req2 = 'SELECT nom,prenom from tdf_coureur';
			$nbLignes2=LireDonneesPDO1($conn,$req2,$tabPreNom);
			foreach($tabPreNom as $val){
				if($val["NOM"]==$nom && $val["PRENOM"]==$prenom){ //on test avec tout les couples nom prénom des coureurs déja présent dans la basse si il n'existe pas déja
					$test=false;
				}
			}
			if($test==true){ //si il n'existe pas on peut l'ajouté sans problème
				$req2 = "INSERT into tdf_coureur (n_coureur,nom,prenom,annee_naissance, annee_prem,compte_oracle,date_insert) values ('$code','$nom','$prenom','$anneenaiss','$anneeprem','PPHP2A_02',sysdate)";
					$cur=preparerRequetePDO($conn,$req2);
					$res=majDonneesPrepareesPDO($cur);
					$req2 = "INSERT into tdf_app_nation (n_coureur,code_cio,annee_debut,compte_oracle,date_insert) values ('$code','$pays','$anneepays','PPHP2A_02',sysdate)";
					$cur=preparerRequetePDO($conn,$req2);
					$res=majDonneesPrepareesPDO($cur);
					echo '<script>alert("Succès, le coureur a été bien ajoutés à la base de données.")</script>';
				}else{//sinon on affiche un message d'erreur pour l'utilisateur et on garde les valeurs déja saisie affiché
					echo '<script>alert("Erreur, le couple (Nom,Prénom) du coureur est déjà utilisé.")</script>';
					$_POST["affiche"]=true;
				}
		}else{//si le nom n'est pas conforme on affiche un message d'erreur et on garde les valeurs déja saisie affiché
			echo '<script>alert("Le Couple Nom Prénom est incorrect.")</script>';
			$_POST["affiche"]=true;
		}
		$conn=null; //on ferme la connection
	}
	include ("ajout.htm"); //on include le formulaire 
?>