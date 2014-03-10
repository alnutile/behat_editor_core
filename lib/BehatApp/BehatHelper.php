<?php namespace BehatApp;


use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use BehatApp\BehatFeatureContextUpdate;
use BehatApp\Exceptions\BehatAppException;

/**
 * See related tests for examples and docs
 *
 * Class BehatHelper
 * @package BehatApp
 */
class BehatHelper
{
    const BASE_BEHAT_FOLDER = '/behat';

    public $behat_folder_base;
    public $fileSystem;
    public $finder;
    public $behatFormatter;
    public $yamlHelper;
    public $filename;
    public $featurePath;
    public $path;
    public $full_path;
    public $storage_path;
    public $bin_path;
    public $results;
    public $app_path;
    public $base_path;
    public $behatYml;
    public $behatYml_path;
    public $templateFolder;
    public $bootstrapPath;
    public $testPath;
    public $fullPathAndFilenameToFeatureContext;
    public $rootHashFolder;
    public $hash;

    public function __construct(Filesystem $fileSystem = null, Finder $finder = null)
    {
        $this->fileSystem           = ($fileSystem == null) ? new Filesystem() : $fileSystem;
        $this->finder               = ($finder == null) ? new Finder() : $finder;
    }


    /**
     * This is the path below your behat.yml tests etc
     * example
     *   base = /tmp/behat
     *   in there would be
     *     behat.yml
     *     features/ <--some tests.feature files
     *     features/bootstrap
     * @param null $base_path
     * @return $this
     */
    public function setBasePath($base_path = null)
    {
        if ($base_path == null) {
            $this->base_path        = $this->getStoragePath() . '/default/';
        } else {
            $this->base_path        = $base_path;
        }
        return $this;
    }

    public function getBasePath()
    {
        if($this->base_path == null) {
            $this->setBasePath();
        }
        return $this->base_path;
    }

    public function setAppPath($app_path = null)
    {
        if ($app_path == null) {
            $current_base           = __DIR__;
            $current_base_array     = explode("/", $current_base);
            $current_base_array     = array_slice($current_base_array, 0, -3);
            $this->app_path         = implode("/", $current_base_array);
        } else {
            $this->app_path = $app_path;
        }
        return $this;
    }

    public function setStoragePath($storage_path = null)
    {
        if ($storage_path == null) {
            $this->storage_path     = $this->getAppPath() . '/storage';
        } else {
            $this->storage_path = $storage_path;
        }
        return $this;
    }

    public function getStoragePath()
    {
        if (!$this->storage_path) {
            $this->storage_path = $this->setStoragePath();
        }
        return $this->storage_path;
    }

    public function getAppPath()
    {
        if(!$this->app_path) {
            $this->setAppPath();
        }
        return $this->app_path;
    }

    public function replaceDashWithDots($name)
    {
        return str_replace('_', '.', $name);
    }

    public function replaceDotsWithDashes($name)
    {
        return str_replace('.', '_', $name);
    }

    public function getBehatYmlPath()
    {
        if(!$this->behatYml_path) {
            $this->setBehatYmlPath();
        }
        return $this->behatYml_path;
    }

    public function setBehatYmlPath($path = null)
    {
        if($path != null)
        {
            $this->behatYml_path = $path;
        } else {
            $this->behatYml_path = $this->getBasePath() . 'behat.yml';
        }
        return $this;
    }

    public function getBootstrapPath()
    {
        if(!$this->bootstrapPath) {
            $this->setBootstrapPath();
        }
        return $this->bootstrapPath;
    }

    public function setBootstrapPath($path = null)
    {
        if($path == null) {
            $this->bootstrapPath = $this->getBasePath() . '/features/bootstrap/';
        } else {
            $this->bootstrapPath = $path;
        }
        return $this;
    }

    public function getFeaturePathWithFeatureFileName()
    {
        if($this->fullPathAndFilenameToFeatureContext == null) $this->setFeaturePathWithFeatureFileName();
        return $this->fullPathAndFilenameToFeatureContext;
    }

    public function setFeaturePathWithFeatureFileName($path = null)
    {
        if($path != null)
        {
            $this->fullPathAndFilenameToFeatureContext = $path;
        } else {
            $this->fullPathAndFilenameToFeatureContext = $this->getBasePath() .  '/features/bootstrap/FeatureContext.php';
        }
        return $this;
    }


    public function getFeaturePath()
    {
        if($this->featurePath == null) $this->setFeaturePath();
        return $this->featurePath;
    }

    public function setFeaturePath($path = null)
    {
        if($path != null)
        {
            $this->featurePath = $path;
        } else {
            $this->featurePath = $this->getBasePath() .  '/features/bootstrap/';
        }
        return $this;
    }

    public function getTestPath()
    {
        if($this->testPath == null) $this->setTestPath();
        return $this->testPath;
    }

    public function setTestPath($path = null)
    {
        if($path == null) {
            $this->testPath = $this->getBasePath() . '/features';
        } else {
            $this->testPath = $path;
        }
        return $this;
    }

    public function createPath($path)
    {
        //@TODO this must be provided by the user
        //$path = ($path == null) ? $this->getBasePath() . '/' . $this->hash : $path;
        if(!$this->fileSystem->exists($path)) {
            $this->fileSystem->mkdir($path);
        }
        return $this;
    }

    public function setTemplateFolder($path = null)
    {
        if($path == null) {
            $this->templateFolder       = $this->getAppPath() . '/lib/BehatApp/template_files/';
        } else {
            $this->templateFolder       = $path;
        }
        return $this;
    }

    public function getTemplateFolder()
    {
        if($this->templateFolder == null) $this->setTemplateFolder();
        return $this->templateFolder;
    }

    public function copyTemplateFilesOver($destination)
    {
        try {
            $this->fileSystem->mirror($this->getTemplateFolder() . '/', $destination);
        }
        catch(IOException $e) {
            throw new BehatAppException("Could not copy over files {$e->getMessage()}");
        }
        return $this;
    }

    public function makeHash()
    {
        return $this->generateRandomString();
    }

    public function generateRandomString($length = 32) {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }

    public function check_slash($full_path)
    {
        $full_path = (substr($full_path, -1) !== '/') ? $full_path . '/' : $full_path;
        return $full_path;
    }

    public function delete($path) {
        return $this->fileSystem->remove($path);
    }
}
