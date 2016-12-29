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
      return json(['susname'=>'/img?fp='.$fileName,'err'=>false]);
    }else{
      return json(['susname'=>'','err'=>'444']);
    }
  }
  //word===================================================
  public function word_export(string $content=null){
    ob_end_clean();
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
    vendor('Tcpdf.tcpdf');
    $pdf = new \Tcpdf(PDF_PAGE_ORIENTATION,PDF_UNIT,PDF_PAGE_FORMAT,true,'UTF-8',false);
    ob_end_clean();
    // set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('chenWeiguang');
    $pdf->SetTitle($data['title']);
    $pdf->SetSubject('');
    $pdf->SetKeywords('');

    // set default header data
    //$pdf->setPrintHeader(false);
    $pdf->SetHeaderMargin('1');
    $pdf->SetHeaderData(false,0, 'CusuCms V1.0.0');

    // set header and footer fonts
    //$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    // set auto page breaks
    $pdf->SetAutoPageBreak(TRUE,'3');

    // set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // set some language-dependent strings (optional)
    if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
      require_once(dirname(__FILE__).'/lang/eng.php');
      $pdf->setLanguageArray($l);
    }

    // ---------------------------------------------------------

    // set font
    $pdf->SetFont('stsongstdlight', '', 16);

    // add a page
    $pdf->AddPage();

    $title = '<p style="font-weight:bold">'.$data['title'].'</p>';
    $pdf->WriteHTML($title,true,0,true,0);
    // set font
    $pdf->SetFont('stsongstdlight', '', 13);
    $txt = $data['info'];

    $pdf->WriteHTML($txt,true,0,true,0);

    //Close and output PDF document
    $pdf->Output(md5($data['title'].$data['id']).'.pdf', 'D');
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
