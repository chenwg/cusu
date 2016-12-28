<?php
declare(strict_types=1);
namespace app\api\controller;
use think\Request;
class Img{
  public function res(Request $req){
    $filename = $req->get('fp');
    if(empty($filename)){
      readfile('files/106ecbacdcc774b5c2ee6686ba2d4b5c.jpg');
    }
    ob_end_clean();
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
    ob_end_clean();
  }
}
