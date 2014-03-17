<?php namespace BehatApp;

class Report {

    protected $rid;
    protected $test_name;
    protected $site_id;
    protected $results;
    protected $duration;
    protected $created;
    protected $status;
    protected $user_id;
    protected $settings = array();
    protected $browser;
    protected $os;
    protected $tags;

    public function __construct(array $params = null)
    {
        $this->rid          = (isset($params['rid'])) ? $params['rid'] : '' ;
        $this->test_name    = (isset($params['test_name'])) ? $params['test_name'] : '' ;
        $this->site_id      = (isset($params['site_id'])) ? $params['site_id'] : '' ;
        $this->results      = (isset($params['results'])) ? $params['results'] : '' ;
        $this->duration     = (isset($params['duration'])) ? $params['duration'] : '' ;
        $this->created      = (isset($params['created'])) ? $params['created'] : '' ;
        $this->status       = (isset($params['status'])) ? $params['status'] : '' ;
        $this->user_id      = (isset($params['user_id'])) ? $params['user_id'] : '' ;
        $this->settings     = (isset($params['settings'])) ? $params['settings'] : '' ;
        $this->browser      = (isset($params['browser'])) ? $params['browser'] : '' ;
        $this->os           = (isset($params['os'])) ? $params['os'] : '' ;
        $this->tags         = (isset($params['tags'])) ? $params['tags'] : '' ;
    }

    /**
     * @param mixed $browser
     */
    public function setBrowser($browser)
    {
        $this->browser = $browser;
    }

    /**
     * @return mixed
     */
    public function getBrowser()
    {
        return $this->browser;
    }

    /**
     * @param mixed $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param mixed $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /**
     * @return mixed
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param mixed $os
     */
    public function setOs($os)
    {
        $this->os = $os;
    }

    /**
     * @return mixed
     */
    public function getOs()
    {
        return $this->os;
    }

    /**
     * @param mixed $results
     */
    public function setResults($results)
    {
        $this->results = $results;
    }

    /**
     * @return mixed
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @param mixed $rid
     */
    public function setRid($rid)
    {
        $this->rid = $rid;
    }

    /**
     * @return mixed
     */
    public function getRid()
    {
        return $this->rid;
    }

    /**
     * @param mixed $settings
     */
    public function setSettings($settings)
    {
        $this->settings = $settings;
    }

    /**
     * @return mixed
     */
    public function getSettings()
    {
        return $this->settings;
    }

    public function setSiteId($site_id)
    {
        $this->site_id = $site_id;
    }

    /**
     * @return mixed
     */
    public function getSiteId()
    {
        return $this->site_id;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param mixed $test_name
     */
    public function setTestName($test_name)
    {
        $this->test_name = $test_name;
    }

    /**
     * @return mixed
     */
    public function getTestName()
    {
        return $this->test_name;
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

}