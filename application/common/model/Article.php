<?php
declare(strict_types=1);
namespace app\common\model;
use think\Model;
use app\common\model\ArticleInfo;
use app\common\model\ArticleImg;
use think\Cache;
class Article extends Model
{
  protected $autoWriteTimestamp = true;
  protected $updateTime = false;
  protected static function init(){
    //no no no no bush
  }
  protected static function base($query){
    $query->where('is_delete',0);
    $query->order('create_time desc');
  }
  //关联info表，取
  public function articleInfo(){
    return $this->hasOne('ArticleInfo','aid');
  }
  //关联img表，取
  public function articleImg(){
    return $this->hasMany('ArticleImg','aid');
  }
  public static function joinInfo(array $condition):array{
    return Article::alias('a')->join(config('database.prefix').'article_info b','a.id=b.aid','left')
    ->where($condition)->find()->toArray();
  }
  public static function joinImg(array $condition,int $count = 1):array{
    $mod = Article::alias('a')->join(config('database.prefix').'article_img b','a.id=b.aid','left')
    ->where($condition);
    return ($count > 1) ? $mod->select()->toArray() : $mod->find()->toArray();
  }
  public static function joinImgInfo(array $condition):array{
    return Article::alias('a')->join(config('database.prefix').'article_img b','a.id=b.aid','left')
    ->join(config('database.prefix').'article_info c','a.id=c.aid','left')
    ->where($condition)->find()->toArray();
  }
  public static function pageArticle(array $condition,int $page=0,string $res = null){
    $resObject = Article::where($condition)->paginate($page>0 ? $page : config('page_size'));
    return ($res == null) ? $resObject : $resObject->toArray();
  }
  public static function search(string $keywords,int $page=0){
    $keywords = strtr(urldecode($keywords), array(' '=>''));
    if(config('full_text')){
      //fenci
      return [];
    }else{
      return Article::where('title|profile','like','%'.$keywords.'%')
      ->paginate($page>0 ? $page : config('page_size'),false,['query' =>array('kw'=>$keywords,'coding'=>'utf-8')]);
    }
  }
}
