document.querySelector("form").addEventListener("submit", async function (e) {
  e.preventDefault();

  const name = document.querySelector("#name").value;
  const email = document.querySelector("#email").value;
  const password = document.querySelector("#password").value;
  const confirmPassword = document.querySelector("#confirm-password").value;

  if (password !== confirmPassword) {
    alert("As senhas não coincidem!");
    return;
  }

  try {
    const response = await fetch("/users", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        name,
        email,
        password,
      }),
    });

    console.log(response, "responseSig")

    if (!response.ok) {
      const errorData = await response.json();
      alert(`Erro: ${errorData.message}`);
    } else {
      alert("Conta criada com sucesso!");
      window.location.assign("/login");
    }
  } catch (error) {
    alert("Erro ao criar conta. Tente novamente mais tarde.");
    console.error(error);
  }
});
