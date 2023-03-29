<?php

namespace App\states;

require_once 'autoloader.php';

use App\states\abstract\State;

class Canceled extends State
{
    const STATE_NAME = 'canceled';

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

    public function event($task): array
    {
        $task['message'] = $this->message;
        return $task;
    }
}