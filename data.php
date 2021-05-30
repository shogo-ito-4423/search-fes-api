<?php

date_default_timezone_set('Asia/Tokyo');

require_once('./conf.php');
require_once('./category.php');
require_once('./place.php');
require_once('./event.php');
require_once('./search.php');

$standDic = CSVtoKeyDIC('./csv/stand.csv', 'number');
$categoryDic = CSVtoKeyDIC('./csv/category.csv', 'categoryId');
$categoryMapDic = CSVtoDIC('./csv/categoryMap.csv');
$placeDic = CSVtoKeyDIC('./csv/place.csv', 'placeNum');
$eventDic = CSVtoDIC('./csv/event.csv');

$categoryObj = new Category($categoryDic);
$categoryMapObj = new CategoryMap($categoryObj, $categoryMapDic);
$placeObj = new Place($placeDic);
$placeMapObj = new PlaceMap($placeObj, $eventDic);
$eventObj = new Event($eventDic);

$searchObj = new Search($categoryMapObj, $placeMapObj, $eventObj);

?>
