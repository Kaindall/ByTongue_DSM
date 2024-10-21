<?php

class ChatRepository {
    public function findById($id)    {
        $file = 'src/resources/db/' . "$id.json";
        if (!file_exists($file)) {throw new ChatNotFound();} 
    
        $data = json_decode(file_get_contents($file), true);
        $chat = new Chat($id, $data['model'], $data['systemInstruction'], $data['contents']);
        return $chat; 
    }
    public function findAllByUserId($userId) {}
    public function create(array $message): Chat {
        $chat = new Chat(null, null, null, null);
        
        //echo 'Checando a instância da conversa' . PHP_EOL;
        if (!($chat instanceof Chat)) {throw new InvalidChatObject();}

        $id = $this->tempRandNums(0, 99);
        $file = 'src/resources/db/' . $id . '.json';
            
        if (!file_exists($file)) {
            //echo 'Persistindo a conversa como '. $file . PHP_EOL;
            $chat->setId($id);
            $chat->setModel('gemini-pro');
            $chat->setSystemInstruction([]);
            $chat->setContent(json_decode(json_encode($message, JSON_PRETTY_PRINT), true));
            $dataToPersist = [
                'model' => $chat->getModel(),
                'systemInstruction' => $chat->getSystemInstruction(),
                'contents' => $chat->getContents()
            ];
            file_put_contents($file, json_encode($dataToPersist), JSON_PRETTY_PRINT);
            //echo 'Conversa persistida com sucesso!' . PHP_EOL;

            return $chat;
        } else {throw new ChatAlreadyExist();}
    }
    public function update(Chat $chat, array $message) {
        //echo 'Atualizando conversa existente' . PHP_EOL;
        $chat->setContent($message);
        $file = 'src/resources/db/' . $chat->getId() . '.json';
        if (!file_exists($file)) {throw new ChatNotFound($chat->getId());} 
       //echo 'Conversa encontrada' . PHP_EOL;

        $fileData = json_decode(file_get_contents($file), true);
        $fileData['contents'][] = $message;
        file_put_contents($file, json_encode($fileData), JSON_PRETTY_PRINT);
    }
    public function delete($id){}

    private function tempRandNums($min, $max) {
        $numeros = [];
        
        for ($i = 0; $i < 8; $i++) {
            $numeros[] = rand($min, $max);
        }
        return implode('', $numeros);
    }

}