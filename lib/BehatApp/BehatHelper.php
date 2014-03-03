<?php namespace BehatApp;


use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use BehatApp\BehatFeatureContextUpdate;

/**
 * See related tests for examples and docs
 *
 * Class BehatHelper
 * @package BehatApp
 */
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
    public $app_path;
    public $behatYml;
    public $behatYml_path;
    public $templateFolder;
    public $bootstrapPath;
    public $testPath;
    public $fullPathAndFilenameToFeatureContext;
    public $rootHashFolder;
    public $hash;

    public function __construct($storage_path, $app_base, Filesystem $fileSystem = null, Finder $finder = null, BehatFormatter $behatFormatter = null)
    {
        $this->storage_path = $storage_path;
        $this->behat_folder_base = $this->storage_path . self::BASE_BEHAT_FOLDER;
        $this->fileSystem = ($fileSystem == null) ? new Filesystem() : $fileSystem;
        $this->finder = ($finder == null) ? new Finder() : $finder;
        $this->behatFormatter = ($behatFormatter == null) ? new BehatFormatter() : $behatFormatter;
        $this->app_path = $app_base;
        $this->bin_path = $this->app_path . '/vendor/bin/';
    }

    public function getProjectHash()
    {
        return $this->hash;
    }

    public function setProjectHash($hash)
    {
        $this->hash     = $hash;
        return $this;
    }

    public function loadTestFromProject($name)
    {

        $this->filename     = $this->replaceDashWithDots($name);
        $this->path         = $this->getProjectHash();
        $this->full_path    = $this->behat_folder_base . '/' . $this->path . '/features/';
        $file               = $this->getFileInfo();
        $content            = $file['content'];
        $name               = $file['name'];
        $path               = $this->full_path;

        return compact('name', 'content', 'path', 'project');
    }

    public function plain2html($content)
    {
        return $this->behatFormatter->plainToHtml($content);
    }

    /**
     * @param string $filename
     * @param string $full_path minus filename should end if forward slash
     * @return array
     */
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

    public function setBaseBinPath($path)
    {
        $this->bin_path = $path;
        return $this;
    }

    public function getBehatYmlPath()
    {
        return $this->behatYml_path;
    }

    public function setBehatYmlPath($path = null)
    {
        if($path != null)
        {
            $this->behatYml_path = $path;
        } else {
            $this->behatYml_path = $this->behat_folder_base . '/' . $this->hash . '/behat.yml';
        }
        return $this;
    }

    public function getBootstrapPath()
    {
        return $this->bootstrapPath;
    }

    public function setBootstrapPath()
    {
        $this->bootstrapPath = $this->behat_folder_base . '/' . $this->hash . '/features/bootstrap';
        return $this;
    }

    public function getFeaturePathWithFeatureFileName()
    {
        return $this->fullPathAndFilenameToFeatureContext;
    }

    public function setFeaturePathWithFeatureFileName()
    {
        $this->fullPathAndFilenameToFeatureContext = $this->behat_folder_base . '/' . $this->hash . '/features/bootstrap/FeatureContext.php';
        return $this;
    }

    public function getTestPath()
    {
        return $this->testPath;
    }

    public function setTestPath()
    {
        $this->testPath = $this->behat_folder_base . '/' . $this->hash . '/features';
        return $this;
    }

    public function getRootHashFolder()
    {
        return $this->rootHashFolder;
    }

    public function setRootHashFolder($path = null)
    {
        $this->rootHashFolder = ($path == null) ? $this->behat_folder_base . '/' . $this->getProjectHash() : $path;
        return $this;
    }

    public function createPath($path = null)
    {
        $path = ($path == null) ? $this->behat_folder_base . '/' . $this->hash : $path;
        $this->fileSystem->mkdir($path);
        return $this;
    }

    public function delete()
    {
        if($this->fileSystem->exists($this->getRootHashFolder($this->hash))) {
            $this->fileSystem->remove($this->getRootHashFolder($this->hash));
        }
    }

    public function copyTemplateFilesOver()
    {
        $this->fileSystem->mirror($this->getTemplateFolder() . '/', $this->behat_folder_base . '/' . $this->hash);
        return $this;
    }

    public function makeHash()
    {
        return $this->generateRandomString();
    }

    public function listProjectFiles()
    {
        $files = array();
        foreach($this->finder->files()->name('*.feature')->in($this->behat_folder_base . '/' . $this->hash . '/features/') as $file) {
            $files[$file->getFilename()] = array(
                'name'         => $file->getFilename(),
                'name_dashed'  => $this->replaceDotsWithDashes($file->getFileName()),
                'path'         => $file->getRealpath(),
                'content'      => $file->getContents(),
            );
        }
        return $files;
    }

    public function generateRandomString($length = 32) {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }

    public function getTemplateFolder()
    {
        $current_base           = __DIR__;
        $this->templateFolder = $current_base . '/template_files';
        return $this->templateFolder;
    }

}