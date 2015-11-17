<?php

namespace Glow\Robots;

class Validate {
    /**
     * Parser Object
     * This is a placeholder object for Glow\Robots\Validate
     *
     * @access protected
     * @var null|Glow\Robots\Validate
     */
    protected $parser = null;

    public function __construct($source = null) {
        $this->parser = new Parser;

        if (!is_null($source)) {
            $this->parser->setSource($source);
            $this->parser->parse();
        }
    }

    /**
     * Set Source
     * Set the robots.txt content
     *
     * @access public
     * @param string $source - The contents of a robot.txt file
     * @return Glow\Robots\Validate
     */
    public function setSource($source) {
        $this->parser->setSource($source);
        $this->parser->parse();

        return $this;
    }

    /**
     * Check
     * Returns true if no errors occured otherwise false
     * This should be considered a strict check as even case
     * sensitivity will cause an error
     *
     * @access public
     * @return bool
     */
    public function check() {
        $errors = $this->parser->getErrors();

        if (count($errors) > 0) {
            return false;
        }

        return true;
    }
}