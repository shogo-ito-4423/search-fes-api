<?php
ini_set('display_errors', 0);
require_once('./data.php');

$numbers = null;
$keys = null;
$conditions = [];

$parameters = $_GET;

foreach ($parameters as $prmKey=>$prmValue) {
 if (!is_array($prmValue) && strpos($prmValue, ',') !== false) {
  $prmValue = explode(',', $prmValue);
 }

 if ($prmKey == 'numbers') {
  $numbers = $prmValue;
 }elseif ($prmKey == 'keys') {
  $keys = $prmValue;
 }else {
  $conditions[$prmKey] = $prmValue;
 }
}


if ($conditions) {
 $result = $searchObj->getStand($searchObj->searchNumber($conditions), $keys);
}else {
 $result = $searchObj->getStand($numbers, $keys);
}

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
//header("Access-Control-Allow-Origin: *");

if (!$parameters) {
 http_response_code(400);
 exit;
}else {
 if ($result) {
  echo json_encode($result, JSON_UNESCAPED_UNICODE);
 }else {
  http_response_code(204);
  exit;
 }
}

?>
