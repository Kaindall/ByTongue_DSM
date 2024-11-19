<?php

class ChatService {
    private ChatRepository $chatRepository;
    private ?IaService $iaService;

    public function __construct(ChatRepository $chatRepository, ?IaService $iaService) {
        $this->chatRepository = $chatRepository;
        $this->iaService = $iaService;
    }

    public function findChat($id) {
        $chatContent = $this->chatRepository->findById($id);
        return json_encode($chatContent, JSON_PRETTY_PRINT);
    }
    public function postMessage($id, $message, $params) {
        if ($message === null || empty($message)) {throw new EmptyBodyException;}
        $msgArr = json_decode($message, true);
        if (!isset($msgArr["content"])) {throw new InvalidBodyException;}

        if(!$id) {
            //echo 'ID da conversa não informado, criando uma nova' . PHP_EOL;
            $chatValidator = new ChatValidator();
            $validatedParams = $chatValidator->validateAll($params);
            if (!isset($msgArr['role'])) {$msgArr = $this->transformMessage('user', $msgArr['content']);}

            $chat = new Chat(
                null, 
                'gemini-1.5-pro', 
                [$msgArr],
                $validatedParams['origin'],
                $validatedParams['target'],
                $validatedParams['level']->value
            );
            $responseContent = json_decode($this->iaService->retrieveResult($chat), true);
            $responseContent['id'] = uniqid('msg-');
            $chat->setContent($responseContent);
            $this->chatRepository->create($chat);
            $response = [
                'id' => $chat->getId(),
                'content' => end($responseContent['parts'])['text']
            ];
            return json_encode($response, JSON_PRETTY_PRINT);
        }
        
        //echo "Id reconhecido na chamada: $id" . PHP_EOL;
        $chat = $this->chatRepository->findById($id);
        if (!isset($msgArr['role'])) {$msgArr = $this->transformMessage('user', $msgArr['content']);}
        $newContent = [];
        $chat->setContent($msgArr);
        $response = json_decode($this->iaService->retrieveResult($chat), true);
        $response['id'] = uniqid('msg-');
        $chat->setContent($response);
        $newContent[] = $msgArr;
        $newContent[] = $response;
        $this->chatRepository->update($chat, $newContent);
        return json_encode([
            'id' => $response['id'],
            'content' => $response['parts'][0]['text']
        ], JSON_PRETTY_PRINT);
    }
    public function deleteMessage(string $chatId, string $msgId): bool {
        return $this->chatRepository->deleteMessage($chatId, $msgId);
    }
    public function deleteChat($id): bool {
        return $this->chatRepository->delete($id);
    }

    private function transformMessage(string $owner, string $msg): array {
        $formattedMessage = [
            'id' => uniqid('msg-'),
            'parts' => [
                ['text' => $msg]
            ],
            'role' => $owner
            ];
        return $formattedMessage;
    }
}