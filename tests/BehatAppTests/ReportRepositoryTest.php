<?php  namespace BehatAppTests;

use BehatApp\InMemoryPersistence;
use BehatApp\Report;
use BehatApp\ReportRepository;
use BehatApp\ReportFactory;

class ReportRepositoryTest extends BaseReportsTest {

    public $report;

    public function setUp()
    {
        parent::setUp();
        $this->report = new Report();
    }

    public function testReportReturnsId()
    {
        $testReport = $this->report;
        $testReport->setRid(10);
        $this->assertEquals(10, $testReport->getRid());
        $testReport = new Report([20, 'test.feature', 'test', 'os']);
        $testReport->setOs("IE");
        $this->assertEquals('IE', $testReport->getOs());
    }

    public function testItCanFindAllReports()
    {
        $this->persistanceGateway = new InMemoryPersistence();
        $this->repo = new ReportRepository($this->persistanceGateway);
        $report1 = (new ReportFactory())->make($this->report1);
        $report2 = (new ReportFactory())->make($this->report2);
        $this->repo->add($report1);
        $this->repo->add($report2);
        $this->assertEquals(array($report1, $report2), $this->repo->findAll());
    }

    public function testIfCanFindAllBySiteID()
    {
        $this->persistanceGateway = new InMemoryPersistence();
        $this->repo = new ReportRepository($this->persistanceGateway);
        $report1 = (new ReportFactory())->make($this->report1);
        $report2 = (new ReportFactory())->make($this->report2);
        $this->repo->add($report1);
        $this->repo->add($report2);
        $this->assertEquals(array($report1), $this->repo->findBySiteId('10'));
    }

    public function testIfCanFindAllByTestName()
    {
        $this->persistanceGateway = new InMemoryPersistence();
        $this->repo = new ReportRepository($this->persistanceGateway);
        $report1 = (new ReportFactory())->make($this->report1);
        $report2 = (new ReportFactory())->make($this->report2);
        $this->repo->add($report1);
        $this->repo->add($report2);
        $this->assertEquals(array($report1), $this->repo->findBy('TestName', 'testp.feature'));
    }

    public function testIfCanFindAllByTestNameAndSiteId()
    {
        $this->persistanceGateway = new InMemoryPersistence();
        $this->repo = new ReportRepository($this->persistanceGateway);
        $report1 = (new ReportFactory())->make($this->report1);
        $report2 = (new ReportFactory())->make($this->report2);
        $this->repo->add($report1);
        $this->repo->add($report2);
        $this->assertEquals(array($report1), $this->repo->findBySiteIdAndTestName('10', 'testp.feature'));
        $this->assertEmpty($this->repo->findBySiteIdAndTestName('10', 'testp.featureTESTTEST'));
    }

    public function testItCanFindByReportId()
    {
        $this->persistanceGateway = new InMemoryPersistence();
        $this->repo = new ReportRepository($this->persistanceGateway);
        $report1 = (new ReportFactory())->make($this->report1);
        $report2 = (new ReportFactory())->make($this->report2);
        $this->repo->add($report1);
        $this->repo->add($report2);
        $this->assertEquals(array($report1), $this->repo->findBy('Rid', 1));
        $this->assertEmpty($this->repo->findBy('Rid', 100));
    }

    public function testIfItCanDeleteUsingReportId()
    {
        $this->persistanceGateway = new InMemoryPersistence();
        $this->repo = new ReportRepository($this->persistanceGateway);
        $report1 = (new ReportFactory())->make($this->report1);
        $this->repo->add($report1);
        $this->assertEquals(array($report1), $this->repo->findBy('Rid', 1));
        $this->repo->delete(1);
        $this->assertEmpty($this->repo->findBy('Rid', 1));
    }

    public function testDeleteManyByReportId()
    {
        $this->persistanceGateway = new InMemoryPersistence();
        $this->repo = new ReportRepository($this->persistanceGateway);
        $report1 = (new ReportFactory())->make($this->report1);
        $report2 = (new ReportFactory())->make($this->report2);
        $this->repo->add($report1);
        $this->repo->add($report2);
        $this->assertEquals(array($report1), $this->repo->findBy('Rid', 1));
        $this->repo->deleteMany([1,2]);
        $this->assertEmpty($this->repo->findBy('Rid', 1));
        $this->assertEmpty($this->repo->findBy('Rid', 2));
    }

}