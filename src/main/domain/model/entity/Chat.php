<?php

class Chat {
    public function __construct(
        public ?int $id,
        public ?string $model,
        public array $systemInstruction,
        public array $contents,
        public array $currentContent
    ) {}
}