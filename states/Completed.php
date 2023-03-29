<?php

namespace App\states;

use App\states\abstract\State;

require_once 'autoloader.php';

class Completed extends State
{
    const STATE_NAME = 'completed';

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
        return [];
    }
}