<?php
// E.Porcq  util_chap11.php  28/08/2018 

function AfficherDonnee1($tab,$nbLignes)
{
  if ($nbLignes > 0) 
  {
    echo "<table border=\"1\">\n";
    echo "<tr>\n";
    foreach ($tab as $key => $val)  // lecture des noms de colonnes
    {
      echo "<th>$key</th>\n";
    }
    echo "</tr>\n";
	echo $nbLignes;
    for ($i = 0; $i < $nbLignes; $i++) // balayage de toutes les lignes
    {
      echo "<tr>\n";
      foreach ($tab as $data) // lecture des enregistrements de chaque colonne
	  {
        echo "<td>$data[$i]</td>\n";
      }
      echo "</tr>\n";
    }
    echo "</table>\n";
  } 
  else 
  {
    echo "Pas de ligne<br />\n";
  } 
  echo "$nbLignes Lignes lues<br />\n";
}
//---------------------------------------------------------------------------------------------
function AfficherDonnee2($tab)
{
  foreach($tab as $ligne)
  {
    foreach($ligne as $valeur)
	  echo $valeur." ";
    echo "<br/>";
  }
}

/*Afficher le classement général dans un tableau en fonction de l'année*/
function AfficherDonneeClassementG($tab)
{
  echo '<table border="1">';
  echo '<tr><td>Classement</td><td>N_Dossard</td><td>Nom</td><td>Prénom</td><td>Nationalité</td><td>Temps Total</td>';
  foreach($tab as $ligne)
  {
	$i = 0;
	echo '<tr>';
    foreach($ligne as $valeur){
	  if($i%5==0&&$i!=0){
		  $heure = explode(".", ($valeur/3600))[0]."h";
		  $minute = explode(".", ($valeur%3600)/60)[0]."min";
		  $seconde = ($valeur%60)."sec";
		  $valeur = $heure." ".$minute." ".$seconde;
	  }
	  echo "<td>$valeur</td>";
	  $i++;
	}  
    echo "</tr>";
  }
  echo "</table>";
}

/*Afficher les étapes et leur gagnant en fonction de l'année*/
function AfficherEtapeGagnant($tab)
{
	echo '<table border="1">';
	echo '<tr><td>N_étape</td><td>Date étape</td><td>Ville étape</td><td>Distance</td><td>Catégorie</td><td>Vainqueur</td></tr>';
	$nEtape=-20;
	foreach($tab as $ligne)
	{
		$i=0;
		$nom=null;
		
		if($ligne["N_ETAPE"]!=$nEtape){
			echo '<tr>';
			foreach($ligne as $valeur){
				if($i<6){
					if($i==4&&$valeur=="CME"){
						$nom=$ligne["NOM"];
					}
					else if($i==5&&$nom!=null){
							$valeur=$nom;
					}
					echo "<td>$valeur</td>";
				}
				$i++;
				
			}
			echo "</tr>";
		}
		$nEtape=$ligne["N_ETAPE"];
	}
	echo "</table>";
	
	
}

/*Afficher les coureurs et leur Sponsor en fonction de l'année*/
function AfficherDonneePartEtSponsor($tab){
	echo '<table border="1">';
	echo '<tr><td>Numéro du Dossard</td><td>Coureur</td><td>Nom du Sponsor</td></tr>';
	$nEtape=-20;
	foreach($tab as $ligne)
	  {
		echo '<tr>';
		foreach($ligne as $valeur){
		  echo "<td>$valeur </td>";
		}
		echo '</tr>';
	  }
	echo '</table>';
	
	
}

/*Afficher les abandons dans un tableau en fonction de l'année*/
function afficherAbandon($tab){
	echo '<table border="1">';
	echo '<tr><td>Numero de dossard</td><td>Coureur</td><td>Nom equipe</td><td>Type abandon</td><td>Information</td></tr>';
	foreach($tab as $ligne)
	  {
		echo '<tr>';
		foreach($ligne as $valeur){
		  echo "<td>$valeur </td>";
		}
		echo '</tr>';
	  }
	echo '</table>';	
}



//---------------------------------------------------------------------------------------------
function AfficherDonnee3($tab,$nb)
{
  for($i=0;$i<$nb;$i++)
    echo $tab[$i][0]." ".$tab[$i][1]." ".$tab[$i][2]."\n";
}
//---------------------------------------------------------------------------------------------
function AfficherTab($tab)
{
	echo "<PRE>";
	print_r($tab);
	echo "</PRE>";
}
//---------------------------------------------------------------------------------------------


?>




