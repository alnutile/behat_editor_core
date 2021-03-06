<?php namespace BehatAppTests;

use org\bovigo\vfs\vfsStream;

class BehatFeatureModelTest extends BehatBaseTests {

    protected  $full_path;
    protected $full_path_updated;
    protected $full_path_delete;
    protected $full_path_create;

    /**
     * Why did I have so many paths?
     * Get VFS working had issue with tmp to vfs using Filesystem
     */
    public function setUp()
    {
        parent::setUp();
        $this->root                 = vfsStream::setup('destination');
        $this->full_path_delete     = '/tmp/testDelete';
        $this->full_path_updated    = "/tmp/testUpdate/";
        $this->full_path_create     = "/tmp/testCreate/";
        if($this->filesystem->exists($this->full_path)) {
            $this->filesystem->remove($this->full_path);
        }

    }

    public function tearDown()
    {
        parent::tearDown();
        if($this->filesystem->exists($this->full_path)) {
            $this->filesystem->remove($this->full_path);
        }

        $this->full_path_updated = "/tmp/testUpdate/";
        if($this->filesystem->exists($this->full_path_updated)) {
            $this->filesystem->chmod($this->full_path_updated, $mode = 0777, $umask = 0000, TRUE);
            $this->filesystem->remove($this->full_path_updated);
        }

        if($this->filesystem->exists($this->full_path_delete)) {
            $this->filesystem->chmod($this->full_path_delete, $mode = 0777, $umask = 0000, TRUE);
            $this->filesystem->remove($this->full_path_delete);
        }
        if($this->filesystem->exists($this->full_path_create)) {
            $this->filesystem->chmod($this->full_path_create, $mode = 0777, $umask = 0000, TRUE);
            $this->filesystem->remove($this->full_path_create);
        }
    }

    public function testNewModel()
    {
        $shouldBe   = $this->model->getNewModel();
        $this->assertEquals('Feature: Your Test Name Here', $shouldBe[1]);
    }

    public function testCreate()
    {
        $path = "/tmp/testCreate.feature";

        $content = $this->model->getNewModel();
        $content = implode("\n", $content);

        $this->full_path = $path;
        if($this->filesystem->exists($this->full_path)) {
            $this->filesystem->remove($this->full_path);
        }
        $this->assertFalse($this->filesystem->exists($this->full_path));

        $this->model->create([$content, $this->full_path]);

        $this->assertFileExists($this->full_path, "File not there");

        $contentSaved = file_get_contents($this->full_path);
        $this->assertEquals($content, $contentSaved, "Content does not match");
    }

    /**
     * @expectedException BehatApp\Exceptions\BehatAppException
     */
    public function testCreateFileExistsFail()
    {
        $content = $this->model->getNewModel();
        $content = implode("\n", $content);
        $this->full_path = "/tmp/testExists.feature";
        $this->model->create([$content, $this->full_path]);
        $this->model->create([$content, $this->full_path]);

    }

    public function testShowAll()
    {
        $root_path              = "/tmp/getAll";
        $this->createMany($root_path);
        $output                 = $this->model->getAll(array($root_path));
        $this->assertCount(4, $output);
    }

    /**
     * @expectedException BehatApp\Exceptions\BehatAppException
     */
    public function testNoRootFolder()
    {
        $root_path              = "/tmp/getAll";
        if($this->filesystem->exists($root_path)) {
            $this->filesystem->remove($root_path);
        }
        $this->model->getAll(array($root_path));
    }

    public function testUpdate()
    {
        $content = $this->model->getNewModel();
        $content = implode("\n", $content);

        $this->full_path_updated = "/tmp/testUpdate/test1.feature";
        if($this->filesystem->exists($this->full_path_updated)) {
            $this->filesystem->remove($this->full_path_updated);
        }
        $this->assertFalse($this->filesystem->exists($this->full_path_updated));

        $this->model->create([$content, $this->full_path_updated]);
        $content = str_replace('Your Test Name Here', 'Your Test is Updated', $content);

        $this->model->update([$content, $this->full_path_updated]);
        $contentSaved = file_get_contents($this->full_path_updated);
        $this->assertContains('Your Test is Updated', $contentSaved, "File not updated");
    }

    public function testUpdateFailDoesNotExist()
    {
        $this->full_path_updated = "/tmp/testUpdate/testFail.feature";
        $output = $this->model->update(["Test Test", $this->full_path_updated]);
        $this->assertEquals('1', $output['error']);
        $this->assertEquals('Missing Feature Test Test', $output['message']);
    }

