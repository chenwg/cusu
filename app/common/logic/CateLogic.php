<?php
declare(strict_types=1);
namespace app\common\logic;
use think\Request;
class CateLogic{
  function infinite_category(array $cateArray):array{
    $cateTree = [];
    foreach($cateArray as $v){
      if(isset($cateArray[$v['pid']])){
        $cateArray[$v['pid']]['children'][] = &$cateArray[$v['id']];
      }else{
        $cateTree[] = &$cateArray[$v['id']];
      }
    }
    return $cateTree;
  }
}
