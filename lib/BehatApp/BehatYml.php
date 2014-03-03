<?php namespace BehatApp;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;
use BehatApp\BehatHelper;

class BehatYml {

    protected   $yml;
    protected   $finder;
    protected   $filesystem;
    public      $behatYml;
    public      $behatHelper;
    public      $full_path;
    public      $helper;
    public      $ymlPathWithFileName;
    public      $destination;

    public function __construct(Yaml $yml = null, Finder $finder = null, Filesystem $filesystem = null, BehatHelper $helper)
    {
        $this->yml             = ($yml == null) ? new Yaml : $yml;
        $this->finder           = ($finder == null) ? new Finder : $finder;
        $this->filesystem       = ($filesystem == null) ? new Filesystem : $filesystem;
        $this->helper           = $helper;
    }

    public function parseBehatYmlFile()
    {
        $this->behatYml = $this->yml->parse($this->ymlPathWithFileName);
        return $this;
    }

    public function getYmlArray()
    {
        return $this->behatYml;
    }

    public function setBehatYmlFileFullPath($full_path_with_file_name)
    {
        $this->ymlPathWithFileName = $full_path_with_file_name;
        return $this;
    }

    public function updateBaseUrl($url)
    {
        $this->behatYml['default']['extensions']["Behat\MinkExtension\Extension"]['base_url'] = $url;
        return $this;
    }

    public function setYmlFeaturePath($full_path)
    {
        $this->behatYml['default']['paths']['features'] = $full_path;
        return $this;
    }

    public function setYmlBootStrapPath($full_path)
    {
        $this->behatYml['default']['paths']['bootstrap'] = $full_path;
        return $this;
    }

    public function writeBehatYmlFile()
    {
        $content = $this->yml->dump($this->behatYml);
        $this->filesystem->dumpFile($this->destination, $content);
        return $this;
    }

    public function setDestination($destination)
    {
        $this->destination      = $destination;
        return $this;
    }

    public function getDestination()
    {
        return $this->destination;
    }
}