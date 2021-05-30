<?php

function writeCSV($datas, $path){
 $fp = fopen($path, 'w');

 foreach($datas as $data){
  fputcsv($fp, $data);
 }
 fclose($fp);
}

function readCSV($path){
    $data = file_get_contents($path);
    //$data = mb_convert_encoding($data, 'UTF-8', 'sjis-win');
    $temp = tmpfile();
    $csv  = array();
    fwrite($temp, $data);
    rewind($temp);
    while (($data = fgetcsv($temp, 0, ",")) !== FALSE) {
        $csv[] = $data;
    }
    fclose($temp);

    return $csv;
}

function plusIndex($array){
 $list = [];
 $countRow = count($array);
 $countColumn = count($array[0]);
 for ($i=1; $i <$countRow ; ++$i) {
  ${$i} = [];
  for ($s=0; $s <$countColumn ; ++$s) {
   ${$i}[$array[0][$s]] = $array[$i][$s];
  }
  $list[] = ${$i};
 }
 return $list;
}

function plusKeyIndex($array, $key){
 $list = [];
 $countRow = count($array);
 $countColumn = count($array[0]);
 for ($i=1; $i <$countRow ; ++$i) {
  ${$i} = [];
  for ($s=0; $s <$countColumn ; ++$s) {
   ${$i}[$array[0][$s]] = $array[$i][$s];
  }
  $list[${$i}[$key]] = ${$i};
 }
 return $list;
}

function plusMap($array, $key, $value){
 $list = [];
 $countRow = count($array);
 $keyIndex = array_search($key, $array[0]);
 $valueIndex = array_search($value, $array[0]);
 for ($i=1; $i <$countRow ; ++$i) {

   $list[$array[$i][$keyIndex]][] = $array[$i][$valueIndex];

 }
 return $list;
}

function CSVtoDIC($path){
 return plusIndex(readCSV($path));
}

function CSVtoKeyDIC($path, $key){
 return plusKeyIndex(readCSV($path), $key);
}

function CSVtoMAP($path, $key, $value){
 return plusMap(readCSV($path), $key, $value);
}

function foreachCount($number, $array){
 $i = 0;
 $newArray = [];
 foreach ($array as $value) {
  if ($i >= $number) {
   break;
  }else{
   $newArray[] = $value;
   ++$i;
  }
 }
 return $newArray;
}
?>
