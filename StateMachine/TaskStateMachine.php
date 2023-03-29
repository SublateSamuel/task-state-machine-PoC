<?php

namespace App\StateMachine;

use App\states\abstract\State;
use App\states\Restarted;

class TaskStateMachine
{
    private ?State $currentState = null;

    public array $history = [];

    public array $task;

    public function __construct(array $task, State $initialState)
    {
        $this->task = $task;
        $this->setNewState($initialState);
    }

    public function transitionTo(State $state): self
    {
        $this->setNewState($state);
        return $this;
    }

    public function restartState(State $state): self
    {
        $this->setNewState(new Restarted($state))->event();
        return $this;
    }

    public function event(): self
    {
        $taskAfterEvent = $this->currentState->event($this->task);
        $this->registerEvent($taskAfterEvent);
        return $this;
    }

    public function getCurrentState(): State
    {
        return $this->currentState;
    }

    private function registerTransition(): void
    {
        if (!array_key_exists((string) $this->currentState, $this->history)) {
            $this->task['status'] = (string) $this->currentState;
            $this->history[(string) $this->currentState] = $this->task;
            return;
          }
        $this->task = $this->history[(string) $this->currentState];
    }

    private function setNewState(State $state): self
    {
        echo "Tansition to state -> " . $state . PHP_EOL;
        $this->currentState = $state;
        $state->setState($this);
        $this->registerTransition();
        return $this;
    }

    private function registerEvent(array $task): void
    {
        if (!empty($task)) {
            $this->task = $task;
            $this->history[(string) $this->currentState] = $this->task;
        }
        $this->task = $this->history[(string) $this->currentState];
    }
}

