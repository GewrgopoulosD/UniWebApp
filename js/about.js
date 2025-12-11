import { BurgerMenu, updateMenuForLoggedIn } from "./global.js"; //import the function BurgerMenu from global.js
BurgerMenu(); //call the function BurgerMenu

let studiesAboutBtn = document.querySelector(".StudiesAboutBtn");
studiesAboutBtn.addEventListener("click", (e) => {
  let menu = document.querySelector(".menu");
  if (menu.classList.contains("showMenu")) {
    menu.classList.remove("showMenu");
    document.querySelector(".hamburgerC").style.display = "none";
    document.querySelector(".hamburger").style.display = "block";
  }
});

let contactAboutBtn = document.querySelector(".ContactAboutBtn");
contactAboutBtn.addEventListener("click", (e) => {
  let menu = document.querySelector(".menu");
  if (menu.classList.contains("showMenu")) {
    menu.classList.remove("showMenu");
    document.querySelector(".hamburgerC").style.display = "none";
    document.querySelector(".hamburger").style.display = "block";
  }
});

if (window.userRole === "student" || window.userRole === "teacher") {
  updateMenuForLoggedIn(); //call the function updateMenuForLoggedIn
}

loadAboutUs();

function loadAboutUs() {
  const fragment = document.createDocumentFragment();

  let main = document.querySelector("main");
  let outDiv = document.createElement("div");
  outDiv.id = "aboutUsDiv";

  let header1 = document.createElement("h1");
  header1.textContent = "About Education University";
  outDiv.append(header1);

  let paragr1 = document.createElement("p");
  paragr1.textContent = `Education University is a forward-thinking academic institution committed to excellence in education, research, and innovation.
                        Since its founding in 2005, the university has developed a strong reputation for offering high-quality programs in Computer 
                        Science, Engineering, Economics, and Social Sciences. We focus on providing a dynamic learning environment where students
                        engage with modern facilities, experienced faculty, and real-world projects. Our goal is to equip every student with 
                        practical skills, critical thinking, and the confidence to pursue successful careers in their chosen fields.
                        With a growing community, industry collaborations, and a culture that values creativity and progress, Education University 
                        continues to shape the next generation of professionals and leaders.`;
  outDiv.append(paragr1);

  let header2 = document.createElement("h2");
  header2.textContent = "Our campus facilities";
  header2.id = "facilitiesHeader";
  outDiv.append(header2);
  let photoFacilities = document.createElement("img");
  photoFacilities.src = "photos/campusfacilities1.jpg";
  photoFacilities.alt = "campus facilities";
  photoFacilities.id = "facilitiesPhoto1";
  outDiv.append(photoFacilities);

  let photoFacilities2 = document.createElement("img");
  photoFacilities2.src = "photos/campusfacilities2.jpg";
  photoFacilities2.alt = "campus library";
  photoFacilities2.id = "facilitiesPhoto2";
  outDiv.append(photoFacilities2);

  let underGraduateP = document.createElement("H2");
  underGraduateP.textContent = "Undergraduate Programs";
  underGraduateP.id = "UnderPrograms";
  outDiv.append(underGraduateP);
  let underGraduateList = document.createElement("ul");
  underGraduateList.id = "UnderPD";
  let ugPrograms = [
    "Computer Science",
    "Mechanical Engineering",
    "Business Administration",
    "Psychology",
    "Environmental Science",
  ];
  ugPrograms.forEach((program) => {
    let li = document.createElement("li");
    li.textContent = program;
    underGraduateList.appendChild(li);
  });
  outDiv.append(underGraduateList);

  let postGraduateP = document.createElement("H2");
  postGraduateP.textContent = "Postgraduate Programs";
  postGraduateP.id = "PostPrograms";
  outDiv.append(postGraduateP);
  let postGraduateList = document.createElement("ul");
  postGraduateList.id = "PostPD";
  let pgPrograms = [
    "Master in Data Science",
    "Master in Civil Engineering",
    "MBA",
    "Master in Clinical Psychology",
    "Master in Sustainable Development",
  ];
  pgPrograms.forEach((program) => {
    let li = document.createElement("li");
    li.textContent = program;
    postGraduateList.appendChild(li);
  });
  outDiv.append(postGraduateList);

  let header3 = document.createElement("h2");
  header3.textContent = "Our Location";
  header3.id = "locationHeader";
  outDiv.append(header3);

  let mapDiv = document.querySelector("#map");

  const map = L.map("map").setView([37.582038, 22.879172], 12); //define the variable map in attribute which called map and put the map in this(37.582038, 22.879172)(the location of uni) area with zoom(12)

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

  let LocationDiv = document.createElement("div");
  LocationDiv.id = "locationDiv";
  main.append(LocationDiv);
  let locationP = document.createElement("p");
  locationP.textContent = `Education University is located in the suburbs of Nafplio, offering a peaceful and safe environment away from the noise and intensity of the city
                           center. The campus is easily accessible by car and local public transportation and is surrounded by natural scenery and essential infrastructure.`;
  LocationDiv.append(locationP);
  let locationMoreP = document.createElement("p");
  locationMoreP.innerHTML = `Location: Pyrgiotika – Central Square, Nafplio 21100, Greece <br>
                              Phone: +30 27520 12345`;
  LocationDiv.append(locationMoreP);
  main.append(LocationDiv);

  fragment.append(outDiv);
  main.prepend(fragment);
}
