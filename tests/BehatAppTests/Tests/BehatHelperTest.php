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

    public function testgetBaseYmlPath()
    {
        $this->project          = $this->makeProject();
        $shouldBe               = $this->storage_path . '/behat/' . $this->project->hash . '/behat.yml';
        $this->behatHelper->setBehatYmlPath($shouldBe);
        $this->assertEquals($shouldBe, $this->behatHelper->getBehatYmlPath($this->project->hash));
    }

    public function testgetBootstrapPath()
    {
        $this->project          = $this->makeProject();
        $shouldBe               = $this->storage_path . '/behat/' . $this->project->hash . '/features/bootstrap';
        $this->behatHelper->setBootstrapPath($shouldBe);
        $this->assertEquals($shouldBe, $this->behatHelper->getBootstrapPath());
    }

    public function testgetFeaturePathWithFeatureFileName()
    {
        $this->project          = $this->makeProject();
        $shouldBe               = $this->storage_path . '/behat/' . $this->project->hash . '/features/bootstrap/FeatureContext.php';
        $this->behatHelper->setFeaturePathWithFeatureFileName($shouldBe);
        $this->assertEquals($shouldBe, $this->behatHelper->getFeaturePathWithFeatureFileName());
    }

    public function testGetAppPathDefault()
    {
        $this->assertEquals($this->app_base, $this->behatHelper->getAppPath());
    }

    public function testSetAppPath()
    {
        $tmp                = "/tmp/foo";
        $this->behatHelper->setAppPath($tmp);
        $this->assertEquals($tmp, $this->behatHelper->getAppPath());
    }

    public function testGetStoragePathDefault()
    {
        $this->assertEquals($this->storage_path, $this->behatHelper->getStoragePath());
    }

    public function testSetStoragePathDefault()
    {
        $tmp                = "/tmp/foo";
        $this->behatHelper->setStoragePath($tmp);
        $this->assertEquals($tmp, $this->behatHelper->getStoragePath());
    }

    public function testGetYmlPathDefault()
    {
        $this->assertEquals($this->templateBehatYml, $this->behatHelper->getBehatYmlPath());
    }

    public function testGetBootstrapPathDefault()
    {
        $boot               = $this->behatHelper->getBasePath() . '/features/bootstrap/';
        $this->assertEquals($boot, $this->behatHelper->getBootstrapPath());
    }

    public function testgetFeaturePathWithFeatureFileNameDefault()
    {
        $boot               = $this->behatHelper->getBasePath() .  '/features/bootstrap/FeatureContext.php';
        $this->assertEquals($boot, $this->behatHelper->getFeaturePathWithFeatureFileName());
    }

    public function testgetTestPath()
    {
        $this->assertNotNull($this->behatHelper->getTestPath());
    }

    public function testgetTestPathDefault()
    {
        $tests               = $this->behatHelper->getBasePath() .  '/features';
        $this->assertEquals($tests, $this->behatHelper->getTestPath());
    }

    public function testsetTestPath()
    {
        $tmp                = "/tmp/tests";
        $this->behatHelper->setTestPath($tmp);
        $this->assertEquals($tmp, $this->behatHelper->getTestPath());
    }

    public function testTemplateFolderDefaults()
    {
        $def                = $this->behatHelper->getAppPath() . '/lib/BehatApp/template_files/';
        $this->assertEquals($def, $this->behatHelper->getTemplateFolder());
    }

    public function testcopyTemplateFilesOver()
    {
        $dest           = '/tmp/testTemplateCopy';
        $this->behatHelper->copyTemplateFilesOver($dest);
        $this->assertFileExists($dest . '/features/test1.feature', "Template files where not copied over");
    }

    /**
     * @expectedException PHPUnit_Framework_Error_Warning
     */
    public function testcopyTemplateFilesOverWriteError()
    {
        $dest                           = '/tmp/testTemplateCopy';
        $this->filesystem->chmod($dest, 0444);
        $this->behatHelper->copyTemplateFilesOver($dest);
    }

    public function testcopyTemplateFilesOverNotThereError()
    {

    }

    public function testSetBasePath()
    {
        $tmp            = '/tmp/basePath';
        $this->behatHelper->setBasePath($tmp);
        $this->assertEquals($tmp, $this->behatHelper->getBasePath());
    }

    public function testSetBasePathDefault()
    {
        $shouldBe = $this->behatHelper->getAppPath() . '/storage/default/';
        $this->behatHelper->setBasePath();
        $this->assertEquals($shouldBe, $this->behatHelper->getBasePath());
    }

}