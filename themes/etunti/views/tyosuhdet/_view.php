<?php
/* @var $this TyosuhdetController */
/* @var $data Tyosuhdet */

$tekijan_nimi = '';
$tt = Tyontekijat::model()->find(" id='".$data->tid."' ");
if(isset($tt['tekijan_nimi']))
 $tekijan_nimi = $tt['tekijan_nimi']
?>


        <div class="col-md-3">
            <div class="panel">
                <div class="panel-heading myBgColors">
                    <h4 class="text-center"><?php echo CHtml::encode($tekijan_nimi); ?></h4>
                </div>
                <div class="panel-body text-center">
                    <p class="lead">
                        <strong></strong>
                    </p>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <?php echo CHtml::encode($data->getAttributeLabel('alku')); ?>
                        <strong class="pull-right"><?php echo CHtml::encode($data->alku); ?></strong>
                    </li>
                    <li class="list-group-item">
                        <?php echo CHtml::encode($data->getAttributeLabel('loppu')); ?>
                        <strong class="pull-right"><?php echo CHtml::encode($data->loppu); ?></strong>
                    </li>
                    <li class="list-group-item">
                        <?php echo CHtml::encode($data->getAttributeLabel('vktyoaika')); ?>
                        <strong class="pull-right"><?php echo CHtml::encode($data->vktyoaika); ?></strong>
                    </li>
                    <li class="list-group-item">
                        <?php echo CHtml::encode($data->getAttributeLabel('tuntihinta')); ?>
                        <strong class="pull-right"><?php echo CHtml::encode($data->tuntihinta); ?></strong>
                    </li>
                    <li class="list-group-item">
                        <?php echo CHtml::encode($data->getAttributeLabel('palkkausmuoto')); ?>
                        <strong class="pull-right"><?php echo CHtml::encode($data->palkkausmuoto); ?></strong>
                    </li>

                </ul>
                <div class="panel-footer">
                    <?php echo CHtml::link(Yii::t('main', 'Muokkaa'), array('tyontekijat/update', 'id'=>$tt['id']),array('class'=>'btn btn-block myBgColors')); ?>
                </div>
            </div>
        </div>




	<?php /*

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tid')); ?>:</b>
	<?php echo CHtml::encode($data->tid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('alku')); ?>:</b>
	<?php echo CHtml::encode($data->alku); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('loppu')); ?>:</b>
	<?php echo CHtml::encode($data->loppu); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('vktyoaika')); ?>:</b>
	<?php echo CHtml::encode($data->vktyoaika); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nimike')); ?>:</b>
	<?php echo CHtml::encode($data->nimike); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('palkkausmuoto')); ?>:</b>
	<?php echo CHtml::encode($data->palkkausmuoto); ?>
	<br />
	<b><?php echo CHtml::encode($data->getAttributeLabel('tuntihinta')); ?>:</b>
	<?php echo CHtml::encode($data->tuntihinta); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('matka_thinta')); ?>:</b>
	<?php echo CHtml::encode($data->matka_thinta); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('lippu_kuumaks')); ?>:</b>
	<?php echo CHtml::encode($data->lippu_kuumaks); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('koe_loppu')); ?>:</b>
	<?php echo CHtml::encode($data->koe_loppu); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('koe_hinta')); ?>:</b>
	<?php echo CHtml::encode($data->koe_hinta); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tuloraja_ajalle')); ?>:</b>
	<?php echo CHtml::encode($data->tuloraja_ajalle); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('perusprosentti')); ?>:</b>
	<?php echo CHtml::encode($data->perusprosentti); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('lisaprosentti')); ?>:</b>
	<?php echo CHtml::encode($data->lisaprosentti); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('kuukaudessa')); ?>:</b>
	<?php echo CHtml::encode($data->kuukaudessa); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('kahdessa_viikossa')); ?>:</b>
	<?php echo CHtml::encode($data->kahdessa_viikossa); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('viikossa')); ?>:</b>
	<?php echo CHtml::encode($data->viikossa); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('paivassa')); ?>:</b>
	<?php echo CHtml::encode($data->paivassa); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('atk_varten')); ?>:</b>
	<?php echo CHtml::encode($data->atk_varten); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('yksi_tuloraja')); ?>:</b>
	<?php echo CHtml::encode($data->yksi_tuloraja); ?>
	<br />

</div>
	*/ ?>


