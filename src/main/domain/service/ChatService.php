<?php

class ChatService {

    public function __construct(
        private ChatRepository $chatRepository, 
        private ?IaService $iaService,
        private ?UserChatsRepository $userChatsRepository) {
    }

    public function findChat($id) {
        $chatContent = $this->chatRepository->findById($id);
        //LOGGER::debug("Objeto recebido pelo Service: " . PHP_EOL . json_encode($chatContent));
        return json_encode($chatContent, JSON_PRETTY_PRINT);
    }
    public function postMessage($chatId, $message, $params) {
        if ($message === null || empty($message)) {throw new EmptyBodyException;}
        $msgArr = json_decode($message, true);
        if (!isset($msgArr["content"])) {throw new InvalidBodyException;}
        if (!isset($msgArr['role'])) {$msgArr = $this->transformMessage('user', $msgArr['content']);}

        if(!$chatId) return $this->createChat($params, $msgArr);
        return $this->updateChat($chatId, $msgArr);
    }
    public function deleteMessage(string $chatId, string $msgId): bool {
        $isDeleted = $this->chatRepository->deleteMessage($chatId, $msgId);
        if ($isDeleted && $_SESSION && isset($_SESSION['user'])) $this->userChatsRepository->refreshLink($chatId);
        return $isDeleted;
    }
    public function deleteChat($id): bool {
        if ($this->chatRepository->delete($id)) return $this->userChatsRepository->deleteLink($id);
        return false;
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
    private function createChat($params, array $message) {
        $chatValidator = new ChatValidator();
        $validatedParams = $chatValidator->validateAll($params);
        $chat = new Chat(
            null, 
            'gemini-2.0-flash', 
            [$message],
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
        if ($_SESSION && isset($_SESSION['user'])) $this->userChatsRepository->createLink($_SESSION['user']->getId(), $chat->getId());
        return json_encode($response, JSON_PRETTY_PRINT);
    }
    private function updateChat(string $chatId, array $message) {
        $chat = $this->chatRepository->findById($chatId);
        $newContent = [];
        $chat->setContent($message);
        $response = json_decode($this->iaService->retrieveResult($chat), true);
        $response['id'] = uniqid('msg-');
        $chat->setContent($response);
        $newContent[] = $message;
        $newContent[] = $response;
        $this->chatRepository->update($chat, $newContent);
        if ($_SESSION && isset($_SESSION['user'])) $this->userChatsRepository->refreshLink($chat->getId());
        return json_encode([
            'id' => $response['id'],
            'content' => $response['parts'][0]['text']
        ], JSON_PRETTY_PRINT);
    }
}
