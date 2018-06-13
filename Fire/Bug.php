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

namespace Fire;

use \Fire\Bug\Panel;
use \Fire\Bug\Panel\Debugger as DebuggerPanel;
use \Fire\BugException;

/**
 * The purpose of this class is to provide a single place where you
 * will interact with the FireBug library.
 */
final class Bug extends Panel
{

    const ID = 'firebug';
    const NAME = 'FireBug Panel';
    const TEMPLATE = '/firebug.phtml';

    /**
     * Instance of Fire\Bug
     * @var \Fire\Bug
     */
    static private $_instance;

    /**
     * Is firebug enabled?
     * @var boolean
     */
    private $_enabled;

    /**
     * The firebug timer start time
     * @var float
     */
    private $_startTime;

    /**
     * Array of panel objects.
     * @var \Fire\Bug\Panel[]
     */
    private $_panels;

    /**
     * The constructor
     */
    public function __construct()
    {
        parent::__construct(self::ID, self::NAME, __DIR__ . self::TEMPLATE);
        $this->_panels = [];
        $this->_enabled = false;
        $this->addPanel(new DebuggerPanel());
    }

    /**
     * Gets the instance of Fire\Bug.
     * @return \Fire\Bug
     */
    static function get()
    {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Enables firebug.
     * @return void
     */
    public function enable()
    {
        $this->_enabled = true;
        $this->_startTime = $this->timer();
    }

    /**
     * Determines if firebug is enabled.
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->_enabled;
    }

    /**
     * Destroys the current instance of FireBug.
     * @return void
     */
    static function destroy()
    {
        self::$_instance = null;
    }

    /**
     * Adds a Fire\Bug\Panel object to the the array of panels.
     * @param \Fire\Bug\Panel $panel The panel you are adding to FireBug
     * @return void
     */
    public function addPanel(Panel $panel)
    {
        $id = $panel->getId();
        if (!empty($this->_panels[$id])) {
            throw new BugException('[FireBug] No panels exist with ID "' . $id . '".');
        }
        $this->_panels[$id] = $panel;
    }

    /**
     * Gets a stored panel object by its ID.
     * @param [type] $id The id of defined on the Fire\Bug\Panel instance object.
     * @return \Fire\Bug\Panel
     */
    public function getPanel($id)
    {
        return $this->_panels[$id];
    }

    /**
     * Gets all stored panels.
     * @return \Fire\Bug\Panel[]
     */
    public function getPanels()
    {
        return $this->_panels;
    }

    /**
     * Method used to measure the amount of time that passed in milliseconds.
     * If you pass in a $start time, then you will be returned time length from
     * the start time. If you don't pass anything in, a start time will be returned.
     * @param float|null $start The start time.
     * @return float
     */
    public function timer($start = null)
    {
        if ($start) {
            $end = microtime(true);
            return round(($end - $start) * 1000, 4);
        } else {
            return microtime(true);
        }
    }

    /**
     * If the timer was started, the load time will be returned
     * @return float|boolean
     */
    public function getLoadTime()
    {
        if (!empty($this->_startTime)) {
            return $this->timer($this->_startTime);
        }
        return false;
    }

    /**
     * Method used to render FireBug.
     * @return void
     */
    public function render()
    {
        if ($this->_enabled) {
            if (php_sapi_name() === 'cli') {
                echo 'FireBug: ' . $this->getLoadTime() . ' milliseconds' . "\n";
            } else {
                ob_start();
                include $this->_template;
                $debugPanel = ob_get_contents();
                ob_end_clean();

                return $debugPanel;
            }
        }
    }

}
