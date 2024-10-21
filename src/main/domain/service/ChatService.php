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
        if(!$id) {
            $this->chatRepository->createChat($message);
        }

        $response = $this->iaService->retrieveResult($message);
        return $response;
    }

    public function deleteMessage() {}

    public function deleteChat() {}
}