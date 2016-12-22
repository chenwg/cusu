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
  public static function s(string $kw,int $page=0):array{
    return self::search($kw,$page);
  }
  public static function inArticle(int $id=0,array $data,string $info=''){
    if(!empty($data['curl']) && empty($data['title']))$data['title'] = (new \app\common\controller\Reptile())->get_title($data['curl']);
    if(empty($data['img1']) && !empty($info)){
      $pattern = "/[img|IMG].*?src=['|\"](.*?(?:[.gif|.jpg|.png|.PNG|.jpeg]))['|\"].*?[\/]?>/";
      preg_match_all($pattern,$info,$match);
      return _res(4,$math);
      if(isset($match[0]))$data['img1'] = $match[0];
      if(isset($match[1]))$data['img2'] = $match[1];
      if(isset($match[2]))$data['img3'] = $match[2];
      if(isset($match[3]))$data['img4'] = $match[3];
    }
    $art = new Article;
    $en = $data['en'];
    $dcache = array_merge($data,['info'=>$info,'id'=>$id]);
    $count = $art->where(['en'=>$en,'is_delete'=>0])->count();
    if($id>0){
      $resRow = $art->where('id',$id)->update($data);
      $resRowi = (new ArticleInfo)->where('aid',$id)->update(['info'=>$info]);
      if($resRow > 0 || $resRowi > 0){
        Cache::rm('a_'.$id);
        Cache::set('a_'.$id,['data'=>$dcache,'title'=>$data['title']]);
        file_exists('word/'.md5('a_'.$id).'.doc') && unlink('word/'.md5('a_'.$id).'.doc');
      }
      if($resRow > 0){
        $arr = $art->where('id',$id)->find();
        Cache::rm($en);
        if($arr['en'] != $en){
          Cache::rm($arr['en']);
          $countold = $art->where('en',$arr['en'])->count();
          for($i=1,$j=1;$i<ceil($countold/config('page_size'))+2,$j<ceil($count/config('page_size'))+2;$i++,$j++){
            Cache::rm($arr['en'].$i);
            Cache::rm($en.$j);
          }
        }
      }
      return _res(1);
    }else{
      if(!empty(Article::get(['title'=>$data['title'],'en'=>$en])))return _res(2);
      $art->save($data);
      $aid = $art->id;
      if($aid>0){
        (new ArticleInfo)->save(['aid'=>$aid,'info'=>$info]);
        Cache::rm($en);
        for($i=1;$i<ceil($count/config('page_size'))+2;$i++){
          Cache::rm($en.$i);
        }
        Cache::set('a_'.$aid,['data'=>$dcache,'title'=>$data['title']]);
        return _res(1,$aid);
      }
    }
    return _res(0);
  }

}
