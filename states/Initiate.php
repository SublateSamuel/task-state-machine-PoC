<?php

namespace App\states;

use App\states\abstract\State;

require_once 'autoloader.php';

class Initiate extends State
{
    const STATE_NAME = 'initiate';

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
        $task['uuid'] = '1343d-13dds-345ff-455ff';
        return $task;
    }
}