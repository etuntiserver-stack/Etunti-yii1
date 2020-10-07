<?php
/* @var $this MobileController */
/* @var $data Mobile */

// <-- adminPaketti
$tas = explode(",",Yii::app()->user->adminPaketti);
// <-- adminPaketti

$date 	= date("d.m.Y",strtotime($data->aloitan));
$tid 	= $data->tid;


$class = '';
// <-- Jos sivu on laskutettu
if( isset($sivu) and $sivu == 'laskutettu' )
{

  	$toteutuneet = false;

	$criteria = new CDbCriteria();
       	$criteria->condition = " kid='".$data->id."' ";
  	$tot = Toteutuneet::model()->find($criteria);
   	if(isset($tot->id))
   	{
      		$toteutuneet = true;
      		$data = $tot;
   	}

	if($data->laskutettu == 1)
	  $checked =  'checked';
	else
	  $checked =  '';


  	if($toteutuneet)
  	{
  		$class = 'border:2px green solid;';

  	} elseif(empty($data->loppui)) {

  	}
}
// Jos sivu on laskutettu -->


/* TAG */
 $tag = '';
 $t = explode("_",$data->asiakas_num);
 if(isset($t[1]) and $t[1] != 000000){ $tag = $t[1]; } else { $tag = Yii::t('main', 'TAG ei ollut käytetty'); }

 $versio = '';
 if(isset($t[0]) and !empty($t[0])){ $versio = $t[0]; }

 $karttaA = '';
 $karttaL = '';
 if(!empty($data->my_location)){
 $expl = explode("_",$data->my_location);

 $my_locationStart = explode("**",$data->my_location);
 if(isset($my_locationStart[0]))  
 $my_locationReal[1] = explode("/",$my_locationStart[0]);
 if(isset($my_locationStart[1]))
 $my_locationReal[2] = explode("/",$my_locationStart[1]);

 if(isset($my_locationReal[1][0]) and isset($my_locationReal[1][1]))
 $karttaA = '<a href="http://maps.google.com/maps?q='.$my_locationReal[1][0].','.$my_locationReal[1][1].'&ll='.$my_locationReal[1][0].','.$my_locationReal[1][1].'&z=17" target="_blank"><span class="fa fa-map"></span></a>';
 if(isset($my_locationReal[2][0]) and isset($my_locationReal[2][1]))
 $karttaL = '<a href="http://maps.google.com/maps?q='.$my_locationReal[2][0].','.$my_locationReal[2][1].'&ll='.$my_locationReal[2][0].','.$my_locationReal[2][1].'&z=17" target="_blank"><span class="fa fa-map-o"></span></a>';
 }

 if(!empty($data->loppui)){ $dloppu[$data->id] = date("H:i",strtotime($data->loppui)); } else { $dloppu[$data->id] = ''; }
 $pv = explode("\n", $data->viesti);

 if(isset($pv[0]) and !empty($pv[0]) and strpos($pv[0], 'xxx') === false){
	$pikkuviesti = '<div class="row"><div class="col-sm-12 text-danger"><b>'.Yii::t('main', 'Viesti').':</b> '.$pv[0].'</div><div>';
 } elseif(isset($pv[1]) and !empty($pv[1]) and strpos($pv[1], 'xxx') === false){
	$pikkuviesti = '<div class="row"><div class="col-sm-12 text-danger"><b>'.Yii::t('main', 'Viesti').':</b> '.$pv[1].'</div><div>';
 } else {
	$pikkuviesti = '';
 }

 if(!empty($data->aloitan)){
 $at[$data->id] = date("H:i",strtotime($data->aloitan));
 $apvm[$data->id] = date("d.m.Y",strtotime($data->aloitan));
 $apvmForSu[$data->id] = date("d.m.Y",strtotime($data->aloitan));
 } else {
 $at[$data->id] = '';
 $apvm[$data->id] = '';
 $apvmForSu[$data->id] = '';
 }

 if(!empty($data->loppui)){
 $lt[$data->id] = date("H:i",strtotime($data->loppui));
 $lpvm[$data->id] = date("d.m.Y",strtotime($data->loppui));
 } else {
 $lt[$data->id] = '';
 $lpvm[$data->id] = '';
 }

 if(!empty($data->loppui) and !empty($data->aloitan)){
 $data->loppui = date("d.m.Y H:i",strtotime($data->loppui));
 $data->aloitan = date("d.m.Y H:i",strtotime($data->aloitan));
 $kesto[$data->id] =  strtotime($data->loppui) - strtotime($data->aloitan);
 } else {
 $kesto[$data->id] =  '';
 }

 if($data->status == '1'){
	$door = '<b><i class="fa fa-hourglass-start text-info"></i></b>';
 } elseif($data->status == '3'){
	$door = '<b><i class="fa fa-check text-success"></i></b>';
 } elseif($data->status == '2'){
	$door = '<b><i class="fa fa-bus text-info"></i></b>';
 } elseif($data->status == '10'){
	$door = '<b><i class="fa fa-cutlery text-info"></i></b>';
 } elseif($data->status == '7'){
	$door = '<i class="fa fa-bolt"></i>';
 } else {
	$door = "";
 }

 $objcts = '';
 $osoite = '';

 $diff = 0;
 if(date('Y-m-d H:i', strtotime($data->aloitan.'+4 hour')) < date('Y-m-d H:i') and empty($data->loppui)){
  	$diff = strtotime(date('Y-m-d H:i'))-strtotime($data->aloitan);
  	$class = 'border:2px red solid;';
 } elseif(empty($data->loppui)) {
  	$diff = strtotime(date('Y-m-d H:i'))-strtotime($data->aloitan);
 }

 $erittelyt = '';
 if( isset($data->tyovuoroot->tyo_erittelyt) and !empty($data->tyovuoroot->tyo_erittelyt)){
	$erittelyt = '<i class="link fa fa-list show_erittelyt" tv_id="'.$data->tyovuoroot->id.'" mob_id="'.$data->id.'"></i>';
 }
