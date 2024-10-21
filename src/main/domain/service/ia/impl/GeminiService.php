<?php
require_once 'src\main\domain\service\ia\Quizzeable.php';

class GeminiService implements IaService {
    private string $key;

    public function __construct() {
        $this->key = getenv("GEMINI_KEY");
    }

    public function retrieveHistory() {
        
    }

    public function retrieveResult($message) {
        if ($message === null || empty($message)) {throw new EmptyBodyException();}

        $jsonMessage = json_decode($message, true);
        if (!isset($jsonMessage["content"])) {throw new InvalidBodyException();}
        $iaClient = new GeminiClient("https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=$this->key");
        return json_encode($iaClient->commitMessage($jsonMessage['content']), JSON_PRETTY_PRINT);
    }

    public function retrieveQuiz(array $params) {
        $client = new GeminiClient("https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=$this->key");
        if (!($client instanceof Quizzeable)) {throw new ObjectNotQuizzeable();}

        $quizValidator = new QuizValidator();
        $args = $quizValidator->validateAll($params);
        return $client->getExam($args);
    }

    public function createChat() {

    }
}