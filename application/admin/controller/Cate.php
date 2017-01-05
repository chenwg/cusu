<?php
declare(strict_types=1);
namespace app\admin\controller;
use app\admin\controller\Entry;
use think\Request;
use app\admin\model\Cate as CateModel;
final class Cate extends Entry
{
  public function index(){
    return view('cate/index',['cateList'=>CateModel::getAllCate()]);
  }
  public function add(Request $req){
    return (new CateModel())->validName($this->get_data($req->post()))
            ->rmCateCache()
            ->insertCate($this->get_data($req->post()));
  }

  public function edit(Request $req,int $id=0){
    if(!$req->isPost()){
      $cate = CateModel::getOneCate($id);
      $cate['p1name'] = $cate['p2name'] = '';
      $cate['sname'] = CateModel::getCate(['pid'=>0]);
      $cate['pname'] = [];
      if($cate['p1'] > 0){
        $p1name = CateModel::getOneCate((int)$cate['p1']);
        $cate['p1name'] = $p1name['name'];
        $cate['pname'] = CateModel::getCate(['pid'=>$cate['p1']]);
      }
      if($cate['p2'] > 0){
        $p2name = CateModel::getOneCate((int)$cate['p2']);
        $cate['p2name'] = $p2name['name'];
      }
      return view('cate/edit',['cate'=>$cate]);
    }
    return (new CateModel())->validName($this->get_data($req->post()),['id'=>['neq',$id]])
            ->rmCateCache()
            ->updateCate($this->get_data($req->post()),['id'=>$id]);
  }

  public function get_cate(int $pid=0){
    return _res(1,CateModel::getCate(['pid'=>$pid]));
  }

  public function delete_cate(int $id=0){
    return (new CateModel())->rmCateCache()->deleteCate($id);
  }

  private function get_data(array $data):array{
    $data['level'] = 1;
    $data['path'] = '';
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
    return $data;
  }
}
