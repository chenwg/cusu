<?php
declare(strict_types=1);
namespace app\home\controller;
use think\Request;
use app\home\logic\ArticleLogic;
use app\home\model\Article as ArticleM;
use think\Cache;
final class Article extends Entry
{
  public function __construct(){
    parent::__construct();
  }

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
    return view('article/article'.$this->tpl,$data);
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

  public function s(Request $req){
    return view('article/article'.$this->tpl,
    empty($req->param('kw')) ? ['data'=>null,'title'=>config('s')] : ArticleM::s($req->param('kw')));
  }

  //-==========================
  private static function getInfoById(int $id=0):array{
    $data = Cache::get('a_'.$id);
    if(empty($data)){
      $article = ArticleM::joinInfo(['id'=>$id]);
      $data = ['data'=>$article,'title'=>$article['title']];
      Cache::set('a_'.$id,$data);
    }
    return $data;
  }
}
