<?php
	include_once "../fonctionOutil/pdo_oracle.php";
	include_once "../fonctionOutil/util_chap11.php";
	include_once "AffichageGeneral.php";
	
	/*Application de la requete pour les étapes dont le coureur est sur le podium*/
	function afficheEtapeParti($conn, $prenom, $nom){
		$sql = "select CONCAT(CONCAT(cou.nom,' '),cou.prenom) as Coureur, tem.annee, tem.n_etape, tem.rang_arrivee as Position from tdf_coureur cou
join tdf_temps tem on tem.n_coureur = cou.n_coureur
where tem.rang_arrivee < 4 and tem.rang_arrivee > 0 and cou.prenom LIKE '$prenom' and cou.nom LIKE '$nom'
order by annee";
		$nb = LireDonneesPDO1($conn, $sql, $donnee);
		if($nb!=0){
			AfficherDonneeEtapesParti($donnee);
		}
		else{
				echo "Aucune place sur le podium dans une ou plusieurs étapes";
		}
	}
	
	/*Application de la requete pour le classement du coureur dans les TDF*/
	function afficheAnneesParti($conn, $prenom, $nom){
		AfficherDonneeAnneesParti($conn, $prenom, $nom);
	}
	
	/*Application de la requete pour afficher les infos de base du coureur*/
	function afficheTDFCoureur($conn, $prenom, $nom){
		$sql="select cou.nom, cou.prenom, annee_naissance, annee_prem, nat.nom as nationalite from tdf_coureur cou
join tdf_app_nation apt on cou.n_coureur=apt.n_coureur
join tdf_nation nat on apt.code_cio=nat.code_cio
where cou.nom='$nom' and cou.prenom='$prenom'";
		$nb = LireDonneesPDO1($conn, $sql, $donnee);
		if($nb!=0){
			AfficherTDFCoureur($donnee);
		}
		else{
				echo "Le coureur n'a participé à aucun TDF";
		}
		
	}
	
	/*Application de la requete pour les abandons du coureur dans tous les TDF*/
	function afficheAbandons($conn, $prenom, $nom){
		$sql="select annee, n_etape, libelle as type, aba.commentaire from tdf_coureur cou
join tdf_abandon aba on cou.n_coureur=aba.n_coureur
join tdf_typeaban typ on typ.c_typeaban=aba.c_typeaban
where nom='".$nom."' and prenom='".$prenom."'
order by annee";
		$nb = LireDonneesPDO1($conn, $sql, $donnee);
		if($nb!=0){
			AfficherDonneeAbandons($donnee);
		}
		else{
				echo "Aucun abandon pour ce coureur";
		}
		
	}
	
	/*Application de la requete pour les pays qu'a visité le TDF*/
	function affichePaysVisite($conn){
		$sql = "select code, nat.nom, annee from CODE_PAYS2 co
join tdf_nation nat on nat.code_cio = co.code
order by code,annee";
		$nb = LireDonneesPDO1($conn, $sql, $donnee);
		//AfficherDonnee2($donnee);
		AfficherDonneePaysVisite($donnee);
	}
	
	/*Application de la requete pour les villes dans les étapes*/
	function afficheVilleEtapes($conn){
		$sql = "select distinct ville, annee from VILLE_ETAPE
order by ville, annee";
		$nb = LireDonneesPDO1($conn, $sql, $donnee);
		//AfficherDonnee2($donnee);
		AfficherVillesEtapes($donnee);
	}
	
	/*Application de la requete pour le nombre de fois que les nations ont participées*/
	function afficheNationsParti($conn){
		$sql= "select nat.nom, count(*) as participation from tdf_app_nation appt
join tdf_nation nat on nat.code_cio = appt.code_cio
group by nat.nom
order by nat.nom";
		$nb = LireDonneesPDO1($conn, $sql, $donnee);
		AfficherNationsPartici($donnee);
	}
	
	/*Application de la requete pour les historiques d'équipes*/
	function afficheEquipeHisto($conn){
		$sql= "select distinct nom, annee from tdf_sponsor spo
join tdf_parti_coureur cou on spo.n_equipe = cou.n_equipe and cou.n_sponsor = spo.n_sponsor
order by nom";
		$nb = LireDonneesPDO1($conn, $sql, $donnee);
		AfficherHisto($donnee);
	}
	
	/*Application de la requete pour les statistiques*/
	function afficheStat($conn){
		$sql= "select par.annee, count(*) as Nombre_Coureur, aba.nombre_abandon, jeu.nombre_jeune, dis.distance from tdf_parti_coureur par
join abandon aba on aba.annee = par.annee
join DISTANCE_ANNEE dis on dis.annee = par.annee
join Jeune jeu on jeu.annee = par.annee
group by par.annee, nombre_abandon, dis.distance, jeu.nombre_jeune
order by par.annee";
		$nb = LireDonneesPDO1($conn, $sql, $donnee);
		
		$sql= "select annee, count(*) as nb_participants from nb_pays
group by annee
order by annee";
		$nb = LireDonneesPDO1($conn, $sql, $donneeP);
		AfficherStat($donnee, $donneeP);
	}
	
 ?>