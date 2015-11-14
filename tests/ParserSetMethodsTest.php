<?php
require_once 'vendor/autoload.php';

class ParserSetMethodsTest extends PHPUnit_Framework_TestCase {
    /**
     * Parser
     * Our Glow\Robots\Parser Object
     *
     * @access protected
     * @var null|Glow\Robots\Parser
     */
    protected $p = null;

    /**
     * Faker
     * The fzaninotto/faker object
     *
     * @access protected
     * @var fzaninotto/faker
     */
    protected $f = null;

    /**
     * Setup
     * The setup that is run before each test
     *
     * @access protected
     */
    protected function setUp() {
        $this->p = new Glow\Robots\Parser;
        $this->f = Faker\Factory::create();
    }

    /**
     * Tear Down
     * Clean up after a test
     *
     * @access protected
     */
    protected function tearDown() {
        $this->p = null;
        $this->f = null;
    }

    public function test_set_original_source() {
        $str = $this->f->word;
        $this->p->setOriginalSource($str);
        $this->assertEquals($this->p->getOriginalSource(), $str);
    }

    public function test_set_source() {
        $str = $this->f->word;
        $this->p->setSource($str);
        $this->assertEquals($this->p->getOriginalSource(), $str);
    }

    public function test_set_line_endings() {
        $str = $this->f->randomLetter;
        $this->p->setLineEndings($str);
        $this->assertEquals($this->p->getLineEndings(), $str);
    }

    public function test_construct_default() {
        $str     = $this->f->word;
        $this->p = new Glow\Robots\Parser($str);
        $this->assertEquals($this->p->getOriginalSource(), $str);
    }

    public function test_set_elements() {
        $array = array('TEST' => 'VALUE');
        $this->p->setElements($array);
        $this->assertEquals($this->p->getElements(), $array);
    }
}