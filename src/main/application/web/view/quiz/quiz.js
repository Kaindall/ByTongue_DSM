const info_box = document.querySelector(".info_box");
const info_lang = document.querySelector(".select_lang")
const quiz_box = document.querySelector(".quiz_box");
const result_box = document.querySelector(".result_box");
const btn_continueLang = document.querySelector(".continue_lang");
const btn_continue = document.querySelector(".continue");
const next_btn = document.querySelector(".next_btn");
const option_list = document.querySelector(".option_list");
const time_count = document.querySelector(".time_sec");

let question_count = 0;
let tickIcon = '<div class="icon tick"><i class="fas fa-check"></i></div>';
let crossIcon = '<div class="icon cross"><i class="fas fa-times"></i></div>';
let counter;
let timeValue = 15;
let userScore = 0;
let question;
let langName;

btn_continueLang.onclick = () => {
   info_box.classList.add('desactive');
   info_lang.classList.remove('desactive');
}

const langButtons = document.querySelectorAll('.lang_button');
langButtons.forEach(button => {
    button.addEventListener('click', function() {
      langButtons.forEach(btn => btn.classList.remove('selected'));

      this.classList.add('selected');

      langName = this.nextElementSibling.textContent;
    });
});

btn_continue.onclick = async () => {
   if(!langName){
      return;
   }
   info_lang.classList.add('desactive');
   showLoading();
   await datafetch(langName);
   hideLoading();
   quiz_box.classList.remove('desactive');
   startTimer(15);
}

next_btn.onclick = () => {
   if(question_count < question.length - 1){
      next_btn.style.display = "none";
      clearInterval(counter)
      startTimer(timeValue)
      question_count++;
      showQuestions(question_count, question);
   } else {
      clearInterval(counter)
      showResultBox();
   }
}

function showLoading() {
   const loadingElement = document.querySelector('#loading');
   loadingElement.style.display = 'flex';
}

function hideLoading() {
   const loadingElement = document.querySelector('#loading');
   loadingElement.style.display = 'none';
}

async function datafetch (langName) {
   switch (langName) {
      case "Português":
         langName = "pt-BR"
         break;
      case "Inglês":
         langName = "en-US"
         break;
      case "Espanhol":
         langName = "es-ES"
         break;
      case "Francês":
         langName = "fr-FR"
         break;
      case "Italiano":
         langName = "it-IT"
         break;
      case "Alemão":
         langName = "de-DE"
         break;
      default:
         break;
   }

   console.log(langName)
   const url = `http://localhost:8000/ias/quiz?from=pt-BR&to=${langName}`
   try {
      const response = await fetch(url);
      const data = await response.json();
      console.log(data)
      question = data
      showQuestions(question_count, question)
   } catch (error) {
      console.log(error)
   }
}

function showQuestions (index, data) {
   const question_text = document.querySelector(".que_text");
   const questions_counter = document.querySelector(".total_que");
   let question_tag = `<span>${index + 1}. ${data[index].question}</span>`;
   let option_tag = `<div class="option"><span>${data[index].options[0]}</span></div>`
                  + `<div class="option"><span>${data[index].options[1]}</span></div>`
                  + `<div class="option"><span>${data[index].options[2]}</span></div>`
                  + `<div class="option"><span>${data[index].options[3]}</span></div>`;

   let totalQuestionsTag = `<span><p>${index + 1}</p>of<p>${data.length}</p>Questions</span>`  

   question_text.innerHTML = question_tag;
   option_list.innerHTML = option_tag;
   questions_counter.innerHTML = totalQuestionsTag;

   const option = option_list.querySelectorAll(".option");
   
   for(let i = 0; i < option.length; i++) {
      option[i].setAttribute("onclick", 'optionSelected(this)');
   }
   
}

function showResultBox() {
   quiz_box.classList.add('desactive');
   result_box.classList.remove('desactive');
   const scoreText = document.querySelector(".score_text");

   if(userScore > 4) {
      let scoreTag = `<span>Woooooooow, You got <p>${userScore}</p> out of <p>${question.length}!!!</p></span>`;
      scoreText.innerHTML = scoreTag;
   } else if(userScore > 2) {
      let scoreTag = `<span>Nice, You got <p>${userScore}</p> out of <p>${question.length}!</p></span>`;
      scoreText.innerHTML = scoreTag;
   } else {
      let scoreTag = `<span>Sorry, You got only <p>${userScore}</p> out of <p>${question.length}!</p></span>`;
      scoreText.innerHTML = scoreTag;
   }

}

function optionSelected(answer) {
   clearInterval(counter);
   let userAnswer = answer.textContent;
   const correctAnswerIndex = question[question_count].correct;
   let correctAnswer = question[question_count].options[correctAnswerIndex];
   let allOptions = option_list.children.length;
   if(userAnswer === correctAnswer) {
      userScore++;
      answer.classList.add("correct");
      answer.insertAdjacentHTML("beforeend", tickIcon)
   } else {
      answer.classList.add("incorrect");
      answer.insertAdjacentHTML("beforeend", crossIcon)
      
      for(let i = 0; i < allOptions; i++) {
         if(option_list.children[i].textContent === correctAnswer) {
            option_list.children[i].setAttribute("class", "option correct")
            option_list.children[i].insertAdjacentHTML("beforeend", tickIcon)
         }
      }
   }

   for(let i = 0; i < allOptions; i++) {
      option_list.children[i].classList.add("disabled")
   }

   next_btn.style.display = "block";
}

function startTimer(time) {
   counter = setInterval(timer, 1000);
   function timer() {
      time_count.textContent = time;
      time--;
      if(time < 9) {
         let addZero = time_count.textContent;
         time_count.textContent = "0" + addZero;
      }
      if(time < 0) {
         clearInterval(counter);
         time_count.textContent = "00";

         let correctAnswerIndex = question[question_count].correct;
         let correctAnswer = question[question_count].options[correctAnswerIndex];
         let allOptions = option_list.children.length;

         for(let i = 0; i < allOptions; i++) {
            if(option_list.children[i].textContent === correctAnswer) {
               option_list.children[i].setAttribute("class", "option correct")
               option_list.children[i].insertAdjacentHTML("beforeend", tickIcon)
            }
         }

         for(let i = 0; i < allOptions; i++) {
            option_list.children[i].classList.add("disabled")
         }
      
         next_btn.style.display = "block";
      }
   }
}