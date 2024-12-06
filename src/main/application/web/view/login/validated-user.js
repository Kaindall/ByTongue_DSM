document.addEventListener("DOMContentLoaded", async () => {
  const currentPath = window.location.pathname;
  const loginPagePath = "/login";
  const quizPagePath = "/quiz";

  const userToken = sessionStorage.getItem("authToken");

  try {
      if (!userToken) {
          if (currentPath !== loginPagePath) {
              window.location.href = loginPagePath;
          }
          return;
      }


      const response = await fetch("/auth", {
          method: "GET",
          headers: {
              "Content-Type": "application/json",
              "Authorization": `Bearer ${userToken}`
          }
      });

      if (!response.ok) {
          if (currentPath !== loginPagePath) {
              window.location.href = loginPagePath;
          }
          return;
      }

      const data = await response.json();


      if (!data.user_id) {
          if (currentPath !== loginPagePath) {
              window.location.href = loginPagePath;
          }
          return;
      }

      if (currentPath === loginPagePath) {
          window.location.href = quizPagePath;
      }
  } catch (error) {
      console.error("Erro ao verificar a sessão:", error);

      if (currentPath !== loginPagePath) {
          window.location.href = loginPagePath;
      }
  }
});
