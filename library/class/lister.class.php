<?php
  // Pencipta: E. Setio Dewo (setio_dewo@telkom.net)
  // 2002
  // version 0.2 27-06-2003

class lister {
  var $headerfmt = "";
  var $footerfmt = "";
  var $detailfmt = "";
  var $tables = "";
  var $fields = "";
  var $MaxRowCount = 0;
  var $startrow = 0;
  var $sqlres;
  var $maxrow = 0;

  function WritePages($pageformat, $pageoff, $separator=", ", $terminator="") {
    Global $strCantQuery;
    $sqlcnt = "select count(0) as maximum from ".$this->tables;
    $rescnt = mysql_query ($sqlcnt) or die ("$strCantQuery: $sqlcnt<br>".mysql_error());
    $this->MaxRowCount = mysql_result ($rescnt, 0, 'maximum');

    $_sisa = $this->MaxRowCount % $this->maxrow;
    //echo "$_sisa <br>";
    if ($_sisa > 0) $pagecount = 1;
    else $pagecount = 0;
    $pagecount = $pagecount + $this->MaxRowCount / $this->maxrow;
    $pagecount = $pagecount - ($_sisa / $this->maxrow);
    $position = $this->startrow / $this->maxrow;
    //echo "<b>$pagecount<b> : ".$this->startrow." / $maxrow <br> position $position<br>";

    $tmp = "";
    $i = 0;
    do {
      if ($i == $position) $tmp1 = $pageoff;
      else $tmp1 = $pageformat;

      $tmp1 = str_replace ("=STARTROW=", ($i) * $this->maxrow, $tmp1);
      $tmp1 = str_replace ("=MAXROW=", $this->maxrow, $tmp1);
      $tmp1 = str_replace ("=PAGE=", $i+1, $tmp1);
      $tmp1 = str_replace ("=PAGECOUNT=", $pagecount, $tmp1);

      if ($i < $pagecount-1) $tmp1 = "$tmp1$separator";
      else $tmp1 = "$tmp1$terminator";
      $tmp = $tmp . $tmp1;
      $i++;
    }
    while ($i < $pagecount);
    return $tmp;
  }
  
  function NameDate($date, $separator='/') {
    $a_bulan = array('01' => 'Januari',
                     '02' => 'Februari',
                     '03' => 'Maret',
                     '04' => 'April',
                     '05' => 'Mei',
                     '06' => 'Juni',
                     '07' => 'Juli',
                     '08' => 'Agustus',
                     '09' => 'September',
                     '10' => 'Oktober',
                     '11' => 'November',
                     '12' => 'Desember');
    
    $a_date  = explode($separator, $date);
    $nama_bulan = $a_bulan[$a_date[1]];
    $new_date = $a_date[0] . ' ' . $nama_bulan . ' ' . $a_date[2];
    return $new_date;
  }
  
  function Parse($p, $sep="<br />") {
    $a_parse = explode($sep, $p);
    $tgl_1 = $this->NameDate($a_parse[0]);
    $tgl_2 = $this->NameDate($a_parse[1]);
    $combine = $tgl_1 . "<hr />" . $tgl_2;
    return $combine;
  }

  function ListIt() {
    $tmpstr = "";
    $query = "";
    Global $strCantQuery;
    Global $strNoContent;

    if (isset($this->headerfmt)) { $tmpstr = $this->headerfmt; }

    $query = "select ".$this->fields. " from ".$this->tables." limit ".$this->startrow.", ".$this->maxrow;
    $this->sqlres = mysql_query ($query) or die ("$strCantQuery: $query <br>Error: ".mysql_error());

    // ambil nama2 field
    $numfields = mysql_num_fields($this->sqlres);
    for ($cl=0; $cl < $numfields; $cl++) {
      $arrfields [$cl] = mysql_field_name ($this->sqlres, $cl);
      //echo $arrfields [$cl] . "<br>";
    }
	$nomer = 0;
    while ($row = mysql_fetch_array($this->sqlres)) {
      $tmp = $this->detailfmt;
      $nomer++;
      for ($cl=0; $cl < $numfields; $cl++) {
	settype($nomer, "string");
	$tmp = str_replace("=NOMER=", $nomer, $tmp);
        $nmf = $arrfields [$cl];
        $tmp = str_replace ("=".$nmf."=", $row[$nmf], $tmp);
        $tmp = str_replace ("=!".$nmf."=", StripEmpty(urlencode($row[$nmf])), $tmp);
	$tmp = str_replace ("=:".$nmf."=", StripEmpty(stripslashes($row[$nmf])), $tmp);
	$tmp = str_replace ("=@".$nmf."=", $this->NameDate($row[$nmf]), $tmp);
	$tmp = str_replace ("=&".$nmf."=", $this->Parse($row[$nmf]), $tmp);
      }
      $tmpstr = $tmpstr . $tmp;
    }
    $tmpstr = $tmpstr . $this->footerfmt;
    return $tmpstr;
  }
}
?>
