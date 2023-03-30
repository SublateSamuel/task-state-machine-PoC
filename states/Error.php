<?php

namespace App\states;

use App\states\abstract\State;

require_once 'autoloader.php';

class Error extends State
{
    const STATE_NAME = 'error';

    protected string $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function __toString(): string
    {
        return self::STATE_NAME;
    }

    public function transition(State $state): void
    {
        // nothing next state
    }

    public function event(array $task): array
    {
        $task['message'] = $this->message;
        $task['bot'] = 'error';
        return $task;
    }
}