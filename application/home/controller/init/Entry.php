<?php
declare(strict_types=1);
namespace app\home\controller\init;
use think\Controller;
use app\home\model\cate\Cate;
class Entry extends Controller{
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
