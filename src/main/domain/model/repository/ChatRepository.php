<?php

class ChatRepository {
    public function findById($id) {
        $file = 'src/resources/db/' . "$id.json";
        
        if (!file_exists($file)) {throw new ChatNotFound();} 
        
        $data = json_decode(file_get_contents($file), true);

        return json_encode($data); 
    }
    public function findAllByUserId($userId) {}
    public function create($chat): Chat {}
    public function update($id, $data) {}
    public function delete($id){}
}