<?php
	$tp_lisatuotteet = array();
?>
        <!-- begin: .tray-center -->
        <div class="tray-center">

        <h2 class="myBgColors p10"> <i class="fa fa-barcode"></i> <?php echo Yii::t('main', 'LASKUTUKSEN AUTOMAATIO ESIKATSELU'); ?></h2>

	</div>

	<center>
		<?php
		if( count($lista) > 0 ){
			echo CHtml::link(Yii::t('main', 'LÄHETÄ KAIKKI LASKUT'), 
				array('lasku/luolaskut', 
					'filter_tyyppi' => (isset($_GET['filter_tyyppi']))?$_GET['filter_tyyppi']:'',
					'filter_postitoimipaikka' => (isset($_GET['filter_postitoimipaikka']))?$_GET['filter_postitoimipaikka']:'',
					'filter_tyoryhma' => (isset($_GET['filter_tyoryhma']))?$_GET['filter_tyoryhma']:'',
					'filter_asiakasryhma' => (isset($_GET['filter_asiakasryhma']))?$_GET['filter_asiakasryhma']:'',
					'from' => $from,
					'to' => $to,
					'paivays' => $paivays,
					'alvsis' => $alvsis,
					'laheta' => true
				), 
				array(
					'class' => 'btn btn-block btn-success lahetakaikki',
					'data-toggle'=>'tooltip', 
					'data-placement'=>'top', 
					'title'=>Yii::t('main', 'Lähetä')
				)
			);
		}
		?>
	</center>
	<br>
