<?php namespace BehatAppTests;

use BehatApp\BehatHelper;

class BehatHelperTest extends BaseTests {

    protected $behatHelper;

    public function setUp()
    {
        parent::setUp();
        $this->behatHelper = new BehatHelper();
    }

    public function testloadTestFromProject()
    {
        $output = $this->behatHelper->loadTestFromProject($this->getHash(), 'test1.feature');
        var_dump($output);
    }


}