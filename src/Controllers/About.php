<?php

namespace Controllers;

use Views\Renderer;

class About extends PublicController
{
    public function run(): void
    {
        $viewData = [
            'nombre' => 'Fulanito de Tal',
            'correo' => 'fulanito@correotal.com',
            'telefono' => '+504 3264-2063',
        ];

        Renderer::render('about', $viewData);
    }
}
