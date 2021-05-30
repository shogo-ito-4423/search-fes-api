<?php

/**
 *
 */
trait NumberTrait
{
 public function getNumber($eventNum)
 {
  return explode('-', $eventNum)[0];
 }

 public function returnNumbers($eventNums, $returnEventNum = false){
  if ($returnEventNum) {
   return array_values($eventNums);
  }else {
   $numbers = array_map(function($eventNum){
    return $this->getNumber($eventNum);
   }, $eventNums);
   return array_values(array_unique($numbers));
  }
 }
}


?>
