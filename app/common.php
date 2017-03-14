<?php
// 应用公共文件
function strIntoStr($arr){
  //hash hash  table   0000000 ... hello man
}

function _res($s=0,$i=null){
  return json(['s'=>$s,'i'=>$i]);
}

function _end($i=''){
  die($i);
}
function _utf8($res){
  return mb_convert_encoding($res,'utf-8','GBK,UTF-8,ASCII,gb18030,UTF-16LE');
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
function check_img($path){
  if(check_url($path)){
    $img = @getimagesize($path);
    if($img && $img[0] > config('src_pic_min_width') && $img[1] > 90 && $img[0] < 800 && $img[1] < 600){
      return true;
    }
    return false;
  }
  return false;
}
function get_img($htmlInfo,$articleData){
  $pattern = '/<img.*?src="(.*?)"/';
  preg_match_all($pattern,$htmlInfo,$match);
  $imgArray = [];
  $curlArray = [];
  if(!empty($articleData['curl'])){
    $curlArray = parse_url($articleData['curl']);
    if(empty($articleData['profile'])){
      preg_match('/<meta\s+name="description"\s+content="([\w\W]*?)"/si',$htmlInfo,$profile);
      if(!empty($profile[1])){
        $articleData['profile'] = $profile[1];
      }
    }
  }
  foreach($match[1] as $v){
    if(count($imgArray) == 4)break;
    if(preg_match('/^(http:\/\/|https:\/\/).*$/',$v)){
      check_img($v) && array_push($imgArray,$v);
    }else{
      if(substr($v,0,2) == '//'){
        check_img('http:'.$v) && array_push($imgArray,'http:'.$v);
      }else{
        if(count($curlArray) > 1 && isset($curlArray['scheme']) && check_img($curlArray['scheme'].'://'.$curlArray['host'].$v)){
          array_push($imgArray,$curlArray['scheme'].'://'.$curlArray['host'].$v);
        }
      }
    }
  }
  isset($imgArray[0]) && (!empty($imgArray[0])) && $articleData['img1'] = '/img?fp='.$imgArray[0];
  isset($imgArray[1]) && (!empty($imgArray[1])) && $articleData['img2'] = '/img?fp='.$imgArray[1];
  isset($imgArray[2]) && (!empty($imgArray[2])) && $articleData['img3'] = '/img?fp='.$imgArray[2];
  isset($imgArray[3]) && (!empty($imgArray[3])) && $articleData['img4'] = '/img?fp='.$imgArray[3];
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
  curl_setopt($ch,CURLOPT_ENCODING,'gzip');
  curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
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
  $pattern = '/[w-].(com|net|org|gov|cc|biz|info|cn)(.(cn|hk))*/';
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
}
