<?php


   $laskunNimetus = $lasku->laskun_nimetys;

   $mmtext = '';
   if(isset($_GET['muistutuslasku'])){
   $laskunNimetus = 'Muistutuslasku';
   $mmtext = '
<h1>MAKSUMUISTUTUS</h1>

<p>Huomautan kohteliaimmin, että kirjanpitomme mukaan emme ole saaneet suoritusta tähän päivään mennessä oheiseen laskuumme:</p>

<table style="border-collapse: collapse; width:740px;">
<tr>
  <th style="width:20%">Lasku nro</th>
  <th style="width:20%">Pvm.</th>
  <th style="width:20%">Eräpäivä</th>
  <th style="width:20%">€/ pääoma</th>
  <th style="width:20%">Toimeksiantonne</th>
</tr>
<tr>
  <td>'.$lasku['laskunumero'].'</td>
  <td>'.$lasku['paivays'].'</td>
  <td>'.$lasku['erapaiva'].'</td>
  <td>'.$lasku['yhteensa_total'].'</td>
  <td></td>
</tr>
</table>



<p>Pyydämme Teitä maksamaan erääntyneen saatavamme:</p>

1. saatavamme määrän:	'.number_format($lasku['yhteensa_total'], 2, ',', ' ').' €<br>
2. huomautuskulut:	10,00 €<br>
3. viivästyskorkoa (10% eräpv. - 1.2.2001)	100,00 €<br>

<p>eli yhteensä	'.number_format($lasku['yhteensa_total']+10, 2, ',', ' ').' €</p>

Saatavamme tulee suorittaa Perivä Yritys Oy:n tilille, Ålandsban¬ken 0000-0000, viimeistään 8.2.2001.<br>

Maksumuistutuksemme on aiheeton, mikäli olette jo maksaneet laskumme.<br>

<p>Mikäli Teillä on huomautettavaa laskumme johdosta, ottanette yhteyttä alle-kirjoittaneeseen viimeistään 8.2.2001. Muussa tapauksessa katsomme, että Teillä ei ole huomautettavaa saatavamme johdosta.</p>

<p>Kohteliaimmin</p>

';
   }

   if($lasku->toimitusosoite == '0'){

   if($lasku->tyyppi == 'henkilo')
	$nimi = $lasku->nimi;
   if($lasku->tyyppi == 'yritys')
	$nimi = $lasku->yritys;

	$osoite = $lasku->osoite;
	$postinumero = $lasku->postinumero;
	$toimipaikka = $lasku->toimipaikka;

   }


   if($lasku->toimitusosoite == '1'){

   if($lasku->tyyppi == 'henkilo')
	$nimi = $lasku->t_nimi;
   if($lasku->tyyppi == 'yritys')
	$nimi = $lasku->t_yritys;


	$osoite = $lasku->t_osoite;
	$postinumero = $lasku->t_postinumero;
	$toimipaikka = $lasku->t_toimipaikka;

   }

   $lasku->yhteensa_total = str_replace(".",",",$lasku->yhteensa_total);
   $lasku->yhteensa_total_verot = str_replace(".",",",$lasku->yhteensa_total_verot);
   $lasku->yhteensa_total_veroton = str_replace(".",",",$lasku->yhteensa_total_veroton);

$html = '<style>
html,body { 
  width: 100%; height: 100%;
  border:1px #333 solid;
  //padding: 4;
  //margin:4px;
  font-family: DejaVu Sans, sans-serif; font-size: 10pt;
  color: #333;
  background: #ffffff;
  line-height: 85%;
}
.colapse1{
    border-collapse: collapse;
    border-spacing: 0;
}