//$versio = '0.0.650';
?>

<tr style="<?php echo $class; ?>" id="rivi_<?php echo $data->id; ?>">

	<td>
		<?php echo CHtml::link('<i class="fa fa-pencil-square-o" aria-hidden="true" style="font-size: 110%"></i>', 
				array('update', 'id'=>$data->id), 
				array(
					'class'=>'btn btn-primary myBgColors', 
					'style'=>'color:white', 
					'data-toggle'=>'tooltip', 
					'data-placement'=>'top', 
					'title'=>Yii::t('main', 'Muokkaa') 
				)
			); 
		?>
	</td>

	<td style="white-space: nowrap">
		<?php echo $door; ?>

		<?php if($diff > 0) : ?>
		 <b><?php echo $this->sprint($diff); ?></b>
		<?php endif; ?>
	</td>

	<td style="white-space: nowrap;" class="text-center">
		<?php
		if($data->gps_enabled == 'enabled') 
			echo '<div><i class="glyphicon glyphicon-map-marker text-success"></i><span class="small">'.$data->app_platform.'</span></div>';
 		if($data->gps_enabled == 'disabled') 
			echo '<div><i class="glyphicon glyphicon-map-marker text-default"></i><span class="small">'.$data->app_platform.'</span></div>';
		?>
		<span class="small"><?=$versio?></span>
	</td>
	<td><b><?php echo CHtml::encode(date("d.m",strtotime($data->aloitan))); ?></b></td>
	<td class="text-center">
		<div><?= 
			'<span data-toggle="tooltip" data-placement="top" title="Aloitus karttalla">'.$karttaA.'</span>
			<span data-toggle="tooltip" data-placement="top" title="Lopetus karttalla">'.$karttaL 
		?></div>
		<?php 
		if(
			$data->tv_id > 0 
			and ( $data->status == 1 or $data->status == 3 )
			and ( $data->app_aloitus_destination_checker > 0 or $data->app_lopetus_destination_checker > 0 )
		){

			if($data->app_aloitus_destination_checker > 1000)
				$data->app_aloitus_destination_checker = 0;
			if($data->app_lopetus_destination_checker > 1000)
				$data->app_lopetus_destination_checker = 0;

			$varoitus_al = '';
			$varoitus_lp = '';
			if($data->app_aloitus_destination_checker > 3 and $data->app_aloitus_destination_checker < 100)
				$varoitus_al = 'text-danger';
			if($data->app_lopetus_destination_checker > 3 and $data->app_lopetus_destination_checker < 100)
				$varoitus_lp = 'text-danger';

		echo '<div class="row">
			<table class="table" width="100%">
			<td class="'.$varoitus_al.'" style="padding: 3px 5px" width="50%" data-toggle="tooltip" title="Aloitus kilometri määrä"><b>'.((empty($data->app_aloitus_destination_checker) or $data->app_aloitus_destination_checker == 0)? '0.00' : $data->app_aloitus_destination_checker).'</b></td>
			<td class="'.$varoitus_lp.'" style="padding: 3px 5px" width="50%" data-toggle="tooltip" title="Lopetus kilometri määrä"><b>'.((empty($data->app_lopetus_destination_checker) or $data->app_lopetus_destination_checker == 0)? '0.00' : $data->app_lopetus_destination_checker).'</b></td>
			</table></div>';
		}
		?>
	</td>

	<td>
	  <?php echo CHtml::link(' ','/index.php/viestinta/create?tid='.$data->tid,array('target'=>'_blank','class'=>'link fa fa-envelope')); ?>&nbsp;
	  <?php echo CHtml::link($this->etuSukunimi($data->tid),'/index.php/tyontekijat/update?id='.$data->tid,array('target'=>'_blank','style'=>'color: #0A98DC;')); ?>
	</td>
	<td><?=$erittelyt?></td>

	<!-- adminPaketti -->
	<?php if( in_array('2',$tas) ) : ?>
	<td>
		<?php if( isset($sivu) and $sivu == 'index' and isset($tv_arr[$tid][$date]) ): ?>
		<span class="link" data-toggle="collapse" data-target="<?php echo '#sushow_'.$data->id; ?>">
		<span class="fa fa-list-alt"></span>
		</span>

		<div style="position:absolute;width:300px;z-index: 2;" class="collapse" id="<?php echo 'sushow_'.$data->id; ?>">
		<div class="row">
		<div class="panel col-sm-12">
		<?php 
		$did 		= date("Ymd",strtotime($date));
		$didoResult 	= '';
		$didoResult 	.= '<div id="suun_'.$did.'_'.$tid.'" class="latikkoAsetukset" pvm="'.$date.'" tid="'.$tid.'">';
		ksort($tv_arr[$tid][$date]);
		foreach($tv_arr[$tid][$date] as $k => $v)
			foreach($v as $v2)
				$didoResult .= '<p><span class="pull-right">'.$this->sprint($v2['tv_kesto']).'</span>'.$v2['tv_edit'].'</p>';
		$didoResult 	.= '</div>';
		echo $didoResult;
		?>
		</div>
		</div>
		</div>
		<?php endif; ?>
	</td>
	<?php endif; ?>
	<!-- adminPaketti -->

	<td>
	  <span class="link" data-toggle="collapse" data-target="<?php echo '#tagshow_'.$data->id; ?>">
	   <?php if(!empty($tag) and $tag != 000000) : ?>
	    <b class="text-success fa fa-tags"></b>
	   <?php else: ?>
	    <b class="text-danger fa fa-tags"></b>
	   <?php endif; ?>
	  </span>
	  <div style="position:absolute;z-index: 2;" class="collapse" id="<?php echo 'tagshow_'.$data->id; ?>">
	     <div class="well"><?php echo $tag; ?></div>
	  </div>
	</td>

	<td>
	  <?php 
	  $asiakas = '';
	  if(!empty($data->kohdenID))
	  {
		$k = Kohteet::model()->findbypk($data->kohdenID);
		if(isset($k->asiakas_id))
		{
			$a = Asiakkaat::model()->findbypk($k->asiakas_id);
			if(isset($a->id) and !empty($a->yrityksen_nimi))
			$asiakas = $a->yrityksen_nimi;
			elseif(isset($a->id) and empty($a->yrityksen_nimi) and !empty($a->yhteyshenkilo))
			$asiakas = $a->yhteyshenkilo;
		}
	  }
	  echo $asiakas;
	  ?>
	</td>

	<td>
	  <span class="link fa fa-pencil-square-o openkohde muokkaminen" id="<?php echo 'kohttisID_'.$data->id; ?>" for="<?php echo 'kohtval_'.$data->id; ?>" data-toggle="collapse" data-target="<?php echo '#kshow_'.$data->id; ?>"></span>&nbsp;

	  <span id="vaihto_<?php echo 'kohttisID_'.$data->id; ?>">
	  <?php 
	  if(!empty($data->kohdenID))
		echo CHtml::link($data->kohde_kannasta,'/index.php/kohteet/update?id='.$data->kohdenID,array('target'=>'_blank','class'=>'text-success')); 
	  else
		echo '<span class="text-danger">'.$data->kohde_kannasta.'</span>';

	  echo $pikkuviesti;
	  ?>
	  </span>

	  <div style="position:absolute;z-index: 2;" class="collapse" id="<?php echo 'kshow_'.$data->id; ?>">
	    <div class="well" id="<?php echo 'kohtval_'.$data->id; ?>"></div>
	  </div>
	</td>

	<td>
	  <span class="link fa fa-pencil-square-o nowrap muokkaminen" data-toggle="collapse" id="<?php echo 'altxt_'.$data->id; ?>" data-target="<?php echo '#alshow_'.$data->id; ?>"> <?php echo $at[$data->id]; ?></span>

	  <div style="position:absolute;z-index: 2;margin-left:-100px" class="collapse" id="<?php echo 'alshow_'.$data->id; ?>">
	    <div class="well form-inline">
	     <?php echo '<input type="text" class="form-control form-group ajaat" request="aloitan" status="'.$data->status.'" id="al_'.$data->id.'" value="'.$apvm[$data->id].' '.$at[$data->id].'">'; ?>

		<div class="form-group input-group-btn">
		     <button class="btn btn-success pvmupdate" for="al_<?php echo $data->id; ?>" >
			<?php echo Yii::t('main', 'ok'); ?>
		     </button>
		</div>

	    </div>
	  </div>
	</td>

	<td width="1">
	  <span class="link fa fa-pencil-square-o nowrap muokkaminen" data-toggle="collapse" id="<?php echo 'lptxt_'.$data->id; ?>" data-target="<?php echo '#ltshow_'.$data->id; ?>"> 
	  <?php 
	  if(empty($lt[$data->id])) 
		echo ' <b class="text-success glyphicon glyphicon-plus"></b>'; 
	  else
	 	echo $lt[$data->id]; 
	  ?></span>

	  <div style="position:absolute;z-index: 2;margin-left:-100px" class="collapse" id="<?php echo 'ltshow_'.$data->id; ?>">
	    <div class="well form-inline">
	     <?php echo '<input type="text" class="form-control form-group ajaat" request="loppui" status="'.$data->status.'" id="lt_'.$data->id.'" value="'.$lpvm[$data->id].' '.$lt[$data->id].'">'; ?>

		<div class="form-group input-group-btn">
		     <button class="btn btn-success pvmupdate" for="lt_<?php echo $data->id; ?>" >
			<?php echo Yii::t('main', 'ok'); ?>
		     </button>
		</div>

	    </div>
	  </div>
	</td>

	<td><span id="kesto_<?php echo $data->id; ?>"><?php echo $this->sprint($kesto[$data->id]); ?></span></td>

	<?php if( isset($sivu) and $sivu == 'laskutettu' ) : ?>
	<td><center>
	    <input type="checkbox" class="chckbxHyvaksynta" id="laskutettu_<?php echo $data->id; ?>" tot="<?php echo $toteutuneet; ?>" <?php echo $checked; ?>> 
	    </center>
	</td>
	<?php endif; ?>

	<?php if( isset($sivu) and $sivu == 'index' ) : ?>
	<td><center>
		<?php 
		if( empty($data->hyvaksytty) ){ 
			echo CHtml::link('<span class="text-danger">Ei</span>', 
				array('//toteutuneet/index', 'from' => date("d.m.Y", strtotime($data->aloitan)), 'to' => date("d.m.Y", strtotime($data->aloitan)), 'tid' => $data->tid), 
				array(
					//'target' => '_blank',
					'data-toggle'=>'tooltip', 
					'data-placement'=>'top', 
					'title'=>Yii::t('main', 'Siirä minut hyväksyntään') 
				)
			); 
		} else {
			echo CHtml::link('<span class="text-success">Kyllä</span>', 
				array('//toteutuneet/index', 'from' => date("d.m.Y", strtotime($data->aloitan)), 'to' => date("d.m.Y", strtotime($data->aloitan)), 'tid' => $data->tid), 
				array(
					//'target' => '_blank',
					'data-toggle'=>'tooltip', 
					'data-placement'=>'top', 
					'title'=>Yii::t('main', 'Siirä minut hyväksyntään') 
				)
			);
		}
		?>
	</center></td>
	<td><center><span class="link glyphicon glyphicon-trash text-danger poistaKohde" for="rivi_<?php echo $data->id; ?>"></span></center></td>
	<?php endif; ?>


</tr>
