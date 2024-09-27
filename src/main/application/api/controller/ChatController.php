<?php

class ChatController {
    #[HttpReceiver(uri: "/", method: "GET")]
    public function send() {
        return "Funcionou";
    }
}