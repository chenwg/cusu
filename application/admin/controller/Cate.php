<?php
declare(strict_types=1);
namespace app\admin\controller;
use app\admin\controller\Entry;
use think\Request;
use app\admin\model\Cate as CateModel;
final class Cate extends Entry
{
  public function add(Request $req){
    if(!$req->isPost()){
      return json(CateModel::getAllCate());
      return view('cate/index',['cateList'=>CateModel::getAllCate()]);
    }
    return CateModel::insertCate($req->post());
  }

  public function edit(Request $req,int $id=0){
    if(!$req->isPost()){
      return view('cate/edit',['cate'=>CateModel::getOneCate($id)]);
    }
    return CateModel::insertCate($req->post(),['id'=>$id]);
  }
}