label{
  line-height: 90%;
  //font-family:  sans-serif; font-size: 9pt;
}
b{
  //font-size: 0em;
  //font-family: DejaVu Sans, sans-serif; 
}
.class10p { width:225px;padding:5px 0 5px 10px; border-bottom:1px #333 solid; }

.class10pNB { padding:2px 0 2px 10px; }
.class50p { padding:0 0 0 100px }
.bordRight { padding:0 2%; border-right:1px #333 solid; display: inline }
TD #tuote TH{
  text-align: left;
  border: 0;
  padding: 10px;
  border-bottom:1px #333 solid;
}

TD #tuote TD{
  text-align: left;
  padding: 2px 10px;
}
H3{ line-height: 110% }
.asiakasKirje{ line-height: 120%; }
embed {height:100%;width:100%}
.yritysta TD { padding: 5px 20px; line-height: 100%; }
.yritysta { 
	//font-family:  sans-serif; 
}
.kuitti b { font-size: 9pt; }
</style>';


	$html .= '<TABLE style="border-collapse: collapse; width:740px; height:30px;">';
	$html .= '<TR>';
	$html .= '<TD  style="width:320px; height:30px;"><img src="'.$asetukset->logon_polkku.'" height="30"></TD>';
	$html .= '<TD  style="height:30px;"><b>'.$laskunNimetus.'</b></TD>';
	$html .= '</TR>';
	$html .= '</TABLE>';






	$html .= '<TABLE style="border-collapse: collapse;width:740px">';
	$html .= '<TR>';
	$html .= '<TD valign="top" 
			style="width:500px;border-top:1px #333 solid;
			border-bottom:1px #333 solid;border-right:1px #333 solid;">';

	$html .= '<div style="padding:0 0 0 70px">';
	$html .= '<H4 class="asiakasKirje">';

	$html .= $nimi.'<BR>';
	$html .= $osoite.'<BR>';
	$html .= $postinumero.' '.$toimipaikka;
	$html .= '</H4>';
	$html .= '</div>';
	$html .= '</TD>';

	$html .= '<TD style="width:240px;border-top:1px #333 solid;border-bottom:1px #333 solid;">';

	if(!isset($_GET['muistutuslasku'])){
	$html .= '<TABLE>';

	$html .= '<TR><TD class="class10p">';
	$html .= '<label>Laskun numero</label><BR>';
	$html .= '<b>'.$lasku->id.'</b>';
	$html .= '</TD></TR>';

	$html .= '<TR><TD class="class10p">';
	$html .= '<label>Laskun päiväys</label><BR>';
	$html .= '<b>'.date("d.m.Y", strtotime($lasku->paivays)).'</b>';
	$html .= '</TD></TR>';

	$html .= '<TR><TD class="class10p">';
	$html .= '<label>Eräpäivä</label><BR>';
	$html .= '<b>'.date("d.m.Y", strtotime($lasku->erapaiva)).'</b>';
	$html .= '</TD></TR>';

	$html .= '<TR><TD class="class10p">';
	$html .= '<label>Viivästyskorko</label><BR>';
	$html .= '<b>'.$lasku->viivastyskorko.' %</b>';
	$html .= '</TD></TR>';

	$html .= '<TR><TD class="class10pNB">';
	$html .= '<label>Viitenumero</label><BR>';
	$html .= '<b>'.$lasku->viitenumero.'</b>';
	$html .= '</TD></TR>';

	$html .= '</TABLE>';
	}
	$html .= '</TD>';

	$html .= '</TR>';
	$html .= '</TABLE>';




	$html .= '<BR><BR>';

	if(!isset($_GET['muistutuslasku'])){

	$html .= '<TABLE style="width:740px" id="tuote">';
	$html .= '<TR>';
	$html .= '<TH style="text-align:left;width:32%" >Tuote</TH>';
	$html .= '<TH style="width:10%">KPL</TH>';
	$html .= '<TH style="width:10%">Hinta</TH>';
	$html .= '<TH style="width:10%">ALV%</TH>';
	$html .= '<TH style="width:10%">Veroton</TH>';
	$html .= '<TH style="width:10%">Ale %</TH>';
	$html .= '<TH style="width:12%">ALV</TH>';
	$html .= '<TH style="width:8%">Yhteensä</TH>';
	$html .= '</TR>';


	$html .= '<tbody>';

/*
  	if( $lasku->laskun_nimetys == 'hyvityslasku' )
	$rivit = $lasku->hyvityslasku;
	else
	$rivit = $lasku->id;
*/



   	foreach($laskunRivit as $rivit){
	if($rivit->ale)
	$ale = $rivit->ale.' %';
	else
	$ale = '';

	$html .= '<TR>';
	$html .= '<TD style="text-align:left;">'.$rivit->tkoodi.'</TD>';
	$html .= '<TD>'.$rivit->kpl.'</TD>';
	$html .= '<TD>'.$rivit->hinta.'</TD>';
	$html .= '<TD>'.$rivit->alv.' %</TD>';
	$html .= '<TD>'.str_replace(".",",",$rivit->veroton).'</TD>';
	$html .= '<TD>'.$ale.'</TD>';
	$html .= '<TD>'.str_replace(".",",",$rivit->hinta_alv).'</TD>';
	$html .= '<TD>'.str_replace(".",",",$rivit->yhteensa_alv).'</TD>';
	$html .= '</TR>';
   	}
	$html .= '</tbody>';	
	$html .= '</TABLE>';



	$html .= '<BR><BR>';

	$html .= '<TABLE>';
	$html .= '<TR>';
	$html .= '<TD width="550" valign="left">';
	  $html .= '<TABLE>';
	  $html .= '<TR><TD align="left">Netto</TD><TD align="left">'.$lasku->yhteensa_total_veroton.' &euro;</TD></TR>';
	  $html .= '<TR><TD align="left">Vero</TD><TD align="left">'.$lasku->yhteensa_total_verot.' &euro;</TD></TR>';
	  $html .= '<TR><TD align="left">Brutto</TD><TD align="left">'.$lasku->yhteensa_total.' &euro;</TD></TR>';
	  $html .= '</TABLE>';
	$html .= '</TD>';
	$html .= '<TD style="text-align:right">';

	  $html .= '<H3>Yhteensä ';
	  $html .= $lasku->yhteensa_total.' &euro;</H3>';

	$html .= '</TD>';
	$html .= '</TR>';
	$html .= '</TABLE>';
	} // muistutuslasku


	if(isset($_GET['muistutuslasku']))
	$html .= $mmtext;








	$html .= '<div style="position:absolute; bottom:10px;">';

	$html .= '<TABLE width="650">';
	$html .= '<TR>';
	$html .= '<TD width="390" valign="top">';
	$html .= '<span>'.$yritys->tyonantaja.'<BR>';
	$html .= $yritys->osoite.'<BR>';
	$html .= $yritys->postinumero.' '.$yritys->postitoimipaikka.'</span>';
	$html .= '</TD>';
	$html .= '<TD  valign="top">';
	$html .= '<span>Y-tunnus: '.$yritys->y_tunnus.'<BR>';
	$html .= $yritys->puhelin.'<BR>';
	$html .= $yritys->sahkoposti.'<BR>';
	$html .= '</span>';
	$html .= '</TD>';
	$html .= '</TR>';
	$html .= '</TABLE>';




	$html .= '<TABLE>';
	$html .= '<TR>';
	$html .= '<TD height="30" valign="top" align="right" style="width:60px;padding: 10px; border-top:2px #333 solid; border-bottom:2px #333 solid;border-right:2px #333 solid;">';
	$html .= '<label>Saajan<BR>tilinumero<BR>Mottagarens<BR>kontonummer</label>';
	$html .= '</TD>';
	$html .= '<TD width="40%" valign="middle" style="padding: 10px; border-top:2px #333 solid; border-bottom:2px #333 solid;border-right:2px #333 solid;">';
	$html .= '<b>'.$asetukset->tilinumero.'</b>';
	$html .= '</TD>';
	$html .= '<TD valign="top" style="width:350px;border-top:2px #333 solid; border-bottom:2px #333 solid;">';

	$html .= '<TABLE width="100%" class="colapse1">';
	$html .= '<TR><TD width="50%" height="53" valign="top" style="padding: 0 10px; line-height: 110%;">';
	$html .= '<label>IBAN</label><BR>';
	$html .= '<b>'.$asetukset->iban.'</b>';
	$html .= '</TD><TD width="50%" height="53" valign="top" style="padding: 0 10px; line-height: 110%; border-left:2px #333 solid;">';
	$html .= '<label>BIC</label><BR>';
	$html .= '<b>'.$asetukset->bic.'</b>';
	$html .= '</TD></TR></TABLE>';

	$html .= '</TD>';
	$html .= '</TR>';
