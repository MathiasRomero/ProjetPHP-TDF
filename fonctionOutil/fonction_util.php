<?php

	/*Remplir les options dans un select pour les nationalité*/
	function remplirOption($tab,$nbLignes)	{
		for ($i=0;$i<$nbLignes;$i++){
			$code=$tab[$i]["CODE_CIO"];
			echo '<option value="'.$code.'">'.$tab[$i]['NOM'];
			echo '</option>';
		}
	}
	
	/*Vérifier le select pour le selected si besoin*/
	function VerifierSelect ($pa,$n)
	{
		if (isset($_POST[$pa]))
		{
		  if ( $_POST[$pa] == $n) 
			  echo "selected";
		}
	}
	
	/*Vérifier un input text pour conserver sa value si besoin*/
	function verifierText($n)
	{  
		if (!empty($_POST[$n]))
		{
		  $var = $_POST[$n];
		  if ($var <> "")
			echo $var; 
		}
		else 
		  echo ""; 
	}
	
?>
