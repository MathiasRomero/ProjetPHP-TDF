window.onload = function () {//fonction qui permet de copié directement la valeur saisie dans année de naissance, dans début de nationnalité 
    /* event listener */
    document.getElementById("anneenaiss").addEventListener('change', modifieDepuis);
    /* function */
    function modifieDepuis(){
        document.getElementById("anneepays").value=document.getElementById("anneenaiss").value;
    }    
}

function message(){
		var nom = document.getElementById("nom");
		var pren = document.getElementById("pren");
		var natio = document.getElementById("select");
		var anneenaiss = document.getElementById("anneenaiss");
		var anneepays = document.getElementById("anneepays");
		var anneeprem = document.getElementById("anneeprem");
		//fonction permetant de pouvoir voir les données remplis avant de confirmé
		var textemodal = document.getElementById("text-modal");
		
		textemodal.innerHTML="Nom : "+nom.value+"<br>Prénom : "+pren.value+"<br> Année de naissance : "+anneenaiss.value+"<br> Première participation : "+anneeprem.value+"<br> Nationalité : "+natio.options[natio.selectedIndex].text+"<br> Depuis : "+anneepays.value+"<br>";
}

function verifierSub(){ 
	//fonction permettant de vérifié des conditions spécial a l'insersion de coureur dans la table
	//si une de ses conditions n'est pas respectées la fenêtre de confirmation ne s'affichera pas
	var nom = creerCoureur.nom.value;
	var pren = creerCoureur.pren.value;
	var verifAnnee =  parseInt (creerCoureur.verifannee.value);
	var affiche = creerCoureur.affiche.value;
	var agemin = new Number ("20");
	var naissMax = new Number("60");
	var naiss = new Number (creerCoureur.anneenaiss.value);
	var prem  = new Number (creerCoureur.anneeprem.value) ;
	var pays = new Number (creerCoureur.anneepays.value);
	if (pren == '' && nom == '') {
		alert("Veuillez saisir un couple nom,prénom.");
		affiche=true;
		$('#exampleModal').modal('hide');
		return false;
	}else
	if (pren == '') {
		alert("Veuillez saisir un prénom.");
		affiche=true;
		$('#exampleModal').modal('hide');
		return false;
	}else
	if (nom == '') {
		alert("Veuillez saisir un nom de famille.");
		affiche=true;
		$('#exampleModal').modal('hide');
		return false;
	}else
	if(creerCoureur.pays.value=="---------------------------------"){
		alert("Veuillez saisir un pays.");
		affiche=true;
		$('#exampleModal').modal('hide');
		return false;
	}else 
	if((agemin+naiss>prem || pays<naiss|| pays>prem || naiss>verifAnnee+naissMax)&&(pays!=0&&prem!=0&&naiss!=0)){ 
		alert("Dates invalides."+pays+" "+naiss+" "+prem);
		affiche=true;
		$('#exampleModal').modal('hide');
		return false;
	}else
	if (prem<verifAnnee&&prem!=0) {
		alert("Tour de France Terminé.");
		affiche=true;
		$('#exampleModal').modal('hide');
		return false;
	}else{
		$('#exampleModal').modal('show');
		message();
		return true;
	}
}

