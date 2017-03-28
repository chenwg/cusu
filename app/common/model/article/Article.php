<?php
declare(strict_types=1);
namespace app\common\model\article;
use think\Model;
use app\common\interfacelib\article\ArticleAction;
use app\common\model\article\ArticleInfo;
use app\common\model\article\ArticleImg;
use think\Cache;
class Article extends Model implements ArticleAction
{
  protected $autoWriteTimestamp = true;
  protected $updateTime = false;
  protected $auto=[];
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
  public static function deleteArticle(int $id,string $en){
    if(empty('uid'))return _res(0);
    if(config('soft_delete')){
      Article::where(['id'=>$id])->update(['is_delete'=>1]);
    }else{
      Article::where(['id'=>$id])->delete();
      ArticleImg::where(['aid'=>$id])->delete();
      ArticleInfo::where(['aid'=>$id])->delete();
    }
    $count = (new Article)->where(['en'=>$en,'is_delete'=>0])->count();
    Cache::rm($en);
    for($i=1;$i<ceil($count/config('page_size'))+2;$i++){
      Cache::rm($en.$i);
    }
    return _res(1);
  }
}
