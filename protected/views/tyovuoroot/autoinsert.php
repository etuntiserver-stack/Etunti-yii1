<?php

    $tt = '';
    $model=new Tyontekijat;
    $list = CHtml::listData(Tyontekijat::model()->findAll(array('order' => 'tekijan_nimi')), 'id', 'tekijan_nimi');
    $tt .= '<select name="tekija" id="nimi" class="form-control">';
    $tt .= '<option>'.Yii::t('main', 'Valitse työntekijä').'</option>';
    foreach($list as $key=>$val){
    $tt .= '<option value="'.$key.'">'.$val.'</option>';
    }
    $tt .= '</select>';


    $k = '';
    $model=new Kohteet;
    $list = CHtml::listData(Kohteet::model()->findAll(array('order' => 'osoite')), 'id', 'osoite');
    $k .= '<select name="tekija" id="nimi" class="form-control">';
    $k .= '<option>'.Yii::t('main', 'Valitse kohde').'</option>';
    foreach($list as $key=>$val){
    $k .= '<option value="'.$key.'">'.$val.'</option>';
    }
    $k .= '</select>';
?>

<div class="modal-dialog">
    <div class="modal-content">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	<h2 class="modal-title"><?php echo Yii::t('main', 'Työvuorojen auto laittaminen'); ?></h2>
	</div>

<div class="modal-body">
<div class="dialogTable clearfix modal-osio">


<div class="row">
  <div class="col-sm-6"><?php echo $tt; ?></div>
  <div class="col-sm-6"><?php echo $k; ?></div>
</div>

</div>
</div> <!-- end modal-body -->



















