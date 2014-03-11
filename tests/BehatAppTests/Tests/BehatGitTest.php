<?php namespace BehatAppTests\Tests;

/**
 *
 * most of this code from
 * https://github.com/teqneers/PHP-Stream-Wrapper-for-Git/blob/master/tests/TQ/Tests/Helper.php
 */

use TQ\Git\StreamWrapper\StreamWrapper;
use TQ\Git\Cli\Binary;
use TQ\Tests\Helper;
use TQ\Git\Repository\Repository;
use BehatApp\GitHelper;
use BehatApp\BehatFeatureModel;

class BehatGitTest extends BehatBaseTests {

    public $gitHiddenFolder;
    public $git_root;
    public $temp;
    public $repo;
    public $gitHelper;
    public $featureModel;

//    public function setup()
//    {
//        parent::setUp();
//        $this->git_root = $this->behatHelper->getBasePath() . 'testGit/';
//        $this->featureModel = new BehatFeatureModel();
//        $this->gitHelper = new GitHelper();
//        $this->temp = sys_get_temp_dir();
//        if($this->filesystem->exists('/tmp/gitTestCreate')) {
//            $this->filesystem->remove('/tmp/gitTestCreate');
//        }
//        if($this->filesystem->exists($this->temp . '/git')) {
//            Helper::removeDirectory($this->temp . '/git');
//        }
//        Helper::createDirectory($this->temp . '/git');
//        if($this->filesystem->exists($this->git_root)) {
//            Helper::removeDirectory($this->git_root);
//        }
//        Helper::createDirectory($this->git_root);
//        Helper::initEmptyGitRepository($this->git_root);
//        $path   = $this->git_root.'/test.txt';
//        file_put_contents($path, 'File 1');
//        Helper::executeGit($this->git_root, sprintf('add %s',
//            escapeshellarg($path)
//        ));
//        Helper::executeGit($this->git_root, sprintf('commit --message=%s',
//            escapeshellarg('Commit 1')
//        ));
//
//        clearstatcache();
//    }
//
//    /**
//     * Tears down the fixture, for example, close a network connection.
//     * This method is called after a test is executed.
//     */
//    public function tearDown()
//    {
//        parent::tearDown();
//        Helper::removeDirectory($this->git_root);
//        StreamWrapper::unregister();
//    }
//
//    public function testGitCreate()
//    {
////        StreamWrapper::register('git', '/usr/bin/git');
////        $repo = $this->getRepository();
////        var_dump("Get Status");
////        var_dump($repo->getStatus());
////        var_dump("Get Log");
////        var_dump($repo->getLog());
////        var_dump("Get Dirt");
////        file_put_contents($this->git_root . '/test_dirt.txt', "Dirt");
////        var_dump($repo->isDirty());
////
////        $this->assertFileExists($this->git_root . '/test.txt');
////        $repo->add(null, TRUE);
////        $repo->commit("Clean Dirty", null, $auth = null, array());
////        var_dump("Dirt 2");
////        var_dump($repo->isDirty());
////
////
////        //This will crash cause now it is dirty
////        $content = file_get_contents(sprintf('git:///%s/test.txt', $this->git_root));
////        // write to a file - change is committed to the repository when file is closed
////        $file = fopen(sprintf('git:///%s/test.txt', $this->git_root), 'w');
////        $file2 = fopen(sprintf('git:///%s/test.txt', $this->git_root), 'w');
////        fwrite($file, 'Test');
////        fclose($file);
////        var_dump("Get Dirt 3");
////        var_dump($repo->isDirty());
////
////        fwrite($file2, 'Test 2');
////        fclose($file2);
////        $content_after = file_get_contents(sprintf('git:///%s/test.txt', $this->git_root));
////        var_dump("Get Log 2");
////        var_dump($repo->getLog());
////        var_dump($content_after);
//    }
//
//    /**
//     * @@expectedException TQ\Vcs\Cli\CallException
//     */
//    public function testFailCommitOnNotDirty()
//    {
//        $path = '/tmp/gitTestCreate';
//        $this->filesystem->mkdir($path);
//        $this->assertFileExists($path);
//        $this->gitHelper = $this->getRepository($path);
//        $this->gitHelper->add(null, $force = TRUE);
//        $this->gitHelper->commit("Initialize", null, null, array());
//    }
//
//    public function testIfNotGitThenWillInstantiate()
//    {
//        $path = '/tmp/gitTestCreate';
//        $this->filesystem->mkdir($path);
//        $this->assertFileExists($path);
//        $this->gitHelper = $this->gitHelper->setUpGitRepo($path, TRUE);
//        //file_put_contents($path . '/test_dirt.txt', "File");
//        //$this->assertTrue($this->gitHelper->isDirty());
////        $this->gitHelper->add(null, $force = TRUE);
////        $this->gitHelper->commit("Initialize", null, null, array());
////        $this->assertFalse($this->gitHelper->isDirty());
//    }
//
//    /**
//     * @param $path optional path to register
//     *
//     * @return  Repository
//     */
//    protected function getRepository($path = null)
//    {
//        $path = ($path == null) ? $this->git_root : $path;
//        return Repository::open($path, new Binary(GIT_BINARY), TRUE);
//    }
}