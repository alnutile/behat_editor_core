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

    public function delete($report_id)
    {
        return $this->persistence->delete($report_id);
    }

    public function deleteMany(array $report_ids)
    {
        foreach ($report_ids as $value) {
            $this->persistence->delete($value);
        }
    }

    public function findBySiteId($id)
    {
        return array_filter($this->findAll(), function($report) use ($id) {
            return $report->getSiteId() == $id;
        });
    }

    /**
     * @param $method the key name as it comes out of ReportRepository::dataArray made camelCase from snake
     * @param $value the value you are looking for
     * @return array
     */
    public function findBy($method, $value)
    {
        return array_filter($this->findAll(), function($report) use ($value, $method) {
            $method = "get$method";
            return $report->$method() == $value;
        });
    }

    public function findBySiteIdAndTestName($id, $name)
    {
        $allFound = $this->persistence->retrieveBySiteIdAndTestName($id, $name);
        $reports = array();
        foreach ($allFound as $reportData) {
            $reports[] = $this->reportFactory->make($reportData);
        }
        return $reports;
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