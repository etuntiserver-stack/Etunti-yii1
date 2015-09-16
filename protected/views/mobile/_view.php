<?php
/* @var $this MobileController */
/* @var $data Mobile */

/* TAG */
 $tag = '';
 $t = explode("_",$data->asiakas_num);
 if(isset($t[1]) and $t[1] != 000000)
    $tag = $t[1];
 else
    $tag = Yii::t('main', 'TAG ei ollut käytetty');

 $karttaA = '';
 $karttaL = '';
if(!empty($data->my_location))
{
 $expl = explode("_",$data->my_location);

 $my_locationStart = explode("**",$data->my_location);
 if(isset($my_locationStart[0]))  
 $my_locationReal[1] = explode("/",$my_locationStart[0]);
 if(isset($my_locationStart[1]))
 $my_locationReal[2] = explode("/",$my_locationStart[1]);

 if(isset($my_locationReal[1][0]) and isset($my_locationReal[1][1]))
 $karttaA = '<a href="http://maps.google.com/maps?q='.$my_locationReal[1][0].','.$my_locationReal[1][1].'&ll='.$my_locationReal[1][0].','.$my_locationReal[1][1].'&z=17" target="_blank"><span class="glyphicon glyphicon-arrow-down"></span></a>';
 if(isset($my_locationReal[2][0]) and isset($my_locationReal[2][1]))
 $karttaL = '<a href="http://maps.google.com/maps?q='.$my_locationReal[2][0].','.$my_locationReal[2][1].'&ll='.$my_locationReal[2][0].','.$my_locationReal[2][1].'&z=17" target="_blank"><span class="glyphicon glyphicon-arrow-up"></span></a>';
}

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
 $lt[$data->id] = '00:00';
 $lpvm[$data->id] = '';
}

if(!empty($data->loppui) and !empty($data->aloitan))
  $kesto[$data->id] =  strtotime($data->loppui) - strtotime($data->aloitan);
else
  $kesto[$data->id] =  '';

if(!empty($data->tietoja) and !empty($data->tietoja))
  $muokattu[$data->id] =  '<span class="glyphicon glyphicon-check text-info"></span>';
else
  $muokattu[$data->id] =  '<span class="glyphicon glyphicon-arrow-down text-danger"></span>';


	if($data->status == '1')
	$door = " <img src='".Yii::app()->request->baseUrl."/img/uborka.png' alt='aloitettu' class='img-responsive' />";
	elseif($data->status == '3')
	$door = " <img src='".Yii::app()->request->baseUrl."/img/ok.png' alt='valmiit' class='img-responsive' />";
	elseif($data->status == '2')
	$door = " <img src='".Yii::app()->request->baseUrl."/img/bussi.jpg' alt='valmiit' class='img-responsive' />";
	elseif($data->status == '10')
	$door = " <img src='".Yii::app()->request->baseUrl."/img/food.png' alt='lounaalla' class='img-responsive' />";
	elseif($data->status == '7')
	$door = " <img src='".Yii::app()->request->baseUrl."/img/virhe.png' alt='virhe' class='img-responsive' />";
	else
	$door = "";


	$objcts = '';
	$osoite = '';

	// <-- adminPaketti
	$obtrue = false;
	$tas = explode(",",Yii::app()->user->adminPaketti);

	if(in_array('2',$tas) and isset($data->tid) and !empty($apvmForSu[$data->id]))
 	{
	  $su = Tyovuoroot::model()->find(" tid='".$data->tid."' and pvm='".$apvmForSu[$data->id]."' ");
	  if(isset($su['id']))
	  $obtrue = true;
	}
	// <-- adminPaketti 

?>

