<?php

namespace Glow\Robots;

use tomverran\Robot\RobotsTxt;

class Parser {
    /**
     * Original Source
     * The original/unmodified source robot.txt file
     *
     * @access protected
     */
    protected $original_source = null;

    /**
     * Parsed
     * A placeholder for the parsed data and meta data
     *
     * @access protected
     */
    protected $parsed = null;

    /**
     * Errors
     * A placeholder for any errors that occurred
     *
     * @access protected
     */
    protected $errors = array();

    /**
     * Elements
     * What elements should we check for in the robots.txt file
     *
     * @access protected
     * @var array
     */
    protected $elements = array(
        'shared'     => array('Sitemap', 'Host', 'Crawl-delay'),
        'user-agent' => array('Disallow', 'Allow'),
    );

    /**
     * tr
     * Placeholder for the tomverran/robots-txt-checker parser
     *
     * @access protected
     */
    protected $tr = null;

    /**
     * Tmp
     * Temp holding property
     *
     * @access protected
     * @var null
     */
    protected $tmp = null;

    /**
     * Class Construt
     * @param string $source
     */
    public function __construct($source = null) {
        if (!is_null($source)) {
            $this->setOriginalSource($source);
            $this->parse();
        }
    }

    /**
     * Get Original Source
     * Returns the original source
     *
     * @access public
     * @return null|string
     */
    public function getOriginalSource() {
        return $this->original_source;
    }

    /**
     * Set Original Source
     * Sets the original robots.txt source
     *
     * @access public
     * @param string $source
     *
     * @return Glow\Robots\Parser
     */
    public function setOriginalSource($source) {
        if (!is_string($source)) {
            throw new \ErrorException('The source data must be a string!');
        }

        $this->reset();

        $this->original_source = $source;

        return $this;
    }

    /**
     * Set Source
     * An alias call for setOriginalSource
     *
     * @access public
     * @param string $source
     *
     * @return Glow\Robots\Parser
     */
    public function setSource($source) {
        return $this->setOriginalSource($source);
    }

    /**
     * Reset
     * Reset the class properties to default
     *
     * @access protected
     */
    protected function reset() {
        $this->original_source = null;
        $this->parsed          = null;
        $this->errors          = array();
        $this->tr              = null;
        $this->tmp             = null;
    }

    /**
     * Parse
     * Parse the robots.txt file
     *
     * @access public
     */
    public function parse() {
        //grab the source
        $source = $this->getOriginalSource();

        //if the source is null throw exception
        if (is_null($source)) {
            throw new \ErrorException('You must set the source data!');
        }

        //save the source in our meta data array
        $this->parsed['--META--']['size'] = strlen($source) / 1000;

        //set some basic meta stuff
        $this->parsed['--META--']['user-agents'] = array();

        //explode our source by line returns
        $xploded_file = explode("\r", $source);

        //if the size is above 500 add a warning
        if ($this->parsed['--META--']['size'] > 500) {
            $this->set_error(1, 'The specified robots.txt source is above the suggested file size limit of 500kb', '--', 'CRITICAL');
        }

        //used to track the relationship between rules and user agents
        $current_user_agent = null;

        //now lets loop through our entire file
        for ($a = 0; $a < count($xploded_file); $a++) {
            //increment our line counter
            $this->increment_counter('lines');

            //a variable for our line number
            $line_number = $a + 1;

            //our local line
            $line = trim($xploded_file[$a]);

            //we want to skip empty lines
            if ((empty($line)) or (strlen($line) == 0)) {
                $this->increment_counter('empty-lines');
                continue;
            }

            //we handle comments a special way
            if (substr($line, 0, 1) == '#') {
                $this->increment_counter('comments');
                $comment = ltrim($line, '#');

                if (!empty($comment)) {
                    if (is_null($current_user_agent)) {
                        $this->parsed['--UNTRACKED--']['comments'][] = $comment;
                    } else {
                        $this->parsed[$current_user_agent]['comments'][] = $comment;
                    }
                }

                continue;
            }

            //check for a user agent update
            if ($this->parse_line($line_number, $line, 'User-agent', NULL, false) !== false) {
                if (in_array($this->tmp, $this->parsed['--META--']['user-agents'])) {
                    $this->set_error(4, 'User Agent [' . $this->tmp . '] was already defined!', $line_number, 'CRITICAL');
                } else {
                    $this->parsed['--META--']['user-agents'][] = $this->tmp;
                }

                $current_user_agent = $this->tmp;
                continue;
            }

            //lets check for shared elements
            foreach ($this->elements['shared'] as $element) {
                $this->parse_line($line_number, $line, $element, '--SHARED--');
                continue;
            }

            if (!is_null($current_user_agent)) {
                //lets check user agent elements
                foreach ($this->elements['user-agent'] as $element) {
                    $this->parse_line($line_number, $line, $element, $current_user_agent);
                }
            }
        }

        $this->tmp = null;
    }

