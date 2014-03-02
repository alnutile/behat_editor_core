<?php namespace BehatApp;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

class BehatYml {

    protected   $yaml;
    protected   $finder;
    protected   $filesystem;
    public      $behatYml;
    public      $full_path;

    public function __construct(Yaml $yaml = null, Finder $finder = null, Filesystem $filesystem = null)
    {
        $this->yaml             = ($yaml == null) ? new Yaml : $yaml;
        $this->finder           = ($finder == null) ? new Finder : $finder;
        $this->filesystem       = ($filesystem == null) ? new Filesystem : $filesystem;
    }

    public function getBehatFile($full_path)
    {
        $this->full_path = $full_path;
        $this->behatYml = $this->yaml->parse($full_path);
        return $this;
    }

    public function updateBaseUrl()
    {

    }

    public function setYml($yml)
    {
        $this->behatYml     = $yml;
        return $this;
    }

    public function setFeaturePath($full_path)
    {
        $this->behatYml['default']['paths']['features'] = $full_path;
        return $this;
    }

    public function setBootStrapPath($full_path)
    {
        $this->behatYml['default']['paths']['bootstrap'] = $full_path;
        return $this;
    }

    public function writeBehatYmlFiles($destination)
    {
        $content = $this->yaml->dump($this->behatYml);
        $this->filesystem->dumpFile($destination, $content);
        return $this;
    }
}