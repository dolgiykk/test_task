<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}
?>
<main class="main main--no-indent pb-0" role="main">
    <form action="" method="get">
    <? foreach ($arResult['EVENT_YEARS'] as $year): ?>
        <span>
            <button type="submit" name="year" value="<?= $year ?>"><?= $year ?></button>
        </span>
    <? endforeach; ?>
    </form>
    <? $counter = 0; ?>
    <div class="container">
    <? foreach ($arResult['EVENTS'] as $event): ?>
        <div class="element">
            <img src="<?= $event['PREVIEW_PICTURE'] ?>" alt=""><br>
            <span><?= $event['NAME'] ?></span>
            <span><?= $event['PREVIEW_TEXT'] ?></span>
            <span><?= $event['PROPERTY_DATE'] ?></span>
        </div>
    <? endforeach; ?>
    </div>
    <div style="float: left">
    <?php
        $APPLICATION->IncludeComponent(
            "bitrix:main.pagenavigation",
            "",
            [
                "PAGE_WINDOW" => 10,
                "NAV_OBJECT" => $arResult['NAV'],
                "SEF_MODE" => "N"
            ],
            false
        );
    ?>
    </div>
</main>

<style>
    img {
        width: 150px;
        height: 150px
    }
    .container {
        display: flex
    }
    .element {
        padding: 10px;
    }
</style>