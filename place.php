<?php

require_once('./numberTrait.php');
require_once('./arrayTrait.php');

/**
 *
 */
class Place
{

 use ArrayTrait;
 private $placeList;

 function __construct($placeList){
  $this->placeList = array_column($placeList, null, 'placeNum');
 }

 public function getPlace($placeNum){
  $n = substr($placeNum, 0, -3);
  $f = substr($placeNum, -3, 1);
  $p = substr($placeNum, -2, 2);
  $alpha = range('A', 'Z');
  $place = ['placeNum'=>null, 'placeName'=>null, 'nextPlaceNum'=>null];
  $place['placeNum'] = $placeNum;

  if ($placeNum < 100) {
   if (isset($this->placeList[$placeNum])) {
    $place['placeName'] = $this->placeList[$placeNum]['placeName'];
    $place['nextPlaceNum'] = $placeNum + 1;
   }

  }elseif ((100 <= $placeNum) && ($placeNum < 1000)) {
   if ($p == '00') {
    $place['placeName'] = '室外'.$alpha[($f-1)].'ブロック';
    $place['nextPlaceNum'] = $placeNum + 100;

   }else {
    $place['placeName'] = '室外'.$alpha[($f-1)].'ブロック-'.$p;
    $place['nextPlaceNum'] = $placeNum + 1;
   }

  }elseif (1000 <= $placeNum) {
   if ($f.$p == '000') {
    $place['nextPlaceNum'] = $placeNum + 1000;
    if (isset($this->placeList[$placeNum])) {
     $place['placeName'] = $this->placeList[$placeNum]['placeName'];

    }else {
     $place['placeName'] = $n.'号館';
    }

   }elseif ($p == '00') {
    $place['nextPlaceNum'] = $placeNum + 100;
    if (isset($this->placeList[$n.'000'])) {
     $place['placeName'] = $this->placeList[$n.'000']['placeName'].$f.'階';

    }else {
     $place['placeName'] = $n.'号館'.$f.'階';
    }


   }else {
    $place['nextPlaceNum'] = $placeNum + 1;
    if (isset($this->placeList[$placeNum])) {
     if (isset($this->placeList[$n.'000'])) {
      $place['placeName'] = $this->placeList[$n.'000']['placeName'].$f.'階'.$this->placeList[$placeNum]['placeName'];

     }else {
      $place['placeName'] = $n.'号館'.$f.'階'.$this->placeList[$placeNum]['placeName'];
     }

    }else {
     $place['placeName'] = $n.'号館'.$f.'階'.$placeNum.'教室';
    }
   }

  }

  return $place;
 }

 public function getPlaces($placeNums, $key = null){
  $places = array_map(function($placeNum){
   return $this->getPlace($placeNum);
  }, $placeNums);
  array_multisort(array_column($places, 'placeNum'), SORT_ASC, $places);
  if ($key) {
   return $this->filterColumn($places, $key, 'placeNum');
  }else {
   return array_column($places, null, 'placeNum');
  }
 }

  public function getPlaceKey(){
   return array_keys($this->getPlace(null));
  }
}

/**
 *
 */
class PlaceMap
{

 use NumberTrait;
 use ArrayTrait;
 private $placeObj;
 private $placeMap;
 const NUMBER = 'eventNum';

 function __construct($placeObj, $placeMap)
 {
  $this->placeObj = $placeObj;
  $this->placeMap = $this->filterColumn($placeMap, [self::NUMBER, 'placeNum']);
 }

 public function getKey(){
  return $this->placeObj->getPlaceKey();
 }

 public function getSearchKey(){
  return 'placeNum';
 }

 public function getEvent($eventNum, $key = null){
  $placeMapList = array_filter($this->placeMap, function($pair) use($eventNum){
   return $pair[self::NUMBER] == $eventNum;
  });
  $placeNumList = array_unique(array_column($placeMapList, 'placeNum'));
  return $this->placeObj->getPlaces($placeNumList, $key);
 }

 public function get($number, $key = null){
  $placeMapList = array_filter($this->placeMap, function($pair) use($number){
   return $this->getNumber($pair[self::NUMBER]) == $number;
  });
  $placeNumList = array_unique(array_column($placeMapList, 'placeNum'));
  return $this->placeObj->getPlaces($placeNumList, $key);
 }

 public function search($placeNum, $returnEventNum = false){
  $key = $this->placeObj->getPlace($placeNum);
  $resultSet = array_filter($this->placeMap, function($pair) use($key){
   return $key['placeNum'] <= $pair['placeNum'] && $pair['placeNum'] < $key['nextPlaceNum'];
  });
  $resultNums = array_unique(array_column($resultSet, self::NUMBER));
  return $this->returnNumbers($resultNums, $returnEventNum);
 }
}

?>
