<?php

namespace App\states\abstract;

require_once 'autoloader.php';

use App\StateMachine\TaskStateMachine;

abstract class State
{
    const STATE_NAME = '';

    protected TaskStateMachine $state;

    public function __toString(): string
    {
        return self::STATE_NAME;
    }

    public function getStateMachine(): TaskStateMachine
    {
        return $this->state;
    }

    public function setState(TaskStateMachine $state): void 
    {
        $this->state = $state;
    }

    abstract public function transition(State $state): void;

    abstract public function event(array $task): array;
}