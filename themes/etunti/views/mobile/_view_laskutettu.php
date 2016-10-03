<?php
/* @var $this MobileController */
/* @var $data Mobile */
/*
// onko toteumassa
  $toteutuneet = false;
  $tot = Toteutuneet::model()->find(" kid='".$data->id."' ");
   if(isset($tot->id))
   {
      $toteutuneet = true;
      $data = $tot;
   }

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
 $karttaA = '<a href="http://maps.google.com/maps?q='.$my_locationReal[1][0].','.$my_locationReal[1][1].'&ll='.$my_locationReal[1][0].','.$my_locationReal[1][1].'&z=17" target="_blank"><span class="fa fa-map"></span></a>';
 if(isset($my_locationReal[2][0]) and isset($my_locationReal[2][1]))
 $karttaL = '<a href="http://maps.google.com/maps?q='.$my_locationReal[2][0].','.$my_locationReal[2][1].'&ll='.$my_locationReal[2][0].','.$my_locationReal[2][1].'&z=17" target="_blank"><span class="fa fa-map-o"></span></a>';
}

if(!empty($data->loppui))
 $dloppu[$data->id] = date("H:i",strtotime($data->loppui));
else
 $dloppu[$data->id] = '';

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

if(!empty($data->tietoja) and !empty($data->tietoja))
  $muokattu[$data->id] =  '<span class="fa fa-check-square-o"></span>';
else
  $muokattu[$data->id] =  '<span class="fa fa-sign-in"></span>';


	if($data->status == '1')
	$door = '<b><i class="fa fa-hourglass-start text-info"></i></b>';
	elseif($data->status == '3')
	$door = '<b><i class="fa fa-check text-success"></i></b>';
	elseif($data->status == '2')
	$door = '<b><i class="fa fa-bus text-info"></i></b>';
	elseif($data->status == '10')
	$door = '<b><i class="fa fa-cutlery text-info"></i></b>';
	elseif($data->status == '7')
	$door = '<i class="fa fa-bolt"></i>';
	else
	$door = "";


	$objcts = '';
	$osoite = '';


  $class 	= '';
  $diff 	= 0;
  if($toteutuneet)
  {
  	$diff = strtotime(date('Y-m-d H:i'))-strtotime($data->aloitan);
  	$class = 'border:2px green solid;';
  } elseif(empty($data->loppui)) {
  	$diff = strtotime(date('Y-m-d H:i'))-strtotime($data->aloitan);
  	$class = '';
  }


if($data->laskutettu == '1')
  $laskutettu =  'checked';
else
  $laskutettu =  '';
?>

<tr style="<?php echo $class; ?>" id="rivi_<?php echo $data->id; ?>">

	<td><b><?php echo $data->id; ?></b></td>
	<td><b><?php echo CHtml::encode(date("d.m",strtotime($data->aloitan))); ?></b></td>
	<td><?php echo $karttaA." ".$karttaL; ?></td>

	<td>
	  <?php echo CHtml::link($data->tekijan_nimi,'/index.php/tyontekijat/update?id='.$data->tid,array('target'=>'_blank')); ?>
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

			if(isset($a->id))
		  	echo CHtml::link($asiakas,'/index.php/asiakkaat/update?id='.$a->id,array('target'=>'_blank'));
		}
	  }

	  ?>
	</td>

	<td>
	  <?php if(!$toteutuneet) : ?> 
	  <span class="link fa fa-pencil-square-o openkohde" id="<?php echo 'kohttisID_'.$data->id; ?>" for="<?php echo 'kohtval_'.$data->id; ?>" data-toggle="collapse" data-target="<?php echo '#kshow_'.$data->id; ?>"></span>&nbsp;

	  <span id="vaihto_<?php echo 'kohttisID_'.$data->id; ?>">
	  <?php endif; ?> 

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

	<td><?php echo $data->viesti; ?></td>

	<td>
	  <?php if(!$toteutuneet) : ?> 
	  <span class="link fa fa-pencil-square-o nowrap" data-toggle="collapse" id="<?php echo 'altxt_'.$data->id; ?>" data-target="<?php echo '#alshow_'.$data->id; ?>"> <?php echo $at[$data->id]; ?></span>
	  <?php else : ?> 
	  <?php echo $at[$data->id]; ?>
	  <?php endif; ?> 

	  <div style="position:absolute;z-index: 2;margin-left:-100px" class="collapse" id="<?php echo 'alshow_'.$data->id; ?>">
	    <div class="well form-inline">
	     <?php echo '<input type="text" class="form-control form-group" request="aloitan" status="'.$data->status.'" id="al_'.$data->id.'" value="'.$apvm[$data->id].' '.$at[$data->id].'">'; ?>

		<div class="form-group input-group-btn">
		     <button class="btn btn-success pvmupdate" for="al_<?php echo $data->id; ?>" >
			<?php echo Yii::t('main', 'ok'); ?>
		     </button>
		</div>

	    </div>
	  </div>
	</td>

	<td>

	  <?php if(!$toteutuneet) : ?> 
	  <span class="link fa fa-pencil-square-o nowrap" data-toggle="collapse" id="<?php echo 'lptxt_'.$data->id; ?>" data-target="<?php echo '#ltshow_'.$data->id; ?>"> 
	  <?php endif; ?> 

	  <?php 
	  if(empty($lt[$data->id])) 
		echo ' <b class="text-success glyphicon glyphicon-plus"></b>'; 
	  else
	 	echo $lt[$data->id]; 
	  ?></span>


	  <div style="position:absolute;z-index: 2;margin-left:-100px" class="collapse" id="<?php echo 'ltshow_'.$data->id; ?>">
	    <div class="well form-inline">
	     <?php echo '<input type="text" class="form-control form-group" request="loppui" status="'.$data->status.'" id="lt_'.$data->id.'" value="'.$lpvm[$data->id].' '.$lt[$data->id].'">'; ?>

		<div class="form-group input-group-btn">
		     <button class="btn btn-success pvmupdate" for="lt_<?php echo $data->id; ?>" >
			<?php echo Yii::t('main', 'ok'); ?>
		     </button>
		</div>

	    </div>
	  </div>
	</td>

	<td><span id="kesto_<?php echo $data->id; ?>"><?php echo $this->sprint($kesto[$data->id]); ?></span></td>
	<td><center>
	    <input type="checkbox" class="chckbxHyvaksynta" id="laskutettu_<?php echo $data->id; ?>" <?php echo $laskutettu; ?> tot="<?php echo $toteutuneet; ?>"> 
	    </center>
	</td>

</tr>
*/
	
	




