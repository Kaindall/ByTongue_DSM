<?php

class ChatRepository {
    public function findById($id)    {
        //echo 'Procurando pela conversa' . PHP_EOL;
        $file = 'src/resources/db/' . "$id.json";
        if (!file_exists($file)) {throw new ChatNotFound($id);} 
        
        //echo 'Conversa encontrada!' . PHP_EOL;
        $data = json_decode(file_get_contents($file), true);
        $chat = new Chat(
            $id, 
            $data['model'], 
            $data['contents'],
            $data['from'],
            $data['target'],
            $data['level']
        );
        return $chat; 
    }
    public function create(array $message, $args): Chat {
        $chat = new Chat(null, null, null, $args['origin'], $args['target'], $args['level']->value);
        //echo 'Checando a instância da conversa' . PHP_EOL;
        if (!($chat instanceof Chat)) {throw new InvalidChatObject();}

        $id = $this->tempRandNums(0, 99);
        $file = 'src/resources/db/' . $id . '.json';
            
        if (!file_exists($file)) {
            //echo 'Persistindo a conversa como '. $file . PHP_EOL;
            $chat->setId($id);
            $chat->setModel('gemini-1.5-pro');
            $chat->setContent(json_decode(json_encode($message), true));
            file_put_contents($file,json_encode($chat->toPersist(), JSON_UNESCAPED_UNICODE));
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
        file_put_contents($file, json_encode($fileData,  JSON_UNESCAPED_UNICODE));
        
        return $chat;
    }
    public function delete($id): bool{
        $file = 'src/resources/db/' . $id . '.json';
        if (!file_exists($file)) {throw new ChatNotFound($id);} 
        if (unlink($file)) {
            return true;
        } else {
            return false;
        }

    }
    private function tempRandNums($min, $max) {
        $numeros = [];
        
        for ($i = 0; $i < 8; $i++) {
            $numeros[] = rand($min, $max);
        }
        return implode('', $numeros);
    }

}