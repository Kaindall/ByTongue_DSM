<?php 

class QuizValidator {
    public function validateAll(array $values) {
        $args = [
            "quantity" => $this->validateQuantity($values['quantity'] ?? 5),
            "level" => CEFRLevel::fromInt($values['level'] ?? 2),
            "origin" => $this->validateLanguage($values['from'] ?? 'pt-BR', 'origin'),
            "target" => $this->validateLanguage($values['to'] ?? 'en-US', 'target')
        ];

        return $args;
    }

    public function validateQuantity($quantity) {
        if (!is_numeric($quantity)) {throw new InvalidQuantityException();}
        return $quantity;
    }

    public function validateLanguage($language, $type) {
        $tempLang = Locale::getDisplayLanguage($language);
        if ($tempLang === $language) {
            if (str_contains($type, 'origin')) throw new OriginLanguageException();
            if (str_contains($type, 'target')) throw new TargetLanguageException();
        }
        return $tempLang;
    }
}