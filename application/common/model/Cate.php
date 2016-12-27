<?php
declare(strict_types=1);
namespace app\common\model;
use think\Model;
use think\Cache;
class Cate extends Model
{
  public static function getAllCate(){
    return (new \app\common\logic\CateLogic())->infinite_category(Cate::get()->toArray());
  }

  public static function getOneCate(int $id){
    return Cate::get($id);
  }

  public static function getFamilyCate(int $id):array{
    return (new \app\common\logic\CateLogic())
            ->infinite_category(Cate::where('path','like','%_'.$id.'_%')->select());
  }

  public static function getCate(array $condition):array{
    return Cate::where($condition)->select();
  }

  public static function insertCate(array $cateData){
    if(self::valid(['cate_name'=>$cateData['cate_name'],'level'=>$cateData['level']])){
      return _res(2);
    }
    return (new Cate($cateData))->allowFeild(true)->save();
  }

  public static function updateCate(array $cateData,array $condition){
    if(self::valid(['cate_name'=>$cateData['cate_name'],'level'=>$cateData['level'],'id'=>'!='.$condition['id']])){
      return _res(2);
    }
    return (new Cate())->allowFeild(true)->isUpdate(true)->save($cateData,$condition);
  }

  public static function valid(array $condition):bool{
    if(!empty(self::getCate($condition)){
      return true;
    }
    return false;
  }
}
