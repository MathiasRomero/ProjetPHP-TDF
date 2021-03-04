<?php
	include("../fonctionOutil/verifsession.php");
	include("../fonctionOutil/pdo_oracle.php");
	include("../fonctionOutil/util_chap11.php");
	include("../fonctionOutil/nom_prenom_function.php");

	$user="PPHP2A_02";
	$mdp="SUPPRIMER";
	$instance = "oci:dbname=kiutoracle18.unicaen.fr:1521/info.kiutoracle18.unicaen.fr;charset=AL32UTF8";
	$conn = OuvrirConnexionPDO($instance,$user,$mdp);

	/*Requete qui va être utiliser pour afficher les nations dans les selects*/
	$req = 'SELECT * FROM tdf_nation order by nom';
	$nbLignes=LireDonneesPDO1($conn,$req,$tab);
	
	include("../fonctionOutil/fonction_util.php");

	if(isset($_POST["BtInsert"])){
		/*Variable booléenne qui va servir pour vérifier 
		si les variables obligatoires sont biens remplis*/
		$fin=true;
		/*Verifie toutes les valeurs entrées dans le formulaire*/
		if(!empty($_POST["nom"])){$nom = $_POST["nom"];}else{$nom = null; $fin=false;}
		if(!empty($_POST["cio"])){$cio = $_POST["cio"];}else{$cio = null; $fin=false;}
		if(!empty($_POST["iso"])){$iso = $_POST["iso"];}else{$iso = null; $fin=false;}
		if(!empty($_POST["aCree"]))	{$aCree = intval($_POST["aCree"]);}else{$aCree = null; $fin=false;}
		if(!empty($_POST["aFin"]))	{$aFin = intval($_POST["aFin"]);}else{$aFin = null;}
		if(isset($_POST["pays"]))	{$pays = $_POST["pays"];}else{$pays = null;}
		if(isset($_POST["aFinP"]))	{$aFinP = $_POST["aFinP"];}else{$aFinP = null;}
		
		$paysC=null;
		$test = true;
		/*Si toutes les variables obligatoires sont remplis*/
		if($fin){
			$nom=nom($nom);
			$iso=nom($iso);
			$cio=nom($cio);
			$iso = $iso." ";
			/*Si le nom et le iso et le cio sont bien écris, on continue sinon erreur*/
			if($nom!="interdit"&&$iso!="interdit"&&!$cio!="interdit"){
				$i=0;
				/*Verifier si les trois varibles de ne sont pas déjà dans tdf_nation*/
				foreach($tab as $val){
					if($val["NOM"]==$nom){
						$test=false;
						echo '<script>alert("Le nom de la nation '.$nom.' est déjà pris")</script>';
					}
					else if($val["CODE_CIO"]==$cio){
						$test=false;
						echo '<script>alert("Le code CIO'.$cio.' est déjà pris")</script>';
					}
					else if($val["CODE_ISO"]==$iso){
						$test=false;
						echo '<script>alert("Le code '.$iso.' ISO est déjà pris")</script>';
					}
					
					/*Si une de ces trois variables est fausse, on stop*/
					if(!$test){
						break;
					}
					$i++;
				}
				/*Si on a bien sélectionner un type de changement*/
				if(isset($_POST["typeC"])&&$test){
					$typeC = $_POST["typeC"];
					/*Si c'est un changement (URSS -> Russie)*/
					if($typeC=="change"){
						if(!empty($pays)&&$pays!="---------------------------------"&&!empty($aFinP)){
							$req3 = "UPDATE tdf_nation set annee_disparition=$aFinP where code_cio='$pays'";
							$cur=preparerRequetePDO($conn,$req3);
							$res=majDonneesPrepareesPDO($cur);
							
							$sql_del = "commit";
							majDonneesPDO($conn, $sql_del);
							
							$req2 = "INSERT into tdf_nation (nom,code_cio,code_iso,annee_creation,compte_oracle,date_insert) values ('$nom','$cio','$iso','$aCree','PPHP2A_02',sysdate)";
							$cur=preparerRequetePDO($conn,$req2);
							$res=majDonneesPrepareesPDO($cur);
							echo '<script>alert("Nouveau pays créé")</script>';
						}
						else{
							echo '<script>alert("Pays parent manquant et/ou son année de fin")</script>';
						}
						
					} //Si c'est un nouveau pays (ex : Algérie)
					else{
						$req2 = "INSERT into tdf_nation (nom,code_cio,code_iso,annee_creation,compte_oracle,date_insert) values ('$nom','$cio','$iso','$aCree','PPHP2A_02',sysdate)";
						$cur=preparerRequetePDO($conn,$req2);
						$res=majDonneesPrepareesPDO($cur);
						echo '<script>alert("Nouveau pays créé")</script>';
					}
				} //Si aucun paramètre d'ajout n'a été choisi (nouveau ou changement)
				else if(!$test){
						echo '<script>alert("Choisissez un paramètre d\'ajout")</script>';
				}
					
			}
			else{
				echo '<script>alert("Le nom ou/et le code cio ou/et le code iso de la nouvelle nation est incorrect")</script>';
			}
		}
		else{
				echo '<script>alert("Il manque des paramètres importants")</script>';
			
		}
		$conn=null;
		echo '<meta http-equiv="refresh" content="0; ajoutNatio.php">';
	}
	include ("ajoutNatio.htm");
?>