<?php namespace BehatApp;

interface Persistence {

    function persist($data);
    function retrieve($ids);
    function retrieveAll();
}