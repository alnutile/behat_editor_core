<?php namespace BehatApp;

use BehatApp\Persistence;

class InMemoryPersistence implements Persistence {
    private $data = array();

    function persist($data) {
        $this->data[] = $data;
    }

    function retrieve($id) {
        return $this->data[$id];
    }

    function retrieveAll()
    {
        return $this->data;
    }

    public function delete($id)
    {
        foreach($this->data as $key => $value) {
            if($value['rid'] == $id) {
                unset($this->data[$key]);
            }
        }
    }


    public function retrieveBySiteIdAndTestName($id, $name)
    {
        $results = [];
        foreach($this->data as $key) {
            if($key['site_id'] == $id && $key['test_name'] == $name) {
                $results[] = $key;
            }
        }
        return $results;
    }


    public function retrieveBySiteId($id)
    {
        $results = [];
        foreach($this->data as $key) {
            if($key['site_id'] == $id) {
                $results[] = $key;
            }
        }
        return $results;
    }
} 