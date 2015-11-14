<?php
require_once 'vendor/autoload.php';

class ParserMethodsIntegrityTest extends PHPUnit_Framework_TestCase {
    /**
     * Parser
     * Our Glow\Robots\Parser Object
     *
     * @access protected
     * @var null|Glow\Robots\Parser
     */
    protected $p = null;

    protected $reflection = null;

    /**
     * Setup
     * The setup that is run before each test
     *
     * @access protected
     */
    protected function setUp() {
        $this->p          = new Glow\Robots\Parser;
        $this->reflection = new ReflectionClass($this->p);
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

    public function test_methods_count() {
        $this->assertCount(25, $this->reflection->getMethods());
    }

    public function test_make_sure_we_have_these_methods() {
        $base_methods = array(
            '__construct',
            'getOriginalSource',
            'setOriginalSource',
            'setSource',
            'reset',
            'parse',
            'parse_line',
            'getParsed',
            'getErrors',
            'set_error',
            'increment_counter',
            'isAllowed',
            'isDisallowed',
            'validate',
            'getTR',
            'getLineEndings',
            'setLineEndings',
            'getElements',
            'setElements',
            'getBeenParsed',
            'getMeta',
            'getSitemaps',
            'getUserAgentData',
            'getUserAgentAllow',
            'getUserAgentDisallow',
        );

        $r_methods = array();

        foreach ($this->reflection->getMethods() as $method) {
            $r_methods[] = $method->getName();
        }

        $this->assertEquals($r_methods, $base_methods);
    }

    public function test_make_sure_these_methods_are_public() {
        $public_methods = array(
            '__construct',
            'getOriginalSource',
            'setOriginalSource',
            'setSource',
            'parse',
            'getParsed',
            'getErrors',
            'isAllowed',
            'isDisallowed',
            'validate',
            'getTR',
            'getLineEndings',
            'setLineEndings',
            'getElements',
            'setElements',
            'getBeenParsed',
            'getMeta',
            'getSitemaps',
            'getUserAgentData',
            'getUserAgentAllow',
            'getUserAgentDisallow',
        );

        $r_methods = array();

        foreach ($this->reflection->getMethods() as $method) {
            if ($method->isPublic() === true) {
                $r_methods[] = $method->getName();
            }
        }

        $this->assertEquals($r_methods, $public_methods);
    }

    public function test_make_sure_these_methods_are_protected() {
        $protected_methods = array(
            'reset',
            'parse_line',
            'set_error',
            'increment_counter',
        );

        $r_methods = array();

        foreach ($this->reflection->getMethods() as $method) {
            if ($method->isProtected() === true) {
                $r_methods[] = $method->getName();
            }
        }

        $this->assertEquals($r_methods, $protected_methods);
    }
}