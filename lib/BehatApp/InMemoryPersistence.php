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
} 