<?php

class Chat {
    public function __construct(
        private ?int $id,
        private ?string $model,
        private ?array $systemInstruction,
        private ?array $contents
    ) {/* echo 'Criando a entidade da conversa' . PHP_EOL; */}

    public function getId(): ?int {return $this->id;}
    public function getModel(): ?string {return $this->model;}
    public function getSystemInstruction(): ?array {return $this->systemInstruction;}
    public function getContents(): ?array {return $this->contents;}

    public function setId(int $id): void {$this->id = $id;}
    public function setModel(string $model): void {$this->model = $model;}
    public function setSystemInstruction(array $instructs): void {$this->systemInstruction = $instructs;}
    public function setContent(array $content): void {
        $this->contents[] = $content;
    }
    public function toPersist() {
        $persistenceContext = [
            'model' => $this->model,
            'systemInstruction' => $this->systemInstruction,
            'contents' => $this->contents
        ];
        return json_encode($persistenceContext);

    }
}