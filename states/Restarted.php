<?php

namespace App\states;

use App\states\abstract\State;

require_once 'autoloader.php';

class Restarted extends State
{
    const STATE_NAME = 'restarted';

    protected State $stateRestarted;

    public function __construct(State $state)
    {
        $this->stateRestarted = $state;
    }

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
        try {
            $this->transition($this->stateRestarted);
        } catch (\Exception){
            $this->transition(
                new Error("It was not possible to restart for the state: {$this->stateRestarted}")
            );
        }
        return [];
    }
}
