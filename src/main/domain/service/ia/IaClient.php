<?php

interface IaClient {
    public function commitMessage(array $message, array $instructs);
}