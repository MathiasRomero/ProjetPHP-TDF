<?php
	include_once "pdo_oracle.php";
	include_once "util_chap11.php";

	/*Afficher le classement général*/
	function afficheClassementG($conn, $val){
		//Création d'une vue en fonction de l'année du classement
		$sqlV = "CREATE OR REPLACE VIEW Classement as
select rownum as classement, n_coureur, n_dossard, nom, prenom,TEMPS_TOTAL, annee from
(
  select n_coureur, annee,to_char(n_dossard) as n_dossard, nom, prenom, sum(total_seconde) +nvl(difference,0) as TEMPS_TOTAL
  from tdf_coureur
  join tdf_temps using(n_coureur)
  join tdf_parti_coureur using(n_coureur, annee)
  left join tdf_temps_difference using(n_coureur,annee)
  where (n_coureur,annee) not in
  (
    select n_coureur,annee from tdf_abandon
  )
 and valide='O' and annee=$val
  group by annee,n_dossard, nom, prenom , difference, n_coureur
  union
  select n_coureur, annee,'------', substr(nom,1,2)||'----', prenom, sum(total_seconde) +nvl(difference,0) as TEMPS_TOTAL
  from tdf_coureur
  join tdf_temps using(n_coureur)
  join tdf_parti_coureur using(n_coureur, annee)
  left join tdf_temps_difference using(n_coureur,annee)
  where (n_coureur,annee) not in
  (
    select n_coureur,annee from tdf_abandon
  )
  and annee=$val and valide='R'
  group by annee,nom, prenom , difference, n_coureur
  order by TEMPS_TOTAL
)";
		majDonneesPDO($conn, $sqlV);
		//La requête SQL
		$sql = "select classement, n_dossard, cla.nom, cla.prenom, nat.nom as Nationalité, temps_total from Classement cla
join tdf_app_nation apt on apt.n_coureur = cla.n_coureur
join tdf_nation nat on apt.code_cio = nat.code_cio
order by classement";
		//Lecture de la requête
		$nb = LireDonneesPDO1($conn, $sql, $donnee);
		//Affichage de la requête
		AfficherDonneeClassementG($donnee);
	}
	/*Afficher les gagnants des étapes en fonction de l'année*/
	function gagnantEtapeParAnnee($conn, $val){	
		if ($conn){
			//AFFICHER PAR N_COUREUR
			//La requête SQL
			$sql = "select tem.n_etape,eta.datetape,CONCAT(CONCAT(eta.ville_d,' - '),eta.ville_a) as ville_etape, eta.distance, eta.cat_code,CONCAT(CONCAT(cou.prenom,' '),cou.nom) as vainqeur_etape, par.n_dossard, spo.nom from tdf_coureur cou
					join tdf_temps tem on cou.n_coureur = tem.n_coureur
					join tdf_parti_coureur par on cou.n_coureur = par.n_coureur and tem.annee = par.annee
					join tdf_sponsor spo on par.n_equipe = spo.n_equipe and par.n_sponsor = spo.n_sponsor
					join tdf_etape eta on tem.n_etape=eta.n_etape and tem.annee = eta.annee 
					where tem.rang_arrivee=1 and tem.annee = $val
					order by n_etape";
			//Lecture de la requête
			$nb = LireDonneesPDO1($conn,$sql,$donnee); 
			//Affichage de la requête
			AfficherEtapeGagnant($donnee);	
		}
	}
	
	/*Afficher les participants et leur Sponsor en fonction de l'année*/
	function participantEtSponsorParAnnee($conn, $val){	
		if ($conn){
			//AFFICHER PAR N_COUREUR
			//La requête SQL
			$sql = "select par.n_dossard, CONCAT(CONCAT(cou.nom,' '), cou.prenom) as Coureur, spo.nom as Nom_Sponsor from tdf_coureur cou
					join tdf_parti_coureur par on cou.n_coureur = par.n_coureur
					join tdf_sponsor spo on par.n_sponsor = spo.n_sponsor and par.n_equipe = spo.n_equipe
					where par.annee = $val
					order by par.n_dossard";
			//Lecture de la requête
			$nb = LireDonneesPDO1($conn,$sql,$donnee); 
			//Affichage de la requête
			AfficherDonneePartEtSponsor($donnee);		// fonctionne avec matrice num/obj
		
		}
	}

	/*Afficher les abandons en fonction de l'année*/
	function abandonCoureur($conn, $val){
		if($conn){
			//La requête SQL
			$sql = "select par.n_dossard,CONCAT(CONCAT(cou.nom,' '), cou.prenom) as coureur, spo.nom,typ.libelle as abandon, aba.commentaire as information from tdf_coureur cou
					join tdf_abandon aba on cou.n_coureur = aba.n_coureur
					join tdf_parti_coureur par on cou.n_coureur = par.n_coureur and aba.annee = par.annee
					join tdf_sponsor spo on par.n_equipe = spo.n_equipe and par.n_sponsor = spo.n_sponsor
					join tdf_typeaban typ on typ.c_typeaban = aba.c_typeaban
					where aba.annee = $val
					order by cou.nom";
			//Lecture de la requête
			$nb = LireDonneesPDO1($conn,$sql,$donnee);
			//Affichage de la requête
			afficherAbandon($donnee);
		}
	}
	
	/*Fonction qui ajoute un message puis affiche les gagnants du TDF en fonction de l'année*/
	function afficherGagnants($conn, $valeur){
		echo "<hr/>";
		echo "AFFICHAGE : Gagnant des étapes de ".$valeur;
		echo "<hr/>";
		gagnantEtapeParAnnee($conn, $valeur);
	}

	/*Fonction qui ajoute un message puis affiche les abandons du TDF en fonction de l'année*/
	function afficherAban($conn, $valeur){
		echo "<hr/>";
		echo "AFFICHAGE : Abandons des coureurs de ".$valeur;
		echo "<hr/>";
		abandonCoureur($conn,$valeur);
	}
	
	/*Fonction qui ajoute un message puis affiche les coureurs et leur Sponso du TDF en fonction de l'année*/
	function afficherCoureursSponso($conn, $valeur){	
		echo "<hr/>";
		echo "AFFICHAGE : Coureurs et sponsors de ".$valeur;
		echo "<hr/>";
		participantEtSponsorParAnnee($conn, $valeur);
	}
	
	
	/*Fonction qui ajoute un message puis affiche le classement général en fonction de l'année*/
	function afficherClassement($conn, $valeur){	
		echo "<hr/>";
		echo "AFFICHAGE : Classement général de ".$valeur;
		echo "<hr/>";
		afficheClassementG($conn, $valeur);
	}
	
	/*Obtenir l'année Max*/
	function getAnneeMax($conn){
		if ($conn){
			$sql = "select max(annee) as max from tdf_annee";
			$nb = LireDonneesPDO1($conn,$sql,$donnee); 
			return $donnee[0]["MAX"];
		}
	}
	
	/*Obtenir l'année Min*/
	function getAnneeMin($conn){
		if ($conn){
			$sql = "select min(annee) as min from tdf_annee";
			$nb = LireDonneesPDO1($conn,$sql,$donnee); 
			return $donnee[0]["MIN"];
		}
	}
 ?>