<?php

require_once __DIR__ . '/autoloader.php';

use App\StateMachine\TaskStateMachine;
use App\states\Canceled;
use App\states\Completed;
use App\states\Initiate;
use App\states\Pendent;
use App\states\Processing;
use App\states\Updated;

$dataTask = [
    'tarefa' => 'buscar arquivos',
];
$file =  'file.pdf';

$task = new TaskStateMachine($dataTask, new Initiate());
$task->event()->transitionTo(new Pendent())
    ->event()->transitionTo(new Processing())
    ->event()->transitionTo(new Updated($file))
    ->event()->transitionTo(new Completed());

$task->restartState(new Pendent());

$task->transitionTo(new Canceled('duplicated task'))->event();

var_export($task->history);

var_export($task->task);
