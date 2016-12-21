<?php
declare(strict_types=1);
namespace app\common\model;
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
