var burger_cacher = document.getElementById("Burger_cacher");
var Burger_fermer = document.getElementById("Burger_fermer");
var burger = document.getElementById("Burger");



function openNav() {
    burger_cacher.classList.add("activate");

}


function closeNav(){
    burger_cacher.classList.remove("activate");
}


burger.onclick = openNav;
Burger_fermer.onclick = closeNav;