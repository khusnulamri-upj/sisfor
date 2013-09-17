<?php
// Author: Emanuel Setio Dewo
// 28 Februari 2006

// *** Main ***
$sub = (empty($_REQUEST['sub']))? 'frmSekolah' : $_REQUEST['sub'];
$sub();

// *** Functions ***
function CariSekolahScript() {
  echo <<<EOF
  <SCRIPT LANGUAGE="JavaScript1.2">
  <!--
  function carisekolah(frm){
    lnk = "cari/carisekolah.php?SekolahID="+frm.AsalSekolah.value+"&Cari="+frm.NamaSekolah.value;
    win2 = window.open(lnk, "", "width=600, height=600, scrollbars, status");
    win2.creator = self;
  }
  -->
  </script>
EOF;
}
function frmSekolah() {
  global $datamhsw;
  CariSekolahScript();
  $NamaSekolah = GetaField('asalsekolah', 'SekolahID', $datamhsw['AsalSekolah'], "concat(Nama, ', ', Kota)");
  $optjur = GetOption2('jurusansekolah', "concat(JurusanSekolahID, ' - ', Nama, ' - ', NamaJurusan)", 'JurusanSekolahID', $datamhsw['JurusanSekolah'], '', 'JurusanSekolahID');
  if ($_SESSION['_LevelID'] == 120) { // Mahasiswa
    $disabled = "disabled";
    $btn1 = "";
    $btn2 = "";
    $btn3 = "";
  } else {
    $disabled = "";
    $btn1 = "<a href='javascript:carisekolah(data)'>Cari</a>";
    $btn2 = "<input type=submit name='Simpan' value='Simpan'>";
    $btn3 = "<input type=reset name='Reset' value='Reset'>";
  }
  echo "<p><table class=box cellspacing=1 cellpadding=4 width=600>
  <form action='?' name='data' method=POST>
  <input type=hidden name='mnux' value='$_SESSION[mnux]' />
  <input type=hidden name='mhswid' value='$datamhsw[MhswID]' />
  <input type=hidden name='submodul' value='$_SESSION[submodul]' />
  <input type=hidden name='sub' value='SekolahSav' />
  <input type=hidden name='BypassMenu' value='1' />

  <tr><td colspan=2 class=ul><b>Sekolah Menengah Atas Mahasiswa</td></tr>

  <tr><td class=inp rowspan=2>Sekolah Asal</td><td class=ul><input type=text name='AsalSekolah' value='$datamhsw[AsalSekolah]' size=10 maxlength=50 $disabled></td></tr>
  <tr><td class=ul><input type=text name='NamaSekolah' value='$NamaSekolah' size=40 maxlength=50 $disabled> $btn1</td></tr>
  
  <tr><td class=inp>Jenis Sekolah</td><td class=ul><b>$datamhsw[JenisSekolahID]</b></td></tr>
  <tr><td class=inp>Jurusan</td><td class=ul><select name='JurusanSekolah' $disabled>$optjur</select></td></tr>
  <tr><td class=inp>Tahun Lulus</td><td class=ul><input type=text name='TahunLulus' value='$datamhsw[TahunLulus]' size=10 maxlength=5 $disabled></td></tr>
  <tr><td class=inp>Nilai Sekolah</td><td class=ul><input type=text name='NilaiSekolah' value='$datamhsw[NilaiSekolah]' size=5 maxlength=5 $disabled></td></tr>
  <tr><td class=ul colspan=2 align=center>$btn2
    $btn3</td></tr>
  </form></table></p>";
}
function SekolahSav() {
  $AsalSekolah = $_REQUEST['AsalSekolah'];
  $JurusanSekolah = $_REQUEST['JurusanSekolah'];
  $TahunLulus = $_REQUEST['TahunLulus'];
  $NilaiSekolah = $_REQUEST['NilaiSekolah'];
  $JenisSekolahID = GetaField('asalsekolah', 'SekolahID', $AsalSekolah, 'JenisSekolahID');
  // Simpan
  $s = "update mhsw set AsalSekolah='$AsalSekolah', JenisSekolahID='$JenisSekolahID',
    JurusanSekolah='$JurusanSekolah',
    TahunLulus='$TahunLulus', NilaiSekolah='$NilaiSekolah'
    where MhswID='$_REQUEST[mhswid]' ";
  $r = _query($s);
  BerhasilSimpan("?mnux=$_SESSION[mnux]&submodul=$_SESSION[submodul]", 100);
}

?>
