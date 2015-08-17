<?php
/* @var $this SivexkuittiController */
/* @var $data Sivexkuitti */


// $tag = explode("_",$data->asiakas_num);
/*<td><?php echo CHtml::encode($tag[1]); ?></td>*/

if(!empty($data->loppui))
 $dloppu[$data->id] = date("H:i",strtotime($data->loppui));
else
 $dloppu[$data->id] = '';

if(!empty($data->aloitan)){
 $at[$data->id] = date("H:i",strtotime($data->aloitan));
 $apvm[$data->id] = date("Y-m-d",strtotime($data->aloitan));
 $apvmForSu[$data->id] = date("d.m.Y",strtotime($data->aloitan));
} else {
 $at[$data->id] = '';
 $apvm[$data->id] = '';
 $apvmForSu[$data->id] = '';
}

if(!empty($data->loppui)){
 $lt[$data->id] = date("H:i",strtotime($data->loppui));
 $lpvm[$data->id] = date("Y-m-d",strtotime($data->loppui));
} else {
 $lt[$data->id] = '';
 $lpvm[$data->id] = '';
}

if(!empty($data->loppui) and !empty($data->aloitan))
  $kesto[$data->id] =  strtotime($data->loppui) - strtotime($data->aloitan);
else
  $kesto[$data->id] =  '';


	if($data->status == '1')
	$door = " <img src='".Yii::app()->request->baseUrl."/img/uborka.png' alt='aloitettu' height='40'/>";
	elseif($data->status == '3')
	$door = " <img src='".Yii::app()->request->baseUrl."/img/ok.png' alt='valmiit' height='40' />";
	elseif($data->status == '2')
	$door = " <img src='".Yii::app()->request->baseUrl."/img/bussi.jpg' alt='valmiit' height='40' />";
	elseif($data->status == '10')
	$door = " <img src='".Yii::app()->request->baseUrl."/img/food.png' alt='lounaalla' height='40' />";
	elseif($data->status == '7')
	$door = " <img src='".Yii::app()->request->baseUrl."/img/virhe.png' alt='virhe' height='40' />";
	else
	$door = "";


	$objcts = '';
	$osoite = '';
	$obtrue = false;

	if(Yii::app()->user->adminPaketti == '2' and isset($data->tid) and !empty($apvmForSu[$data->id]))
 	{
	  $su = Tyovuoroot::model()->findAll(" tid='".$data->tid."' and pvm='".$apvmForSu[$data->id]."' ");
     	  foreach($su as $ob)
	  {
	    $k = Kohteet::model()->findbypk($ob->kohde);
	    if(isset($k->osoite))
	    $osoite = $k->osoite;

	    $obtrue = true;
	    $objcts .=  $ob->alku."-".$ob->loppu." ".$osoite."<br>";
	  }
	}
?>

<tr>
	<td width="1"><?php echo $door; ?></td>
	<td><?php echo CHtml::link(CHtml::encode($data->id), array('update', 'id'=>$data->id)); ?></td>
	<td><b><?php echo CHtml::encode(date("d.m",strtotime($data->aloitan))); ?></b></td>
	<td><?php echo CHtml::encode($data->tekijan_nimi); ?></td>
	<td><?php echo CHtml::encode($data->kohde_kannasta); ?></td>
		
	<?php if(Yii::app()->user->adminPaketti == '2') : ?>
	<td width="1">
	<?php if($obtrue) : ?>
	<div class="row">
	 <div class="col-sm-3">
	  <div class="btn btn-info" data-toggle="collapse" data-target="<?php echo '#sushow_'.$data->id; ?>"><?php echo Yii::t('main', 'suunniteltu'); ?> <b class="caret"></b></div>
	  <div style="position:absolute;width:300px;z-index: 99999999;" class="collapse" id="<?php echo 'sushow_'.$data->id; ?>">
	  <br>
	    <div class="alert alert-info" style="">
	      <?php  echo $objcts; ?>
	    </div>
	  </div>
	 </div>
	</div>
	<?php endif; ?>
	</td>
	<?php endif; ?>

	<td width="1">
	<div class="row">
	 <div class="col-sm-3">
	  <div class="btn btn-default" data-toggle="collapse" data-target="<?php echo '#alshow_'.$data->id; ?>"><?php echo $at[$data->id]; ?> <b class="caret"></b></div>
	  <div class="row collapse col-sm-4" id="<?php echo 'alshow_'.$data->id; ?>">
	  <br>
	     <?php echo '<input type="datetime-local" class="pvmupdate btn btn-sm btn-default" request="aloitan" status="'.$data->status.'" id="al_'.$data->id.'" value="'.$apvm[$data->id].'T'.$at[$data->id].'">'; ?>
	  </div>
	 </div>
	</div>
	</td>

	<td width="1">
	<div class="row">
	 <div class="col-sm-3">
	  <div class="btn btn-default" data-toggle="collapse" data-target="<?php echo '#ltshow_'.$data->id; ?>"><?php echo $lt[$data->id]; ?> <b class="caret"></b></div>
	  <div class="row collapse col-sm-4" id="<?php echo 'ltshow_'.$data->id; ?>">
	  <br>
	     <?php echo '<input type="datetime-local" class="pvmupdate btn btn-sm btn-default" request="loppui" status="'.$data->status.'" id="lt_'.$data->id.'" value="'.$lpvm[$data->id].'T'.$lt[$data->id].'">'; ?>
	  </div>
	 </div>
	</div>
	</td>

	<td><?php echo sprint($kesto[$data->id]); ?></td>
