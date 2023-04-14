let form = document.getElementById("form");

async function login(e) {
  e.preventDefault();

  let res = await fetch("./auth.php");
}

form.addEventListener("submit", login);
