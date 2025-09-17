<?php
require_once __DIR__ . '/../services/AspectoService.php';

class AspectoController {
    private $service;

    public function __construct() {
        $this->service = new AspectoService();
    }

    public function index() {
        return $this->service->getAllAspectos(); // ✅ corregido (plural)
    }

    public function show($id) {
        return $this->service->getAspectoById($id);
    }

    public function store($data) {
        return $this->service->createAspecto($data);
    }

    public function update($id, $data) {
        return $this->service->updateAspecto($id, $data);
    }

    public function destroy($id) {
        return $this->service->deleteAspecto($id);
    }
}
