<?php
declare(strict_types=1);
namespace app\home\controller\init;
use think\Controller;
use think\Request;
use app\home\model\cate\Cate;
class Entry extends Controller{
  public function __construct(Request $req){
    if(!empty($req->get('link'))){
      return $this->redirect($req->get('link'));
    }
  }
  protected function view(string $viewTpl='',array $data=[]){
    if(!isset($data['cate'])){
      $data['cate'] = Cate::getAllCate();
    }
    if(request()->header('x-pjax')){
      $viewTpl = $viewTpl.'-item';
    }
    return view($viewTpl,$data);
  }

}
