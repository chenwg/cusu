<?php
declare(strict_types=1);
namespace app\admin\controller;
use think\Controller;
use think\Request;
use app\admin\model\Article;
final class Edit extends Controller
{
  public function __construct(){
    if(empty(session('uid')))$this->redirect('/login');
  }
  public function index(int $id=0){
    return view('',['article'=>$id> 0 ? Article::joinImgInfo(['id'=>$id]) : null]);
  }
  public function add(Request $req){
    return Article::inArticle((int)$req->post('id'),[
      'title'=>htmlspecialchars($req->post('title','trim')),
      'profile'=>htmlspecialchars($req->post('profile')),
      'curl'=>$req->post('curl'),
      'en'=>$req->post('en')
    ],$req->post('info','htmlspecialchars'));
  }
  public function delete(int $id=0,string $en=null){
    return Article::deleteArticle($id,$en);
  }
}
