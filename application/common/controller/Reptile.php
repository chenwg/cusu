<?php
declare(strict_types=1);
namespace app\common\controller;
use think\Controller;
class Reptile extends Controller
{
  protected $p;
  public function get_title(string $curl,string $html=''):string{
    if(empty($html)){
      $html = $this->get_html($curl);
    }
    if(empty($html))return substr($curl,0,30);
    preg_match('/<title>(.*)<\/title>/i',$html,$title);
    if(isset($title[1]) && !empty($title[1]))return $title[1];
    return substr($curl,0,30);
  }
  private function post_html(string $curl,array $param = []){
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$curl);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
    curl_setopt($ch,CURLOPT_POST,count($param));
    curl_setopt($ch,CURLOPT_HEADER,0);
    curl_setopt($ch,CURLOPT_ENCODING,'gzip');
    curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);//抓取302跳转后的页面
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$param);
    $res = curl_exec($ch);
    curl_close($ch);
    return $res;
  }
  public function get_html($curl){
    $ch = curl_init($curl);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);//跳过https验证
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_ENCODING,'gzip');
    curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);//抓取302跳转后的页面
    curl_setopt($ch,CURLOPT_BINARYTRANSFER,true);
    $res = curl_exec($ch);
    curl_close($ch);
    if(!$res)return '';
    return _utf8($res);
  }
}
