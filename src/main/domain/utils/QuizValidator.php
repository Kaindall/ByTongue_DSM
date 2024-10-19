<?php 

class QuizValidator {
    public function validateAll(array $values) {
        $args = [
            "quantity" => $this->validateQuantity($values['quantity'] ?? 5),
            "level" => CEFRLevel::fromInt($values['level'] ?? 2),
            "origin" => $this->validateOrigin($values['from'] ?? 'português'),
            "target" => $this->validateOrigin($values['target'] ?? 'inglês')
        ];

        return $args;
    }

    public function validateQuantity($quantity) {
        if (!is_numeric($quantity)) {throw new InvalidQuantityException();}
        return $quantity;
    }

    public function validateOrigin($origin) {
        $tempOrigin = Locale::getDisplayLanguage($origin);
        if ($tempOrigin === $origin) {throw new OriginLanguageException();}
        return $tempOrigin;
    }

    public function validateTarget($target) {
        $tempTarget = Locale::getDisplayLanguage($target);
        if ($tempTarget === $target) {throw new TargetLanguageException();}
        return $tempTarget;
    }
}