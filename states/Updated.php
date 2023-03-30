<?php

namespace App\states;

use App\states\abstract\State;

require_once 'autoloader.php';

class Updated extends State
{
    const STATE_NAME = 'updated';

    protected $file;

    public function __construct($file)
    {
        $this->file = $file;
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
        $task['files'] = [
            'name' => 'file.pdf',
            'sha256' => 'dqwhfioquhwfiouhqw',
            'url' => 'http://download.file.pdf'
        ];
        $task['bot'] = 'finish';
        if (!in_array($this->file, $task['files'])) {
            $this->transition(new Error(
                "It was not possible to proceed with the task without the file -> {$this->file}"
                )
            );
            $this->getStateMachine()->event();
            return [];
        }
        return $task;
    }
}
