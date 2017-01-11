<?php
declare(strict_types=1);
namespace app\home\model\article;
use app\common\model\article\Article as Particle;
class Article extends Particle
{
  public static function s(string $kw,int $page=0):array{
    $data = self::search($kw,$page);
    $dataArray = $data->toArray();
    if(isset($dataArray['data'][0])){
      foreach($dataArray['data'] as &$v){
        $v['profile'] = str_replace($kw,'<font color="#c40000">'.$kw.'</font>',$v['profile']);
        $v['title'] = str_replace($kw,'<font color="#c40000">'.$kw.'</font>',$v['title']);
      }
      return ['data'=>$dataArray['data'],'page'=>$data->render(),'title'=>config('s')];
    }
    return ['data'=>[],'title'=>config('s')];
  }
}
