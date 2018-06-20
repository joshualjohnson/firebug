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


namespace Fire\Bug;

/**
 * Abstract class all debug panels sould inheret from.
 */
abstract class Panel
{

    /**
     * The id given to the panel.
     * @var string
     */
    protected $_id;

    /**
     * A readable name given to the panel.
     * @var string
     */
    protected $_name;

    /**
     * A path to a pthml template.
     * @var string
     */
    protected $_template;

    /**
     * The Constructor
     * @param string $id The ID of this panel
     * @param string $name The name of this panel
     * @param string $template Path to a template for this panel
     */
    public function __construct($id, $name, $template)
    {
        $this->_id = $id;
        $this->_name = $name;
        $this->_template = $template;
    }

    /**
     * Gets the ID for the given panel.
     * @return string The ID
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * Sets the name for the given panel.
     * @param string $name The name of the panel.
     * @return void
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * Gets the name for the given panel.
     * @return string The name
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Returns the code passed in wrapped within a <pre> tag.
     * @param  string $content The code you want to render
     * @param  boolean $dark Do you want the dark theme?
     * @return string The HTML to render
     */
    public function renderCode($code, $dark = true)
    {
        $code = '';
        $code .= '<span class="fs-label">';
        $code .= '<span class="fs-pre-wrap">wrap</span>';
        $code .= '<pre class="debugger'. ($dark) ? ' fs-dark' : '' . '">';
        $code .= $code;
        $code .= '</pre>';
        $code .= '</span>';

        return $code;
    }

    /**
     * Returns the HTML passed in wrapped within a <pre> tag.
     * @param  string $content The code you want to render
     * @param  boolean $dark Do you want the dark theme?
     * @return string The HTML to render
     */
    public function renderHtml($html, $dark = true)
    {
        return $this->renderCode(htmlspecialchars($html), $dark);
    }

    /**
     * Returns the JSON passed in wrapped within a <pre> tag.
     * @param  string $content The code you want to render
     * @param  boolean $dark Do you want the dark theme?
     * @return string The HTML to render
     */
    public function renderJson($json, $dark = true)
    {
        if (is_object($json)) {
            $jsonCode = json_encode($json, JSON_PRETTY_PRINT);
        } else if (is_array($json)) {
            $jsonCode = json_encode(($json) ? $json : (object) $json, JSON_PRETTY_PRINT);
        } else {
            $jsonCode = json_encode(json_decode($json), JSON_PRETTY_PRINT);
        }
        return $this->renderCode(htmlspecialchars($jsonCode), $dark);
    }

    /**
     * Returns a rendered debug_backtrace.
     * @param array $debug_backtrace
     * @return string
     */
    public function renderTrace($debug_backtrace)
    {
        $traceLine = '';
        foreach ($debug_backtrace as $index => $trace) {
            $traceLine .= '#' . $index . ' ';
            if (!empty($trace['file'])) {
                $traceLine .= $trace['file'];
            }
            if (!empty($trace['line'])) {
                $traceLine .= '(' . $trace['line'] . ') ';
            }
            if (!empty($trace['class'])) {
                $traceLine .= $trace['class'] . '::';
            }
            $traceLine .= $trace['function'] . '()'
                . '<br>';
        }
        return $traceLine;
    }

    /**
     * Renders the panel.
     * @return void
     */
    public function render()
    {
        ob_start();
        include __DIR__ . '/panel-top.phtml';
        include $this->_template;
        include __DIR__ . '/panel-bottom.phtml';
        ob_end_flush();
    }
}
