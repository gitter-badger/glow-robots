<?php
require_once 'vendor/autoload.php';

class ValidateTest extends PHPUnit_Framework_TestCase {
    /**
     * Parser
     * Our Glow\Robots\Parser Object
     *
     * @access protected
     * @var null|Glow\Robots\Parser
     */
    protected $p = null;

    /**
     * Setup
     * The setup that is run before each test
     *
     * @access protected
     */
    protected function setUp() {
        $this->p = new Glow\Robots\Validate;
    }

    /**
     * Tear Down
     * Clean up after a test
     *
     * @access protected
     */
    protected function tearDown() {
        $this->p = null;
    }

    public function test_above500kb_source() {
        $colossal_contents = file_get_contents($this->_file_path('above500kb'));
        $this->p->setSource($colossal_contents);

        $this->assertFalse($this->p->check());
    }

    public function test_duplicate_user_agent() {
        $contents = file_get_contents($this->_file_path('duplicate_useragent'));
        $this->p->setSource($contents);

        $this->assertFalse($this->p->check());
    }

    public function test_delimeter_missing() {
        $contents = file_get_contents($this->_file_path('delimetermissing'));
        $this->p->setSource($contents);

        $this->assertFalse($this->p->check());
    }

    public function test_improper_directive_case() {
        $contents = file_get_contents($this->_file_path('impropercase'));
        $this->p->setSource($contents);

        $this->assertFalse($this->p->check());
    }

    public function test_valid_file() {
        $contents = file_get_contents($this->_file_path('cnn'));
        $this->p->setSource($contents);

        $this->assertTrue($this->p->check());
    }

    public function test_construct() {
        $contents = file_get_contents($this->_file_path('cnn'));
        $this->p  = new Glow\Robots\Validate($contents);

        $this->assertTrue($this->p->check());
    }

    protected function _file_path($name) {
        $path = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'sample_robots' . DIRECTORY_SEPARATOR . $name . '.txt';
        return $path;
    }
}