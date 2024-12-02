document.querySelector("form").addEventListener("submit", async function (e) {
  e.preventDefault();

  const email = document.querySelector("#email").value.trim();
  const password = document.querySelector("#password").value.trim();

  if (!email || !password) {
    alert("Por favor, preencha todos os campos.");
    return;
  }

  const response = await fetch("http://localhost:8000/auth", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({ email, password })
  });

  if (response.status === 200) {
    const userResponse = await fetch("http://localhost:8000/auth", {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        "Authorization": `Bearer ${response.status}`
      }
    });

    if (userResponse.ok) {
      const userData = await userResponse.json();
      sessionStorage.setItem("authToken", userData.user_id);
      window.location.assign("/quiz");
    } else {
      alert("Erro ao obter os dados do usuário.");
    }

  } else if (response.status === 401) {
    alert("Login ou senha incorretos.");
  }
});
