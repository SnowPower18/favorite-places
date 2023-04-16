const form = document.querySelector("#form");
const errorMessageElem = document.querySelector("#error_message");
const usernameInput = document.querySelector("#username_input");
const passwordInput = document.querySelector("#password_input");
const checkPasswordInput = document.querySelector("#confirm_password_input");
const submitButton = document.querySelector("#submit_button");

async function register(e) {
  e.preventDefault();

  //get values from the form
  const data = new FormData(form);

  //prevent sign up if passwords do not match
  if (passwordInput.value !== checkPasswordInput.value) {
    return;
  }

  let res = await fetch("./register.php", {
    method: "POST",
    body: data
  });

  const status = res.status;
  res = await res.json();

  //if no credentials or already existing user
  if (status == 400 || status == 409 || status == 500) {
    errorMessageElem.classList.remove("hidden");
    errorMessageElem.textContent = res.error;
  } else {
    window.location.href = "../index.php";
  }
}

function formValidation() {
  const filledOut =
    usernameInput.value !== "" &&
    passwordInput.value !== "" &&
    checkPasswordInput.value !== "";
  const passwordsMatch =
    passwordInput.value === checkPasswordInput.value && passwordInput !== "";

  if (filledOut && passwordsMatch) {
    submitButton.removeAttribute("disabled");
  } else {
    submitButton.setAttribute("disabled", "");
  }

  if (
    passwordInput.value !== "" &&
    checkPasswordInput.value !== "" &&
    !passwordsMatch
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

form.addEventListener("submit", register);
usernameInput.addEventListener("input", formValidation);
passwordInput.addEventListener("input", formValidation);
checkPasswordInput.addEventListener("input", formValidation);
