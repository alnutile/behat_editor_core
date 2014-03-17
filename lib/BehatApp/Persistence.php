<?php namespace BehatApp;

interface Persistence {

    function persist($data);
    function retrieve($ids);
    function delete($id);
    function retrieveAll();
    function retrieveBySiteIdAndTestName($id, $name);
}