<p>
<div class="container-fluid">
	<?php if( count($lista) > 0 ): ?>
	<table class="table table-bordered">
	<tr>
	<th>Asiakas</th>
	<th>Laskutuksen tiedot</th>
	<th>Laskutuksen rivit</th>
	</tr>
	<?php foreach($lista as $item) : ?>
	<tr>
	<td width="10%"><h3><?=$item->Fullname?></h3></td>
	<td width="15%">
	<?php
	$yhteensa_total_veroton	= 0;
	$yhteensa_total	= 0;
	$is_ok_lasku 	= true;
	$l_class	= 'class="bg-default"';

	if(isset($asetukset->id) and empty( $item->laskutus_kanava ) and !empty( $asetukset->asiakas_laskutus_kanava )){
		$item->laskutus_kanava = $asetukset->asiakas_laskutus_kanava;
	}
	if(isset($asetukset->id) and empty( $item->kirjeenluokka ) and !empty( $asetukset->asiakas_kirjeenluokka )){
		$item->kirjeenluokka = $asetukset->asiakas_kirjeenluokka;
	}
	if(isset($asetukset->id) and empty( $item->viivastyskorko ) and !empty( $asetukset->asiakas_viivastyskorko )){
		$item->viivastyskorko = $asetukset->asiakas_viivastyskorko;
	}
	if(isset($asetukset->id) and empty( $item->maksuehto ) and !empty( $asetukset->asiakas_maksuehto )){
		$item->maksuehto = $asetukset->asiakas_maksuehto;
	}


	if( 
		( $item->laskutus_kanava == 'sahkoposti' and empty($item->sahkoposti) )
		or ( $asetukset->palvelu_tyyppi == 4 and $asetukset->netvisor_kaytto == 1 and $item->netvisorkey == 0 )
	){ 
		$is_ok_lasku 	= false; 
		$l_class	= 'class="alert bg-danger"';
	}
	?>
	<div>
	<p><b><?=Yii::t('main', 'Laskutus kanava')?></b>-<?=(!empty($item->laskutus_kanava))? Yii::t('main', $item->laskutus_kanava):''?></p>
	<p><b><?=Yii::t('main', 'Päiväys')?></b>-<?=date("d.m.Y",strtotime($paivays))?></p>
	<p><b><?=Yii::t('main', 'Eräpäivä')?></b>-<?=(!empty($item->maksuehto))? date("d.m.Y",strtotime($paivays ." +$item->maksuehto day")):''?></p>
	<p><b><?=Yii::t('main', 'Maksuehto')?></b>-<?=(!empty($item->maksuehto))? $item->maksuehto:''?></p>
	<p><b><?=Yii::t('main', 'Viivästyskorko')?></b>-<?=(!empty($item->viivastyskorko))? $item->viivastyskorko:''?></p>
	 <div <?=$l_class?>>
	 <?=($item->laskutus_kanava == 'sahkoposti' and empty($item->sahkoposti))? '<p>'.Yii::t('main', 'Sähköpostiosoite ei saisi olla tyhjä tässä laskutus kanavassa.').'</p>':''?>
	 <?=( $asetukset->palvelu_tyyppi == 4 and $asetukset->netvisor_kaytto == 1 and $item->netvisorkey == 0 )? '<p>'.Yii::t('main', 'Asiakas ei vielä saanut netvisorkey. Päivittä tämän asiakkaan tiedot.').'</p>':''?>
	 </div>
	</div>
	</td>
	<td>


	<!-- Lahetys Pää lasku -->
	<?php if( $is_ok_lasku and $laheta !== null ){

		$laskunumero = '';
		if($asetukset->lasku_laskunumero == 1){
			$criteria = new CDbCriteria();
       			$criteria->select = " id, MAX(ABS(laskunumero)) as laskunumero ";
			$vm = Lasku::model()->find($criteria);
			if( isset($vm->id) ){ $laskunumero = $vm->laskunumero+1; }
		}

		$lasku = new Lasku;
		$lasku->yid = 1;
		$lasku->tilanne = 1;
		$lasku->laskunumero = $laskunumero;
		$lasku->tapahtumapvm = date("Y-m-d H:i:s");
		$lasku->netvisor_dimension_name = $item->netvisor_dimension_name;
		$lasku->netvisor_dimension_item = $item->netvisor_dimension_item;
		$lasku->tyyppi = $item->tyyppi;
		$lasku->yritys = $item->yrityksen_nimi;
		$lasku->y_tunnus = $item->y_tunnus;
		$lasku->nimi = $item->yhteyshenkilo;
		$lasku->as_nro = $item->asiakasnumero;
		$lasku->osoite = $item->osoite;
		$lasku->toimitusosoite = $item->osoite;
		$lasku->postinumero = $item->postinumero;
		$lasku->toimipaikka = $item->kaupunki;
		$lasku->laskutus = $item->laskutus_kanava;
		$lasku->sahkoposti = $item->sahkoposti;
		$lasku->verkkolaskuosoite = $item->verkkolaskuosoite;
		$lasku->v_tunnus = $item->valittajan_tunnus;
		$lasku->yhteyshenkilo = $item->yhteyshenkilo;
		$lasku->puhelin = $item->puhelin;
		$lasku->paivays = $paivays;
		$lasku->erapaiva = date("Y-m-d",strtotime($paivays." +$item->maksuehto day"));
		$lasku->maksuehto = $item->maksuehto;
		$lasku->viitenumero = $this->Viite($item->asiakasnumero."00".$item->id);
		$lasku->yhteensa_total = $yhteensa_total;
		$lasku->saaja_iban = $asetukset->iban;
		$lasku->laskun_nimetys = 'Lasku';
		$lasku->alv_muoto = $alvsis;
		if(!$lasku->save()){
			print_r($lasku->getErrors());
		}
	} ?>
	<!-- / Lahetys -->

		<?php $hyv_lista = $this->hyvaksyttyListaByAsiakas($item->id, $from, $to); ?>
		<p class="m_icons"><i class="link fa fa-2x fa-edit"></i></p>
		<div class="row alv_valinta" style="display:none">
		 <div class="col-sm-3">
			<p><select class="form-control" class="lasku_alv_muoto">
			<option value="0">Hinnat ALV 0%</option>
			<option value="1">Hinnat sis. ALV</option>
			</select></p>
		 </div>
		</div>
		<table class="table table-striped rivintaulu">
		<tr>
		<th class="th_tuote">Tuote</th>
		<th>Hinta</th>
		<th>Yksikkö</th>
		<th>Määrä</th>
		<th>ALV</th>
		<th>Veroton</th>
		<th>Yhteensä</th>
		<th>Free text</th>
		</tr>
		<?php $key = 0; ?>
		<?php foreach($hyv_lista as $mob) : ?>
		<?php $key++; ?>
		<?php
			$t 		= $this->num(strtotime($mob->loppui)-strtotime($mob->aloitan));
			$rivi_kpl 	= 0;
			$r		= [];
			$r 		= $this->hinnastoHintaat($mob->tyovuoroot->tuoteID, $item->asiakasnumero, $mob->kohteet, $t, $rivi_kpl);
			$tp_id		= (( isset($r['tp_id']) )? $r['tp_id']:0);
			$nimike		= (( isset($r['tp_nimike']) )? $r['tp_nimike']:'');
			$kpl 		= (( isset($r['kpl']) )? $r['kpl']:0);
			$hinta 		= (( isset($r['hinta']) )? $r['hinta']:0);
			$alv 		= (( isset($r['alv']) )? $r['alv']:0);
			$yksikko	= (( isset($r['yksikko']) )? $r['yksikko']:'');
			$freetext	= $mob->kohde_kannasta.' - '.date("d.m.Y", strtotime($mob->aloitan));

			// <-- ALV laskin
			$veroton 	= 0;
			$yht 		= 0;
			if( $alvsis == 0 ){
				$laske = ($hinta*$kpl)/100*$alv;
				$veroton = round(($hinta*$kpl), 2);
				$yht = $laske+$veroton;
			}
			if( $alvsis == 1 ){
				$yht = $hinta*$kpl;
				$jakaa = '1.'.$alv;
				$l = $yht/$jakaa;
				$veroton = round($l, 2);
			}
			//     ALV laskin -->

			$yhteensa_total += $yht;
			$yhteensa_total_veroton += $veroton;

			if( isset($lasku->id) and $tp_id != 0){
			   $lr = new LaskunRivit;
			   $lr->lid	= $lasku->id;
			   $lr->rivi	= $key;
			   $lr->tkoodi	= $nimike;
			   $lr->kpl	= $kpl;
			   $lr->yksikko	= $yksikko;
			   $lr->hinta	= $hinta;
			   $lr->hinta_alv= $yht-$veroton;
			   $lr->veroton	= $veroton;
			   $lr->yhteensa_alv= $yht;
			   $lr->alv	= $alv;
			   $lr->ale	= 0;
			   $lr->tuoteID = $tp_id;
			   $lr->free_text= $freetext;
			   if(!$lr->save()){
				print_r($lr->getErrors());
			   }
			}
	        ?>
		<?php if( $tp_id != 0 and $laheta == null ) : ?>
		<tr>
		<td class="input_nimike"><?=$nimike?></td>
		<td class="input_number"><?=number_format($hinta, 2, ',', ' ')?></td>
		<td class="input_yksikko"><?=$yksikko?></td>
		<td class="input_number"><?=$kpl?></td>
		<td class="input_number"><?=$alv?></td>
		<td class="input_number"><?=number_format($veroton, 2, ',', ' ')?></td>
		<td class="input_number"><?=number_format($yht, 2, ',', ' ')?></td>
		<td class="input_text"><?=$freetext?></td>
		</tr>
		<?php endif; ?>

		<!-- Lisatuote -->
		<?php $lisa_tuotteet = json_decode($mob->tyovuoroot->lisa_tuotteet, true); ?>
		<?php if( isset($lisa_tuotteet['tuote']) and is_array($lisa_tuotteet['tuote']) ) : ?>
		<?php foreach($lisa_tuotteet['tuote'] as $k => $v) : ?>
		<?php $key++; ?>
		<?php
			if( isset($tp_lisatuotteet[$mob->tyovuoroot->tyopaari][$mob->tyovuoroot->pvm][$v]) ){ continue; }
			$t 		= json_decode($mob->tyovuoroot->lisa_tuotteet, true)['maara'][$k];
			$rivi_kpl 	= json_decode($mob->tyovuoroot->lisa_tuotteet, true)['maara'][$k];
			$r		= [];
			$r 		= $this->hinnastoHintaat($v, $item->asiakasnumero, $mob->kohteet, $t, $rivi_kpl);
			$tp_id		= (( isset($r['tp_id']) )? $r['tp_id']:0);
			$nimike		= (( isset($r['tp_nimike']) )? $r['tp_nimike']:'');
			$kpl 		= (( isset($r['kpl']) )? $r['kpl']:0);
			$hinta 		= (( isset($r['hinta']) )? $r['hinta']:0);
			$alv 		= (( isset($r['alv']) )? $r['alv']:0);
			$yksikko	= (( isset($r['yksikko']) )? $r['yksikko']:'');
			$freetext	= $mob->kohde_kannasta.' - '.date("d.m.Y", strtotime($mob->aloitan));

			// <-- ALV laskin
			$veroton 	= 0;
			$yht 		= 0;
			if( $alvsis == 0 ){
				$laske = ($hinta*$kpl)/100*$alv;
				$veroton = round(($hinta*$kpl), 2);
				$yht = $laske+$veroton;
			}
			if( $alvsis == 1 ){
				$yht = $hinta*$kpl;
				$jakaa = '1.'.$alv;
				$l = $yht/$jakaa;
				$veroton = round($l, 2);
			}
			//     ALV laskin -->

			$yhteensa_total += $yht;
			$yhteensa_total_veroton += $veroton;

			if( isset($lasku->id) and $tp_id != 0){
			   $lr = new LaskunRivit;
			   $lr->lid	= $lasku->id;
			   $lr->rivi	= $key;
			   $lr->tkoodi	= $nimike;
			   $lr->kpl	= $kpl;
			   $lr->yksikko	= $yksikko;
			   $lr->hinta	= $hinta;
			   $lr->hinta_alv= $yht-$veroton;
			   $lr->veroton	= $veroton;
			   $lr->yhteensa_alv= $yht;
			   $lr->alv	= $alv;
			   $lr->ale	= 0;
			   $lr->tuoteID = $tp_id;
			   $lr->free_text= $freetext;
			   if(!$lr->save()){
				print_r($lr->getErrors());
			   }
			}
	        ?>
		<?php if( $tp_id != 0 and $laheta == null ) : ?>
		<tr>
		<td class="input_nimike"><?=$nimike?></td>
		<td class="input_number"><?=number_format($hinta, 2, ',', ' ')?></td>
		<td class="input_yksikko"><?=$yksikko?></td>
		<td class="input_number"><?=$kpl?></td>
		<td class="input_number"><?=$alv?></td>
		<td class="input_number"><?=number_format($veroton, 2, ',', ' ')?></td>
		<td class="input_number"><?=number_format($yht, 2, ',', ' ')?></td>
		<td class="input_text"><?=$freetext?></td>
		</tr>
		<?php endif; ?>
		<?php if( isset($mob->tyovuoroot) and is_array(json_decode($mob->tyovuoroot->tyopaari, true))){
			$tp_lisatuotteet[$mob->tyovuoroot->tyopaari][$mob->tyovuoroot->pvm][$v] = $item->id; 
		} ?>
		<?php endforeach; ?>
		<?php endif; ?>
		<!-- / Lisatuote -->

		<!-- Update tyovuoro -->
		<?php if( $is_ok_lasku and $laheta !== null and isset($lasku->id) and $mob->tv_id > 0 ){
			$tl = Tyovuoroot::model()->findByPk($mob->tv_id);
			if( isset($tl->id) ){
			   Tyovuoroot::model()->updateByPk($tl->id, array('lasku_id' => $lasku->id));
			}
		} ?>
		<!-- / Update tyovuoro -->
		<?php endforeach; ?>
		</table>
	</td>
	</tr>

	<?php if( $is_ok_lasku and $laheta !== null and isset($lasku->id) ){
		Lasku::model()->updateByPk($lasku->id, 
			array(
			'yhteensa_total_verot' => round(($yhteensa_total-$yhteensa_total_veroton), 2), 
			'yhteensa_total_veroton' => round($yhteensa_total_veroton, 2), 
			'yhteensa_total' => $yhteensa_total
		));

		// <<- Lahetys Trust
		if(isset($lasku->id) 
			and $lasku->tilanne == 1 
			and $asetukset->palvelu_tyyppi == 2 
		){
			$l = Lasku::model()->findByPk($lasku->id); 
			$resp = $this->finvoiceAuto($l->id, 'finvoiceTrust');
			echo $resp;
		}
		// Lahetys Trust -->

		// <<- Lahetys Netvisor
		if(isset($lasku->id) 
			and $lasku->tilanne == 1 
			and $asetukset->palvelu_tyyppi == 4
			and $asetukset->netvisor_kaytto == 1
		){
			$return = $this->lahetaNetvisoriin($lasku->id);
			if( $return != false ){
				$criteria=new CDbCriteria;
				$criteria->condition = " lasku_id='".$lasku->id."' AND peruutettu=0 ";
				Tyovuoroot::model()->updateAll(array('laskutettu' => '1'), $criteria);
			}
		}
		// Lahetys Netvisor -->
	} ?>

	<tr>
	<!-- Painikkeet -->
	<?php if($is_ok_lasku): ?>
		<th>
		<?php
			echo CHtml::link(Yii::t('main', 'Lähetä lasku'), 
				array('lasku/luolaskut', 'from' => $from, 'to' => $to, 'alvsis' => $alvsis, 'asiakas_id' => $item->id, 'paivays' => $paivays, 'laheta' => true), 
				array(
					'class' => 'btn btn-block btn-success',
					'data-toggle'=>'tooltip', 
					'data-placement'=>'top', 
					'title'=>Yii::t('main', 'Lähetä')
				)
			); 
		?>
		</th>
		<th><div class="text-right"><h3><?=Yii::t('main', 'Yhteensä')?></h3></div></th>
		<th><div class="text-left"><h3><?=number_format($yhteensa_total, 2, ',', ' ')?>&euro;</h3></div></th>
	<?php else: ?>
		<tr><th><button class="btn btn-block btn-danger">Lasku ei mennyt läpi</button></th></tr>
	<?php endif; ?>
	<!-- / Painikkeet -->
	</tr>
	<?php endforeach; ?>
	</table>
	<?php endif; // count($lista) > 0 ?>

	<?php if( $laheta ){
		$this->redirect(array('/lasku/auto'));
	} ?>


