<?php
namespace app\home\controller;
use think\Controller;
class Entry extends Controller{
  protected $tpl = '';
  public function __construct($latpl = '-item'){
    if(request()->header('x-pjax')){
      $this->tpl = $latpl;
    }
  }
}
