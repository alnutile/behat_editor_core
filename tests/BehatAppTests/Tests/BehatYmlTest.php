<?php namespace BehatAppTests\Tests;

use Symfony\Component\Filesystem\Filesystem;
class BehatYmlTest extends BehatBaseTests {

    public $destination;

    public function tearDown()
    {
        parent::tearDown();
        $files = new Filesystem();
        if($files->exists($this->destination)) {
            $files->remove($this->destination);
        }
    }

    public function testgetBehatYmlFile()
    {
        $behat_yml = $this->templateBehatYml;
        $this->behatYml->setBehatYmlFileFullPath($behat_yml);
        $output = $this->behatYml->parseBehatYmlFile();
        $this->assertArrayHasKey('default', $output->behatYml);
    }

    public function testsetYmlFeaturePath()
    {
        $behat_yml_path = $this->templateBehatYml;
        $this->behatYml->setBehatYmlFileFullPath($behat_yml_path);
        $this->behatYml->setYmlFeaturePath('/test/test');
        $this->assertContains('/test/test', $this->behatYml->getYmlArray()['default']['paths']['features']);
    }


    public function testsetYmlBootStrapPath()
    {
        $behat_yml_path = $this->templateBehatYml;
        $this->behatYml->setBehatYmlFileFullPath($behat_yml_path);
        $this->behatYml->setYmlBootStrapPath('/test/test');
        $this->assertContains('/test/test', $this->behatYml->getYmlArray()['default']['paths']['bootstrap']);
    }

    public function testwriteBehatYmlFiles()
    {
        $timestamp          = time();
        $behat_yml_path     = $this->templateBehatYml;
        $this->behatYml->setBehatYmlFileFullPath($behat_yml_path)
            ->parseBehatYmlFile();
        $this->destination        = "/tmp/behat_yml_" . $timestamp . ".yml";
        $this->behatYml->setDestination($this->destination);
        $this->behatYml->writeBehatYmlFile();

        $new_file = $this->behatYml->setBehatYmlFileFullPath($this->destination)
            ->parseBehatYmlFile();
        $this->assertArrayHasKey('default', $new_file->getYmlArray());
    }

}