    /**
     * Parse Line
     * This is the major workhorse for parsing the robots.txt line. It may not be
     * super efficent but it works guud!
     *
     * @access protected
     * @param  [type]  $line_number [description]
     * @param  [type]  $data        [description]
     * @param  [type]  $directive   [description]
     * @param  [type]  $user_agent  [description]
     * @param  boolean $save_value  [description]
     * @return [type]               [description]
     */
    protected function parse_line($line_number, $data, $directive, $user_agent, $save_value = true) {
        $directive_lowercase = strtolower($directive);
        $directive           = ucfirst(strtolower($directive));

        if (substr(strtolower($data), 0, strlen($directive_lowercase)) != $directive_lowercase) {
            return false;
        }

        //check to make sure we have ":" symbol after our directive
        if (substr($data, strlen($directive), 1) != ':') {
            $this->set_error(3, 'The ' . $directive . ' directive expects a ":" delimeter before the element value!', $line_number, 'CRITICAL');
            $this->increment_counter('skipped-due-to-error');
            return false;
        }

        //check for case sensitivity now
        if (substr($data, 0, strlen($directive)) != $directive) {
            $this->set_error(2, $directive . ' directive has improper casing - ' . substr($data, 0, strlen($directive)) . ' should be [' . $directive . ']', $line_number, 'WARN');
        }

        //increment our counters
        $this->increment_counter($directive_lowercase);

        //lets get the directive value
        $value = trim(ltrim(substr_replace($data, '', 0, strlen($directive)), ':'));

        if ($save_value === true) {
            $this->parsed[$user_agent][$directive_lowercase][] = $value;
        } else {
            $this->tmp = $value;
        }

        return true;
    }

    /**
     * Get Parsed
     * Returns all of the parsed data
     *
     * @access public
     * @return null|array
     */
    public function getParsed() {
        return $this->parsed;
    }

    /**
     * Get Errors
     * Returns all of the errors that may have occured
     *
     * @access public
     * @return array
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * Set Error
     * Sets an error
     * @param int $code
     * @param string $message
     * @param int $line
     * @param string $level
     */
    protected function set_error($code, $message, $line, $level) {
        $this->errors[] = array(
            'code'    => $code,
            'message' => $message,
            'line'    => $line,
            'level'   => $level,
        );

        $this->increment_counter('errors');
    }

    /**
     * Increment Counter
     * A nice helper method that creates (if needed) and increments counters
     *
     * @access protected
     * @param  string $idx
     */
    protected function increment_counter($idx) {
        if (!isset($this->parsed['--META--']['counts'][$idx])) {
            $this->parsed['--META--']['counts'][$idx] = 0;
        }

        $this->parsed['--META--']['counts'][$idx]++;
    }

    /**
     * IsAllowed
     * Are you allowed to crawl a url?
     *
     * @access public
     * @param  string  $url
     * @param  string  $user_agent
     * @return boolean
     */
    public function isAllowed($url, $user_agent = '*') {
        if (count($this->parsed) == 0) {
            throw new \ErrorException('E-1');
        }

        if (is_null($this->getSource())) {
            throw new \ErrorException('E-2');
        }

        $this->init_tr();

        return $this->tr->isAllowed($user_agent, $url);
    }

    /**
     * IsDisAllowed
     * Are you not allowed to crawl a url?
     *
     * @access public
     * @param  string  $url
     * @param  string  $user_agent
     * @return boolean
     */
    public function isDisallowed($url, $user_agent = '*') {
        if (count($this->parsed) == 0) {
            throw new \ErrorException('E-1');
        }

        if (is_null($this->getSource())) {
            throw new \ErrorException('E-2');
        }

        $this->init_tr();

        if ($this->tr->isAllowed($user_agent, $url) === true) {
            return false;
        }

        return true;
    }

    /**
     * Validate
     * Validates the contents of the robot.txt file
     *
     * Strict mode will fail validation on any error
     * While normal mode will only fail on critial errors
     *
     * @access public
     * @param  boolean $strict
     * @return boolean
     */
    public function validate($strict = false) {
        if (count($this->parsed) == 0) {
            throw new \ErrorException('E-1');
        }

        if (is_null($this->getSource())) {
            throw new \ErrorException('E-2');
        }

        //if we have no errors validation is good
        if (count($this->errors) == 0) {
            return true;
        }

        //if strict mode is on and we have errors baddddd
        if ($strict === true) {
            return false;
        }

        //if we are not in strict mode we just want to find critical errors
        foreach ($this->errors as $error) {
            if ($error['level'] == 'CRITICAL') {
                return false;
            }
        }

        return true;
    }

    /**
     * InitTR
     * Init the tomverran/robots-txt-checker class
     *
     * @access protected
     */
    protected function init_tr() {
        if (is_null($this->tr)) {
            $this->tr = new RobotsTxt($this->getSource());
        }
    }

    /**
     * Get TR
     * Returns the tomverran/robots-txt-checker object
     *
     * @access public
     * @return null|tomverran/robots-txt-checker/RobotsTxt
     */
    public function getTR() {
        return $this->tr;
    }
}