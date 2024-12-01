<?php
require_once 'src/main/domain/service/ia/Quizzeable.php';

class GeminiService implements IaService {
    private string $key;

    public function __construct() {
        $this->key = getenv("GEMINI_KEY");
    }

    public function retrieveResult(Chat $chat) {
        $model = $chat->getModel();
        $iaClient = new GeminiClient("https://generativelanguage.googleapis.com/v1beta/models/$model:generateContent?key=$this->key");
        return json_encode($iaClient->commitMessage($chat->getContentsWithoutId(), $chat->getInstructions()), JSON_PRETTY_PRINT);
    }

    public function retrieveQuiz(array $params) {
        $client = new GeminiClient("https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=$this->key");
        if (!($client instanceof Quizzeable)) {throw new ObjectNotQuizzeableException;}

        $quizValidator = new QuizValidator();
        $args = $quizValidator->validateAll($params);
        return $client->getExam($args);
    }

}