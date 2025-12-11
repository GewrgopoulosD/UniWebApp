import { BurgerMenu, updateMenuForLoggedIn } from "./global.js"; //import the function BurgerMenu from global.js
BurgerMenu(); //call the function BurgerMenu

let readMoreBtn = document.getElementById("readMore");
readMoreBtn.addEventListener("click", () => {
  window.location.href = "About.php"; //when the user click the button, redirect user to About.php
});

let programsPBtn = document.getElementById("programsP");
programsPBtn.addEventListener("click", () => {
  window.location.href = "About.php#UnderPrograms"; //when the user click the button goes to About.php and in the section with the degrees
});
let programsUBtn = document.getElementById("programsM");
programsUBtn.addEventListener("click", () => {
  window.location.href = "About.php#UnderPrograms"; //when the user click the button goes to About.php and in the section with the degrees
});

// MAPS (LEAFLET)
var map = L.map("map").setView([37.582038, 22.879172], 12); //define the variable map in attribute which called map and put the map in this(37.582038, 22.879172)(the location of uni) area with zoom(12)

//leaflet maps work with layers and tiles, its not an "image" with the map so,
L.tileLayer(
  "https://tile.openstreetmap.org/{z}/{x}/{y}.png", //the L is the main object which leaflet privide us when we load it, create the layer with the z(zoom), x(column),y[row]. when the user moves the map, it can "make" new "picture" with the new layers and tile
  {
    maxZoom: 19, //users cant bigger zoom than 19
    attribution:
      '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>', //its necessary to put the copyrights of OpenStreetMap
  }
).addTo(map); // add layers and tile in map

L.marker([37.582038, 22.879172])
  .addTo(map) //put a marker in position [] and add it to map(our variable)
  .bindPopup("Education University")
  .openPopup(); //make a popup space with this ("") inside

if (window.userRole === "student" || window.userRole === "teacher") {
  updateMenuForLoggedIn(); //call the function updateMenuForLoggedIn
}
