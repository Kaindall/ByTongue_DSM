<?php

class Chat {
    private array $instructions;
    public function __construct(
        private ?int $id,
        private ?string $model,
        private ?array $contents,
        private string $origin,
        private string $target,
        private ?string $level,
    ) {
        /* echo 'Criando a entidade da conversa' . PHP_EOL; 
        echo 'Declarando as instruções iniciais do systema'*/
        $originLang = Locale::getDisplayLanguage($this->origin);
        $targetLang = Locale::getDisplayLanguage($this->target);
        $this->instructions = [
            'parts' => [
                ['text' => "Você é um professor de $targetLang, gere um nome pra si mesmo"],
                ['text' => "Você está ensinando a um aluno em $originLang"],
                ['text' => "De acordo com o CEFR o nível do aluno é $level"],
                ['text' => "Engaje na conversa fazendo perguntas sobre a aula. Ou seja, $targetLang"]
            ]
        ];
    }

    public function getId(): ?int {return $this->id;}
    public function getModel(): ?string {return $this->model;}
    public function getInstructions(): ?array {return $this->instructions;}
    public function getContents(): ?array {return $this->contents;}
    public function getOrigin(): ?string {return $this->origin;}
    public function getTarget(): ?string {return $this->target;}
    public function getLevel(): ?string {return $this->level;}

    public function setId(int $id): void {$this->id = $id;}
    public function setModel(string $model): void {$this->model = $model;}
    public function setInstructions(array $instructs): void {$this->instructions = $instructs;}
    public function setContent(array $content): void {
        $this->contents[] = $content;
    }
    public function toPersist() {
        $persistenceContext = [
            'model' => $this->model,
            'from' => $this->origin,
            'target' => $this->target,
            'level'=> $this->level,
            'contents' => $this->contents
        ];
        return $persistenceContext;

    }
}