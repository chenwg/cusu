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
    if(!$html)return substr($curl,0,30);
    $encode = mb_detect_encoding($html);
		if(!$encode || strtolower($encode) != 'utf-8')$html = mb_convert_encoding($html,'UTF-8');
    preg_match('/<title>(.*)<\/title>/i',$html,$title);
    if(isset($title[1]))return $title[1];
    return substr($curl,0,30);
  }
  private function post_html(string $curl,array $param = []){
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$curl);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
    curl_setopt($ch,CURLOPT_POST,count($param));
    curl_setopt($ch,CURLOPT_HEADER,0);
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
    curl_setopt($ch,CURLOPT_BINARYTRANSFER,true);
    $res = curl_exec($ch);
    curl_close($ch);
    if(!$res)return '';
    return mb_convert_encoding($res,'UTF-8');
  }
}
