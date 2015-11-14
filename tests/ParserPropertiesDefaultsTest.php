<?php
require_once 'vendor/autoload.php';

class ParserPropertiesDefaultsTest extends PHPUnit_Framework_TestCase {
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

    //PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - \\
    //PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - \\
    //PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - \\
    //PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - \\

    public function test_property_default_original_source() {
        $this->assertNull($this->p->getOriginalSource());
    }

    public function test_property_default_parsed() {
        $this->assertNull($this->p->getParsed());
    }

    public function test_property_default_errors() {
        $this->assertEquals($this->p->getErrors(), array());
        $this->assertEquals(count($this->p->getErrors()), 0);
    }

    public function test_property_default_lineEndings() {
        $this->assertEquals($this->p->getLineEndings(), "\r");
    }

    public function test_property_default_elements() {
        $elements = $this->p->getElements();

        $this->assertTrue(is_array($elements));
        $this->assertArrayHasKey('shared', $elements);
        $this->assertArrayHasKey('user-agent', $elements);

        $this->assertCount(3, $elements['shared']);
        $this->assertCount(2, $elements['user-agent']);

        $this->assertEquals($elements['shared'][0], 'Sitemap');
        $this->assertEquals($elements['shared'][1], 'Host');
        $this->assertEquals($elements['shared'][2], 'Crawl-delay');

        $this->assertEquals($elements['user-agent'][0], 'Disallow');
        $this->assertEquals($elements['user-agent'][1], 'Allow');
    }

    public function test_property_default_tr() {
        $this->assertNull($this->p->getTR());
    }

    public function test_property_beenParsed() {
        $this->assertFalse($this->p->getBeenParsed());
    }

    //PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - \\
    //PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - \\
    //PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - \\
    //PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - PROPERTY DEFAULTS - \\
}