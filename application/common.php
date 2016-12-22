<?php
declare(strict_types=1);
// 应用公共文件
function strIntoStr($arr){
   //hash hash  table   0000000 ... hello man
}

function _res($s=0,$i=null){
  return json(['s'=>$s,'i'=>$i]);
}

function _md(string $p):string{
  return md5(md5($p.'vkii').'vs');
}

function export($type,$id,$content){
  //1是word  2是pdf 3是excel
  if($type == 1)return (new \app\common\controller\FileHanding('word'.'/'.md5('a_'.$id).'.doc'))->word_export($content);
  if($type == 2)return (new \app\common\controller\FileHanding('pdf'.'/'.md5('a_'.$id).'.pdf'))->pdf_export($content);
  if($type == 3)return (new \app\common\controller\FileHanding('excel'.'/'.md5('a_'.$id).'.xls'))->excel_export($content);
}

function img_upload(){
  return (new \app\common\controller\FileHanding(null))->img_upload();
}
