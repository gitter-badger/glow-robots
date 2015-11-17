<?php
require_once 'vendor/autoload.php';

class ValidateMethodsIntegrityTest extends PHPUnit_Framework_TestCase {
    /**
     * Parser
     * Our Glow\Robots\Validate Object
     *
     * @access protected
     * @var null|Glow\Robots\Validate
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
        $this->p          = new Glow\Robots\Validate;
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
        $this->assertCount(3, $this->reflection->getMethods());
    }

    public function test_make_sure_we_have_these_methods() {
        $base_methods = array(
            '__construct',
            'setSource',
            'check',
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
            'setSource',
            'check',
        );

        $r_methods = array();

        foreach ($this->reflection->getMethods() as $method) {
            if ($method->isPublic() === true) {
                $r_methods[] = $method->getName();
            }
        }

        $this->assertEquals($r_methods, $public_methods);
    }
}