<tr id="rivi_<?php echo $data->id; ?>">

	<td>
	<div class="row">
	  <div class="col-sm-6">
		<?php echo $door; ?>
	  </div>
	</div>
	</td>

	<td><?php echo CHtml::link(CHtml::encode($data->id), array('update', 'id'=>$data->id)); ?></td>
	<td><b><?php echo CHtml::encode(date("d.m",strtotime($data->aloitan))); ?></b></td>
	<td><?php echo $karttaA." ".$karttaL; ?></td>

	<td>
	<div class="row">
	<!--
	  <div class="col-sm-2">
	  <?php 
	     $filename = "../../img/tekijat/".Yii::app()->user->domain."/".$data->tid.".jpg";
	     if (file_exists(Yii::app()->request->baseUrl."img/tekijat/".Yii::app()->user->domain."/".$data->tid.".jpg"))
	     echo '<img src="'.$filename.'" class="img-thumbnail">';
	     else
	     echo '<img src="../../img/tekijat/noname.jpg" class="img-responsive">';
	  ?>
	  </div>
	 -->
	  <?php echo CHtml::link(' ','/index.php/viestinta/create?tid='.$data->tid,array('target'=>'_blank','class'=>'link glyphicon glyphicon-envelope')); ?>&nbsp;
	  <?php echo CHtml::link($data->tekijan_nimi,'/index.php/tyontekijat/update?id='.$data->tid,array('target'=>'_blank')); ?>
	</div>
	</td>

	<!-- adminPaketti -->
	<?php if(in_array('2',$tas)) : ?>
	<?php
	$did = date("Ymd",strtotime($apvm[$data->id]));
	?>
	<td>
	  <span class="link text-warning vietyovuoroon" pvmtid="<?php echo $did.'_'.$data->tid; ?>"><b class="glyphicon glyphicon-fullscreen"></b></span>
	<?php  if($obtrue == true):  ?>

	  <span class="link text-success" data-toggle="collapse" data-target="<?php echo '#sushow_'.$data->id; ?>">
	  <b class="glyphicon glyphicon-sort-by-attributes-alt"></b>
	  </a>

	    <div style="position:absolute;width:300px;z-index: 2;" class="collapse" id="<?php echo 'sushow_'.$data->id; ?>">
	    <div class="alert alert-info" style=""><?php $this->renderPartial('//tyovuoroot/did',array('pvm'=>$data->aloitan,'tid'=>$data->tid,'from'=>'mobiili')); ?></div>
	    </div>

	<?php endif; ?>
	</td>
	<?php endif; ?>
	<!-- adminPaketti -->

	<td>
	  <span class="link" data-toggle="collapse" data-target="<?php echo '#tagshow_'.$data->id; ?>">
	   <?php if(!empty($tag) and $tag != 000000) : ?>
	    <b class="text-success glyphicon glyphicon-tag"></b>
	   <?php else: ?>
	    <b class="text-danger glyphicon glyphicon-tag"></b>
	   <?php endif; ?>
	  </span>
	  <div style="position:absolute;z-index: 2;" class="collapse" id="<?php echo 'tagshow_'.$data->id; ?>">
	     <div class="well"><?php echo $tag; ?></div>
	  </div>
	</td>

	<td>
	  <span class="link glyphicon glyphicon-edit openkohde" id="<?php echo 'kohttisID_'.$data->id; ?>" for="<?php echo 'kohtval_'.$data->id; ?>" data-toggle="collapse" data-target="<?php echo '#kshow_'.$data->id; ?>"></span>&nbsp;

	  <span id="vaihto_<?php echo 'kohttisID_'.$data->id; ?>">
	  <?php 
	  if(!empty($data->kohdenID))
		echo CHtml::link($data->kohde_kannasta,'/index.php/kohteet/update?id='.$data->kohdenID,array('target'=>'_blank','class'=>'text-success')); 
	  else
		echo '<span class="text-danger">'.$data->kohde_kannasta.'</span>';
	  ?>
	  </div>

	  <div style="position:absolute;z-index: 2;" class="collapse" id="<?php echo 'kshow_'.$data->id; ?>">
	    <div class="well" id="<?php echo 'kohtval_'.$data->id; ?>"></div>
	  </div>
	</td>

	<td width="1">
	  <span class="link" data-toggle="collapse" id="<?php echo 'altxt_'.$data->id; ?>" data-target="<?php echo '#alshow_'.$data->id; ?>"><?php echo $at[$data->id]; ?></span>
	  <div style="position:absolute;z-index: 2;margin-left:-100px" class="collapse" id="<?php echo 'alshow_'.$data->id; ?>">
	    <div class="well">
	     <?php echo '<input type="datetime-local" class="pvmupdate form-control" request="aloitan" status="'.$data->status.'" id="al_'.$data->id.'" for="altxt_'.$data->id.'" value="'.$apvm[$data->id].'T'.$at[$data->id].'">'; ?>
	    </div>
	  </div>
	</td>

	<td width="1">
	  <span class="link" data-toggle="collapse" id="<?php echo 'lptxt_'.$data->id; ?>" data-target="<?php echo '#ltshow_'.$data->id; ?>"><?php echo $lt[$data->id]; ?></span>
	  <div style="position:absolute;z-index: 2;margin-left:-100px" class="collapse" id="<?php echo 'ltshow_'.$data->id; ?>">
	    <div class="well">
	     <?php echo '<input type="datetime-local" class="pvmupdate form-control" request="loppui" status="'.$data->status.'" id="lt_'.$data->id.'" for="lptxt_'.$data->id.'" value="'.$lpvm[$data->id].'T'.$lt[$data->id].'">'; ?>
	    </div>
	  </div>
	</td>

	<td><span id="kesto_<?php echo $data->id; ?>"><?php echo sprint($kesto[$data->id]); ?></span></td>
	<td><center><?php echo $muokattu[$data->id]; ?></center></td>
	<td><center><span class="link glyphicon glyphicon-trash text-danger poistaKohde" for="rivi_<?php echo $data->id; ?>"></span></center></td>
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


