<?php namespace BehatApp;

use BehatApp\Persistence;
use BehatApp\InMemoryPersistenceTest;
use BehatApp\ReportFactory;

class ReportRepository  {

    protected $persistence;

    function __construct(Persistence $persistence = null)
    {
        $this->persistence      = $persistence ? : new InMemoryPersistence();
        $this->reportFactory    = new ReportFactory();

    }

    public function add($report)
    {
        if (is_array($report)) {
            foreach($report as $r) {
                $this->addOne($r);
            }
        } else {
            $this->addOne($report);
        };
    }

    private function addOne(Report $report) {
        $this->persistence->persist(array(
            'rid'           => $report->getRid(),
            'site_id'       => $report->getSiteId(),
            'test_name'     => $report->getTestName(),
            'results'       => $report->getResults(),
            'duration'      => $report->getDuration(),
            'created'       => $report->getCreated(),
            'status'        => $report->getStatus(),
            'user_id'       => $report->getUserId(),
            'settings'      => $report->getSettings(),
            'browser'       => $report->getBrowser(),
            'os'            => $report->getOs(),
            'tags'          => $report->getTags()
        ));
    }

    public function findAll()
    {
        $allReports = $this->persistence->retrieveAll();

        $reports = array();
        foreach ($allReports as $reportData) {
            $reports[] = $this->reportFactory->make($reportData);
        }
        return $reports;

    }

    public function findBySiteId($id)
    {
        return array_filter($this->findAll(), function($report) use ($id) {
            return $report->getSiteId() == $id;
        });
    }

    /**
     * Quick way to get array format of data
     */
    static public function dataArray()
    {
        $rid = 0;
        $site_id = 0;
        $test_name = 'test.feature';
        $results = "Test results here";
        $duration = "";
        $created = 0;
        $status = 0;
        $user_id = 0;
        $settings = array();
        $browser = 'Chrome';
        $os = "Linux";
        $tags = "@javascript";
        $data = compact('rid', 'test_name', 'site_id', 'results', 'duration', 'created', 'status', 'user_id', 'settings', 'browser', 'os', 'tags');

        return $data;
    }
}