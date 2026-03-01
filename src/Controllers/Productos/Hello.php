<?php

namespace Controllers\Productos;

use Controllers\PublicController;
use Views\Renderer;

class Hello extends PublicController
{
    private string $txtNombre = '';
    private string $txtResultado = '';
    private array $viewData = [];

    public function run(): void
    {
        if ($this->isPostBack()) {
            $this->txtNombre = $_POST['txtNombre'] ?? '';
            if (empty($this->txtNombre)) {
                $this->txtResultado = 'Por favor, ingresa tu nombre.';
            } else {
                $this->txtResultado = 'Bienvenido '.$this->txtNombre;
            }
        }
        $this->prepararViewData();
        Renderer::render('productos/hello', $this->viewData);
    }

    private function prepararViewData(): void
    {
        $this->viewData['txtNombre'] = $this->txtNombre;
        $this->viewData['mensajeFinal'] = $this->txtResultado;
    }
}
