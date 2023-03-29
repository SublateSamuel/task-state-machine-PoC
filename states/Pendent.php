<?php

namespace App\states;

use App\states\abstract\State;

require_once 'autoloader.php';

class Pendent extends State
{
    const STATE_NAME = 'pendent';

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
        $task['bot'] = 'ready';
        return $task;
    }
}