<?php  namespace BehatAppTests\Tests;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use BehatApp\BehatHelper;
use BehatApp\BehatYml;
use BehatApp\BehatFeatureModel;
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
    public $testBehatYmlFile;
    public $model;
    public $filesystem;
    public $destination;
    public $finder;

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
        $this->testBehatYmlFile = '/tmp/behatYml/behat.yml';
        $this->storage_path     = $this->app_base . '/storage';
        $this->formatter        = new BehatFormatter();
        $this->behatHelper      = new BehatHelper();
        $this->behatHelper
            ->setStoragePath($this->storage_path)
            ->setAppPath($this->app_base);
        $this->model            = new BehatFeatureModel();
        $this->behatYml         = new BehatYml(null, null, null, $this->behatHelper);
        $this->yaml             = new Yaml();
        $this->filesystem       = new Filesystem();
        $this->finder           = new Finder();
        $this->project          = $this->makeProject();
        $this->behatHelper->setBehatYmlPath($this->templateBehatYml);
        $this->behatYml->setDestination($this->testBehatYmlFile);

        if(!$this->filesystem->exists('/tmp/behatYml')) {
            $this->filesystem->mkdir('/tmp/behatYml');
        }
        if(!$this->filesystem->exists('/tmp/testTemplateCopy')) {
            $this->filesystem->mkdir('/tmp/testTemplateCopy');
        }
        $this->filesystem->copy($this->templateBehatYml, $this->testBehatYmlFile);
    }

    public function makePlainTextTest()
    {
        $test = <<<HEREDOC
@example
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

    public function makeHtmlTestOutput()
    {
        $out = <<<HEREDOC
@example<br>Feature: ls<br>&nbsp;&nbsp;  Scenario: List files in directory<br>&nbsp;&nbsp;&nbsp;&nbsp;    Given I am in a directory "tmp"<br>&nbsp;&nbsp;&nbsp;&nbsp;    And I have a file named "foo"<br>&nbsp;&nbsp;&nbsp;&nbsp;    And I have a file named "bar"<br>&nbsp;&nbsp;&nbsp;&nbsp;    When I run "ls"<br>&nbsp;&nbsp;&nbsp;&nbsp;    Then I should get:<br>&nbsp;&nbsp;&nbsp;&nbsp;    """<br>&nbsp;&nbsp;&nbsp;&nbsp;    bar<br>&nbsp;&nbsp;&nbsp;&nbsp;    foo<br>&nbsp;&nbsp;&nbsp;&nbsp;    """<br>
HEREDOC;
        return $out;
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
        if($this->filesystem->exists('/tmp/behatYml')) {
            $this->filesystem->remove('/tmp/behatYml');
        }
        if($this->filesystem->exists('/tmp/testTemplateCopy')) {
            $this->filesystem->chmod('/tmp/testTemplateCopy', 0777);
            $this->filesystem->remove('/tmp/testTemplateCopy');
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