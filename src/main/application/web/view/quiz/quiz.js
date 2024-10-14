const info_box = document.querySelector(".info_box");
const btn_continue = document.querySelector(".continue");
const quiz_box = document.querySelector(".quiz_box");
const next_btn = document.querySelector(".next_btn");
const result_box = document.querySelector(".result_box");

const option_list = document.querySelector(".option_list");
const time_count = document.querySelector(".time_sec");

let question_count = 0;
let tickIcon = '<div class="icon tick"><i class="fas fa-check"></i></div>';
let crossIcon = '<div class="icon cross"><i class="fas fa-times"></i></div>';
let counter;
let timeValue = 15;
let userScore = 0;

btn_continue.onclick = () => {
   info_box.classList.add('desactive');
   quiz_box.classList.remove('desactive');
   showQuestions(question_count)
   startTimer(15)
}

next_btn.onclick = () => {
   if(question_count < questions.length - 1){
      next_btn.style.display = "none";
      clearInterval(counter)
      startTimer(timeValue)
      question_count++;
      showQuestions(question_count);
   } else {
      clearInterval(counter)
      showResultBox();
   }
}

function showQuestions (index) {
   const question_text = document.querySelector(".que_text");
   const questions_counter = document.querySelector(".total_que");
   let question_tag = `<span>${questions[index].number}. ${questions[index].question}</span>`;
   let option_tag = `<div class="option"><span>${questions[index].alternatives[0]}</span></div>`
                  + `<div class="option"><span>${questions[index].alternatives[1]}</span></div>`
                  + `<div class="option"><span>${questions[index].alternatives[2]}</span></div>`
                  + `<div class="option"><span>${questions[index].alternatives[3]}</span></div>`;

   let totalQuestionsTag = `<span><p>${questions[index].number}</p>of<p>${questions.length}</p>Questions</span>`  

   question_text.innerHTML = question_tag;
   option_list.innerHTML = option_tag;
   questions_counter.innerHTML = totalQuestionsTag;

   const option = option_list.querySelectorAll(".option");
   for(let i = 0; i < option.length; i++) {
      option[i].setAttribute("onclick", "optionSelected(this)");
   }
   
}

function showResultBox() {
   quiz_box.classList.add('desactive');
   result_box.classList.remove('desactive');
   const scoreText = document.querySelector(".score_text");

   if(userScore > 7) {
      let scoreTag = `<span>Woooooooow, You got <p>${userScore}</p> out of <p>${questions.length}!!!</p></span>`;
      scoreText.innerHTML = scoreTag;
   } else if(userScore > 4) {
      let scoreTag = `<span>Nice, You got <p>${userScore}</p> out of <p>${questions.length}!</p></span>`;
      scoreText.innerHTML = scoreTag;
   } else {
      let scoreTag = `<span>Sorry, You got only <p>${userScore}</p> out of <p>${questions.length}!</p></span>`;
      scoreText.innerHTML = scoreTag;
   }

}

function optionSelected(answer) {
   clearInterval(counter);
   let userAnswer = answer.textContent;
   let correctAnswer = questions[question_count].answer;
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

         let correctAnswer = questions[question_count].answer;
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