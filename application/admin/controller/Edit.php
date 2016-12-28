<?php
declare(strict_types=1);
namespace app\admin\controller;
use app\admin\controller\Entry;
use think\Request;
use app\admin\model\Article;
final class Edit extends Entry
{
  public function __construct(){
    if(empty(session('uid')))$this->redirect('/login');
  }
  public function index(int $id=0){
    $article = null;
    if($id>0){
      $article = Article::joinImgInfo(['id'=>$id]);
      $article['img'] = [$article['img1'],$article['img2'],$article['img3'],$article['img4']];
      $article['img'] = array_filter($article['img']);
    }
    return view('',['article'=>$article]);
  }
  public function add(Request $req){
    return Article::inArticle((int)$req->post('id'),[
      'title'=>htmlspecialchars($req->post('title','trim')),
      'profile'=>htmlspecialchars($req->post('profile')),
      'img1'=> ($req->post('img1') == 'undefined') ? '' : $req->post('img1'),
      'img2'=> ($req->post('img2') == 'undefined') ? '' : $req->post('img2'),
      'img3'=> ($req->post('img3') == 'undefined') ? '' : $req->post('img3'),
      'img4'=> ($req->post('img4') == 'undefined') ? '' : $req->post('img4'),
      'curl'=>$req->post('curl'),
      'en'=>$req->post('en')
    ],$req->post('info','htmlspecialchars'));
  }
  public function img_upload(){
    return img_upload();
  }
  public function delete(int $id=0,string $en=null){
    return Article::deleteArticle($id,$en);
  }
}
