<?php
declare(strict_types=1);
namespace app\common\model;
use think\Model;
use think\Cache;
use think\Db;
class Cate extends Model
{
  protected static function init(){

  }
  protected static function base($query){
    $query->where('is_delete',0);
  }
  public static function getAllCate(){
    return Cache::get('cusuCate') ? Cache::get('cusuCate') : (new \app\common\logic\CateLogic())->infinite_category(Cate::where('is_delete',0)->column('*','id'));
  }

  public static function getOneCate(int $id):array{
    return Cate::get($id)->toArray();
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
    self::cacheAllCate();
    return (new Cate($cateData))->allowFeild(true)->save();
  }

  public static function updateCate(array $cateData,array $condition){
    if(self::valid(['cate_name'=>$cateData['cate_name'],'level'=>$cateData['level'],'id'=>'!='.$condition['id']])){
      return _res(2);
    }
    if($cateData['prePath'] != $cateData['path']){
      Db::execute("UPDATE ".config('database.prefix')."cate SET path=REPLACE(path,'".
        $cateData['prePath']."','".$cateData['path']."') WHERE path like '%".$cateData['prePath']."%'");
    }
    self::cacheAllCate();
    return (new Cate())->allowFeild(true)->isUpdate(true)->save($cateData,$condition);
  }

  public static function deleteCate(int $id){
    if(empty('uid'))return _res(0);
    if(config('soft_delete')){
      Cate::where('id',$id)->update(['is_delete'=>1]);
      Db::execute("UPDATE ".config('database.prefix')."cate SET is_delete = 1 WHERE find_in_set('".$id."',path)");
    }else{
      Cate::destroy($id);
      Db::execute("DELETE FROM ".config('database.prefix')."cate WHERE find_in_set('".$id."',path)");
    }
    self::cacheAllCate();
    return _res(1);
  }

  public static function valid(array $condition):bool{
    return (!empty(self::getCate($condition))) ? true : false;
  }

  private static function cacheAllCate(){
    Cache::set('susuCate',(new \app\common\logic\CateLogic())->infinite_category(Cate::column('*','id')));
  }
}
