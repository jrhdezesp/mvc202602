<?php

namespace Controllers\Roles;

use Controllers\PublicController;
use Dao\Roles\Roles as RolesDao;
use Utilities\Site;
use Utilities\Validators;
use Views\Renderer;

class Rol extends PublicController
{
    private $viewData = [];
    private $mode = 'DSP';
    private $modeDescriptions = [
        'DSP' => 'Detalle de %s %s',
        'INS' => 'Nuevo Rol',
        'UPD' => 'Editar %s %s',
        'DEL' => 'Eliminar %s %s',
    ];
    private $readonly = '';
    private $showCommitBtn = true;
    private $rol = [
        'rolescod' => '',
        'rolesdsc' => '',
        'rolesest' => 'ACT',
    ];
    private $funcionesRol = [];
    private $rol_xss_token = '';

    public function run(): void
    {
        try {
            $this->getData();
            $this->loadFuncionesData();
            if ($this->isPostBack()) {
                $formAction = strval($_POST['form_action'] ?? 'rol');
                if ($formAction === 'funciones_rol') {
                    $this->handleFuncionesRolPost();
                } elseif ($this->validateData()) {
                    $this->handlePostAction();
                }
            }
            $this->setViewData();
            Renderer::render('roles/rol', $this->viewData);
        } catch (\Exception $ex) {
            Site::redirectToWithMsg(
                'index.php?page=Roles_Roles',
                $ex->getMessage()
            );
        }
    }

    private function getData()
    {
        $this->mode = $_GET['mode'] ?? 'NOF';
        if (isset($this->modeDescriptions[$this->mode])) {
            $this->readonly = $this->mode === 'DEL' ? 'readonly' : '';
            $this->showCommitBtn = $this->mode !== 'DSP';
            if ($this->mode !== 'INS') {
                $this->rol = RolesDao::getRolById(strval($_GET['rolescod'] ?? ''));
                if (!$this->rol) {
                    throw new \Exception('No se encontró el Rol', 1);
                }
            }
        } else {
            throw new \Exception('Formulario cargado en modalidad invalida', 1);
        }
    }

    private function validateData()
    {
        $errors = [];
        $this->rol_xss_token = $_POST['rol_xss_token'] ?? '';
        $this->rol['rolescod'] = strval($_POST['rolescod'] ?? '');
        $this->rol['rolesdsc'] = strval($_POST['rolesdsc'] ?? '');
        $this->rol['rolesest'] = strval($_POST['rolesest'] ?? '');

        if (Validators::IsEmpty($this->rol['rolescod'])) {
            $errors['rolescod_error'] = 'El código del rol es requerido';
        }

        if (Validators::IsEmpty($this->rol['rolesdsc'])) {
            $errors['rolesdsc_error'] = 'La descripción del rol es requerida';
        }

        if (!in_array($this->rol['rolesest'], ['ACT', 'INA'])) {
            $errors['rolesest_error'] = 'El estado del rol es invalido';
        }

        if (count($errors) > 0) {
            foreach ($errors as $key => $value) {
                $this->rol[$key] = $value;
            }

            return false;
        }

        return true;
    }

    private function handlePostAction()
    {
        switch ($this->mode) {
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
                break;
        }
    }

    private function handleInsert()
    {
        $result = RolesDao::insertRol(
            $this->rol['rolescod'],
            $this->rol['rolesdsc'],
            $this->rol['rolesest']
        );
        if ($result > 0) {
            Site::redirectToWithMsg(
                'index.php?page=Roles_Roles',
                'Rol creado exitosamente'
            );
        }
    }

    private function handleUpdate()
    {
        $result = RolesDao::updateRol(
            $this->rol['rolescod'],
            $this->rol['rolesdsc'],
            $this->rol['rolesest']
        );
        if ($result > 0) {
            Site::redirectToWithMsg(
                'index.php?page=Roles_Roles',
                'Rol actualizado exitosamente'
            );
        }
    }

    private function handleDelete()
    {
        $result = RolesDao::deleteRol($this->rol['rolescod']);
        if ($result > 0) {
            Site::redirectToWithMsg(
                'index.php?page=Roles_Roles',
                'Rol eliminado exitosamente'
            );
        }
    }

    private function loadFuncionesData(): void
    {
        if ($this->mode !== 'INS') {
            $this->funcionesRol = RolesDao::getFuncionesByRol($this->rol['rolescod']);
            foreach ($this->funcionesRol as &$funcion) {
                $stateKey = 'fnrolest_'.strtolower($funcion['fnrolest']);
                $funcion[$stateKey] = 'selected';
            }
            unset($funcion);
        }
    }

    private function handleFuncionesRolPost(): void
    {
        if ($this->mode === 'INS' || $this->mode === 'DEL') {
            throw new \Exception('No se puede editar funciones en este modo.', 1);
        }

        $fncod = strval($_POST['fncod'] ?? '');
        $fnrolest = strval($_POST['fnrolest'] ?? '');

        if (Validators::IsEmpty($fncod)) {
            throw new \Exception('Debe seleccionar una funcion valida.', 1);
        }

        if (!in_array($fnrolest, ['ACT', 'INA'])) {
            throw new \Exception('El estado de la funcion para el rol es invalido.', 1);
        }

        RolesDao::upsertRolFuncion(
            $this->rol['rolescod'],
            $fncod,
            $fnrolest
        );

        Site::redirectToWithMsg(
            sprintf('index.php?page=Roles_Rol&mode=%s&rolescod=%s', $this->mode, urlencode($this->rol['rolescod'])),
            'Funcion del rol actualizada exitosamente'
        );
    }

    private function setViewData(): void
    {
        $this->viewData['mode'] = $this->mode;
        $this->viewData['rolescod'] = $this->rol['rolescod'];
        $this->viewData['rol_xss_token'] = $this->rol_xss_token;
        $this->viewData['FormTitle'] = sprintf(
            $this->modeDescriptions[$this->mode],
            $this->rol['rolescod'],
            $this->rol['rolesdsc']
        );
        $this->viewData['showCommitBtn'] = $this->showCommitBtn;
        $this->viewData['readonly'] = $this->readonly;

        $rolesestKey = 'rolesest_'.strtolower($this->rol['rolesest']);
        $this->rol[$rolesestKey] = 'selected';

        $this->viewData['rol'] = $this->rol;
        $this->viewData['funcionesRol'] = $this->funcionesRol;
        $this->viewData['showFuncionesRolManager'] = $this->mode !== 'INS' && $this->mode !== 'DEL';
    }
}
