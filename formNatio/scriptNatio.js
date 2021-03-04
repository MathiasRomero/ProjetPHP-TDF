/*Lors du chargement de la fenêtre, on appelle*/
window.onload = function () {
    /* event listener */
    document.getElementById("aFinP").addEventListener('change', finParent);
    /* function */
	function finParent(){
		var aFinP = document.getElementById("aFinP");
		var aCree = document.getElementById("creation");
		aCree.value=aFinP.value;
	
	}
}


/*Fonction qui permet de faire apparaitre ou non
un select pour selectionner un pays parent 
et un input text*/
function changement(){
		var selecte = document.getElementById("select");
		var change = document.getElementById("change");
		var aFinP = document.getElementById("aFinP");
		if(change.checked){
				selecte.hidden = false;
				aFinP.required = true;
		}
		else{
			selecte.hidden = true;
			aFinP.required=false;
			
		}
}

/*
Afficher les messages dans le modal
Ce message contient toutes les informations entrées
dans le formulaire
*/
function message(){
		var nom = document.getElementById("nom");
		var cio = document.getElementById("cio");
		var iso = document.getElementById("iso");
		var aCree = document.getElementById("creation");
		var aFin = document.getElementById("aFin");
		var aFinP = document.getElementById("aFinP");
		var pays = document.getElementById("pays");
		
		var textemodal = document.getElementById("text-modal");
		
		
		if(document.getElementById("select").hidden){
			textemodal.innerHTML="Nom : "+nom.value+"<br>ISO : "+iso.value+"<br> CIO : "+cio.value+"<br> Année Création : "+aCree.value+"<br> Année Fin : "+aFin.value+"<br>";
		}
		else{
			textemodal.innerHTML="Nom pays parent : "+pays.options[pays.selectedIndex].text+"<br>Année fin du parent : "+aFinP.value+"<br>Nom : "+nom.value+"<br>ISO : "+iso.value+"<br> CIO : "+cio.value+"<br> Année Création : "+aCree.value+"<br> Année Fin : "+aFin.value+"<br>";
		}
		
		
}