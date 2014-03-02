<?php  namespace BehatAppTests;

use Symfony\Component\Finder;
use Symfony\Component\Filesystem;
use BehatApp\BehatHelper;
use BehatApp\BehatYml;
use Symfony\Component\Yaml;
use BehatApp\BehatFormatter;

class BaseTests extends \PHPUnit_Framework_TestCase
{
    public $behatApp;
    public $project;
    public $hash;
    public $yaml;
    public $behatYml;
    public $app;

    public function __construct()
    {
        $this->setUp();
    }

    public function testHereStart()
    {
        var_dump("Here");
    }

    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    public function getHash()
    {
        return $this->hash;
    }

    public function setUp()
    {
        $this->formatter        = new BehatFormatter();
        $this->behatApp         = new BehatHelper();
        $this->behatYml         = new BehatYml();
        $this->yaml             = new Yaml();
        $this->project          = $this->makeProject();
    }

    public function makePlainTextTest()
    {
        $test = <<<HEREDOC
@example \n
Feature: Test P 2 HTML
  Scenario: Test 1
    Given I am on "/test"
    Then I should see "test"
HEREDOC;
        return $test;
    }

    public function tearDown()
    {
        $files = new Filesystem();
        if($files->exists($this->behatApp->getRootHashFolder($this->getHash()))) {
            $files->remove($this->behatApp->getRootHashFolder($this->getHash()));
        }
    }

    public function makeProject()
    {
        $dateTime               = new \DateTime('now');
        $dateTime               = $dateTime->format('Y-m-d H:i:s');

        $this->hash             = $this->behatApp->makeHash();
        $project                = new \stdClass();
        $project->name          = 'admin';
        $project->description   = "Some text";
        $project->hash          = $this->hash;
        $project->active        = 1;
        $project->created_at    = $dateTime;
        $project->updated_at    = $dateTime;
        $this->project = $project;

        return $this->project;
    }
}