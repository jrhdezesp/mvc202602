<?php

namespace Controllers\Mantenimientos\Libros;

use Controllers\PublicController;
use Views\Renderer;

class Formulario extends PublicController
{
    private array $viewData = [];

    public function run(): void
    {
        Renderer::render('mantenimientos/libros/formulario', $this->viewData);
    }
}
