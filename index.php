<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
require $_SERVER['DOCUMENT_ROOT'] . '/local/templates/example/header.php';

$APPLICATION->SetTitle("Test");

$APPLICATION->IncludeComponent(
    'test:test',
    '',
    [
        'IBLOCK_API_CODE' => config('iblock.Test'),
        'PAGE_WINDOW' => 10,
        'PROPERTY_CODE' => 'TEST_LIST',
        'PAGE_SIZE' => 10,
        'CACHE_TYPE' => 'Y',
        'CACHE_TIME' => 3600
    ]
);

$entityId = 'IBLOCK_1_SECTION'; // Объект (CRM-компания)
$valueId = 8; // ID экземпляра (ID CRM-компании)
$fieldId = 'UF_TEST_LIST'; // Код поля

$result = $GLOBALS["USER_FIELD_MANAGER"]->GetUserFields($entityId, $valueId);
echo "Значение поля $fieldId = " . $result[$fieldId]['VALUE'];


require $_SERVER['DOCUMENT_ROOT'] . '/local/templates/example/footer.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php';


