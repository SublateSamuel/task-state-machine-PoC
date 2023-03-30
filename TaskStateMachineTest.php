<?php

require_once __DIR__ . '/autoloader.php';

use App\StateMachine\TaskStateMachine;
use App\states\Initiate;
use App\states\Pendent;
use App\states\Processing;
use App\states\Updated;
use App\states\Error;
use App\states\Completed;
use PHPUnit\Framework\TestCase;

class TaskStateMachineTest extends TestCase
{
    public function testInitialState(): void
    {
        $task = ['id' => 1, 'name' => 'Test Task'];
        $initialState = new Initiate();
        $stateMachine = new TaskStateMachine($task, $initialState);

        $this->assertInstanceOf(Initiate::class, $stateMachine->getCurrentState());
        $this->assertArrayHasKey((string) $initialState, $stateMachine->history);
        $this->assertEquals((string) $initialState, $stateMachine->task['status']);
    }

    public function testTransitionToState(): void
    {
        $task = ['id' => 1, 'name' => 'Test Task'];
        $initialState = new Initiate();
        $stateMachine = new TaskStateMachine($task, $initialState);

        $nextState = new Pendent();
        $stateMachine->transitionTo($nextState);

        $this->assertInstanceOf(Pendent::class, $stateMachine->getCurrentState());
        $this->assertArrayHasKey((string) $nextState, $stateMachine->history);
        $this->assertEquals((string) $nextState, $stateMachine->task['status']);
    }

    public function testRestartState(): void
    {
        $task = ['id' => 1, 'name' => 'Test Task'];
        $initialState = new Processing();
        $stateMachine = new TaskStateMachine($task, $initialState);

        $restartState = new Pendent();
        $stateMachine->restartState($restartState);

        $this->assertArrayHasKey('restarted', $stateMachine->history);
        $this->assertInstanceOf(Pendent::class, $stateMachine->getCurrentState());
        $this->assertEquals((string) $restartState, $stateMachine->task['status']);
        $this->assertEquals('ready', $stateMachine->task['bot']);
    }

    public function testEventInInitiateState(): void
    {
        $task = ['id' => 1, 'name' => 'Test Task'];
        $initialState = new Initiate();
        $stateMachine = new TaskStateMachine($task, $initialState);

        $stateMachine->event();

        $this->assertEquals('1343d-13dds-345ff-455ff', $stateMachine->task['uuid']);
        $this->assertInstanceOf(Initiate::class, $stateMachine->getCurrentState());
    }

    public function testEventInPendentState(): void
    {
        $task = ['id' => 1, 'name' => 'Test Task'];
        $initialState = new Pendent();
        $stateMachine = new TaskStateMachine($task, $initialState);

        $stateMachine->event();

        $this->assertEquals('ready', $stateMachine->task['bot']);
        $this->assertInstanceOf(Pendent::class, $stateMachine->getCurrentState());
    }

    public function testEventInProcessingState(): void
    {
        $task = ['id' => 1, 'name' => 'Test Task'];
        $initialState = new Processing();
        $stateMachine = new TaskStateMachine($task, $initialState);

        $stateMachine->event();

        $this->assertEquals('running', $stateMachine->task['bot']);
        $this->assertInstanceOf(Processing::class, $stateMachine->getCurrentState());
    }

    public function testEventInUpdateState(): void
    {
        $task = ['id' => 1, 'name' => 'Test Task'];
        $expectedFile = 'file.pdf';
        $initialState = new Updated($expectedFile);
        $stateMachine = new TaskStateMachine($task, $initialState);

        $stateMachine->event();

        $this->assertArrayHasKey((string) $initialState, $stateMachine->history);
        $this->assertArrayHasKey('files', $stateMachine->task);
        $this->assertEquals((string) $initialState, $stateMachine->task['status']);
        $this->assertEquals('finish', $stateMachine->task['bot']);
        $this->assertInstanceOf(Updated::class, $stateMachine->getCurrentState());
    }

    public function testEventInUpdateTransitionToErrorState(): void
    {
        $task = ['id' => 1, 'name' => 'Test Task'];
        $expectedFile = 'otherFile.pdf';
        $initialState = new Updated($expectedFile);
        $stateMachine = new TaskStateMachine($task, $initialState);

        $stateMachine->event();

        $this->assertArrayHasKey('error', $stateMachine->history);
        $this->assertEquals('error', $stateMachine->task['bot']);
        $this->assertEquals('error', $stateMachine->task['status']);
        $this->assertEquals(
            "It was not possible to proceed with the task without the file -> {$expectedFile}",
            $stateMachine->task['message']
        );
        $this->assertNotEquals($expectedFile, $stateMachine->task['files']['name']);
        $this->assertInstanceOf(Error::class, $stateMachine->getCurrentState());
    }

    public function testEventInCompletedState(): void
    {
        $task = ['id' => 1, 'name' => 'Test Task'];
        $initialState = new Completed();
        $stateMachine = new TaskStateMachine($task, $initialState);

        $stateMachine->event();

        $this->assertArrayHasKey((string) $initialState, $stateMachine->history);
        $this->assertEquals((string) $initialState, $stateMachine->task['status']);
        $this->assertInstanceOf(Completed::class, $stateMachine->getCurrentState());
    }
}
