<?php
	include("fonctionOutil/verifsession.php");
	include_once "fonctionOutil/fonction_util.php";
	include "rechercheAnnee.htm";
	include "fonctionOutil/pdo_oracle.php";
	include "fonctionOutil/util_chap11.php";
	include "fonctionOutil/fonction_annee.php";

	$res=false;
	$db_username = "PPHP2A_02";
	$db_password = "SUPPRIMER";
	
	$db = "oci:dbname=kiutoracle18.unicaen.fr:1521/info.kiutoracle18.unicaen.fr;charset=AL32UTF8";
	$conn = OuvrirConnexionPDO($db,$db_username,$db_password);
	
	if ( isset($_POST["BtSub"])&&isset($_POST["general"])  ){
		if(!empty($_POST["annee"])){
			$annee=$_POST["annee"];
			if($_POST["general"]=="classG"){ //Si il a selectionné le classement général en fonction de l'année remplie
				if(TDFDeroule($annee, $conn)){
						afficherClassement($conn, $annee);
				}
				else{
					echo "Aucun Tour de France ne s'est déroulé en ".$annee;
				}
				
			}
			else if($_POST["general"]=="Et+Ga"){ //Si il a selectionné les étapes + gagnants en fonction des années
				if(TDFDeroule($annee, $conn)){
						afficherGagnants($conn, $annee);
				}
				else{
					echo "Aucun Tour de France ne s'est déroulé en ".$annee;
				}
				
			}
			else if($_POST["general"]=="Part+Spon"){ //Si il a selectionné les participants et leur sponsor en fonction de l'année remplie
				if(TDFDeroule($annee, $conn)){
						afficherCoureursSponso($conn, $annee);
				}
				else{
					echo "Aucun Tour de France ne s'est déroulé en ".$annee;
				}
				
			}
			else if($_POST["general"]=="aban"){ //Si il a selectionné les abandons en fonction de l'année remplie
				if(TDFDeroule($annee,$conn)){
					afficherAban($conn, $annee);
				}
				else{
					echo "Aucun Tour de France ne s'est déroulé en ".$annee;
				}
			}
			else{
				echo "Erreur : rien à afficher <br>";
			}
		}
		else{
				echo "Erreur : rien à afficher <br>";
		}
	}
	
	/*Verifier si l'année rempli correspond à une des années de la BDD*/
	function TDFDeroule($annee, $conn){
		if($annee<getAnneeMax($conn) && $annee>getAnneeMin($conn)){
				return true;
		}
		else{
			return false;
		}
		
	}
?>
    