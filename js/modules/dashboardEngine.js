import { createElement } from "../global.js";

//-----------------------------------//
//ENGINE DASHBOARD (COMMON FUNCTIONS)
//-----------------------------------//

//-------------------------------//
// Fetch Data
//-------------------------------//
export async function fetchData(url) {
  //method to fetch data and not repeat the same code
  const response = await fetch(url);
  return await response.json();
}

//-------------------------------//
//Render List
//-------------------------------//
export function renderList(containerDiv, rows, config = {}) {
  //method to render lists
  if (!Array.isArray(rows)) {
    //check for right data from db
    console.error("renderList waits rows to be an array", rows);
    rows = []; // for not break;
    return;
  }

  const oldUL = containerDiv.querySelector(".dashboardUlContentInside");
  if (oldUL) oldUL.remove(); // Remove previous UL if exists

  let ulContentInside = createElement("ul", "", "dashboardUlContentInside"); //make a ul

  rows.forEach((row) => {
    // for each row make and li
    let li = createElement("li", "", "dashboardListItem");

    let itemId = row.id ?? row.course_id ?? row.topic_id; // take the first value(id) which isnt null and keep it as item id
    li.dataset.itemId = itemId; // and set it to dataset to edit it if we want, also with that we will fetch sth

    for (let key in row) {
      //for all keys
      if (key === "id" || key.endsWith("_id")) continue; //except id
      let span = createElement("span", row[key]);
      li.appendChild(span);
    }

    if (config.onClick) {
      //if there is (onclick in) config
      li.style.cursor = "pointer"; // make the li clickable
      li.addEventListener("click", () => {
        config.onClick(itemId, row); // on click hit the api which took from outside
      });
    }

    //if config has edit handler
    if (config.editHandler) {
      let editBtn = createElement(
        "button",
        config.labels?.edit || "Edit", //if has name, put this name as name , else put edit
        "edit-btn", //className
        (e) => {
          e.stopPropagation(); //dont do the normal li click (fetch)
          config.editHandler(itemId, li, row); // do what caller says
        }
      );
      console.log("editttTTTTTT0");
      li.appendChild(editBtn); //put the edit btn in li
    }

    //same for delete handler
    if (config && config.deleteHandler) {
      let deleteBtn = createElement(
        "button",
        config.labels?.delete || "Delete",
        "delete-btn",
        (e) => {
          e.stopPropagation();
          config.deleteHandler(itemId, li, row);
        }
      );
      console.log("DELETEBUTTON");
      li.appendChild(deleteBtn);
    }
    ulContentInside.appendChild(li);
  });

  containerDiv.appendChild(ulContentInside);
}

//-------------------------------//
//Refresh Courses
//-------------------------------//
export async function refreshList(apiUrl, containerDiv, config = {}) {
  //method to refresh the list
  let Data = await fetchData(apiUrl);
  renderList(containerDiv, Data.data, config);
}

//-------------------------------//
//Update Dashboard Content
//-------------------------------//
export function updateDashboardContent(
  divWithContentsOfChoice,
  headerText,
  btnText,
  userRole,
  apiUrl,
  config = {},
  listConfig = {}
) {
  let fragmentDivContent = document.createDocumentFragment(); // make a fragment to hold temporary the contents
  divWithContentsOfChoice.innerHTML = "";

  let divContent = createElement("div", "", "dashboardContent"); // create a div to hold the content
  fragmentDivContent.append(divContent);

  let headerRowContent = createElement("div", "", "dashboardHeaderRow"); // create a header row of content
  let headerContent = createElement("h3", headerText); // create an h3 for the content title
  headerContent.style.textDecoration = "underline";
  headerRowContent.appendChild(headerContent);

  if (userRole !== "student") {
    // check if user is teacher or student
    let btnAddContent = createElement("Button", btnText, "", async () => {
      //create a button to add new content f.e. new course, assignment etc.
      if (document.getElementById("newContentInput")) return; // prevent multiple input fields

      btnAddContent.style.display = "none";
      let inputNewContent = createElement("input");
      inputNewContent.id = "newContentInput";
      inputNewContent.placeholder = btnText;

      let btnSubmitForNewContent = createElement(
        "button",
        "Submit",
        "",
        async () => {
          // create a button to submit the new content
          let value = inputNewContent.value.trim();
          if (!value) {
            alert("Please enter a value");
            return;
          }
          if (config.submitHandler) await config.submitHandler(value);

          inputNewContent.remove();
          btnSubmitForNewContent.remove();
          btnAddContent.style.display = "inline-block";

          refreshList(apiUrl, divContent, listConfig);
        }
      );

      headerRowContent.appendChild(inputNewContent);
      headerRowContent.appendChild(btnSubmitForNewContent);
    });

    headerRowContent.appendChild(btnAddContent);
  }

  fragmentDivContent.appendChild(headerRowContent);
  fragmentDivContent.appendChild(divContent);

  divWithContentsOfChoice.appendChild(fragmentDivContent);
  //   refreshList(apiUrl, divContent, listConfig);
}