//
	$html .= '<TR>';
	$html .= '<TD height="30" valign="top" align="right" style="width:60px;padding: 10px; border-bottom:2px #333 solid;border-right:2px #333 solid;">';
	$html .= '<label>Saaja<BR>Mottagare</label>';
	$html .= '</TD>';
	$html .= '<TD width="40%" valign="top" style="padding: 10px; border-bottom:2px #333 solid;border-right:2px #333 solid;">';
	$html .= '<b>'.$yritys->tyonantaja.'<BR>';
	$html .= $yritys->osoite.'<BR>';
	$html .= $yritys->postinumero.' '.$yritys->postitoimipaikka.'</b>';
	$html .= '</TD>';
	$html .= '<TD width="45%" valign="top" style="padding:2px 10px;">';
	$html .= '<b>TILISIIRTO GIRERING</b>';
	$html .= '</TD>';
	$html .= '</TR>';
//
	$html .= '<TR>';
	$html .= '<TD height="40" valign="top" align="right" style="width:60px;padding: 10px;">';
	$html .= '<label>Maksaja<BR>Betalare</label>';
	$html .= '</TD>';
	$html .= '<TD width="40%" valign="top" style="padding: 10px; border-right:2px #333 solid;">';
	$html .= '<b>'.$nimi.'<BR>';
	$html .= $osoite.'<BR>';
	$html .= $postinumero.' '.$toimipaikka.'</b>';
	$html .= '</TD>';
	$html .= '<TD width="45%" valign="top" style="border-bottom:2px #333 solid;">';
	$html .= '';
	$html .= '</TD>';
	$html .= '</TR>';
