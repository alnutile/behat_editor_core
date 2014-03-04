<?php namespace BehatApp\Tests;

use BehatAppTests\Tests\BehatBaseTests;
use org\bovigo\vfs\vfsStream;

class BehatFeatureModelTest extends BehatBaseTests {

    protected  $full_path;
    protected $full_path_updated;

    public function setUp()
    {
        parent::setUp();
        $this->root         = vfsStream::setup('destination');
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
        $this->full_path_updated = "/tmp/testUpdate/test1.feature";
        if($this->filesystem->exists($this->full_path_updated)) {
            $this->filesystem->remove($this->full_path_updated);
        }
    }

    public function testNewModel()
    {
        $shouldBe   = $this->model->getNewModel();
        $this->assertEquals('Feature: Your Feature Here', $shouldBe[1]);
    }

    public function testCreate()
    {
        $content = $this->model->getNewModel();
        $content = implode("\n", $content);

        $this->full_path = "/tmp/testCreate.feature";
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
        $content                = $this->model->getNewModel();
        $content                = implode("\n", $content);
        $root_path              = "/tmp/getAll";
        if($this->filesystem->exists($root_path)) {
            $this->filesystem->remove($root_path);
            $this->filesystem->mkdir($root_path);
        }

        if(!$this->filesystem->exists($root_path)) {
            $this->filesystem->mkdir($root_path);
        }
        $this->full_path        = "/tmp/getAll/testExists1.feature";
        $this->full_path2       = "/tmp/getAll/testExists2.feature";
        $this->full_path3       = "/tmp/getAll/testExists3.feature";
        $this->full_path4       = "/tmp/getAll/testExists4.feature";
        $this->model->create([$content, $this->full_path]);
        $this->model->create([$content, $this->full_path2]);
        $this->model->create([$content, $this->full_path3]);
        $this->model->create([$content, $this->full_path4]);
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
        $content = str_replace('Your Feature Here', 'Your Feature Updated', $content);

        $this->model->update([$content, $this->full_path_updated]);
        $contentSaved = file_get_contents($this->full_path_updated);
        $this->assertContains('Your Feature Updated', $contentSaved, "File not updated");
    }

    /**
     * @expectedException BehatApp\Exceptions\BehatAppException
     */
    public function testUpdateFail()
    {
        $this->full_path_updated = "/tmp/testUpdate/testFail.feature";
        $this->model->create(["Test Test", $this->full_path_updated]);
    }

    public function testDelete()
    {

    }

    public function testView()
    {

    }

    public function testValidation()
    {
        //1. Must have feature
        //2. Must have scenario
        //3. Feature above scenario
        //4. Must have Given I am
    }

    public function testfindByName()
    {

    }

    public function testfindByTags()
    {

    }



}