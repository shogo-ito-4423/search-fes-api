<?php

/**
 *
 */
trait ArrayTrait
{
 public function filterColumn($array, $key, $index = null)
 {
  if (is_array($key)) {
   $filterdArray = array_map(function($pair) use($key){
    return array_intersect_key($pair, array_flip($key));
   }, $array);
   if ($index) {
    return array_column($filterdArray, null, $index);
   }else {
    return array_values($filterdArray);
   }
  }else {
   return array_column($array, $key, $index);
  }
 }
}

?>
