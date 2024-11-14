const chatInput = document.querySelector(".chat-input textarea");
const sendChatBtn = document.querySelector(".chat-input span");
const chatbox = document.querySelector(".chatbox");
const chatbotToggler = document.querySelector(".chatbot-toggler");
const chatbotCloseBtn = document.querySelector(".close-btn");

let userMessage;
let id;
const inputInitHeight = chatInput.scrollHeight;

const createChatLi = (message, className) => {
  // Create a chat <li> element with passed message and className
  const chatLi = document.createElement("li");
  chatLi.classList.add("chat", className);
  let chatContent = className === "outgoing" ? `<p></p>` : `<p></p>`;
  chatLi.innerHTML = chatContent;
  chatLi.querySelector("p").textContent = message;
  return chatLi;
}

const generateResponse = (incomingChatLi) => {
  const API_URL = `http://localhost:8000/ias/chat/${id}`;
  const messageElement = incomingChatLi.querySelector("p");

  const requestBody = {
    content: userMessage // User message
  };

  // Define the properties and message for the API request
  const requestOptions = {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(requestBody),
  };

  (async () => {
    try {
      const response = await fetch(API_URL, requestOptions);
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }
      const data = await response.json();

      if (data && data.content) {
        messageElement.textContent = data.content;
      } else {
        messageElement.textContent = "Resposta inesperada da API.";
      }
    } catch (error) {
      console.log(error);
      messageElement.classList.add("error");
      messageElement.textContent = "Oops! Something went wrong. Please try again.";
    } finally {
      chatbox.scrollTo(0, chatbox.scrollHeight);
    }
  })();
}

const createResponseChat = (incomingChatLi) => {
  const level = 1;
  const from = "pt-BR";
  const to = "en-US";
  const API_URL = `http://localhost:8000/ias/chat`;
  const messageElement = incomingChatLi.querySelector("p");

  const requestBody = {
    level, // Nível do usuário
    from, // Idioma de origem
    to, // Idioma de destino
    content: userMessage // Mensagem do usuário
  };

  // Define the properties and message for the API request
  const requestOptions = {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(requestBody),
  };

  (async () => {
    try {
      const response = await fetch(API_URL, requestOptions);
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }
      const data = await response.json();
      id = data.id;

      if (data && data.content) {
        messageElement.textContent = data.content;
      } else {
        messageElement.textContent = "Resposta inesperada da API.";
      }
    } catch (error) {
      console.log(error);
      messageElement.classList.add("error");
      messageElement.textContent = "Oops! Something went wrong. Please try again.";
    } finally {
      chatbox.scrollTo(0, chatbox.scrollHeight);
    }
  })();
}


const handleChat = () => {
  userMessage = chatInput.value.trim();

  if (!userMessage) return;
  // Append the user's message to the chatbox
  chatInput.value = "";
  chatInput.style.height = `${inputInitHeight}px`;

  chatbox.appendChild(createChatLi(userMessage, "outgoing"));
  chatbox.scrollTo(0, chatbox.scrollHeight);

  setTimeout(() => {
    // Display "Thinking..." message while waiting for the response
    const incomingChatLi = createChatLi("...", "incoming");
    chatbox.appendChild(incomingChatLi);
    chatbox.scrollTo(0, chatbox.scrollHeight);
    if (!id) {
      createResponseChat(incomingChatLi);
    } else {
      generateResponse(incomingChatLi);
    }
  }, 600)
}

chatInput.addEventListener("input", () => {
  chatInput.style.height = `${inputInitHeight}px`;
  chatInput.style.height = `${chatInput.scrollHeight}px`;
})
chatInput.addEventListener("keydown", (e) => {
  if (e.key === "Enter" && !e.shiftKey && window.innerWidth > 600) {
    e.preventDefault();
    handleChat();
  }
})

sendChatBtn.addEventListener("click", handleChat);

document.addEventListener("DOMContentLoaded", function () {
  const p1 = "Bem vindo ao Bytongue chatbot!!"
  const p2 = "Envie uma mensagem para se conectar a um bot professor!!"
  const incomingChatLi = createChatLi(`${p1}`, "incoming");
  chatbox.appendChild(incomingChatLi);
  setTimeout(() => {
    const incomingChatLi = createChatLi(`${p2}`, "incoming");
    chatbox.appendChild(incomingChatLi);
  }, 2000);
});