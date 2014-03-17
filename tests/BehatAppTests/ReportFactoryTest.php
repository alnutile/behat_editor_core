<?php namespace BehatAppTests;

use BehatApp\ReportFactory;

class ReportFactoryTest extends \PHPUnit_Framework_TestCase {

    function testAReportHasAllItsComposingParts() {
        $rid = 10;
        $test_name = 'test.feature';
        $site_id = 30;
        $results = "Test results here";
        $duration = "0h 2m 22s";
        $created = "123456789";
        $status = 1;
        $user_id = 200;
        $settings = array();
        $browser = 'Chrome';
        $os = "Linux";
        $tags = "@javascript";
        $data = compact('rid', 'test_name', 'site_id', 'results', 'duration', 'created', 'status', 'user_id', 'settings', 'browser', 'os', 'tags');
        $report = (new ReportFactory())->make(($data));

        $this->assertEquals($browser, $report->getBrowser());
        $this->assertEquals($os, $report->getOs());
    }
}
 