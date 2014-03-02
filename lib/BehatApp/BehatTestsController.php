<?php namespace BehatApp;

use BehatWrapper\BehatWrapper;
use BehatApp\BehatHelper;
use BehatWrapper\BehatCommand;
use Project;

class BehatTestsController extends \BaseController
{

    protected $behatWrapper;
    protected $behatTestHelper;
    protected $project;
    protected $command;
    protected $hash;
    protected $fileArray;

    public function __construct(BehatWrapper $behatWrapper = null, BehatHelper $behatTestHelper = null, Project $project = null)
    {
        $this->behatWrapper = ($behatWrapper == null) ? new BehatWrapper() : $behatWrapper;
        $this->behatTestHelper = ($behatTestHelper == null) ? new BehatHelper() : $behatTestHelper;
        $this->project = ($project == null) ? new Project() : $project;

    }


    public function run($project_id, $test_name)
    {
        $this->hash             = \Project::find($project_id)->hash;
        $this->fileArray        = $this->behatTestHelper->loadTestFromProject($this->hash, $test_name);
        list($bin_path, $yml_path, $feature, $test_path) = $this->setupPaths();
        $this->behatWrapper->setBehatBinary($bin_path);
        $command = BehatCommand::getInstance()
            ->setOption('config', $yml_path)
            ->setFlag('no-paths')
            ->setTestPath($test_path);
        return \Response::json($this->behatWrapper->run($command));
    }

    protected function setupPaths()
    {
        $bin_path   = $this->behatTestHelper->getBaseBinPath();
        $yml_path   = $this->behatTestHelper->getBehatYmlPath($this->hash);
        $feature    = $this->behatTestHelper->getFeaturePath($this->hash);
        $test_path  = $this->fileArray['path'] . $this->fileArray['name'];
        return array($bin_path, $yml_path, $feature, $test_path);
    }
}