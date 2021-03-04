/*
Affiché les balises input nom et prénom dans cas où il selectionne
le palmarès du coureur
*/
function NomPrenom(){
		var lNom = document.getElementById("lNom");
		var lPre = document.getElementById("lPre");
		var nom = document.getElementById("nom");
		var pren = document.getElementById("pren");
		var contenu = document.getElementById("select");
		if(contenu.value=="palmares"){
				lNom.hidden = false;
				lPre.hidden = false;
				nom.hidden = false;
				pren.hidden = false;
		}
		else{
				lNom.hidden = true;
				lPre.hidden = true;
				nom.hidden = true;
				pren.hidden = true;
		}
	
	
}

/*Au chargement de la page, vérifier si il a selectionné le palmarès*/
window.onload = function () {
	NomPrenom();  
}