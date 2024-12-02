document.addEventListener("DOMContentLoaded", () => {
  const authToken = sessionStorage.getItem("authToken");
  const loginNavItem = document.querySelector('.nav-item a[href="/login"]');

  if (authToken) {
    loginNavItem.textContent = "Logout";
    loginNavItem.href = "#";

    loginNavItem.addEventListener("click", async (event) => {
      event.preventDefault();

      try {
        const response = await fetch("http://localhost:8000/auth", {
          method: "DELETE",
          headers: {
            "Content-Type": "application/json",
            "Authorization": `Bearer ${authToken}`
          }
        });

        if (response.ok) {
          sessionStorage.removeItem("authToken");
          alert("Você foi deslogado com sucesso.");
          window.location.href = "/";
        } 
      } catch (error) {
        console.error("Erro ao conectar-se à API:", error);
        alert("Ocorreu um erro ao encerrar a sessão. Verifique sua conexão.");
      }
    });
  }
});
