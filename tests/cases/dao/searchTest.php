<?php
/**
 * pdo adaptor
 *
 */
require_once __DIR__ . '/../header.php';

/**
 * test base
 */
class TestDaoSearch extends PHPUnit_Framework_TestCase
{
    /**
     * dao base
     * @var \Flickrer\Dao\Search
     */
    protected $dao;

    public function tearDown()
    {
        // delete all
        $this->dao->query('truncate table recent_searches;');
        // remove object
        $this->dao = null;
        parent::tearDown();

    }

    public function setUp()
    {
        $this->dao = \Flickrer\Dao\Search::singleton();
        parent::setUp();
    }

    public function testAddSearch()
    {
        $uid = 1;
        $params = ['text' => 'ipad pro'];
        $s = $this->dao->addSearch($uid, $params);
        $this->assertInstanceOf('\Flickrer\Model\Search', $s);
        // id must be valid
        $this->assertTrue($s->get('id') > 0);
    }

    public function testNoRepetitiveEntries()
    {
        $uid = 1;
        $params = ['text' => 'ipad pro'];
        $this->dao->addSearch($uid, $params);

        // again - it should be null
        $s = $this->dao->addSearch($uid, $params);
        $this->assertNull($s);
    }

    public function testGetSearches()
    {
        // 1st. insert a few
        $uid = 1;
        $this->dao->addSearch($uid, ['text' => 'yo']);
        $this->dao->addSearch($uid, ['text' => 'hello']);
        $this->dao->addSearch($uid, ['text' => 'world']);

        // get them all
        $searches = $this->dao->getSearchesByUserId($uid);
        $this->assertEquals(count($searches), 3);
        // also data must be valid
        foreach ($searches as $s) {
            $this->assertInstanceOf('\Flickrer\Model\Search', $s);
        }

    }

}