</div>
</p>

<script type="text/javascript">
$(document).ready(function(){

  $(".lahetakaikki").click(function(e){
	e.preventDefault();
	if(!confirm('Oletko varma')){
		return false;
	} else {
		window.location.href=$(this).attr('href');
	}
  });

  // <-- muokkaus
  $(document).delegate(".fa-edit","click",function(){
    $(this).removeClass('fa-edit').addClass('fa-save');
    $(this).closest('.m_icons').append('<i class="link fa fa-2x fa-plus uusirivi" style="margin-left:10px"></i>');
    $(this).closest('td').find('.th_tuote').before('<th class="th_poisto"></th>');
    $(this).closest('td').find('.alv_valinta').show(370);
    $( $(this).closest('table').find('.rivintaulu td.input_nimike') ).each(function( index ) {
	$(this).replaceWith('<td class="poisto_td"><i class="link fa fa-2x fa-trash"></i></td><td class="input_nimike"><input type="text" class="form-control" value="'+ $(this).text() +'"></td>');
    });
    $( $(this).closest('table').find('.rivintaulu td.input_text') ).each(function( index ) {
	$(this).replaceWith('<td class="input_text"><input type="text" class="form-control" value="'+ $(this).text() +'"></td>');
    });
    $( $(this).closest('table').find('.rivintaulu td.input_number') ).each(function( index ) {
	todigit = $(this).text().replace(/\,/g, '.');
	$(this).replaceWith('<td class="input_number"><input type="number" class="form-control" value="'+ todigit +'"></td>');
    });
  });

  $(document).delegate(".fa-save","click",function(){
    $(this).removeClass('fa-save').addClass('fa-edit');
    $(this).closest('td').find('.th_poisto').remove();
    $(this).closest('td').find('.alv_valinta').hide(370);
    $(this).next('.uusirivi').remove();
    $( $(this).closest('table').find('.rivintaulu td.input_nimike') ).find('input').each(function( index ) {
	$(this).closest('tr').find('.poisto_td').remove();
	$(this).replaceWith('<td class="input_nimike">'+ $(this).val() +'</td>');
    });
    $( $(this).closest('table').find('.rivintaulu td.input_text') ).find('input').each(function( index ) {
	$(this).replaceWith('<td class="input_text">'+ $(this).val() +'</td>');
    });
    $( $(this).closest('table').find('.rivintaulu td.input_number') ).find('input').each(function( index ) {
	todigit = $(this).val().replace(/\./g, ',');
	$(this).replaceWith('<td class="input_number">'+ todigit +'</td>');
    });
  });

  $(document).delegate(".uusirivi","click",function(){
	$(this).closest('td').find('.rivintaulu').append(''+
		'<tr>' +
		'<td class="poisto_td"><i class="link fa fa-2x fa-trash"></i></td>' +
		'<td class="input_text"><input type="text" class="form-control" placeholder="Tuotenimi"></td>' +
		'<td class="input_number"><input type="number" class="form-control" placeholder="Hinta"></td>' +
		'<td></td>' +
		'<td class="input_number"><input type="number" class="form-control"></td>' +
		'<td class="input_number"><input type="number" class="form-control"></td>' +
		'<td class="input_number"><input type="number" class="form-control"></td>' +
		'<td class="input_number"><input type="number" class="form-control"></td>' +
		'<td class="input_text"><input type="text" class="form-control" placeholder="Teksti"></td>' +
		'</tr>'
	);
  });

  $(document).delegate(".fa-trash","click",function(){
	$(this).closest('tr').remove();
  });

  function eachLaskenta(){

  var alvsis = $('.lasku_alv_muoto').val();
  $("#rivit input").each(function() {

	var hinta_alv_0 = 0;
	var alv = 0;
	var kpl = 0;
	var ale = 0;

	var inputKenta = $(this).attr("id").split("_");
	if($("#hinta_"+inputKenta[1]).val()) { hinta_alv_0 = parseFloat($("#hinta_"+inputKenta[1]).val()) };
	if($("#alv_"+inputKenta[1]).val()) { alv = parseFloat($("#alv_"+inputKenta[1]).val()) };
	if($("#kpl_"+inputKenta[1]).val()) { kpl = parseFloat($("#kpl_"+inputKenta[1]).val()) };
	if($("#ale_"+inputKenta[1]).val()) { ale = parseFloat($("#ale_"+inputKenta[1]).val()) };


	inputKenta[1] = parseFloat(inputKenta[1], 10);

	if(ale > 0)
	hinta_alv_0 = hinta_alv_0-((hinta_alv_0/100)*ale);

	if( alvsis == '0'){
		var laske = (hinta_alv_0*kpl)/100*alv;
		var veroton = hinta_alv_0*kpl;
		var yhteensa = laske+veroton;
	}
	if( alvsis == '1'){
		var yhteensa = hinta_alv_0*kpl;
		var jakaa = '1.'+alv;
		var l = yhteensa/parseFloat(jakaa);
		var veroton = l;
		var laske = yhteensa-veroton;
	}

	$("#hinta_alv_"+inputKenta[1]).val(laske.toFixed(2));
	$("#veroton_"+inputKenta[1]).val(veroton.toFixed(2));
	$("#yhteensa_alv_"+inputKenta[1]).val(yhteensa.toFixed(2));

  });
    	yhteensaTotal();

  }
  //     muokkaus -->

});
</script>
