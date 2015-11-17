<?php
require_once 'vendor/autoload.php';

class ParserExceptionsTest extends PHPUnit_Framework_TestCase {
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

    public function test_set_original_source() {
        $obj = new stdClass();
        $this->setExpectedException('ErrorException');
        $this->p->setOriginalSource($obj);
    }

    public function test_parse_no_sorce_data() {
        $this->setExpectedException('ErrorException');
        $this->p->parse();
    }

    public function test_is_allowed_not_parsed() {
        $this->setExpectedException('ErrorException');
        $this->p->IsAllowed('test');
    }

    public function test_is_disallowed_not_parsed() {
        $this->setExpectedException('ErrorException');
        $this->p->IsDisallowed('test');
    }

    public function test_set_elements_non_array() {
        $this->setExpectedException('ErrorException');
        $this->p->setElements(new stdClass());
    }

    public function test_sitemaps_exception() {
        $this->setExpectedException('ErrorException');
        $this->p->getSitemaps();
    }

    public function test_getuseragentdata_exception() {
        $this->setExpectedException('ErrorException');
        $this->p->getUserAgentData();
    }

    public function test_get_user_agent_allow_exception() {
        $this->setExpectedException('ErrorException');
        $this->p->getUserAgentAllow();
    }

    public function test_get_user_agent_disallow_exception() {
        $this->setExpectedException('ErrorException');
        $this->p->getUserAgentDisallow();
    }

    public function test_get_meta_exception() {
        $this->setExpectedException('ErrorException');
        $this->p->getMeta();
    }
}