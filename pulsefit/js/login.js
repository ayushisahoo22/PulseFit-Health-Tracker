function login(event) {
  event.preventDefault();

  const username = document.getElementById("username").value;
  const password = document.getElementById("password").value;

  if (username === "" || password === "") {
    alert("Please fill all fields");
    return;
  }
  localStorage.setItem("loggedIn", "true");

  alert("Login successful!");

  window.location.href = "index.html";
}