<?php
declare(strict_types=1);
namespace app\api\controller;
use think\Request;
class Img{
  public function __destruct(){
    ob_end_clean();
  }
  public function res(Request $req){
    $filename = $req->get('fp');
    ob_end_clean();
    if(empty($filename)){
      header('Content-Type:image/jpg');
      readfile('files/106ecbacdcc774b5c2ee6686ba2d4b5c.jpg');
      exit;
    }
    if(preg_match('/^(http:\/\/|https:\/\/).*$/',$filename)){
      header('location:'.$filename);
    }else{
      header('Content-Type:image/'.substr($filename,strripos($filename,'.')+1));
      if(!file_exists('files'.'/'.$filename)){
        readfile('files/106ecbacdcc774b5c2ee6686ba2d4b5c.jpg');
      }else{
        readfile('files'.'/'.$filename);
      }
    }
  }
}
