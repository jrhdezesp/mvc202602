<?php

namespace Dao\Restaurantes;

use Dao\Table;

class Restaurantes extends Table
{
    public static function getRestaurantes(
        string $strPartialNombre = '',
        string $strOrderBy = '',
        bool $blnOrderDescending = false,
        int $intPage = 0,
        int $intItemsPerPage = 10
    ) {
        $strSql = 'SELECT r.id_restaurante, r.nombre, r.tipo_cocina, r.ubicacion, r.calificacion, r.capacidad_comensales FROM DatosRestaurantes r';
        $strSqlCount = 'SELECT COUNT(*) as count FROM DatosRestaurantes r';
        $arrConditions = [];
        $arrParams = [];

        if ($strPartialNombre !== '') {
            $arrConditions[] = 'r.nombre LIKE :partialNombre';
            $arrParams['partialNombre'] = '%'.$strPartialNombre.'%';
        }

        if (count($arrConditions) > 0) {
            $strSql .= ' WHERE '.implode(' AND ', $arrConditions);
            $strSqlCount .= ' WHERE '.implode(' AND ', $arrConditions);
        }

        if (!in_array($strOrderBy, ['id_restaurante', 'nombre', 'calificacion', ''])) {
            throw new \Exception('Error Processing Request OrderBy has invalid value');
        }

        if ($strOrderBy !== '') {
            $strSql .= ' ORDER BY '.$strOrderBy;
            if ($blnOrderDescending) {
                $strSql .= ' DESC';
            }
        }

        $intItemsPerPage = $intItemsPerPage > 0 ? $intItemsPerPage : 10;
        $intPage = $intPage >= 0 ? $intPage : 0;
        $intNumeroRegistros = self::obtenerUnRegistro($strSqlCount, $arrParams)['count'];
        $intPaginas = $intNumeroRegistros > 0 ? ceil($intNumeroRegistros / $intItemsPerPage) : 1;

        if ($intPage > $intPaginas - 1) {
            $intPage = $intPaginas - 1;
        }

        $intOffset = $intPage * $intItemsPerPage;
        $strSql .= ' LIMIT '.$intOffset.', '.$intItemsPerPage;
        $arrRegistros = self::obtenerRegistros($strSql, $arrParams);

        return [
            'restaurantes' => $arrRegistros,
            'total' => $intNumeroRegistros,
            'page' => $intPage,
            'itemsPerPage' => $intItemsPerPage,
        ];
    }

    public static function getRestauranteById(int $intIdRestaurante)
    {
        $strSql = 'SELECT r.id_restaurante, r.nombre, r.tipo_cocina, r.ubicacion, r.calificacion, r.capacidad_comensales FROM DatosRestaurantes r WHERE r.id_restaurante = :id_restaurante';
        $arrParams = ['id_restaurante' => $intIdRestaurante];

        return self::obtenerUnRegistro($strSql, $arrParams);
    }

    public static function insertRestaurante(
        string $strNombre,
        string $strTipoCocina,
        string $strUbicacion,
        ?float $fltCalificacion,
        ?int $intCapacidadComensales
    ) {
        $strSql = 'INSERT INTO DatosRestaurantes (nombre, tipo_cocina, ubicacion, calificacion, capacidad_comensales) VALUES (:nombre, :tipo_cocina, :ubicacion, :calificacion, :capacidad_comensales)';
        $arrParams = [
            'nombre' => $strNombre,
            'tipo_cocina' => $strTipoCocina,
            'ubicacion' => $strUbicacion,
            'calificacion' => $fltCalificacion,
            'capacidad_comensales' => $intCapacidadComensales,
        ];

        return self::executeNonQuery($strSql, $arrParams);
    }

    public static function updateRestaurante(
        int $intIdRestaurante,
        string $strNombre,
        string $strTipoCocina,
        string $strUbicacion,
        ?float $fltCalificacion,
        ?int $intCapacidadComensales
    ) {
        $strSql = 'UPDATE DatosRestaurantes SET nombre = :nombre, tipo_cocina = :tipo_cocina, ubicacion = :ubicacion, calificacion = :calificacion, capacidad_comensales = :capacidad_comensales WHERE id_restaurante = :id_restaurante';
        $arrParams = [
            'id_restaurante' => $intIdRestaurante,
            'nombre' => $strNombre,
            'tipo_cocina' => $strTipoCocina,
            'ubicacion' => $strUbicacion,
            'calificacion' => $fltCalificacion,
            'capacidad_comensales' => $intCapacidadComensales,
        ];

        return self::executeNonQuery($strSql, $arrParams);
    }

    public static function deleteRestaurante(int $intIdRestaurante)
    {
        $strSql = 'DELETE FROM DatosRestaurantes WHERE id_restaurante = :id_restaurante';
        $arrParams = ['id_restaurante' => $intIdRestaurante];

        return self::executeNonQuery($strSql, $arrParams);
    }
}
