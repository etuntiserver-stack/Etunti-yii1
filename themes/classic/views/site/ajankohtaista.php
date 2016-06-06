<?php $this->renderPartial('/site/header'); ?>


<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/etusivu_2.css">
<ul class="steps expanded even-4">
    <li class="disabled"><?php echo CHtml::link('Etusivu', Yii::app()->request->baseUrl.'/index.php/site/index'); ?></li>
    <li class="active"><?php echo CHtml::link('Ajankohtaista', Yii::app()->request->baseUrl.'/index.php/site/ajankohtaista'); ?></li>
    <li class="disabled"><?php echo CHtml::link('Asiakkaat', Yii::app()->request->baseUrl.'/index.php/site/asiakkaat'); ?></li>
    <li class="disabled"><?php echo CHtml::link('Yritys', Yii::app()->request->baseUrl.'/index.php/site/yritys'); ?></li>
    <li class="disabled"><?php echo CHtml::link('Yhteystiedot', Yii::app()->request->baseUrl.'/index.php/site/yhteystiedot'); ?></li>
</ul>



        <!-- Services -->
        <section class="esittely">
            <div class="paddings">
                <div class="container">
                    <!-- Icon Big -->
                    <!-- End Icon Big -->
                        <h1 class="title-subtitle text-center">Ajankohtaista
                            <span>
                              Sivu on suunnitelmissa.
                            </span>
                        </h1>
                        <hr>
                    <!-- End Titles Heading -->
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2 lead">
                        
<div class="text-center">
<p>
Sivu on suunnitelmissa.
</p>
</div>
                        <hr>

                        </div>
                    </div>
                </div>
                <!-- End Container-->
            </div>
        </section>        <!-- Services -->


<?php $this->renderPartial('/site/footer'); ?>
