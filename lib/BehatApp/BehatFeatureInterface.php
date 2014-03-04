<?php namespace BehatApp;

interface BehatFeatureInterface {

    public function getNewModel();
    public function create(array $params);
    public function getAll(array $params);

}