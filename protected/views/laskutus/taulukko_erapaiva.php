<?php
// taulukko_erapaiva.php
$between = " AND paivays BETWEEN '".date("Y-m-d",strtotime($from))."' AND '".date("Y-m-d",strtotime($to))."' ";
?>

<h2>Eräpäivä</h2>
<div>
<TABLE  class="table well table-striped">
<thead>
<TR>
<TH>Nro.</TH>
<TH>Päiväys</TH>
<TH>Eräpäivä</TH>
<TH>Asiakkas</TH>
<TH>Yht&euro;</TH>
<TH>Tilanne</TH>
<TH></TH>
<TH></TH>
</TR>
</thead>
<?php
   $lasku = Laskutus::model()->findAll(" tilanne = 'lähetetty' and erapaiva < CURDATE() $between ");
   foreach($lasku as $l)
   {
   if($l->nimi)
	$nimi = $l->nimi;
   if($l->yritys)
	$nimi = $l->yritys;
   if($l->laskutus == 'posti')
	$imgLaskutus = '<center><img src="laskutus/img/posti.png" height="30"></center>';
   if($l->laskutus == 'sahkoposti')
	$imgLaskutus = '<center><img src="laskutus/img/email.png" height="30"></center>';
   if($l->tilanne)
	$tilanne = $l->tilanne;
   else
	$tilanne = "------";

   if($l->tilanne == 'avoin')
	$styleTilanne = 'style="color: '.$avoinColor.'"';
   elseif($l->tilanne == 'lähetetty')
	$styleTilanne = 'style="color: '.$lahetettyColor.'"';
   elseif($l->tilanne == 'maksettu')
	$styleTilanne = 'style="color: '.$maksettuColor.'"';
   else
	$styleTilanne = 'style=""';

   $erapaiva = '<span class="erapaiva">'.date("d.m",strtotime($l->erapaiva)).'</span>';


	echo '<TR>
	<TD width=1 align=center>'.$l->id.'</TD>
	<TD width=1>'.date("d.m",strtotime($l->paivays)).'</TD>
	<TD width=1>'.$erapaiva.'</TD>
	<TD>'.$nimi.'</TD>
	<TD>'.num($l->yhteensa_total).'</TD>
	<TD '.$styleTilanne.'><div class="tilanne" id="to_'.$l->id.'">'.$tilanne.'</div><div id="next_'.$l->id.'" style="display:none"></div></TD>
	<TD>'.$imgLaskutus.'</TD>
	<TD width=1><a href="index.php?lasku_new=true&selaa=true&laskunumero='.$l->id.'"><img src="laskutus/img/view.png" height="30"></a></TD>
	</TR>';
   }
?>

</TABLE>
</div>
