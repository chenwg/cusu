<?php
declare(strict_types=1);
namespace app\common\controller;
use think\Controller;
class FileHanding extends Controller
{
  protected $path;
  public function __construct($p=null){
    if(!empty($p)){
      if(file_exists($p))return $this->redirect('/'.$p);
      $this->path = $p;
    }
    //Hi man ======================== My name is chenWeiguang
  }
  public function img_upload(){
    $file = $_FILES['suFiles'];
    if($file['size'][0] > 501*1024)return json(['susname'=>'','err'=>$file['name'][0].'图片大小不能大于500k']);
    if(!in_array($file['type'][0],['image/jpeg','image/jpg','image/png','image/gif']))return json(['susname'=>'','err'=>$file['name'][0].'图片格式不正确']);
    $fileName = md5(time().$file['name'][0]).'.'.pathinfo($file['name'][0],PATHINFO_EXTENSION);
    if(move_uploaded_file($file['tmp_name'][0],'files'.'/'.$fileName)){
      return json(['susname'=>'/files'.'/'.$fileName,'err'=>false]);
    }else{
      return json(['susname'=>'','err'=>'444']);
    }
  }
  //word===================================================
  public function word_export(string $content=null){
    ob_start();
    echo '<html xmlns:o="urn:schemas-microsoft-com:office:office"',
    'xmlns:w="urn:schemas-microsoft-com:office:word"',
    'xmlns="http://www.w3.org/TR/REC-html40">',
    iconv('UTF-8','GB2312',$content),
    '</html>';
    $this->save_file();
    return file_exists($this->path) ? $this->redirect('/'.$this->path) : '文件不存在';
  }
  //word====================================================

  //pdf=====================================================
  //
  public function pdf_export($data){
    ///bug bug -----------------============================bug
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$_SERVER['SERVER_NAME'].'/tct/cnex.php');
    curl_setopt($ch,CURLOPT_POST,count($data));
    curl_setopt($ch,CURLOPT_HEADER,0);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
    $res = curl_exec($ch);
    curl_close($ch);
    return $res;
  }

  //pdf=====================================================

  //excel===================================================
  public function excel_export(){
    return ture;
  }

  //excel===================================================
  private function save_file(){
    $data = ob_get_contents();
    ob_end_clean();
    $fp = fopen($this->path,'wb+');
    fwrite($fp,$data);
    fclose($fp);
  }
}
