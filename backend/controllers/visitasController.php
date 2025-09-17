<?php
require_once __DIR__ . '/../services/VisitaService.php';

class VisitasController {
    private $service;

    public function __construct() {
        $this->service = new VisitaService();
    }

    public function index() {
        return $this->service->getAllVisitas();
    }

    public function show($id) {
        return $this->service->getVisitaById($id);
    }

    public function store($data) {
        return $this->service->createVisita($data);
    }

    public function update($id, $data) {
        return $this->service->updateVisita($id, $data);
    }

    public function destroy($id) {
        return $this->service->deleteVisita($id);
    }
}
