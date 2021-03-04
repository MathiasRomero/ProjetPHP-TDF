/*
Afficher les messages dans le modal
Ce message contient toutes les informations entrées
dans le formulaire
*/
function message(){
		var numE = document.getElementById("numE");
		var numC = document.getElementById("numC");
		var paysD = document.getElementById("paysD");
		var paysA = document.getElementById("paysA");
		var villeD = document.getElementById("villeD");
		var villeA = document.getElementById("villeA");
		var dist = document.getElementById("dist");
		var moy = document.getElementById("moy");
		var date = document.getElementById("date");
		var typeE = document.getElementById("typeE");
		
		var textemodal = document.getElementById("text-modal");
		
		textemodal.innerHTML="Numéro d'épreuve : "+numE.value+"<br>Numéro complémentaire : "+numC.value+"<br>Pays ville de départ : "+paysD.options[paysD.selectedIndex].text+"<br>Pays ville d'arrivée : "+paysA.options[paysA.selectedIndex].text+"<br> Ville de départ : "+villeD.value+"<br> Ville d'arrivée : "+villeA.value+"<br> Distance : "+dist.value+"<br>";
		textemodal.innerHTML+="Moyenne :"+moy.value+"<br>Date : "+date.value+"<br>Type d'épreuve : "+typeE.options[typeE.selectedIndex].text;
		
		
}