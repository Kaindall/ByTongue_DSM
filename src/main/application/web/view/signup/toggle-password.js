// toggle-password.js
const togglePassword = document.querySelector("#toggle-password");
const password = document.querySelector("#password");
const toggleConfirmPassword = document.querySelector("#toggle-confirm-password");
const confirmPassword = document.querySelector("#confirm-password");

togglePassword.addEventListener("click", function () {
  const type = password.getAttribute("type") === "password" ? "text" : "password";
  password.setAttribute("type", type);
  this.textContent = type === "password" ? "Mostrar" : "Ocultar";
});

toggleConfirmPassword.addEventListener("click", function () {
  const type = confirmPassword.getAttribute("type") === "password" ? "text" : "password";
  confirmPassword.setAttribute("type", type);
  this.textContent = type === "password" ? "Mostrar" : "Ocultar";
});
