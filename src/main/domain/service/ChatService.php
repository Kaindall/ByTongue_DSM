<?php

class ChatService {
    private ChatRepository $chatRepository;
    private ?IaService $iaService;

    public function __construct(ChatRepository $chatRepository, ?IaService $iaService) {
        $this->chatRepository = $chatRepository;
        $this->iaService = $iaService;
    }

    public function findChat($chatId) {
        $chatContent = json_decode($this->chatRepository->findById($chatId), true)['contents'];
        return json_encode($chatContent, JSON_PRETTY_PRINT);
    }
    //TODO: criar chat se não existir ou apensar conteúdo ao fim. Fazer isso usando ChatEntity
    //TODO: incluir ChatEntity nas operações
    public function postMessage($id, $message) {
        if ($message === null || empty($message)) {throw new EmptyBodyException();}
        $msgArr = json_decode($message, true);
        if (!isset($msgArr["content"])) {throw new InvalidBodyException();}

        if(!$id) {
            //echo 'ID não encontrado' . PHP_EOL;
            if (!isset($msgArr['role'])) {$msgArr = $this->transformMessage('user', $msgArr['content']);}
            $chat = $this->chatRepository->create($msgArr);
            $response = $this->iaService->retrieveResult($chat->getContents());
            $this->chatRepository->update($chat, json_decode($response, true));
            return $response;
        }
        
        return;
        /* $response = $this->iaService->retrieveResult($message);
        return $response; */
    }

    public function deleteMessage() {}

    public function deleteChat() {}

    private function transformMessage(string $owner, string $msg) {
        $formattedMessage = [
            'parts' => [
                ['text' => $msg]
            ],
            'role' => $owner
            ];
        return $formattedMessage;
    }
}