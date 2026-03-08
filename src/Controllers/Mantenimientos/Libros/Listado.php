<?php

// insert, select, update, delete
// INS, DSP, UPD, DEL

namespace Controllers\Mantenimientos\Libros;

use Controllers\PublicController;
use Dao\Mantenimientos\Libros as LibrosDAO;
use Views\Renderer;

const LIST_VIEW_TEMPLATE = 'mantenimientos/libros/listado';

class Listado extends PublicController
{
    private array $librosList = [];

    public function run(): void
    {
        $this->librosList = LibrosDAO::getAllLibros();
        Renderer::render(LIST_VIEW_TEMPLATE, $this->prepareViewData());
    }

    private function prepareViewData()
    {
        return
        [
            'libros' => $this->librosList,
        ];
    }
}
