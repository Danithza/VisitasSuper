<?php
require_once __DIR__ . '/../services/NomVisitaService.php';

class NomVisitaController {
    private $service;

    public function __construct() {
        $this->service = new NomVisitaService();
    }

    public function index() {
        return $this->service->getAllNomVisita();
    }

    public function show($id) {
        return $this->service->getNomVisitaById($id);
    }

    public function store($data) {
        return $this->service->createNomVisita($data);
    }

    public function update($id, $data) {
        return $this->service->updateNomVisita($id, $data);
    }

    public function destroy($id) {
        return $this->service->deleteNomVisita($id);
    }
}