//
	$html .= '<TR>';
	$html .= '<TD height="10" valign="top" align="right" style="width:60px;padding:0 10px; border-bottom:2px #333 solid;">';
	$html .= '<label>Allekirjoitus<BR>Underskrift</label>';
	$html .= '</TD>';
	$html .= '<TD width="40%" valign="bottom" style="border-bottom:2px #333 solid;border-right:2px #333 solid;">';
	$html .= '<HR style="border-bottom:0.2px #333 solid;">';
	$html .= '</TD>';
	$html .= '<TD width="40%" valign="top" style="border-bottom:2px #333 solid;">';

	$html .= '<TABLE width="100%" class="colapse1">';
	$html .= '<TR><TD width="50" valign="middle" style="padding:7px 10px;">';
	$html .= '<label>Viitenro<BR>Ref.nr</label><BR>';
	$html .= '</TD><TD width="45%" valign="top" style="padding:7px 10px; border-left:2px #333 solid;">';
	$html .= '<b>'.$lasku->viitenumero.'</b>';
	$html .= '</TD><TD width="45%" valign="top" style="padding:0 10px;">';
	$html .= '</TD></TR></TABLE>';

	$html .= '</TD>';
	$html .= '</TR>';
//
	$html .= '<TR>';
	$html .= '<TD valign="middle" align="right" style="width:60px;padding:0 10px; border-bottom:2px #333 solid; border-right:2px #333 solid;">';
	$html .= '<label>Tililtä nro<BR>Från konto nr</label><BR>';
	$html .= '</TD>';
	$html .= '<TD width="40%" valign="bottom" style="border-right:2px #333 solid; border-bottom:2px #333 solid;">';

	$html .= '<div style="padding: 0 10px">';
	$html .= '<div class="bordRight"> </div>';
	$html .= '<div class="bordRight"> </div>';
	$html .= '<div class="bordRight"> </div>';
	$html .= '<div class="bordRight"> </div>';
	$html .= '<div class="bordRight"> </div>';
	$html .= '<div class="bordRight"> </div>';
	$html .= '<div class="bordRight">-</div>';
	$html .= '<div class="bordRight"> </div>';
	$html .= '<div class="bordRight"> </div>';
	$html .= '<div class="bordRight"> </div>';
	$html .= '<div class="bordRight"> </div>';
	$html .= '<div class="bordRight"> </div>';
	$html .= '<div class="bordRight"> </div>';
	$html .= '<div class="bordRight"> </div>';
	$html .= '<div class="bordRight"> </div>';
	$html .= '</div>';

	$html .= '</TD>';
	$html .= '<TD valign="top" style="width:350px;border-bottom:2px #333 solid;">';

	$html .= '<TABLE width="100%" class="colapse1">';
	$html .= '<TR><TD width="50" valign="middle" style="padding:7px 10px;">';
	$html .= '<label>Eräpäivä<BR>Förf.dag</label><BR>';
	$html .= '</TD><TD width="45%" valign="top" style="padding:7px 10px; border-left:2px #333 solid;">';
	$html .= '<b>'.date("d.m.Y", strtotime($lasku->erapaiva)).'</b>';
	$html .= '</TD><TD width="45%" valign="top" style="padding:3px 10px; border-left:2px #333 solid;">';
	$html .= '<label>Euro</label><BR>';
	$html .= '<b style="padding:0 7px; white-space: nowrap;">'.$lasku->yhteensa_total.'</b>';
	$html .= '</TD></TR></TABLE>';

	$html .= '</TD>';
	$html .= '</TR>';
	$html .= '</TABLE>';
	$html .= '</div>';







/*
	urlViiva($lasku->saaja_virtualkoodi);
  	$img = 'img/barcodes/'.$lasku->id.'.png';
  	$content = file_get_contents($url);
  	file_put_contents($img, $content);

	$html .= '<TABLE width="100%">';
	$html .= '<TR>';
	$html .= '<TD width="100%" style="padding: 10px;"><center><img src="img/barcodes/'.$lasku->id.'.png" height="45"/></center></TD>';
	//$html .= '<TD width="50%" style="padding: 10px;" align="right">PANKKI BANKEN</TD>';
	$html .= '</TR></TABLE>';
*/

echo $html;




?>