    public function testUpdateFailCanNotWrite()
    {
        $this->full_path_updated = "/tmp/testUpdate";
        if(!$this->filesystem->exists($this->full_path_updated)) {
            $this->filesystem->mkdir($this->full_path_updated, $mode = 0777);
        } else {
            $this->filesystem->chmod($this->full_path_updated, $mode = 0777);
        }
        $this->filesystem->dumpFile($this->full_path_updated . '/test1.feature', "Test Delete", $mode = 0444);
        $this->filesystem->chmod($this->full_path_updated, $mode = 0555, $umask = 0000, TRUE);
        $this->assertFileExists($this->full_path_updated . '/test1.feature', "File not there");
        $output = $this->model->update(["Test Test", $this->full_path_updated . '/test1.feature']);
        $this->assertEquals('1', $output['error']);
        $this->assertNotEquals('Update Complete', $output['message']);
    }

    public function testDelete()
    {
        $content = $this->model->getNewModel();
        $content = implode("\n", $content);
        $this->full_path_delete = "/tmp/testDelete";
        if(!$this->filesystem->exists($this->full_path_delete)) {
            $this->filesystem->mkdir($this->full_path_delete);
        }
        $this->model->create([$content, $this->full_path_delete . '/test1.feature']);
        $this->assertFileExists($this->full_path_delete . '/test1.feature', "File not there");
        $this->model->delete($this->full_path_delete . '/test1.feature');
        $this->assertFileNotExists($this->full_path_delete . '/test1.feature', "File still there");
    }


    /**
     * @expectedException BehatApp\Exceptions\BehatAppException
     */
    public function testDeleteErrorFileNotFeature()
    {
        $this->full_path_delete = "/tmp/testDelete";
        if(!$this->filesystem->exists($this->full_path_delete)) {
            $this->filesystem->mkdir($this->full_path_delete);
        }
        $this->filesystem->dumpFile($this->full_path_delete . '/test1.featureTEST', "Test Delete");
        $this->assertFileExists($this->full_path_delete . '/test1.featureTEST', "File not there");
        $this->model->delete($this->full_path_delete . '/test1.featureTEST');
    }

    /**
     * @expectedException BehatApp\Exceptions\BehatAppException
     */
    public function testDeleteErrorNoPermissionsToWriteToFolder()
    {
        $this->full_path_delete = "/tmp/testDelete";
        if(!$this->filesystem->exists($this->full_path_delete)) {
            $this->filesystem->mkdir($this->full_path_delete, $mode = 0777);
        } else {
            $this->filesystem->chmod($this->full_path_delete, $mode = 0777);
        }
        $this->filesystem->dumpFile($this->full_path_delete . '/test1.feature', "Test Delete");
        $this->assertFileExists($this->full_path_delete . '/test1.feature', "File not there");
        $this->filesystem->chmod($this->full_path_delete, $mode = 0555, $umask = 0000, $recursive = TRUE);
        $this->model->delete($this->full_path_delete . '/test1.feature');
    }

    /**
     * @expectedException BehatApp\Exceptions\BehatAppException
     */
    public function testDeleteErrorNoPermissionsToWriteToDeleteFile()
    {
        $this->full_path_delete = "/tmp/testDelete";
        if(!$this->filesystem->exists($this->full_path_delete)) {
            $this->filesystem->mkdir($this->full_path_delete, $mode = 0777);
        } else {
            $this->filesystem->chmod($this->full_path_delete, $mode = 0777);
        }
        $this->filesystem->dumpFile($this->full_path_delete . '/test1.feature', "Test Delete", $mode = 0444);
        $this->filesystem->chmod($this->full_path_delete, $mode = 0555, $umask = 0000, TRUE);
        $this->assertFileExists($this->full_path_delete . '/test1.feature', "File not there");
        $this->model->delete($this->full_path_delete . '/test1.feature');
    }

    public function testUpdateAll()
    {
        $root_path                  = "/tmp/testUpdate";
        $this->createMany($root_path);
        $test_update_content        =   "Test Update Feature Scenario Given I am";
        $this->assertFileExists($this->full_path4, "Create many failed so can not run this test");
        $this->model->updateMany([[$test_update_content, $this->full_path], [$test_update_content, $this->full_path2], [$test_update_content, $this->full_path3], [$test_update_content, $this->full_path4]]);
        $contentSaved = file_get_contents($this->full_path);
        $this->assertEquals($contentSaved, $test_update_content, "Update many did not work");
        $contentSaved = file_get_contents($this->full_path2);
        $this->assertEquals($contentSaved, $test_update_content, "Update many did not work");
        $contentSaved = file_get_contents($this->full_path3);
        $this->assertEquals($contentSaved, $test_update_content, "Update many did not work");
        $contentSaved = file_get_contents($this->full_path4);
        $this->assertEquals($contentSaved, $test_update_content, "Update many did not work");
    }

