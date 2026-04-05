<?php

namespace Controllers\Restaurantes;

use Controllers\PublicController;
use Dao\Restaurantes\Restaurantes as RestaurantesDao;
use Utilities\Site;
use Utilities\Validators;
use Views\Renderer;

class Restaurante extends PublicController
{
    private $arrViewData = [];
    private $strMode = 'DSP';
    private $arrModeDescriptions = [
        'DSP' => 'Detalle de %s %s',
        'INS' => 'Nuevo Restaurante',
        'UPD' => 'Editar %s %s',
        'DEL' => 'Eliminar %s %s',
    ];
    private $strReadonly = '';
    private $blnShowCommitBtn = true;
    private $arrRestaurante = [
        'id_restaurante' => 0,
        'nombre' => '',
        'tipo_cocina' => '',
        'ubicacion' => '',
        'calificacion' => '',
        'capacidad_comensales' => '',
    ];
    private $strRestauranteXssToken = '';

    public function run(): void
    {
        try {
            $this->getData();

            if ($this->isPostBack()) {
                if ($this->validateData()) {
                    $this->handlePostAction();
                }
            }

            $this->setViewData();
            Renderer::render('restaurantes/restaurante', $this->arrViewData);
        } catch (\Exception $ex) {
            Site::redirectToWithMsg(
                'index.php?page=Restaurantes_Restaurantes',
                $ex->getMessage()
            );
        }
    }

    private function getData(): void
    {
        $this->strMode = $_GET['mode'] ?? 'NOF';

        if (!isset($this->arrModeDescriptions[$this->strMode])) {
            throw new \Exception('Formulario cargado en modalidad invalida', 1);
        }

        $this->strReadonly = $this->strMode === 'DEL' ? 'readonly' : '';
        $this->blnShowCommitBtn = $this->strMode !== 'DSP';

        if ($this->strMode !== 'INS') {
            $intIdRestaurante = intval($_GET['id_restaurante'] ?? 0);
            $arrData = RestaurantesDao::getRestauranteById($intIdRestaurante);

            if (!$arrData) {
                throw new \Exception('No se encontró el Restaurante', 1);
            }

            $this->arrRestaurante = $arrData;
            $this->arrRestaurante['calificacion'] = strval($this->arrRestaurante['calificacion']);
            $this->arrRestaurante['capacidad_comensales'] = strval($this->arrRestaurante['capacidad_comensales']);
        }
    }

    private function validateData(): bool
    {
        $arrErrors = [];

        $this->strRestauranteXssToken = $_POST['restaurante_xss_token'] ?? '';
        $this->arrRestaurante['id_restaurante'] = intval($_POST['id_restaurante'] ?? 0);
        $this->arrRestaurante['nombre'] = htmlspecialchars(trim($_POST['nombre'] ?? ''));
        $this->arrRestaurante['tipo_cocina'] = htmlspecialchars(trim($_POST['tipo_cocina'] ?? ''));
        $this->arrRestaurante['ubicacion'] = htmlspecialchars(trim($_POST['ubicacion'] ?? ''));
        $this->arrRestaurante['calificacion'] = trim(strval($_POST['calificacion'] ?? ''));
        $this->arrRestaurante['capacidad_comensales'] = trim(strval($_POST['capacidad_comensales'] ?? ''));

        if (Validators::IsEmpty($this->arrRestaurante['nombre'])) {
            $arrErrors['nombre_error'] = 'El nombre es requerido';
        }

        if ($this->arrRestaurante['calificacion'] !== '') {
            $fltCalificacion = floatval($this->arrRestaurante['calificacion']);
            if ($fltCalificacion < 0 || $fltCalificacion > 5) {
                $arrErrors['calificacion_error'] = 'La calificación debe estar entre 0 y 5';
            }
        }

        if ($this->arrRestaurante['capacidad_comensales'] !== '') {
            $intCapacidad = intval($this->arrRestaurante['capacidad_comensales']);
            if ($intCapacidad < 0) {
                $arrErrors['capacidad_comensales_error'] = 'La capacidad no puede ser negativa';
            }
        }

        if (count($arrErrors) > 0) {
            foreach ($arrErrors as $strKey => $strValue) {
                $this->arrRestaurante[$strKey] = $strValue;
            }

            return false;
        }

        return true;
    }

    private function handlePostAction(): void
    {
        switch ($this->strMode) {
            case 'INS':
                $this->handleInsert();
                break;
            case 'UPD':
                $this->handleUpdate();
                break;
            case 'DEL':
                $this->handleDelete();
                break;
            default:
                throw new \Exception('Modo invalido', 1);
        }
    }

    private function handleInsert(): void
    {
        $result = RestaurantesDao::insertRestaurante(
            $this->arrRestaurante['nombre'],
            $this->arrRestaurante['tipo_cocina'],
            $this->arrRestaurante['ubicacion'],
            $this->arrRestaurante['calificacion'] === '' ? null : floatval($this->arrRestaurante['calificacion']),
            $this->arrRestaurante['capacidad_comensales'] === '' ? null : intval($this->arrRestaurante['capacidad_comensales'])
        );

        if ($result > 0) {
            Site::redirectToWithMsg(
                'index.php?page=Restaurantes_Restaurantes',
                'Restaurante creado exitosamente'
            );
        }
    }

    private function handleUpdate(): void
    {
        $result = RestaurantesDao::updateRestaurante(
            $this->arrRestaurante['id_restaurante'],
            $this->arrRestaurante['nombre'],
            $this->arrRestaurante['tipo_cocina'],
            $this->arrRestaurante['ubicacion'],
            $this->arrRestaurante['calificacion'] === '' ? null : floatval($this->arrRestaurante['calificacion']),
            $this->arrRestaurante['capacidad_comensales'] === '' ? null : intval($this->arrRestaurante['capacidad_comensales'])
        );

        if ($result > 0) {
            Site::redirectToWithMsg(
                'index.php?page=Restaurantes_Restaurantes',
                'Restaurante actualizado exitosamente'
            );
        }
    }

    private function handleDelete(): void
    {
        $result = RestaurantesDao::deleteRestaurante($this->arrRestaurante['id_restaurante']);

        if ($result > 0) {
            Site::redirectToWithMsg(
                'index.php?page=Restaurantes_Restaurantes',
                'Restaurante eliminado exitosamente'
            );
        }
    }

    private function setViewData(): void
    {
        $this->arrViewData['mode'] = $this->strMode;
        $this->arrViewData['restaurante_xss_token'] = $this->strRestauranteXssToken;
        $this->arrViewData['FormTitle'] = sprintf(
            $this->arrModeDescriptions[$this->strMode],
            $this->arrRestaurante['id_restaurante'],
            $this->arrRestaurante['nombre']
        );
        $this->arrViewData['showCommitBtn'] = $this->blnShowCommitBtn;
        $this->arrViewData['readonly'] = $this->strReadonly;
        $this->arrViewData['restaurante'] = $this->arrRestaurante;
    }
}
