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
        $this->assertFileExists($this->testBehatYmlFile, "Setup did not set tmp yml file");
        $this->behatYml->setBehatYmlFileFullPath($this->testBehatYmlFile);
        $output = $this->behatYml->parseBehatYmlFile()->getYmlArray();
        $this->assertArrayHasKey('default', $output);
    }

    public function testsetYmlFeaturePath()
    {
        $this->behatYml->setBehatYmlFileFullPath($this->testBehatYmlFile)
            ->parseBehatYmlFile();
        $this->behatYml->setYmlFeaturePath('/test/test');
        $this->assertContains('/test/test', $this->behatYml->getYmlArray()['default']['paths']['features']);
    }

    public function testupdateBaseUrl()
    {
        $this->behatYml->setBehatYmlFileFullPath($this->testBehatYmlFile)
            ->parseBehatYmlFile();
        $this->behatYml->updateBaseUrl('http://example.com');
        $this->assertContains('http://example.com', $this->behatYml->getYmlArray()['default']['extensions']["Behat\MinkExtension\Extension"]['base_url']);
    }

    public function testsetYmlBootStrapPath()
    {
        $this->behatYml->setBehatYmlFileFullPath($this->testBehatYmlFile)
            ->parseBehatYmlFile();
        $this->behatYml->setYmlBootStrapPath('/test/test');
        $this->assertContains('/test/test', $this->behatYml->getYmlArray()['default']['paths']['bootstrap']);
    }

    public function testwriteBehatYmlFiles()
    {
        $timestamp          = time();
        $this->behatYml->setBehatYmlFileFullPath($this->testBehatYmlFile)
            ->parseBehatYmlFile();
        $this->destination        = "/tmp/behat_yml_" . $timestamp . ".yml";
        $this->behatYml->setDestination($this->destination);
        $this->behatYml->writeBehatYmlFile();

        $new_file = $this->behatYml->setBehatYmlFileFullPath($this->destination)
            ->parseBehatYmlFile();
        $this->assertArrayHasKey('default', $new_file->getYmlArray());
    }



}