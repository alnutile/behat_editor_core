<?php namespace BehatAppTests\Tests;

use BehatApp\BehatHelper;
use Symfony\Component\Filesystem\Filesystem;

class BehatHelperTest extends BehatBaseTests {

    public $textTest;

    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
        $files = new Filesystem();
        if($files->exists($this->destination)) {
            $files->remove($this->destination);
        }
    }

    public function testloadTestFromProject()
    {
        $this->behatHelper
            ->setProjectHash($this->project->hash)
            ->setRootHashFolder();
        $this->destination  = $this->behatHelper->getRootHashFolder();
        $output             = $this->behatHelper->loadTestFromProject('test1.feature');
        $this->assertEquals('test1.feature', $output['name']);
        $this->assertEquals($this->shouldBe(), $output['content'], "Content does not match the test file");
    }

    public function testgetFileInfo()
    {
        $this->project          = $this->makeProject();
        $this->behatHelper
            ->setProjectHash($this->project->hash)
            ->createPath()
            ->copyTemplateFilesOver($this->getHash());
        $this->behatYml->writeBehatYmlFile();
        $project = $this->storage_path . '/behat/' . $this->project->hash . '/features/';
        $output = $this->behatHelper->getFileInfo('test1.feature', $project);
        $this->assertEquals('test1.feature', $output['name']);
        $this->assertEquals($this->shouldBe(), $output['content'], "Content does not match the test file");
    }

    public function testreplaceDashWithDots()
    {
        $filename_should_be     = "test1.feature";
        $this->assertEquals($filename_should_be, $this->behatHelper->replaceDashWithDots('test1_feature'));
    }

    public function testreplaceDotsWithDashes()
    {
        $filename_should_be     = "test1_feature";
        $this->assertEquals($filename_should_be, $this->behatHelper->replaceDotsWithDashes('test1.feature'));
    }

    public function testgetBaseBinPath()
    {
        $should_be = $this->app_base . '/vendor/bin/';
        $this->assertEquals($should_be, $this->behatHelper->getBaseBinPath());
    }

    public function testgetBaseYmlPath()
    {
        $this->project          = $this->makeProject();
        $this->behatHelper->setProjectHash($this->project->hash)->setBehatYmlPath();
        $shouldBe               = $this->storage_path . '/behat/' . $this->project->hash . '/behat.yml';
        $this->assertEquals($shouldBe, $this->behatHelper->getBehatYmlPath($this->project->hash));
    }

    public function testgetBootstrapPath()
    {
        $this->project          = $this->makeProject();
        $shouldBe               = $this->storage_path . '/behat/' . $this->project->hash . '/features/bootstrap';
        $this->behatHelper->setProjectHash($this->project->hash)->setBootstrapPath();
        $this->assertEquals($shouldBe, $this->behatHelper->getBootstrapPath());
    }

    public function testgetFeaturePathWithFeatureFileName()
    {
        $this->project          = $this->makeProject();
        $shouldBe               = $this->storage_path . '/behat/' . $this->project->hash . '/features/bootstrap/FeatureContext.php';
        $this->behatHelper->setProjectHash($this->project->hash)->setFeaturePathWithFeatureFileName();
        $this->assertEquals($shouldBe, $this->behatHelper->getFeaturePathWithFeatureFileName());
    }

    public function testgetTestPath()
    {
        $this->project          = $this->makeProject();
        $shouldBe               = $this->storage_path . '/behat/' . $this->project->hash . '/features';
        $this->behatHelper->setProjectHash($this->project->hash)->setTestPath();
        $this->assertEquals($shouldBe, $this->behatHelper->getTestPath());
    }

    public function testgetRootHashFolder()
    {
        $this->project          = $this->makeProject();
        $shouldBe               = $this->storage_path . '/behat/' . $this->project->hash;
        $this->behatHelper->setProjectHash($this->project->hash);
        $this->behatHelper->setRootHashFolder();
        $this->assertEquals($shouldBe, $this->behatHelper->getRootHashFolder());
    }
}