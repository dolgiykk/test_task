<?php

if (! defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use App\Helpers\Params\GetListParams;
use App\Helpers\Params\PagenavigationParams;
use App\Services\EventsIblockService;
use Bitrix\Main\Application;
use Bitrix\Main\Context;
use Bitrix\Main\Data\Cache;

class SimplenewsComponent extends CBitrixComponent
{
    private ?int $year;

    private ?string $page;

    private EventsIblockService $eventsIblockService;

    /**
     * @throws Exception
     */
    public function executeComponent()
    {
        global $APPLICATION;

        $this->year = Context::getCurrent()->getRequest()->getQuery('year') ?? null;
        $this->page = Context::getCurrent()->getRequest()->getQuery('page') ?? null;
        $this->eventsIblockService = EventsIblockService::getInstance();
        $cache = Cache::createInstance();
        $cacheKey = "pagenavigation_{$this->year}_{$this->page}_{$this->arParams['PAGE_SIZE']}";

        if ($this->arParams['CACHE_TYPE'] === 'N') {
            $this->getResult();
        } elseif ($this->arParams['CACHE_TYPE'] === 'Y') {
            if ($cache->initCache($this->arParams['CACHE_TIME'], $cacheKey)) {
                $this->arResult = $cache->getVars();
            } elseif ($cache->startDataCache($this->arParams['CACHE_TIME'], $cacheKey)) {
                $this->getResult();
                $cache->endDataCache($this->arResult);
            }
        } else {
            $obCache = Application::getInstance()->getManagedCache();
            if ($obCache->read($this->arParams['CACHE_TIME'], $cacheKey)) {
                $this->arResult = $obCache->get($cacheKey);
            } else {
                $this->getResult();
                $obCache->set($cacheKey, $this->arResult);
            }
        }

        $APPLICATION->SetTitle('Список новостей (' . count($this->arResult['EVENTS']) . ') шт.');
        $this->includeComponentTemplate();
    }

    /**
     * @throws \Protobuf\Exception
     */
    private function getResult(): void
    {
        $this->getAllYears();

        if (is_null($this->year)) {
            $this->year = $this->arResult['EVENT_YEARS'][0];
        }

        $getListParams = new GetListParams();
        $getListParams->setFilter([
            'ACTIVE' => 'Y',
            '>=PROPERTY_DATE' => ConvertDateTime("01.01.{$this->year}", 'YYYY-MM-DD'),
            '<=PROPERTY_DATE' => ConvertDateTime("31.12.{$this->year}", 'YYYY-MM-DD'),
        ]);

        $pageNavigationParams = new PagenavigationParams();
        $pageNavigationParams->setPageSize($this->arParams['PAGE_SIZE']);
        $listPaginated = $this->eventsIblockService->getListPaginated($getListParams, $pageNavigationParams);

        $this->arResult['EVENTS'] = $listPaginated->getItems();
        foreach ($this->arResult['EVENTS'] as &$value) {
            $value['PREVIEW_PICTURE'] = \CFile::GetPath($value['PREVIEW_PICTURE']);
        }

        $this->arResult['PAGE'] = $this->page;
        $this->arResult['NAV'] = $listPaginated->getNav();
    }

    private function getAllYears(): void
    {
        $getListParams = new GetListParams();
        $getListParams->setFilter(['ACTIVE' => 'Y']);
        $getListParams->setOrder(['PROPERTY_DATE' => 'DESC']);
        $eventsAll = $this->eventsIblockService->getList($getListParams);

        foreach ($eventsAll as $event) {
            $explodedDate = explode('-', $event['PROPERTY_DATE']);
            $this->arResult['EVENT_YEARS'][] = $explodedDate[0];
        }

        $this->arResult['EVENT_YEARS'] = array_values(array_unique($this->arResult['EVENT_YEARS']));
    }
}
