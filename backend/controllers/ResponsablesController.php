<?php
require_once __DIR__ . '/../services/ResponsableService.php';

class ResponsablesController {
    private $service;

    public function __construct() {
        $this->service = new ResponsableService();
    }

    public function index() {
        return $this->service->getAllResponsables();
    }

    public function show($id) {
        return $this->service->getResponsableById($id);
    }

    public function store($data) {
        return $this->service->createResponsable($data);
    }

    public function update($id, $data) {
        return $this->service->updateResponsable($id, $data);
    }

    public function destroy($id) {
        return $this->service->deleteResponsable($id);
    }
}
