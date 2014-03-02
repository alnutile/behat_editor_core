<?php namespace BehatApp;


use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use BehatApp\BehatFormatter;
use BehatApp\BehatYml;
use BehatApp\BehatFeatureContextUpdate;

class BehatHelper
{
    const BASE_BEHAT_FOLDER = '/behat';

    protected $behat_folder_base;
    protected $fileSystem;
    protected $finder;
    protected $behatFormatter;
    public $filename;
    public $path;
    public $full_path;
    public $storage_path;
    public $bin_path;
    public $results;
    public $behatYml;

    public function __construct(Filesystem $fileSystem = null, Finder $finder = null, BehatFormatter $behatFormatter = null, BehatYml $behatYml = null)
    {
        $this->storage_path = storage_path();
        $this->behat_folder_base = $this->storage_path . self::BASE_BEHAT_FOLDER;
        $this->fileSystem = ($fileSystem == null) ? new Filesystem() : $fileSystem;
        $this->finder = ($finder == null) ? new Finder() : $finder;
        $this->behatYml = ($behatYml == null) ? new BehatYml() : $behatYml;
        $this->behatFormatter = ($behatFormatter == null) ? new BehatFormatter() : $behatFormatter;
        $base_app_path = base_path();
        $this->bin_path = $base_app_path . '/vendor/bin/';
    }

    public function loadTestFromProject($hash, $name)
    {

        $this->filename     = $this->replaceDashWithDots($name);
        $this->path         = $hash;
        $this->full_path    = $this->behat_folder_base . '/' . $this->path . '/features/';
        $file               = $this->getFileInfo();
        $content            = $this->plain2html($file['content']);
        $name               = $file['name'];
        $path               = $this->full_path;

        return compact('name', 'content', 'path', 'project');
    }

    public function plain2html($content)
    {
        return $this->behatFormatter->plainToHtml($content);
    }

    public function getFileInfo($filename = null, $full_path = null)
    {
        $this->filename     = ($filename != null) ? $filename : $this->filename;
        $this->full_path    = ($full_path != null) ? $full_path : $this->full_path;
        $fileFound = array();
        foreach($this->finder->files()->name($this->filename)->in($this->full_path) as $file) {
            $fileFound = array(
                'name'     => $file->getFilename(),
                'path'     => $file->getRealpath(),
                'content'  => $file->getContents(),
            );
        }
        return $fileFound;
    }

    public function replaceDashWithDots($name)
    {
        return str_replace('_', '.', $name);
    }

    public function replaceDotsWithDashes($name)
    {
        return str_replace('.', '_', $name);
    }

    public function getBaseBinPath()
    {
        return $this->bin_path;
    }

    public function getBehatYmlPath($project_hash)
    {
        return $this->behat_folder_base . '/' . $project_hash . '/behat.yml';
    }

    public function getFeaturePath($project_hash)
    {
        return $this->behat_folder_base . '/' . $project_hash . '/features/bootstrap';
    }

    public function getBootstrapPath($project_hash)
    {
        return $this->behat_folder_base . '/' . $project_hash . '/features/bootstrap';
    }

    public function getFeaturePathWithFeatureFileName($project_hash)
    {
        return $this->behat_folder_base . '/' . $project_hash . '/features/bootstrap/FeatureContext.php';
    }

    public function getTestPath($project_hash)
    {
        return $this->behat_folder_base . '/' . $project_hash . '/features';
    }

    public function getRootHashFolder($project_hash)
    {
        return $this->behat_folder_base . '/' . $project_hash;
    }

    public function updateYmlFile($project_hash)
    {
        $project_root   = $this->getRootHashFolder($project_hash);
        $feature_root   = $this->getTestPath($project_hash);
        $bootstrap      = $this->getBootstrapPath($project_hash);
        $this->behatYml = $this->behatYml->getBehatFile($project_root . '/behat.yml')
            ->setBootStrapPath($bootstrap)
            ->setFeaturePath($feature_root)
            ->writeBehatYmlFiles($project_root . '/behat.yml');
        return $this->behatYml;
    }

    public function createPath($hash)
    {
        $this->fileSystem->mkdir($this->behat_folder_base . '/' . $hash);
        return $this;
    }

    public function delete($hash)
    {
        if($this->fileSystem->exists($this->getRootHashFolder($hash))) {
            $this->fileSystem->remove($this->getRootHashFolder($hash));
        }
    }

    public function copyTemplateFilesOver($hash)
    {
        $this->fileSystem->mirror($this->behat_folder_base . '/template/', $this->behat_folder_base . '/' . $hash);
        return $this;
    }

    public function makeHash()
    {
        return str_random(64);
    }

    public function listProjectFiles($hash)
    {
        $files = array();
        foreach($this->finder->files()->name('*.feature')->in($this->behat_folder_base . '/' . $hash . '/features/') as $file) {
            $files[$file->getFilename()] = array(
                'name'         => $file->getFilename(),
                'name_dashed'  => $this->replaceDotsWithDashes($file->getFileName()),
                'path'         => $file->getRealpath(),
                'content'      => $file->getContents(),
            );
        }
        return $files;
    }

}