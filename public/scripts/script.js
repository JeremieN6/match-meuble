// /* Set the width of the side navigation to 250px and the left margin of the page content to 250px and add a black background color to body */
// function openNav() {
//     document.getElementById("mySidenav").style.width = "250px";
//     document.getElementById("mainPage").style.marginLeft = "250px";
//     document.body.style.backgroundColor = "rgba(0,0,0,0.4)";
//   }
  
//   /* Set the width of the side navigation to 0 and the left margin of the page content to 0, and the background color of body to white */
//   function closeNav() {
//     document.getElementById("mySidenav").style.width = "0";
//     document.getElementById("mainPage").style.marginLeft = "0";
//   }

/* Set the width of the side navigation to 250px */
function openNav() {
  document.getElementById("mySidenav").style.width = "250px";
}

function openNavMobile() {
  document.getElementById("mySidenavMobile").style.width = "250px";
}

/* Set the width of the side navigation to 0 */
function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
}

/* Set the width of the side navigation to 0 */
function closeNavMobile() {
  document.getElementById("mySidenavMobile").style.width = "0";
}

/* LISTE DES OFFRES ET DEMANDES */ 
document.addEventListener('DOMContentLoaded', function () {
    // Sélectionnez les boutons et les listes
    var btnOffres = document.getElementById('btnOffres');
    var btnDemandes = document.getElementById('btnDemandes');
    var listeOffres = document.getElementById('listeOffres');
    var listeDemandes = document.getElementById('listeDemandes');

    // Fonction pour afficher la liste des offres et masquer la liste des demandes
    btnOffres.addEventListener('click', function () {
        listeOffres.style.display = 'block';
        listeDemandes.style.display = 'none';
    });

    // Fonction pour afficher la liste des demandes et masquer la liste des offres
    btnDemandes.addEventListener('click', function () {
        listeOffres.style.display = 'none';
        listeDemandes.style.display = 'block';
    });
});