<?php 

class ChatValidator {
    public function validateAll(array $values) {
        $args = [
            "level" => CEFRLevel::fromInt($values['level'] ?? 2),
            "origin" => $this->validateLanguage($values['from'] ?? 'pt-BR', 'origin'),
            "target" => $this->validateLanguage($values['target'] ?? 'en-US', 'target')
        ];
        return $args;
    }

    public function validateLanguage($language, $type) {
        $tempLang = Locale::getDisplayLanguage($language);
        if ($tempLang === $language) {
            if (str_contains($type, 'origin')) throw new OriginLanguageException();
            if (str_contains($type, 'target')) throw new TargetLanguageException();
        }
        return $language;
    }
}