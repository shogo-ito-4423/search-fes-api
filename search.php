<?php

/**
 *
 */
class Search
{

 private $objectIdList = [];
 private $mapList = [];
 private $keyList = [];
 private $searchKeyList = [];

 function __construct(...$mapList)
 {
  foreach ($mapList as $map) {
   $objectId = spl_object_id($map);
   $this->objectIdList[] = $objectId;
   $this->mapList[$objectId] = $map;
   $this->keyList = $this->keyList + array_fill_keys($map->getKey(), $objectId);
   $this->searchKeyList[$map->getSearchKey()] = $objectId;
  }
 }

 public function searchNumber($query){
  $numbers = null;
  foreach ($query as $key => $value) {
   if (is_array($value)) {
    $keyNumbers = $this->mapList[$this->searchKeyList[$key]]->searchOR($value);
   }else {
    $keyNumbers = $this->mapList[$this->searchKeyList[$key]]->search($value);
   }
   if (is_null($numbers)) {
    $numbers = $keyNumbers;
   }else {
    $numbers = array_intersect($numbers, $keyNumbers);
   }

  }
  sort($numbers);
  return $numbers;
 }

 public function getStand($numbers, $keys = null){

  if (!is_array($numbers)) {
   $numbers = explode(',', $numbers);
  }
  if ($keys && !is_array($keys)) {
   $keys = explode(',', $keys);
  }

  if ($keys) {
   $useKeys = array_intersect_key($this->keyList, array_flip($keys));
  }else {
   $useKeys = $this->keyList;
  }

  $results = [];
  foreach ($numbers as $number) {
   foreach ($useKeys as $key=>$id) {
    if ($this->mapList[$id]->get($number, $key)) {
     $results[$number][$key] = $this->mapList[$id]->get($number, $key);
    }
   }
  }
  return $results;
 }
}

?>
