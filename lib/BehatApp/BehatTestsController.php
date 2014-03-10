<?php namespace BehatApp;

use BehatWrapper\BehatWrapper;
use BehatApp\BehatHelper;
use BehatWrapper\BehatCommand;

class BehatTestsController extends \BaseController
{

    protected $behatWrapper;
    protected $behatTestHelper;
    protected $project;
    protected $command;
    protected $hash;
    protected $fileArray;

    public function __construct(BehatWrapper $behatWrapper = null, BehatHelper $behatTestHelper = null)
    {
        $this->behatWrapper = ($behatWrapper == null) ? new BehatWrapper() : $behatWrapper;
        $this->behatTestHelper = ($behatTestHelper == null) ? new BehatHelper() : $behatTestHelper;

    }

    public function run($path, $test_path)
    {
        $this->behatTestHelper->setBasePath($path);
        $yml_path   = $this->behatTestHelper->getBehatYmlPath($path);
        $command = BehatCommand::getInstance()
            ->setOption('config', $yml_path)
            ->setFlag('no-paths')
            ->setTestPath($test_path);
        return $this->behatWrapper->run($command);
    }
}
