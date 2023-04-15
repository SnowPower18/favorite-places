const form = document.querySelector("#form");
const errorMessageElem = document.querySelector("#error_message");
const usernameInput = document.querySelector("#username_input");
const passwordInput = document.querySelector("#password_input");
const submitButton = document.querySelector("#submit_button");

async function login(e) {
  e.preventDefault();

  //get values from the form
  const data = new FormData(form);

  let res = await fetch("./auth.php", {
    method: "POST",
    body: data
  });

  const status = res.status;
  res = await res.json();

  //if no credentials or invalid
  if (status == 401 || status == 500) {
    errorMessageElem.classList.remove("hidden");
    errorMessageElem.textContent = res.error;
  }
}

function formValidation() {
  if (usernameInput.value === "" || passwordInput.value === "") {
    submitButton.setAttribute("disabled", "");
  } else {
    submitButton.removeAttribute("disabled");
  }
}

function resetLoginError() {
  errorMessageElem.classList.add("hidden");
  errorMessageElem.textContent = "";
}

form.addEventListener("submit", login);
usernameInput.addEventListener("input", resetLoginError);
passwordInput.addEventListener("input", resetLoginError);
usernameInput.addEventListener("input", formValidation);
passwordInput.addEventListener("input", formValidation);
