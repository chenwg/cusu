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
      //return json(CateModel::getAllCate());
      return view('cate/index',['cateList'=>CateModel::getAllCate()]);
    }
    $data = $req->post();
    $data['level'] = 1;
    if($data['p1'] > 0){
      $data['pid'] = $data['p1'];
      $data['level'] = 2;
      $data['path'] = $data['p1'];
    }
    if($data['p2'] > 0){
      $data['pid'] = $data['p2'];
      $data['path'] = $data['p1'].','.$data['p2'];
      $data['level'] = 3;
    }
    return CateModel::insertCate($data);
  }

  public function edit(Request $req,int $id=0){
    if(!$req->isPost()){
      return view('cate/edit',['cate'=>CateModel::getOneCate($id)]);
    }
    return CateModel::insertCate($req->post(),['id'=>$id]);
  }

  public function get_cate(int $pid=0){
    return _res(1,CateModel::getCate(['pid'=>$pid]));
  }

}
