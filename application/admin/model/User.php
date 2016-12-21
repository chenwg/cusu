<?php
declare(strict_types=1);
namespace app\admin\model;
use think\Model;
class User extends Model
{
  protected static function init(){
    //no no no no bush
  }

  public static function getMember(string $username){
    return User::where('username',$username)->find();
  }
}