    public function testDeleteMany()
    {
        $root_path              = "/tmp/testDelete";
        $this->createMany($root_path);
        $this->assertFileExists($this->full_path4, "Oops setup failed");
        $this->model->deleteMany([$this->full_path, $this->full_path2, $this->full_path3, $this->full_path4]);
        $this->assertFileNotExists($this->full_path, "Delete many did not work");
        $this->assertFileNotExists($this->full_path2, "Delete many did not work");
        $this->assertFileNotExists($this->full_path3, "Delete many did not work");
        $this->assertFileNotExists($this->full_path4, "Delete many did not work");
    }

    public function testFind()
    {
        $path                   = $this->full_path_create;
        $content                = $this->model->getNewModel();
        $content                = implode("\n", $content);
        $this->model->create([$content, $path . "test1.feature"]);
        $this->assertFileExists($path . "test1.feature", "File not there can not test view");
        $found                  = $this->model->get([$path, "test1.feature"]);
        $this->assertCount(1, $found);
        foreach($found as $file) {
            $this->assertEquals("test1.feature", $file->getFilename());
        }
    }

    /**
     * @expectedException BehatApp\Exceptions\BehatAppException
     */
    public function testViewFailDirNotFound()
    {
        $path                   = $this->full_path_create;
        $found                  = $this->model->get([$path, "test1.feature"]);
    }

    /**
     * @expectedException BehatApp\Exceptions\BehatAppException
     */
    public function testViewFailFileNotFound()
    {
        $path                   = $this->full_path_create;
        $this->filesystem->mkdir($path);
        $found                  = $this->model->get([$path, "NOTTHERE.feature"]);
    }

    /**
     * @expectedException BehatApp\Exceptions\BehatAppException
     */
    public function testFailGetNonFeatureFile()
    {
        $path                   = $this->full_path_create;
        $this->filesystem->mkdir($path);
        $found                  = $this->model->get([$path, "test1.TEST"]);
    }

    public function testfindByTag()
    {
        $this->createMany($this->full_path_create);
        $found                  = $this->model->findByTag([$this->full_path_create, "@example"]);
        $this->assertCount(4, $found);
    }


    public function testfindByTagOnly3()
    {
        $this->createMany($this->full_path_create);
        $found                  = $this->model->findByTag([$this->full_path_create, "@example"]);
        file_put_contents($this->full_path2, "Nothing here to see");
        $this->assertCount(3, $found);
    }

    public function testfindByTagOnly4IgnoringFileWithNoFeatureName()
    {
        $this->createMany($this->full_path_create);
        $found                  = $this->model->findByTag([$this->full_path_create, "@example"]);
        file_put_contents($this->full_path_create . '/testNOTHERE', "Nothing here to see");
        $this->assertCount(4, $found);
    }

    /**
     * multiple tags
     */
    public function testFindByTags()
    {

    }

    public function testValidationTextGivenIAmOnFail()
    {
        $content_bad                = "Feature Scenario";
        $output = $this->model->validate($content_bad);
        $this->assertEquals('Missing Given I am on Feature Scenario', $output);
    }

    public function testValidationTextFeatureFail()
    {
        $content_bad                = "Test Test";
        $output = $this->model->validate($content_bad);
        $this->assertEquals('Missing Feature Test Test', $output);
    }

    public function testValidationTextFeatureFailMoreThanOneFeature()
    {
        $content_bad                = "Feature Feature Scenario Test Test";
        $output = $this->model->validate($content_bad);
        $this->assertEquals('Feature is in test more than once Feature Feature Scenario Test Test', $output);
    }

    public function testValidationTextFeaturePass()
    {
        $content_good               = $this->makePlainTextTest();
        $this->assertFalse($this->model->validate($content_good));
    }

    public function testValidationTextScenarioFail()
    {
        $content_bad                = "Feature Test Test";
        $output = $this->model->validate($content_bad);
        $this->assertEquals('Missing Scenario Feature Test Test', $output);
    }

    public function testValidationTextScenarioPass()
    {
        $content_good               = $this->makePlainTextTest();
        $this->model->validate($content_good);
    }

    protected function createMany($root_path)
    {
        $content                = $this->model->getNewModel();
        $content                = implode("\n", $content);
        if($this->filesystem->exists($root_path)) {
            $this->filesystem->remove($root_path);
            $this->filesystem->mkdir($root_path);
        }

        if(!$this->filesystem->exists($root_path)) {
            $this->filesystem->mkdir($root_path);
        }
        $this->full_path        = "$root_path/testExists1.feature";
        $this->full_path2       = "$root_path/testExists2.feature";
        $this->full_path3       = "$root_path/testExists3.feature";
        $this->full_path4       = "$root_path/testExists4.feature";
        $this->model->create([$content, $this->full_path]);
        $this->model->create([$content, $this->full_path2]);
        $this->model->create([$content, $this->full_path3]);
        $this->model->create([$content, $this->full_path4]);
    }



}