<?php

require_once('./numberTrait.php');
require_once('./arrayTrait.php');

class Category
{

 use ArrayTrait;
 private $category;
 const KEY = ['superCategoryId', 'categoryId', 'categoryName'];

 function __construct($category)
 {
  $this->category = $this->filterColumn($category, self::KEY, 'categoryId');
 }

 public function getCategory($categoryId){
  return $this->category[$categoryId];
 }

 public function getCategorys($categoryIds, $key = null){
  $categorys = array_filter($this->category, function($pair) use($categoryIds){
   return in_array($pair['categoryId'], $categoryIds);
  });
  if ($key) {
   return $this->filterColumn($categorys, $key, 'categoryId');
  }else {
   return array_column($categorys, null, 'categoryId');
  }
 }

 public function getCategoryList($superCategoryId){
  return array_filter($this->category, function($pair) use($superCategoryId){
   return $pair['superCategoryId'] == $superCategoryId;
  });
 }

 public function getCategoryKey(){
  return self::KEY;
 }
}


class CategoryMap
{

 use NumberTrait;
 private $categoryObj;
 private $categoryMap;

 function __construct($categoryObj, $categoryMap)
 {
  $this->categoryObj = $categoryObj;
  $this->categoryMap = $categoryMap;
 }

 public function getKey(){
  return $this->categoryObj->getCategoryKey();
 }

 public function getSearchKey(){
  return 'categoryId';
 }

 public function get($number, $key = null){
  $categoryMapList = array_filter($this->categoryMap, function($pair) use($number){
   return $pair['number'] == $this->getNumber($number);
  });
  $categoryIdList = array_column($categoryMapList, 'categoryId');
  return $this->categoryObj->getCategorys($categoryIdList, $key);
 }

 public function search($categoryId){
  $result = array_filter($this->categoryMap, function($pair) use($categoryId){
   return $pair['categoryId'] == $categoryId;
  });
  return array_column($result, 'number');
 }

 public function searchOR($categoryIds){
  $result = array_filter($this->categoryMap, function($pair) use($categoryIds){
   return in_array($pair['categoryId'], $categoryIds);
  });
  return array_values(array_unique(array_column($result, 'number')));
 }
}

?>
