<?php
declare(strict_types=1);
namespace app\home\controller\article;
use app\home\controller\init\Entry;
use think\Request;
use app\home\logic\ArticleLogic;
use app\home\model\article\Article as ArticleM;
use think\Cache;
final class Article extends Entry
{
  public function res(string $en='suibi',int $id=0){
    if($id>0){
      $data = self::getInfoById($id);
    }else{
      $data = Cache::get($en.request()->get('page'));
      if(empty($data)){
        $article = ArticleM::pageArticle(['en'=>$en]);
        $data = ['data'=>$article,'page'=>$article->render(),'title'=>config('titleArr.'.$en)];
        Cache::set($en.request()->get('page'),$data);
      }
    }
    return $this->view('article/article',$data);
  }

  //$id=0
  public function export_word(int $id=0){
    if($id>0){
      $data = self::getInfoById($id);
      $content = '<h3>'.$data['title'].'</h3><br>'.'<p>'.$data['data']['info'].'</p>';
      return export(1,$id,$content);
    }
    return 'error';
  }

  public function export_pdf(int $id=0){
    if($id>0){
      $data = self::getInfoById($id);
      return export(2,$id,$data['data']);
    }
    return 'error';
  }

  public function s(Request $req){
    return $this->view('article/article',
    empty($req->get('kw')) ? ['data'=>null,'title'=>config('s')] : ArticleM::s($req->get('kw')));
  }

  //-==========================
  private static function getInfoById(int $id=0):array{
    $data = Cache::get(config('mdp').$id);
    if(empty($data)){
      $article = ArticleM::joinInfo(['id'=>$id]);
      $data = ['data'=>$article,'title'=>$article['title']];
      Cache::set(config('mdp').$id,$data);
    }
    return $data;
  }
}
