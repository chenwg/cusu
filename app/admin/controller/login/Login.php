<?php
namespace app\admin\controller\login;
use app\admin\controller\init\Entry;
use think\Request;
use app\admin\model\user\User;
final class Login extends Entry
{
  public function index(){
    return view('login/login',[]);
  }

  public function login(Request $req){
    $user = User::getMember($req->post('username'));
    if(empty($user))return _res(2);
    if(_md($req->post('password')) !== $user['password'])return _res(3);
    session('uid',$user['username']);
    return _res(1);
  }
}
