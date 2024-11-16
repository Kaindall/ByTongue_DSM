<?php

class ChatService {
    private ChatRepository $chatRepository;
    private ?IaService $iaService;

    public function __construct(ChatRepository $chatRepository, ?IaService $iaService) {
        $this->chatRepository = $chatRepository;
        $this->iaService = $iaService;
    }

    public function findChat($id) {
        $chatContent = $this->chatRepository->findById($id)->getContents();
        return json_encode($chatContent, JSON_PRETTY_PRINT);
    }
    public function postMessage($id, $message, $params) {
        if ($message === null || empty($message)) {throw new EmptyBodyException;}
        $msgArr = json_decode($message, true);
        if (!isset($msgArr["content"])) {throw new InvalidBodyException();}

        if(!$id) {
            //echo 'ID da conversa não informado, criando uma nova' . PHP_EOL;
            $chatValidator = new ChatValidator();
            $validatedParams = $chatValidator->validateAll($params);
            if (!isset($msgArr['role'])) {$msgArr = $this->transformMessage('user', $msgArr['content']);}
            $chat = $this->chatRepository->create($msgArr, $validatedParams);
            $response = $this->iaService->retrieveResult($chat);
            $responseContent = json_decode($response, true);    
            $this->chatRepository->update($chat, $responseContent);
            $response = [
                'id' => $chat->getId(),
                'content' => end($responseContent['parts'])['text']
            ];
            return json_encode($response, JSON_PRETTY_PRINT);
        }
        
        //echo "Id reconhecido na chamada: $id" . PHP_EOL;
        $chat = $this->chatRepository->findById($id);
        if (!isset($msgArr['role'])) {$msgArr = $this->transformMessage('user', $msgArr['content']);}
        $chat = $this->chatRepository->update($chat, $msgArr);

        $response = $this->iaService->retrieveResult($chat);
        $responseContent = json_decode($response, true);    
        $this->chatRepository->update($chat, $responseContent);
        return json_encode(['content' => $responseContent['parts'][0]['text']], JSON_PRETTY_PRINT);
    }

    public function deleteMessage() {
        
    }

    public function deleteChat($id): bool {
        return $this->chatRepository->delete($id);
    }

    private function transformMessage(string $owner, string $msg): array {
        $formattedMessage = [
            'parts' => [
                ['text' => $msg]
            ],
            'role' => $owner
            ];
        return $formattedMessage;
    }
}