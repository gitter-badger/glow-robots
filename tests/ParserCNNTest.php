<?php
require_once 'vendor/autoload.php';

class ParserCNNTest extends PHPUnit_Framework_TestCase {
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
        $this->p->setSource(file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'sample_robots' . DIRECTORY_SEPARATOR . 'cnn.txt'));
        $this->p->parse();
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

    public function test_parsed_meta() {
        $meta = $this->p->getMeta();

        $this->assertEquals(is_array($meta), true);
        $this->assertArrayHasKey('size', $meta);
        $this->assertArrayHasKey('user-agents', $meta);
        $this->assertArrayHasKey('counts', $meta);

        $this->assertEquals($meta['size'], 0.94);

        $this->assertArrayHasKey(0, $meta['user-agents']);
        $this->assertEquals($meta['user-agents'][0], '*');

        $this->assertArrayHasKey('user-agent', $meta['counts']);
        $this->assertArrayHasKey('sitemap', $meta['counts']);
        $this->assertArrayHasKey('disallow', $meta['counts']);
        $this->assertArrayHasKey('allow', $meta['counts']);
        $this->assertArrayHasKey('empty-lines', $meta['counts']);
        $this->assertArrayHasKey('skipped-due-to-error', $meta['counts']);
        $this->assertArrayHasKey('lines', $meta['counts']);
        $this->assertEquals('1', $meta['counts']['user-agent']);
        $this->assertEquals('5', $meta['counts']['sitemap']);
        $this->assertEquals('29', $meta['counts']['disallow']);
        $this->assertEquals('0', $meta['counts']['host']);
        $this->assertEquals('1', $meta['counts']['empty-lines']);
        $this->assertEquals('0', $meta['counts']['skipped-due-to-error']);
        $this->assertEquals('37', $meta['counts']['lines']);
    }

    public function test_errors() {
        $errors = $this->p->getErrors();

        $this->assertEquals($errors, array());
    }

    public function test_get_sitemaps() {
        $sitemaps = $this->p->getSitemaps();
        $this->assertCount(5, $sitemaps);
        $this->assertEquals($sitemaps[0], 'http://www.cnn.com/sitemaps/sitemap-index.xml');
        $this->assertEquals($sitemaps[1], 'http://www.cnn.com/sitemaps/sitemap-news.xml');
        $this->assertEquals($sitemaps[2], 'http://www.cnn.com/sitemaps/sitemap-video-index.xml');
        $this->assertEquals($sitemaps[3], 'http://www.cnn.com/sitemaps/sitemap-section.xml');
        $this->assertEquals($sitemaps[4], 'http://www.cnn.com/sitemaps/sitemap-interactive.xml');
    }

    public function test_get_user_agent_disallow() {
        $disallow = $this->p->getUserAgentDisallow();

        $this->assertCount(29, $disallow);
        $this->assertEquals($disallow[0], '/editionssi');
        $this->assertEquals($disallow[1], '/ads');
        $this->assertEquals($disallow[2], '/aol');
        $this->assertEquals($disallow[3], '/audio');
        $this->assertEquals($disallow[4], '/beta');
        $this->assertEquals($disallow[5], '/browsers');
        $this->assertEquals($disallow[6], '/cl');
        $this->assertEquals($disallow[7], '/cnews');
        $this->assertEquals($disallow[8], '/cnn_adspaces');
        $this->assertEquals($disallow[9], '/cnnbeta');
        $this->assertEquals($disallow[10], '/cnnintl_adspaces');
        $this->assertEquals($disallow[11], '/development');
        $this->assertEquals($disallow[12], '/help/cnnx.html');
        $this->assertEquals($disallow[13], '/NewsPass');
        $this->assertEquals($disallow[14], '/NOKIA');
        $this->assertEquals($disallow[15], '/partners');
        $this->assertEquals($disallow[16], '/pipeline');
        $this->assertEquals($disallow[17], '/pointroll');
        $this->assertEquals($disallow[18], '/POLLSERVER');
        $this->assertEquals($disallow[19], '/pr/');
        $this->assertEquals($disallow[20], '/PV');
        $this->assertEquals($disallow[21], '/quickcast');
        $this->assertEquals($disallow[22], '/Quickcast');
        $this->assertEquals($disallow[23], '/QUICKNEWS');
        $this->assertEquals($disallow[24], '/test');
        $this->assertEquals($disallow[25], '/virtual');
        $this->assertEquals($disallow[26], '/WEB-INF');
        $this->assertEquals($disallow[27], '/web.projects');
        $this->assertEquals($disallow[28], '/search');
    }

    public function test_get_user_agent_allow() {
        $allow = $this->p->getUserAgentAllow();

        $this->assertCount(1, $allow);
        $this->assertEquals($allow[0], '/partners/ipad/live-video.json');
    }

    public function test_get_user_agent_data_that_doesnt_exists() {
        $ua_data = $this->p->getUserAgentData('SomeReallyFakeBot');
        $this->assertCount(0, $ua_data);
        $this->assertEquals($ua_data, array());
    }

    public function test_get_user_agent_data_that_does_exists() {
        $ua_data = $this->p->getUserAgentData('*');
        $this->assertEquals(is_array($ua_data), true);
        $this->assertArrayHasKey('allow', $ua_data);
        $this->assertArrayHasKey('disallow', $ua_data);
    }

    public function test_get_user_agent_allow_that_doesnt_exists() {
        $ua_data = $this->p->getUserAgentAllow('SomeFakeUA');
        $this->assertCount(0, $ua_data);
        $this->assertEquals($ua_data, array());
    }

    public function test_get_user_agent_disallow_that_doesnt_exists() {
        $ua_data = $this->p->getUserAgentDisallow('SomeFakeUa');
        $this->assertCount(0, $ua_data);
        $this->assertEquals($ua_data, array());
    }

    public function test_isallowed() {
        $this->assertTrue($this->p->isAllowed('/somegoodurl.php'));
    }

    public function test_isdisallowed() {
        $this->assertTrue($this->p->isDisallowed('/help/cnnx.html'));
    }
}