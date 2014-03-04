<?php namespace BehatApp;

use BehatApp\Exceptions\BehatAppException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Exception\IOException;

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

    public function update(array $params)
    {
        list($content, $destination) = $params;
        if(!$this->filesystem->exists($destination)) {
            throw new BehatAppException("File does not exists $destination please use create");
        } else {
            try {
                $this->filesystem->dumpFile($destination, $content, $mode = 0775);
            }
            catch(IOException $e) {
                throw new BehatAppException("Can not update the file {$e->getMessage()}");
            }
        }
    }

    public function updateMany(array $files_and_path)
    {
        foreach($files_and_path as $file_and_path){
            $this->update($file_and_path);
        }
    }


    public function delete($folder_path)
    {
        $filename   = explode('/', $folder_path);
        $filename   = array_slice($filename, -1);
        $filename   = $filename[0];

        if(!$this->filesystem->exists($folder_path)) {
            throw new BehatAppException("File does not exists $folder_path please use create");
        } elseif(!$this->featureFileCheck($filename)) {
            throw new BehatAppException("File is not a feature file I can not delete it.");
        } else {
            try {
                $this->filesystem->remove($folder_path);
            }
            catch(IOException $e) {
                throw new BehatAppException("Can not remove file due to permissions {$e->getMessage()}");
            }
        }
    }

    public function deleteMany(array $files_and_path)
    {
        foreach($files_and_path as $file_and_path){
            $this->delete($file_and_path);
        }
    }

    protected function featureFileCheck($filename)
    {
        $filename   = explode('.', $filename);
        if($filename[1] != 'feature') return FALSE;
        return TRUE;
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