<?php namespace BehatApp;

interface BehatFeatureInterface {

    public function getNewModel();
    public function create(array $params);
    public function getAll(array $params);
    public function update(array $params);
    public function updateMany(array $params);
    public function delete($params);
    public function deleteMany(array $params);

}