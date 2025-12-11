import { BurgerMenu } from "./global.js";
BurgerMenu();

const params = new URLSearchParams(window.location.search); //we set a js tool who read the parameters (after ?) from the page which came us here
const mode = params.get("mode"); //set it on const mode ("login" or "signup")

let container = document.querySelector("#authCont");

if (!mode || (mode !== "login" && mode !== "signup")) {
  // check for scam injection
  let fail = document.createElement("p");
  fail.textContent = "Invalid mode";
  fail.style.fontSize = "x-large";
  container.append(fail);
} else {
  loadForm(mode);
}

function loadForm(mode) {
  const fragment = document.createDocumentFragment(); // we make a temporary container

  let div = document.createElement("div"); //we make a div

  let header = document.createElement("h2"); //we make an element h2
  header.textContent = mode === "login" ? "Login" : "Create account"; //if mode = login > h2=login else create account

  div.append(header); // we put the h2 in the div

  let errorElem = document.createElement("h4"); // we make an element if the user does wrong input to prompt him with a message about his fault
  errorElem.style.display = "none"; //initialy we hide this element and if he makes a mistake we will display it
  div.append(errorElem);

  let errorValid = params.get("error"); // if we took an error from the parameters we save it here
  if (errorValid) {
    errorElem.style.display = "block";
    if (errorValid === "empty") {
      errorElem.textContent = "Fill all fields!";
    } else if (errorValid === "user") {
      errorElem.textContent = "User with this email didnt found!";
    } else if (errorValid === "pass") {
      errorElem.textContent = "Wrong password, try again!";
    } else if (errorValid === "mail") {
      errorElem.textContent = "Invalid email, try again!";
    } else if (errorValid === "passL") {
      errorElem.textContent = "Password must be at least 6 characters!";
    } else if (errorValid === "userEx") {
      errorElem.textContent = "Username already exist,try again!";
    } else if (errorValid === "mailEx") {
      errorElem.textContent = "Email already exist,try again!";
    } else if (errorValid === "memCode") {
      errorElem.textContent =
        "Special code doesn't match with the selected role!";
    } else if (errorValid === "nlog") {
      errorElem.textContent = "You have to login if you want to continue!";
    }
  }

  let form = document.createElement("form"); // make a form
  form.method = "POST"; //send data with post
  form.action = "Auth.php"; // to auth.php
  form.id = mode === "login" ? "login" : "signup"; // put an id in form

  div.append(form);

  let hiddenMode = document.createElement("input");
  hiddenMode.type = "hidden";
  hiddenMode.name = "mode";
  hiddenMode.value = mode; // login or signup
  form.appendChild(hiddenMode);

  if (mode === "signup") {
    //if mode === sign up make more elements in form for the username, password, role, spec code
    let user = document.createElement("input"); //we make an input for userName
    user.type = "text";
    user.placeholder = "Username";
    user.name = "username";
    user.required = true;

    form.append(user); //we put the first element in the form (not in dom yet)
  }

  let email = document.createElement("input");
  email.type = "email";
  email.placeholder = "Email";
  email.name = "email";
  email.required = true;
  form.appendChild(email);

  let password = document.createElement("input");
  password.type = "password";
  password.placeholder = "Password";
  password.name = "password";
  password.required = true;

  form.appendChild(password);

  if (mode === "signup") {
    //if mode === sign up make more element in form for the specidic code
    let member = document.createElement("select");
    member.name = "member";
    member.id = "sel";
    member.required = true;

    ["Professor", "Student"].forEach(function (role) {
      let option = document.createElement("option");
      option.value = role === "Professor" ? 0 : 1;
      option.text = role;
      member.appendChild(option);
    });
    form.appendChild(member);

    let spCode = document.createElement("input");
    spCode.type = "text";
    spCode.placeholder = "Special registration code";
    spCode.name = "spcode";
    spCode.required = true;
    form.appendChild(spCode);
  }

  // VALIFATE THE INPUTS //
  form.addEventListener("submit", function (e) {
    if (mode === "signup") {
      const password = form
        .querySelector("input[name='password']")
        .value.trim(); // check password and trim it
      if (password.length < 6) {
        errorElem.textContent = "Password must be at least 6 characters!";
        errorElem.style.display = "block";
        e.preventDefault();
        return;
      }
    }
  });

  let btn = document.createElement("button");
  btn.type = "submit";
  btn.id = "btn";
  btn.textContent = mode === "login" ? "Login" : "Sign Up";
  form.appendChild(btn);

  //now we put all the element together in the fragment and then in the dom
  fragment.appendChild(div);
  container.appendChild(fragment);
}

const mDiv = document.querySelector("#authCont");
mDiv.firstElementChild.classList.add("formDiv");
