<?php
declare(strict_types=1);
namespace app\common\controller;
use think\Controller;
class FileHanding extends Controller
{
  protected $path;
  public function __construct($p){
    if(file_exists($p))return $this->redirect('/'.$p);
    $this->path = $p;
    //Hi man ======================== My name is chenWeiguang
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
