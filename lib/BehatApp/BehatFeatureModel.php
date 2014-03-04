<?php namespace BehatApp;

use BehatApp\Exceptions\BehatAppException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class BehatFeatureModel extends BehatAppBase implements BehatFeatureInterface {

    public      $model;
    protected   $finder;
    protected   $filesystem;

    public function __construct(Filesystem $filesystem = null, Finder $finder = null)
    {
        $this->model        = $this->newModel();
        $this->finder       = ($finder == null) ? new Finder() : $finder;
        $this->filesystem   = ($filesystem == null) ? new Filesystem() : $filesystem;
    }

    public function getNewModel()
    {
        return $this->model;
    }

    public function setModelArray(array $model){
        $this->model = $model;
    }

    public function create(array $params)
    {
        list($content, $destination) = $params;
        if($this->filesystem->exists($destination)) {
            throw new BehatAppException("File already exists $destination");
        } else {
            $this->filesystem->dumpFile($destination, $content, $mode = 0775);
        }
    }

    public function getAll(array $params)
    {
        $files = array();
        list($root_path) = $params;
        if(!$this->filesystem->exists($root_path)) {
            throw new BehatAppException("Folder does not exists $root_path");
        } else {
            $files = $this->finder->files()
                ->in($root_path)
                ->name('*.feature')
                ->sortByName();
        }
        return $files;
    }

    protected function newModel()
    {
        $model = array(
            '@example',
            'Feature: Your Feature Here',
            '  Scenario: Your First Scenario',
            '    Given I am on "/"',
            '    Then I should see "test"'
        );

        return $model;
    }

}