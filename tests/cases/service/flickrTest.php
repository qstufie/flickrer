<?php
/**
 * service test
 *
 */
require_once __DIR__ . '/../header.php';

class TestServiceFlickr extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Flickrer\Service\Flickr
     */
    protected $service;

    public function tearDown()
    {
        $this->service = null;
        \Flickrer\Dao\Search::singleton()->query('truncate table recent_searches;');
        parent::tearDown();
    }

    public function setUp()
    {
        $this->service = \Flickrer\Service\Flickr::singleton();
        parent::setUp();
    }

    public function testAddSearch()
    {
        // need to fake session as local doesn't have it
        $s = $this->service->addSearch(['text' => 'test'], 1);
        $this->assertInstanceOf('\Flickrer\Model\Search', $s);

        // also, by user id 1, it should get this.
        $searches = $this->service->getSearches(1);
        $this->assertEquals(count($searches), 1);
        $this->assertEquals('test', current($searches['element'])['_text']);
    }

    public function testSearch()
    {
        $results = $this->service->search(['text' => 'ipad pro'], 1);
        // data can't be null
        $this->assertNotNull($results['images'], $results['meta'], $results['recent_searches']);
        // item count must match
        $this->assertEquals(count($results['images']['element']), \Flickrer\App::getSetting('items_per_page'));

        // then we add a few searches and do it again.
        $this->service->addSearch(['text' => 'test'], 1);
        $this->service->addSearch(['text' => 'test again'], 1);

        $results = $this->service->search(['text' => 'ipad pro'], 1);
        $this->assertNotNull($results['images']['element'], $results['meta'], $results['recent_searches']);
        // then photos must be 12 already (we know ipad pro is popular)
        $this->assertEquals(count($results['images']['element']), \Flickrer\App::getSetting('items_per_page'));
        $this->assertEquals(count($results['recent_searches']['element']), 3); // why it's 3, cos we just added one :D

    }

}
