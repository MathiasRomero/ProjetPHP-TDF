<?php
/* 
Ce fichier .php permet de créer/remplacer les vues si elles ont été supprimées
de la BDD. Ce fichier sera include dans le menu.php
*/


$user="PPHP2A_02";
$mdp="SUPPRIMER";
$instance = "oci:dbname=kiutoracle18.unicaen.fr:1521/info.kiutoracle18.unicaen.fr;charset=AL32UTF8";
$conn = OuvrirConnexionPDO($instance,$user,$mdp);

/*Creation/Remplacement de la vue VILLE_ETAPE*/
$sql = "CREATE OR REPLACE VIEW VILLE_ETAPE as
select ville_a ville, annee from tdf_etape
union
select ville_d ville, annee from tdf_etape";
majDonneesPDO($conn,$sql);
/*Creation/Remplacement de la vue Abandon*/
$sql = "CREATE OR REPLACE VIEW Abandon as
select annee, count(*) as nombre_abandon from tdf_abandon
group by annee
order by annee";
majDonneesPDO($conn,$sql);
/*Creation/Remplacement de la vue DISTANCE_ANNEE*/
$sql = "CREATE OR REPLACE VIEW DISTANCE_ANNEE AS
select annee, SUM(distance) as distance from tdf_etape
group by annee
order by annee";
majDonneesPDO($conn,$sql);
/*Creation/Remplacement de la vue CODE_PAYS*/
$sql = "CREATE OR REPLACE VIEW CODE_PAYS as
select distinct eta.code_cio_d, eta.code_cio_a, annee from tdf_etape eta
where eta.code_cio_d in
(
select code_cio from tdf_nation
)";
majDonneesPDO($conn,$sql);
/*Creation/Remplacement de la vue CODE_PAYS2*/
$sql = "CREATE OR REPLACE VIEW CODE_PAYS2 as
select code_cio_d code, annee from CODE_PAYS
union
select code_cio_a code, annee from CODE_PAYS";
majDonneesPDO($conn,$sql);
/*Creation/Remplacement de la vue nation*/
$sql = "CREATE OR REPLACE VIEW nation as
select spo.code_cio, count(*) as participation from tdf_sponsor spo
join tdf_nation nat on nat.code_cio = nat.code_cio
group by spo.code_cio
order by code_cio";
majDonneesPDO($conn, $sql);

/*Récuperation des années du TDF enregistrées dans la BDD*/
$sqlAnnee = "select distinct annee from tdf_parti_coureur
order by annee";

$nb = LireDonneesPDO1($conn, $sqlAnnee, $donnee);
/*La dernière année du TDF enregistrée se trouve à la dernière 
cellule du tableau*/
$TAILLEMAX = intval(count($donnee))-1;
$anneeFin = intval($donnee[$TAILLEMAX]["ANNEE"]);
$i=0;

$annee=0;

/*Création/Remplacement d'une vue du classement général pour chaque année*/
while(intval($donnee[$i]["ANNEE"])<$anneeFin){
	$annee=strval($donnee[$i]["ANNEE"]);
	$sqlV = "CREATE OR REPLACE VIEW Classement$annee as
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
 and valide='O' and annee='$annee'
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
  and annee='$annee' and valide='R'
  group by annee,nom, prenom , difference, n_coureur
  order by TEMPS_TOTAL
)";
	majDonneesPDO($conn, $sqlV);
	$i++;
}

/*Creation/Remplacement de la vue nb_pays*/
$sql="create or replace view nb_pays as 
select nat.nom, annee, count(*) as nombre_nations from tdf_nation nat
join tdf_app_nation apt on apt.code_cio=nat.code_cio
join tdf_coureur cou on cou.n_coureur = apt.n_coureur
join tdf_parti_coureur part on part.n_coureur=cou.n_coureur
group by nat.nom, annee, nat.code_cio";

majDonneesPDO($conn, $sql);
	


?>




