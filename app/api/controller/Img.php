<?php
declare(strict_types=1);
namespace app\api\controller;
use think\Request;
class Img{
  public function __construct(){
    if(ob_get_contents()){
      ob_end_clean();
    }
  }
  public function __destruct(){
    if(ob_get_contents()){
      ob_end_clean();
    }
  }
  public function res(Request $req){
    $filename = $req->get('fp');
    if(empty($filename)){
      header('Content-Type:image/jpg');
      readfile(IMG_PATH.'106ecbacdcc774b5c2ee6686ba2d4b5c.jpg');
      exit;
    }
    if(preg_match('/^(http:\/\/|https:\/\/).*$/',$filename)){
      header('location:'.$filename);
    }else{
      header('Content-Type:image/'.substr($filename,strripos($filename,'.')+1));
      if(!file_exists(IMG_PATH.$filename)){
        readfile(IMG_PATH.'106ecbacdcc774b5c2ee6686ba2d4b5c.jpg');
      }else{
        readfile(IMG_PATH.$filename);
      }
    }
  }
}
