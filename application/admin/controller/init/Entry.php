<?php
declare(strict_types=1);
namespace app\admin\controller\init;
use think\Controller;
class Entry extends Controller
{
  public function __construct(){
    if(empty(session('uid')))return redirect('/login');
  }
}
