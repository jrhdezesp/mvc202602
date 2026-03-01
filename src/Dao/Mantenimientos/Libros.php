<?php

namespace Dao\Mantenimientos;

use Dao\Table;

class Libros extends Table
{
    /*
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT 'Primary Key',
    titulo VARCHAR(255) NOT NULL COMMENT 'Título del libro',
    resumen VARCHAR(255) NOT NULL UNIQUE COMMENT 'Resumen del libro, debe ser único',
    autor VARCHAR(255) NOT NULL COMMENT 'Nombre del autor del libro',
    fecha_publicacion DATE NOT NULL COMMENT 'Fecha de publicación del libro',
    genero VARCHAR(100) NOT NULL COMMENT 'Género literario del libro',
    precio DECIMAL(10, 2) NOT NULL COMMENT 'Precio del libro'
    */

    public static function getAllLibros(): array
    {
        $libros = [];
        $sqlstr = 'SELECT * FROM libros;';
        $libros = self::obtenerRegistros($sqlstr, []);

        return $libros;
    }

    public static function getLibroById(int $id): array
    {
        $sqlstr = 'SELECT * FROM libros WHERE id = :id;';
        $param = [':id' => $id];

        return self::obtenerUnRegistro($sqlstr, $param);
    }
}
