<?php

namespace App\states;

use App\states\abstract\State;

require_once 'autoloader.php';

class Processing extends State
{
    const STATE_NAME = 'processing';

    public function __toString(): string
    {
        return self::STATE_NAME;
    }

    public function transition(State $state): void
    {
        $this->getStateMachine()->transitionTo($state);
    }

    public function event(array $task): array
    {
        $task['bot'] = 'running';
        return $task;
    }
}