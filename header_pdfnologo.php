<?php
// Author : Emanuel Setio Dewo
// Email  : setio.dewo@gmail.com
// Start  : 23 Agustus 2008
//

if (file_exists("../fpdf.php")) require("../fpdf.php");
else require("fpdf.php");

class PDF extends FPDF {
  function Header() {
    $mrg = 19;
    $pjg = 150;    
    $identitas = GetFields('identitas', "Kode", KodeID, '*');
    $logo = (file_exists("../img/logo.jpg"))? "../img/logo.jpg" : "img/logo.jpg";
    //$this->Image($logo, 18, 14, 24);
    //$this->SetFont("Times", '', 11);
    //$this->Cell($mrg);
    //$this->Cell($pjg, 6, $identitas['Yayasan'], 0, 1, 'C');
    $this->SetFont("Times", 'B', 16);
    $this->Cell($mrg);
    $this->Cell($pjg, 7, $identitas['Nama'], 0, 1, 'C');
    
    $this->SetFont("Times", 'I', 10);
    $this->Cell($mrg);
    $this->Cell($pjg, 5, $identitas['Alamat1'].' '.$identitas['Alamat2'], 0, 1, 'C');
    //$this->Cell($mrg);
    //$this->Cell($pjg, 5, $identitas['Alamat2'], 0, 1, 'C');
    //$this->Cell($pjg, 5, $identitas['Kota'], 0, 1, 'C');
    $this->SetFont("Times", 'I', 7);
    $this->Cell($mrg);
    $this->Cell($pjg, 5, "Telp. ".$identitas['Telepon'].", Fax. ".$identitas['Fax'].", Website:".$identitas['Website'].", Email:".$identitas['Email'], 0, 1, 'C');
    $this->Cell(5);
    $this->Cell(180, 0, "", 1, 1);
    $this->Ln(2);
  }
}
function BuatHeaderPDF($p, $x=10, $y=5, $w=190) {
  $p->Image("../img/header_image.gif", $x, $y, $w);
  $p->Ln(26);
}
function BuatHeaderPDF0($p, $x=10, $y=5, $w=190) {
  $p->Image("../img/header_image.gif", $x, $y, $w);
}

?>
