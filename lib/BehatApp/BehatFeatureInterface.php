<?php namespace BehatApp;

interface BehatFeatureInterface {

    public function getNewModel();
    public function create(array $params, $vcs);
    public function getAll(array $params);
    public function update(array $params, $vcs);
    public function updateMany(array $params, $vcs);
    public function delete($params, $vcs);
    public function deleteMany(array $params, $vcs);

}