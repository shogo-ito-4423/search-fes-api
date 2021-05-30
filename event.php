<?php

require_once('./numberTrait.php');
require_once('./arrayTrait.php');

/**
 *
 */
class Event
{

 use NumberTrait;
 use ArrayTrait;
 private $event;
 const NUMBER = 'eventNum';
 const KEY = [self::NUMBER, 'startTime', 'finishTime'];

 function __construct($event)
 {
  $filterdEvent = $this->filterColumn($event, self::KEY);
  $this->event = array_map(function($pair){
   $pair['startTime'] = new DateTime($pair['startTime']);
   $pair['finishTime'] = new DateTime($pair['finishTime']);
   return $pair;
  }, $filterdEvent);
 }

 public function getKey(){
  return self::KEY;
 }

 public function getSearchKey(){
  return 'date';
 }

 public function getEvent($eventNum, $key = null){
  $eventSet = array_filter($this->event, function($pair) use($eventNum){
   return $pair[self::NUMBER] == $eventNum;
  });
  array_multisort(array_column($eventSet, 'startTime'), SORT_ASC, $eventSet);
  return $this->filterColumn($eventSet, $key, self::NUMBER);
 }

 public function get($number, $key = null){
  $eventSet = array_filter($this->event, function($pair) use($number){
   return $this->getNumber($pair[self::NUMBER]) == $number;
  });
  array_multisort(array_column($eventSet, 'startTime'), SORT_ASC, $eventSet);
  return $this->filterColumn($eventSet, $key, self::NUMBER);
 }

 public function search($date, $returnEventNum = false){
  $resultSet = array_filter($this->event, function($pair) use($date){
   return $pair['startTime']->format('md') == $date;
  });
  $resultEventNums = array_unique(array_column($resultSet, self::NUMBER));
  return $this->returnNumbers($resultEventNums, $returnEventNum);
 }

 public function searchOR($dates, $returnEventNum = false){
  $resultSet = array_filter($this->event, function($pair) use($dates){
   return in_array($pair['startTime']->format('md'), $dates);
  });
  $resultEventNums = array_unique(array_column($resultSet, self::NUMBER));
  return $this->returnNumbers($resultEventNums, $returnEventNum);
 }

 public function searchFrom($from){
  $fromTime = new DateTime($from);
  $result = array_filter($this->event, function($pair) use($fromTime){
   return $fromTime <= $pair['startTime'];
  });
  return array_values(array_unique(array_column($result, self::NUMBER)));
 }

 public function searchTo($to){
  $toTime = new DateTime($to);
  $result = array_filter($this->event, function($pair) use($toTime){
   return $pair['finishTime'] <= $toTime;
  });
  return array_values(array_unique(array_column($result, self::NUMBER)));
 }

 public function searchRange($from, $to){
  $fromTime = new DateTime($from);
  $toTime = new DateTime($to);
  $result = array_filter($this->event, function($pair) use($fromTime, $toTime){
   return ($fromTime <= $pair['startTime']) && ($pair['finishTime'] <= $toTime);
  });
  return array_values(array_unique(array_column($result, self::NUMBER)));
 }
}

?>
