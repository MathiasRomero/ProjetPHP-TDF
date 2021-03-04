<?php
	/*Afficher les étapes dont le coureur est sur le podium en tableau*/
	function AfficherDonneeEtapesParti($tab)
	{
	  echo '<table border="1">';
	  echo '<tr><td>Coureur</td><td>Année</td><td>N_etape</td><td>Position</td></tr>';
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
	/*Afficher les classements du TDF du coureur en tableau*/
	function AfficherDonneeAnneesParti($conn, $prenom, $nom)
	{
	  $anneeDeb = 1979;
	  $anneeFin = 2019;
	  $contenir=false;
	  echo '<table border="1">';
	  for($i=$anneeDeb; $i<$anneeFin; $i++){
		   $sql = "select CONCAT(CONCAT(nom,' '),prenom) as Coureur,annee,classement from Classement$i
where prenom Like '$prenom' and nom like '$nom'
order by annee";
		$nb = LireDonneesPDO1($conn, $sql, $tab);
		if($tab[0]["ANNEE"]==$i&&$nb!=0){
			if($contenir==false){
				echo '<tr><td>Coureur</td><td>Année</td><td>Classement</td></tr>';
			}
			$contenir=true;
			echo "<tr><td>".$tab[0]["COUREUR"]."</td><td>".$tab[0]["ANNEE"]."</td><td>".$tab[0]["CLASSEMENT"]."</td></tr>";
		}
	  }
	  echo '</table>';
	  if(!$contenir){
		echo "Le coureur n'a participé a aucun TDF";  
	  }
	}
	
	/*Afficher les abandons du coureur dans tous les TDF en tableau*/
	function AfficherDonneeAbandons($tab){
	  echo '<table border="1">';
	  echo '<tr><td>Année</td><td>N_etape</td><td>Type</td><td>Commentaire</td></tr>';
	  foreach($tab as $ligne)
	  {
		echo '<tr>';
		foreach($ligne as $valeur){
		  if($valeur!=null){
			echo "<td>$valeur </td>";
		  }
		  else{
			echo "<td>aucun</td>";  
	      }
		}
		echo '</tr>';
	  }
	  echo '</table>';
	}
	
	/*Afficher les pays visités lors de tous les TDF en tableau*/
	function AfficherDonneePaysVisite($tab)
	{
	  echo '<table border="1">';
	  echo '<tr><td>Pays</td><td>Années</td></tr>';
	  $pays=null;
	  $pays2=null;
	  $curseur=-1;
	  $tab2=array();
	  foreach($tab as $ligne)
	  {
		$i=0;
		foreach($ligne as $valeur){
			if($i%3==1){
					if($pays!=$valeur){
						$curseur=0;
						if($curseur==0&&$pays!=null){
							echo "<tr>";
							echo "<td>";
							echo $pays;
							echo "</td>";
							echo "<td>";
							for($j=0;$j<sizeof($tab2);$j++){
								echo $tab2[$j]." ";
							}
							echo "</td>";
							echo "</tr>";
							$tab2=array();
						}
						$pays=$valeur;
					}
					
			}
			else if($i%3==2){
				if($ligne["CODE"]=="FRA"){
						$tab2[0]="Toutes les années";
				}
				else{
					$tab2[$curseur]=$valeur;
				}
				
			}
			$i++;
		}
		$curseur++;
	  }
	  echo '</table>';
	}
	
	/*Afficher les villes étapes de tous les TDF en tableau*/
	function AfficherVillesEtapes($tab){
		echo '<table border="1">';
	  echo '<tr><td>Villes</td><td>Années</td></tr>';
	  $ville=null;
	  $ville2=null;
	  $curseur=-1;
	  $tab2=array();
	  foreach($tab as $ligne)
	  {
		$i=0;
		foreach($ligne as $valeur){
			if($i%2==0){
					if(preg_match("/ /", $valeur)){
						$valeur = str_replace(' ', '', $valeur);
					}
					if(preg_match("/ /", $ville)){
						$ville = str_replace(' ', '', $ville);
					}
					if($ville!=$valeur){
						$curseur=0;
						if($curseur==0&&$ville!=null){
							echo "<tr>";
							echo "<td>";
							echo $ville;
							echo "</td>";
							echo "<td>";
							for($j=0;$j<sizeof($tab2);$j++){
								echo $tab2[$j]." ";
							}
							echo "</td>";
							echo "</tr>";
							$tab2=array();
						}
						$ville=$valeur;
					}
					
			}
			else if($i%2==1){
				$tab2[$curseur]=$valeur;
			}
			$i++;
		}
		$curseur++;
	  }
	  echo '</table>';
		
	}
	
	/*Afficher le nombre de fois participés par pays en tableau*/
	function AfficherNationsPartici($tab){
		echo '<table border="1">';
		echo '<tr><td>Pays</td><td>Nombre de participations</td></tr>';
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
	
	/*Afficher l'historique des équipes dans les TDF en tableau*/
	function AfficherHisto($tab){
		echo '<table border="1">';
	  echo '<tr><td>Equipes</td><td>Années</td></tr>';
	  $ville=null;
	  $ville2=null;
	  $curseur=-1;
	  $tab2=array();
	  $debut=0;
	  $fin=0;
	  foreach($tab as $ligne)
	  {
		$i=0;
		foreach($ligne as $valeur){
			
			
			if($i%2==0){
					$val=$valeur;
					$vil=$ville;
					if(preg_match("/ /", $valeur)){
						$val = str_replace(' ', '', $valeur);
					}
					if(preg_match("/ /", $ville)){
						$vil = str_replace(' ', '', $ville);
					}
					if($vil!=$val){
						$curseur=0;
						if($curseur==0&&$ville!=null){
							echo "<tr>";
							echo "<td>";
							echo $ville;
							echo "</td>";
							echo "<td>";
							if($debut==$fin){
									echo "$debut";
							}
							else{
									echo "$debut-$fin";
							}
							echo "</td>";
							echo "</tr>";
							$tab2=array();
						}
						$ville=$valeur;
					}
					
			}
			else if($i%2==1){
				if($debut>intval($valeur)){
						$debut=intval($valeur);
				}
				else{
						if($fin<intval($valeur)){
							$fin=intval($valeur);
						}
				}
			}
			if($curseur==0){
				$debut=intval($ligne["ANNEE"]);
				$fin=intval($ligne["ANNEE"]);
			}
			$i++;
		}
		$curseur++;
	  }
	  echo '</table>';
		
	}
	
	/*Afficher les informations de base du Coureur en tableau*/
	function AfficherTDFCoureur($tab){
		echo '<table border="1">';
		echo "<tr><td>Nom</td><td>Prenom</td><td>Année de Naissance</td><td>Première année de participation</td><td>Nationalité</td></tr>";
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
	
	/*Afficher le nombre de nations participantes par année en tableau*/
	function AfficherStat($tab, $tabP){
		echo '<table border="1">';
		echo "<tr><td>Année</td><td>Nombre de coureurs</td><td>Nombre d'abandons</td><td>Nombre de jeunes</td><td>Distance parcourue</td><td>Nombre de nations présentées</td></tr>";
		$i=0;
		foreach($tab as $ligne)
		{
			echo '<tr>';
			foreach($ligne as $valeur){
				echo "<td>$valeur </td>";
			}
			echo"<td>".$tabP[$i]["NB_PARTICIPANTS"]."</td>";
			echo '</tr>';
			$i++;
		}
		echo '</table>';
	}

 ?>