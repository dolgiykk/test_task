<?php

namespace App\Repositories;

use App\Repositories\Shared\AbstractIblockRepository;
use Bitrix\Iblock\Elements\ElementEventsTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;

/**
 * Для корректной работы со свойствами элемента ИБ необходимо в настройках ИБ узакать Символьный код API
 * И подклюить класс Bitrix\Iblock\Elements\Element+Символьный код Api+Table (В нашем случае это ElementEventsTable)
 */
class EventsIblockRepository extends AbstractIblockRepository
{
    public function __construct()
    {
        parent::__construct(ElementEventsTable::class);
    }

    /**
     * @return string
     */
    public function getIblockCode(): string
    {
        return "Events";
    }

    /**
     * @return void
     * @throws ArgumentException
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function init(): void
    {
        $this->setSelectFields([
            'ID',
            'NAME',
            'PREVIEW_TEXT',
            'DATE_CREATE',
            'PREVIEW_PICTURE',
            'PROPERTY_DATE' => 'DATE.VALUE',
        ]);
        parent::init();
    }
}
