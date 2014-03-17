<?php namespace BehatAppTests;

use BehatApp\ReportRepository;
use BehatApp\ReportFactory;
use Mockery as m;

class BaseReportsTest extends \PHPUnit_Framework_TestCase {

    public $report1;
    public $repo;
    public $report2;
    public $persistanceGateway;

    public function setUp()
    {
        $this->persistanceGateway     = m::mock('BehatApp\Persistence');
        $this->repo             = new ReportRepository($this->persistanceGateway);

        $reportDefaults         = ReportRepository::dataArray();

        $this->report1          = array_merge($reportDefaults, array('site_id' => 10, 'test_name' => 'testp.feature'));

        $this->report2          = array_merge($reportDefaults, array('site_id' => 11, 'test_name' => 'test2.feature'));
    }

    public function tearDown()
    {
        m::close();
    }

    public function testTest()
    {

    }

}