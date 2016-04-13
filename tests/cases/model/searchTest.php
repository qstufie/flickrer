<?php
/**
 * Search object
 *
 */
require_once __DIR__ . '/../header.php';

class TestModelSearch extends PHPUnit_Framework_TestCase
{
    public function testSearchObject()
    {
        $cleanData = ['user_id' => 1, 'search_params' => ['text' => 'yo', 'tag' => 'ipads']];
        $tainted = $cleanData;
        $tainted['foo'] = 'bar';

        $s1 = new \Flickrer\Model\Search($cleanData);
        $s2 = new \Flickrer\Model\Search($tainted);
        $this->assertEquals($s1->getData(), $s2->getData());

        // test get params must be valid object
        $params = $s2->getSearchParams();
        $this->assertEquals($params, $cleanData['search_params']);

    }
}
