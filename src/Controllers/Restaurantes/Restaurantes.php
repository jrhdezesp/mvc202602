<?php

namespace Controllers\Restaurantes;

use Controllers\PublicController;
use Dao\Restaurantes\Restaurantes as DaoRestaurantes;
use Utilities\Context;
use Utilities\Paging;
use Views\Renderer;

class Restaurantes extends PublicController
{
    private $strPartialNombre = '';
    private $strOrderBy = '';
    private $blnOrderDescending = false;
    private $intPageNumber = 1;
    private $intItemsPerPage = 10;
    private $arrViewData = [];
    private $arrRestaurantes = [];
    private $intRestaurantesCount = 0;
    private $intPages = 0;

    public function run(): void
    {
        $this->getParamsFromContext();
        $this->getParams();

        $arrTmpRestaurantes = DaoRestaurantes::getRestaurantes(
            $this->strPartialNombre,
            $this->strOrderBy,
            $this->blnOrderDescending,
            $this->intPageNumber - 1,
            $this->intItemsPerPage
        );

        $this->arrRestaurantes = $arrTmpRestaurantes['restaurantes'];
        $this->intRestaurantesCount = $arrTmpRestaurantes['total'];
        $this->intPages = $this->intRestaurantesCount > 0 ? ceil($this->intRestaurantesCount / $this->intItemsPerPage) : 1;

        if ($this->intPageNumber > $this->intPages) {
            $this->intPageNumber = $this->intPages;
        }

        $this->setParamsToContext();
        $this->setParamsToDataView();
        Renderer::render('restaurantes/restaurantes', $this->arrViewData);
    }

    private function getParams(): void
    {
        $this->strPartialNombre = isset($_GET['partialNombre']) ? htmlspecialchars(trim($_GET['partialNombre'])) : $this->strPartialNombre;
        $this->strOrderBy = isset($_GET['orderBy']) && in_array($_GET['orderBy'], ['id_restaurante', 'nombre', 'calificacion', 'clear']) ? $_GET['orderBy'] : $this->strOrderBy;

        if ($this->strOrderBy === 'clear') {
            $this->strOrderBy = '';
        }

        $this->blnOrderDescending = isset($_GET['orderDescending']) ? boolval($_GET['orderDescending']) : $this->blnOrderDescending;
        $this->intPageNumber = isset($_GET['pageNum']) ? intval($_GET['pageNum']) : $this->intPageNumber;
        $this->intItemsPerPage = isset($_GET['itemsPerPage']) ? intval($_GET['itemsPerPage']) : $this->intItemsPerPage;
    }

    private function getParamsFromContext(): void
    {
        $this->strPartialNombre = Context::getContextByKey('restaurantes_partialNombre');
        $this->strOrderBy = Context::getContextByKey('restaurantes_orderBy');
        $this->blnOrderDescending = boolval(Context::getContextByKey('restaurantes_orderDescending'));
        $this->intPageNumber = intval(Context::getContextByKey('restaurantes_page'));
        $this->intItemsPerPage = intval(Context::getContextByKey('restaurantes_itemsPerPage'));

        if ($this->intPageNumber < 1) {
            $this->intPageNumber = 1;
        }

        if ($this->intItemsPerPage < 1) {
            $this->intItemsPerPage = 10;
        }
    }

    private function setParamsToContext(): void
    {
        Context::setContext('restaurantes_partialNombre', $this->strPartialNombre, true);
        Context::setContext('restaurantes_orderBy', $this->strOrderBy, true);
        Context::setContext('restaurantes_orderDescending', $this->blnOrderDescending, true);
        Context::setContext('restaurantes_page', $this->intPageNumber, true);
        Context::setContext('restaurantes_itemsPerPage', $this->intItemsPerPage, true);
    }

    private function setParamsToDataView(): void
    {
        $this->arrViewData['partialNombre'] = $this->strPartialNombre;
        $this->arrViewData['orderBy'] = $this->strOrderBy;
        $this->arrViewData['orderDescending'] = $this->blnOrderDescending;
        $this->arrViewData['pageNum'] = $this->intPageNumber;
        $this->arrViewData['itemsPerPage'] = $this->intItemsPerPage;
        $this->arrViewData['restaurantesCount'] = $this->intRestaurantesCount;
        $this->arrViewData['pages'] = $this->intPages;
        $this->arrViewData['restaurantes'] = $this->arrRestaurantes;

        if ($this->strOrderBy !== '') {
            $strOrderByKey = 'Order'.str_replace(' ', '', ucwords(str_replace('_', ' ', $this->strOrderBy)));
            $strOrderByKeyNoOrder = 'OrderBy'.str_replace(' ', '', ucwords(str_replace('_', ' ', $this->strOrderBy)));
            $this->arrViewData[$strOrderByKeyNoOrder] = true;

            if ($this->blnOrderDescending) {
                $strOrderByKey .= 'Desc';
            }

            $this->arrViewData[$strOrderByKey] = true;
        }

        $arrPagination = Paging::getPagination(
            $this->intRestaurantesCount,
            $this->intItemsPerPage,
            $this->intPageNumber,
            'index.php?page=Restaurantes_Restaurantes',
            'Restaurantes_Restaurantes'
        );

        $this->arrViewData['pagination'] = $arrPagination;
    }
}
