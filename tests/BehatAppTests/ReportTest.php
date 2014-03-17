<?php  namespace BehatAppTests;

use BehatApp\Report;
use BehatApp\ReportRepository;
use BehatApp\ReportFactory;
use BehatApp\InMemoryPersistence;

class ReportTest extends BaseReportsTest {

    public $report;

    public function setUp()
    {
        $this->report = new Report();
    }

    public function testItCallsThePersistenceWhenAddingAReport()
    {
        $reportRepository = new ReportRepository();
        $reportDefaults         = ReportRepository::dataArray();
        $reportData = array_merge($reportDefaults, array('site_id' => 10, 'name' => 'testp.feature'));
        $report = (new ReportFactory())->make($reportData);

        $reportRepository->add($report);
    }

    function testAPersistedReportCanBeRetrievedFromTheGateway() {

        $persistanceGateway = \Mockery::mock('BehatApp\Persistence');

        $reportRepository       = new ReportRepository($persistanceGateway);
        $reportDefaults         = ReportRepository::dataArray();
        $reportData             = array('site_id' => 10, 'test_name' => 'testp.feature');
        $reportData             = array_merge($reportDefaults, $reportData);
        $persistanceGateway->shouldReceive('persist')->once()->with($reportData);
        $persistanceGateway->shouldReceive('retrieve')->once()->with(0)->andReturn($reportData);
        $report                 = (new ReportFactory())->make($reportData);
        $reportRepository->add($report);
        $this->assertEquals($reportData, $persistanceGateway->retrieve(0));
    }


    function testCanAddMultiple()
    {
        $persistanceGateway = \Mockery::mock('BehatApp\Persistence');

        $reportRepository       = new ReportRepository($persistanceGateway);
        $reportDefaults         = ReportRepository::dataArray();

        $reportData             = array('site_id' => 10, 'test_name' => 'testp.feature');
        $reportData             = array_merge($reportDefaults, $reportData);

        $reportData2            = array('site_id' => 11, 'test_name' => 'test2.feature');
        $reportData2            = array_merge($reportDefaults, $reportData2);

        $persistanceGateway->shouldReceive('persist')->once()->with($reportData);
        $persistanceGateway->shouldReceive('persist')->once()->with($reportData2);

        $persistanceGateway->shouldReceive('retrieve')->once()->with(0)->andReturn($reportData);
        $persistanceGateway->shouldReceive('retrieve')->once()->with(1)->andReturn($reportData2);

        $report                 = (new ReportFactory())->make($reportData);
        $report2                = (new ReportFactory())->make($reportData2);

        $reportRepository->add(array($report, $report2));
        $this->assertEquals($reportData, $persistanceGateway->retrieve(0));
        $this->assertEquals($reportData2, $persistanceGateway->retrieve(1));
    }
}