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
    return (!empty(Cache::get('cusuCate'))) ? Cache::get('cusuCate')
        : self::cacheAllCate();
  }

  public static function getOneCate(int $id):array{
    return Cate::get($id)->toArray();
  }

  public static function getFamilyCate(int $id):array{
    return (new \app\common\logic\CateLogic())
            ->infinite_category(Cate::where('path','like','%_'.$id.'_%')->select());
  }

  public static function getCate(array $condition=[]):array{
    return Cate::where($condition)->select();
  }

  public static function getUseCate(){
    $cate = Cache::get('useCate');
    $p1 = Cate::where('p1','gt',0)->column('p1');
    $p2 = Cate::where('p2','gt',0)->column('p2');
    $p = array_unique(array_merge($p1,$p2));
    if(!empty($cate)){
      return $cate;
    }else{
      $allCate = Cate::getCate(['is_delete'=>0]);
      foreach($allCate as $k=>$v){
        if(in_array($v['id'],$p)){
          unset($allCate[$k]);
        }
      }
      Cache::set('useCate',$allCate);
      return $allCate;
    }
  }

  public function insertCate(array $cateData){
    return (new Cate())->allowField(true)->save($cateData) ? _res(1,'新增成功') : _res(0,'提交失败，请重试');
  }

  public function updateCate(array $cateData,array $condition){
    if(!empty($cateData['prePath']) && $cateData['prePath'] != $cateData['path']){
      Db::execute("UPDATE ".config('database.prefix')."cate SET path=REPLACE(path,'".
        $cateData['prePath']."','".$cateData['path']."') WHERE path like '%".$cateData['prePath']."%'");
    }
    return is_numeric((new Cate())->allowField(true)->isUpdate(true)->save($cateData,$condition)) ? _res(1,'编辑成功') : _res(0,'提交失败，请重试');
  }

  public function deleteCate(int $id=0){
    if(empty('uid'))return _res(0);
    if(config('soft_delete')){
      Cate::where('id',$id)->update(['is_delete'=>1]);
      Db::execute("UPDATE ".config('database.prefix')."cate SET is_delete = 1 WHERE find_in_set('".$id."',path)");
    }else{
      Cate::destroy($id);
      Db::execute("DELETE FROM ".config('database.prefix')."cate WHERE find_in_set('".$id."',path)");
    }
    return _res(1);
  }

  public static function valid(array $condition):bool{
    return (!empty(self::getCate($condition))) ? true : false;
  }

  public static function cacheAllCate(){
    Cache::set('susuCate',(new \app\common\logic\CateLogic())->infinite_category(Cate::column('*','id')));
    return Cache::get('susuCate');
  }
  public function rmCateCache(){
    Cache::rm('susuCate');
    Cache::rm('useCate');
    return $this;
  }
  public function validName(array $cateData=[],array $otherCondition=[]){
    $conditionCn = ['name'=>$cateData['name'],'level'=>$cateData['level']];
    $conditionEn = ['en'=>$cateData['en'],'level'=>$cateData['level']];
    if(!empty($otherCondition)){
      $conditionCn = array_merge($conditionCn,$otherCondition);
      $conditionEn = array_merge($conditionEn,$otherCondition);
    }
    if(self::valid($conditionCn)){
      _end('中文名称重复');
    }
    if(self::valid($conditionEn)){
      _end('英文名称重复');
    }
    return $this;
  }
}
