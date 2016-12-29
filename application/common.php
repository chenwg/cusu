<?php
// 应用公共文件
function strIntoStr($arr){
  //hash hash  table   0000000 ... hello man
}

function _res($s=0,$i=null){
  return json(['s'=>$s,'i'=>$i]);
}

function _md($p){
  return md5(md5($p.'vkii').'vs');
}

function export($type,$id,$content){
  //1是word  2是pdf 3是excel
  if($type == 1)return (new \app\common\controller\FileHanding('word'.'/'.md5('a_'.$id).'.doc'))->word_export($content);
  if($type == 2)return (new \app\common\controller\FileHanding(null))->pdf_export($content);
  if($type == 3)return (new \app\common\controller\FileHanding('excel'.'/'.md5('a_'.$id).'.xls'))->excel_export($content);
}

function img_upload(){
  return (new \app\common\controller\FileHanding(null))->img_upload();
}

function get_img($htmlInfo,$articleData){
  $pattern = '/<img.*?src="(.*?)".*?>/';
  preg_match_all($pattern,$htmlInfo,$match);
  $imgArray = [];
  $curlArray = parse_url($articleData['curl']);
  foreach($match[1] as $v){
    if(count($imgArray) == 4)break;
    if(preg_match('/^(http:\/\/|https:\/\/).*$/',$v)){
      array_push($imgArray,$v);
    }else{
      if(count($curlArray) > 1 && isset($curlArray['scheme'])){
        array_push($imgArray,$curlArray['scheme'].$curlArray['host'].'/'.$v);
      }
    }
  }
  isset($imgArray[0]) && (!empty($imgArray[0])) && $articleData['img1'] = $imgArray[0];
  isset($imgArray[1]) && (!empty($imgArray[1])) && $articleData['img2'] = $imgArray[1];
  isset($imgArray[2]) && (!empty($imgArray[2])) && $articleData['img3'] = $imgArray[2];
  isset($imgArray[3]) && (!empty($imgArray[3])) && $articleData['img4'] = $imgArray[3];
  return $articleData;
}

function check_http($url){
  if(!preg_match('/^(http:\/\/|https:\/\/).*$/',$url)){
    return false;
  }
  return true;
}
function check_url($url){
  $ch = curl_init($url);
  curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
  curl_setopt($ch,CURLOPT_NOBODY,true);
  $result = curl_exec($ch);
  $status = curl_getinfo($ch,CURLINFO_HTTP_CODE);
  curl_close($ch);
  if($result && $status == 200){
    return true;
  }
  return false;
}


function get_domain($url){
  return true;
  //$pattern = '/[w-].(com|net|org|gov|cc|biz|info|cn)(.(cn|hk))*/';
  /*
  preg_match($pattern, $url, $matches);
  if(count($matches) > 0) {
    return $matches[0];
  }else{
    $rs = parse_url($url);
    $main_url = $rs['host'];
    if(!strcmp(long2ip(sprintf('%u',ip2long($main_url))),$main_url)) {
      return $main_url;
    }else{
      $arr = explode('.',$main_url);
      $count=count($arr);
      $endArr = array('com','net','org','3322');
      if (in_array($arr[$count-2],$endArr)){
        $domain = $arr[$count-3].'.'.$arr[$count-2].'.'.$arr[$count-1];
      }else{
        $domain = $arr[$count-2].'.'.$arr[$count-1];
      }
      return $domain;
    }
  }
  */
}
