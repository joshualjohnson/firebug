<?php

/**
 *    __  _____   ___   __          __
 *   / / / /   | <  /  / /   ____ _/ /_  _____
 *  / / / / /| | / /  / /   / __ `/ __ `/ ___/
 * / /_/ / ___ |/ /  / /___/ /_/ / /_/ (__  )
 * `____/_/  |_/_/  /_____/`__,_/_.___/____/
 *
 * @package FireStudio
 * @subpackage FireBug
 * @author UA1 Labs Developers https://ua1.us
 * @copyright Copyright (c) UA1 Labs
 */


namespace Test\Fire\Bug\Panel;

use Fire\Test\TestCase;
use Fire\Bug\Debugger as FireBugDebugger;
use Fire\Bug\Panel\Debugger as FireBugPanelDebugger;
use Fire\BugException;

/**
 * Test suite for Fire\Bug\Panel\Debugger
 */
class Debugger extends TestCase {

    /**
     * Tests the Fire\Bug\Panel\Debugger::addDebugger() and Fire\Bug\Panel\Debugger:getDebuggers() methods.
     * @return void
     */
    public function testAddAndGetDebugger()
    {
        $debuggerPanel = new FireBugPanelDebugger();
        try {
            $debuggerPanel->addDebugger([]);
        } catch (BugException $e) {
            $exception = $e;
        }
        $this->should('When trying to add a debuger, the object you pass in must be of class Fire\Bug\Debugger.');
        $this->assert(isset($exception) && $exception instanceof BugException);

        $debuggerPanel->addDebugger(new FireBugDebugger());
        $debuggers = $debuggerPanel->getDebuggers();
        $this->should('When a debugger is added, we should be able to get that debugger back.');
        $this->assert(is_array($debuggers));
    }

}
