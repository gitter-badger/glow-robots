<?php
require_once 'vendor/autoload.php';

class ParserTest extends PHPUnit_Framework_TestCase {
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
        $this->p = new Glow\Robots\Parser;
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
        $this->p->parse();

        $errors = $this->p->getErrors();
        $this->assertCount(1, $errors);
        $this->assertEquals($errors[0]['code'], 1);
        $this->assertEquals($errors[0]['message'], 'The specified robots.txt source is above the suggested file size limit of 500kb');
        $this->assertEquals($errors[0]['line'], '--');
        $this->assertEquals($errors[0]['level'], 'CRITICAL');
    }

    public function test_all_empty_source() {
        $contents = file_get_contents($this->_file_path('emptylines'));
        $this->p->setSource($contents);
        $this->p->parse();

        $parsed_data = $this->p->getParsed();
        $this->assertEquals($parsed_data['--META--']['counts']['empty-lines'], 10);
    }

    public function test_nothing_but_comments() {
        $contents = file_get_contents($this->_file_path('nothingbutcomments'));
        $this->p->setSource($contents);
        $this->p->parse();

        $parsed_data = $this->p->getParsed();
        $this->assertEquals($parsed_data['--META--']['counts']['comments'], 6);
    }

    public function test_duplicate_user_agent() {
        $contents = file_get_contents($this->_file_path('duplicate_useragent'));
        $this->p->setSource($contents);
        $this->p->parse();

        $errors = $this->p->getErrors();
        $this->assertCount(1, $errors);
        $this->assertEquals($errors[0]['code'], 4);
        $this->assertEquals($errors[0]['message'], 'User Agent [*] was already defined!');
        $this->assertEquals($errors[0]['line'], 4);
        $this->assertEquals($errors[0]['level'], 'CRITICAL');
    }

    public function test_parse_line() {
        $contents = file_get_contents($this->_file_path('basic'));
        $this->p->setSource($contents);
        $this->p->parse();

        $parsed_data = $this->p->getParsed();
        $this->assertEquals($parsed_data['--META--']['counts']['lines'], 2);
    }

    protected function _file_path($name) {
        $path = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'sample_robots' . DIRECTORY_SEPARATOR . $name . '.txt';
        return $path;
    }
}