</tr>

	
	


	
	

	<?php /*

	<td><?php echo CHtml::encode($data->asiakas_num); ?></td>
	
	

	<td><?php echo CHtml::encode($data->time); ?></td>
	
	

	<td><?php echo CHtml::encode($data->requests); ?></td>
	
	

	<td><?php echo CHtml::encode($data->puh_numero); ?></td>
	
	

	<td><?php echo CHtml::encode($data->imei); ?></td>

	<td><?php echo CHtml::encode($data->getAttributeLabel('sim_serial_number')); ?>:</td>
	<?php echo CHtml::encode($data->sim_serial_number); ?>
	

	<td><?php echo CHtml::encode($data->getAttributeLabel('subscriber_id')); ?>:</td>
	<?php echo CHtml::encode($data->subscriber_id); ?>
	

	<td><?php echo CHtml::encode($data->getAttributeLabel('my_location')); ?>:</td>
	<?php echo CHtml::encode($data->my_location); ?>
	

	<td><?php echo CHtml::encode($data->getAttributeLabel('osoite')); ?>:</td>
	<?php echo CHtml::encode($data->osoite); ?>
	

	<td><?php echo CHtml::encode($data->getAttributeLabel('kohde_kannasta')); ?>:</td>
	<?php echo CHtml::encode($data->kohde_kannasta); ?>
	

	<td><?php echo CHtml::encode($data->getAttributeLabel('kohdenID')); ?>:</td>
	<?php echo CHtml::encode($data->kohdenID); ?>
	

	<td><?php echo CHtml::encode($data->getAttributeLabel('aloitan')); ?>:</td>
	<?php echo CHtml::encode($data->aloitan); ?>
	

	<td><?php echo CHtml::encode($data->getAttributeLabel('loppui')); ?>:</td>
	<?php echo CHtml::encode($data->loppui); ?>
	

	<td><?php echo CHtml::encode($data->getAttributeLabel('viesti')); ?>:</td>
	<?php echo CHtml::encode($data->viesti); ?>
	

	<td><?php echo CHtml::encode($data->getAttributeLabel('tekijan_nimi')); ?>:</td>
	<?php echo CHtml::encode($data->tekijan_nimi); ?>
	

	<td><?php echo CHtml::encode($data->getAttributeLabel('tid')); ?>:</td>
	<?php echo CHtml::encode($data->tid); ?>
	

	<td><?php echo CHtml::encode($data->getAttributeLabel('etaisyys')); ?>:</td>
	<?php echo CHtml::encode($data->etaisyys); ?>
	

	<td><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</td>
	<?php echo CHtml::encode($$data->status); ?>
	

	<td><?php echo CHtml::encode($data->getAttributeLabel('tietoja')); ?>:</td>
	<?php echo CHtml::encode($data->tietoja); ?>
	

	<td><?php echo CHtml::encode($data->getAttributeLabel('admin')); ?>:</td>
	<?php echo CHtml::encode($data->admin); ?>
	

	<td><?php echo CHtml::encode($data->getAttributeLabel('hyvaksytty')); ?>:</td>
	<?php echo CHtml::encode($data->hyvaksytty); ?>
	

	*/ ?>


