const form = document.querySelector("#form");
const errorMessageElem = document.querySelector("#error_message");
const usernameInput = document.querySelector("#username_input");
const passwordInput = document.querySelector("#password_input");
const checkPasswordInput = document.querySelector("#confirm_password_input");
const submitButton = document.querySelector("#submit_button");

async function login(e) {
  e.preventDefault();

  //get values from the form
  const data = new FormData(form);

  //prevent sign up if passwords do not match
  if (passwordInput !== checkPasswordInput) {
    return;
  }

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
  const filledOut =
    usernameInput !== "" && passwordInput !== "" && checkPasswordInput !== "";
  const passwordsMatch =
    passwordInput.value === checkPasswordInput.value && passwordInput != "";

  if (!filledOut || !passwordsMatch) {
    submitButton.setAttribute("disabled", "");
  } else {
    submitButton.removeAttribute("disabled");
  }

  if (
    passwordInput.value !== "" &&
    checkPasswordInput.value !== "" &&
    passwordsMatch
  ) {
    showPasswordError();
  } else {
    hidePasswordError();
  }
}

function showPasswordError() {
  errorMessageElem.classList.remove("hidden");
  errorMessageElem.textContent = "Passwords do not match";
}

function hidePasswordError() {
  errorMessageElem.classList.add("hidden");
  errorMessageElem.textContent = "";
}

form.addEventListener("submit", login);
usernameInput.addEventListener("input", formValidation);
passwordInput.addEventListener("input", formValidation);
checkPasswordInput.addEventListener("input", formValidation);
