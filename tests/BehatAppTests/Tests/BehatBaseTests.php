<?php  namespace BehatAppTests\Tests;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use BehatApp\BehatHelper;
use BehatApp\BehatYml;
use Symfony\Component\Yaml\Yaml;
use BehatApp\BehatFormatter;

class BehatBaseTests extends \PHPUnit_Framework_TestCase
{
    public $project;
    public $hash;
    public $yaml;
    public $behatYml;
    public $behatHelper;
    public $app;
    public $app_base;
    public $formatter;
    public $storage_path;
    public $templateFiles;
    public $templateBehatYml;

    public function __construct()
    {
        $this->setUp();
    }

    public function testHereStart()
    {

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
        $current_base           = __DIR__;
        $current_base_array     = explode("/", $current_base);
        $current_base_array     = array_slice($current_base_array, 0, -3);
        $this->app_base         = implode("/", $current_base_array);
        $this->templateFiles    = $this->app_base . '/lib/BehatApp/template_files/features/';
        $this->templateBehatYml = $this->app_base . '/lib/BehatApp/template_files/behat.yml';
        $this->storage_path     = $this->app_base . '/storage';
        $this->formatter        = new BehatFormatter();
        $this->behatHelper      = new BehatHelper($this->storage_path, $this->app_base);
        $this->behatYml         = new BehatYml(null, null, null, $this->behatHelper);
        $this->yaml             = new Yaml();
        $this->project          = $this->makeProject();
        $this->behatHelper->setProjectHash($this->project->hash)->createPath()
            ->copyTemplateFilesOver();
        $this->behatHelper->setBehatYmlPath();
        $this->behatYml->setDestination($this->behatHelper->getBehatYmlPath());
        $this->behatYml->writeBehatYmlFile();
    }

    public function makePlainTextTest()
    {
        $test = <<<HEREDOC
Feature: ls
  Scenario: List files in directory
    Given I am in a directory "tmp"
    And I have a file named "foo"
    And I have a file named "bar"
    When I run "ls"
    Then I should get:
    """
    bar
    foo
    """
HEREDOC;
        return $test;
    }

    public function shouldBe()
    {
        return $this->makePlainTextTest();
    }

    public function tearDown()
    {
        $files = new Filesystem();
        if($files->exists($this->storage_path . '/behat')) {
            $files->remove($this->storage_path . '/behat');
        }
    }

    public function makeProject()
    {
        $dateTime               = new \DateTime('now');
        $dateTime               = $dateTime->format('Y-m-d H:i:s');

        $this->hash             = $this->behatHelper->makeHash();
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