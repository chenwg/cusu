<?php
declare(strict_types=1);
namespace app\admin\model;
use app\common\model\Article as Particle;
use think\Cache;
class Article extends Particle
{
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
  public static function s(string $keywords,int $page=0):array{
    return self::search($keywords,$page);
  }
  public static function inArticle(int $id=0,array $articleData,string $articleInfo=''){
    if(!empty($articleData['curl']) && empty($articleData['title'])){
      $articleData['title'] = (new \app\common\controller\Reptile())->get_title($articleData['curl']);
    }
    if(empty($articleData['img1']) && !empty($articleInfo)){
      $pattern = '/<img.*?src="(.*?)".*?>/i';
      preg_match_all($pattern,$articleInfo,$match);
      isset($match[1][0]) && $articleData['img1'] = $match[1][0];
      isset($match[1][1]) && $articleData['img2'] = $match[1][1];
      isset($match[1][2]) && $articleData['img3'] = $match[1][2];
      isset($match[1][3]) && $articleData['img4'] = $match[1][3];
    }
    $articleModel = new Article;
    $en = $articleData['en'];
    $articleCache = array_merge($articleData,['info'=>$articleInfo,'id'=>$id]);
    $count = $articleModel->where(['en'=>$en,'is_delete'=>0])->count();
    if($id>0){
      $resRow = $articleModel->where('id',$id)->update($articleData);
      $resRowInfo = (new ArticleInfo)->where('aid',$id)->update(['info'=>$info]);
      if($resRow > 0 || $resRowInfo > 0){
        Cache::rm('a_'.$id);
        Cache::set('a_'.$id,['data'=>$articleCache,'title'=>$articleData['title']]);
        file_exists('word/'.md5('a_'.$id).'.doc') && unlink('word/'.md5('a_'.$id).'.doc');
      }
      if($resRow > 0){
        $articleOne = $articleModel->where('id',$id)->find();
        Cache::rm($en);
        if($articleOne['en'] != $en){
          Cache::rm($articleOne['en']);
          $countold = $articleModel->where('en',$articleOne['en'])->count();
          for($i=1,$j=1;$i<ceil($countold/config('page_size'))+2,$j<ceil($count/config('page_size'))+2;$i++,$j++){
            Cache::rm($articleOne['en'].$i);
            Cache::rm($en.$j);
          }
        }
      }
      return _res(1);
    }else{
      if(!empty(Article::get(['title'=>$articleData['title'],'en'=>$en])))return _res(2);
      $art->save($articleData);
      $aid = $articleModel->id;
      if($aid>0){
        (new ArticleInfo)->save(['aid'=>$aid,'info'=>$articleInfo]);
        Cache::rm($en);
        for($i=1;$i<ceil($count/config('page_size'))+2;$i++){
          Cache::rm($en.$i);
        }
        Cache::set('a_'.$aid,['data'=>$articleCache,'title'=>$articleData['title']]);
        return _res(1,$aid);
      }
    }
    return _res(0);
  }

}
