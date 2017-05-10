<?php

?>



<?php $form=$this->beginWidget('CActiveForm', array( 
    'id'=>'digisten-hinnasto-lomake-form', 
    // Please note: When you enable ajax validation, make sure the corresponding 
    // controller action is handling ajax validation correctly. 
    // See class documentation of CActiveForm for details on this, 
    // you need to use the performAjaxValidation()-method described there. 
    'enableAjaxValidation'=>false, 
)); ?>

    <?php echo $form->errorSummary($model); ?>

<div class="row">
 <div class="col-sm-3">

    <div class="section fill mb5"> 
        <?php echo $form->labelEx($model,'snapshot_pvm'); ?>
        <?php echo $form->numberField($model,'snapshot_pvm', array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'snapshot_pvm'); ?>
    </div> 

 </div><div class="col-sm-3">

    <div class="section fill mb5"> 
        <?php echo $form->labelEx($model,'jarjestelmanvalvoja'); ?>
        <?php echo $form->numberField($model,'jarjestelmanvalvoja', array('class'=>'form-control', 'step' => 'any')); ?>
        <?php echo $form->error($model,'jarjestelmanvalvoja'); ?>
    </div> 

 </div>
</div>

<hr>

<div class="row">
 <div class="col-sm-3">

    <div class="section fill mb5"> 
        <?php echo $form->labelEx($model,'etyo_1000'); ?>
        <?php echo $form->numberField($model,'etyo_1000', array('class'=>'form-control', 'step' => 'any')); ?>
        <?php echo $form->error($model,'etyo_1000'); ?>
    </div> 

    <div class="section fill mb5"> 
        <?php echo $form->labelEx($model,'etyo_1000_2000'); ?>
        <?php echo $form->numberField($model,'etyo_1000_2000', array('class'=>'form-control', 'step' => 'any')); ?>
        <?php echo $form->error($model,'etyo_1000_2000'); ?>
    </div> 

    <div class="section fill mb5"> 
        <?php echo $form->labelEx($model,'etyo_2000_3000'); ?>
        <?php echo $form->numberField($model,'etyo_2000_3000', array('class'=>'form-control', 'step' => 'any')); ?>
        <?php echo $form->error($model,'etyo_2000_3000'); ?>
    </div> 

    <div class="section fill mb5"> 
        <?php echo $form->labelEx($model,'etyo_3000_6000'); ?>
        <?php echo $form->numberField($model,'etyo_3000_6000', array('class'=>'form-control', 'step' => 'any')); ?>
        <?php echo $form->error($model,'etyo_3000_6000'); ?>
    </div> 

    <div class="section fill mb5"> 
        <?php echo $form->labelEx($model,'etyo_6000_9000'); ?>
        <?php echo $form->numberField($model,'etyo_6000_9000', array('class'=>'form-control', 'step' => 'any')); ?>
        <?php echo $form->error($model,'etyo_6000_9000'); ?>
    </div> 

    <div class="section fill mb5"> 
        <?php echo $form->labelEx($model,'etyo_9000_plus'); ?>
        <?php echo $form->numberField($model,'etyo_9000_plus', array('class'=>'form-control', 'step' => 'any')); ?>
        <?php echo $form->error($model,'etyo_9000_plus'); ?>
    </div> 

 </div><div class="col-sm-3">

    <div class="section fill mb5"> 
        <?php echo $form->labelEx($model,'elasku_1000'); ?>
        <?php echo $form->numberField($model,'elasku_1000', array('class'=>'form-control', 'step' => 'any')); ?>
        <?php echo $form->error($model,'elasku_1000'); ?>
    </div> 

    <div class="section fill mb5"> 
        <?php echo $form->labelEx($model,'elasku_1000_2000'); ?>
        <?php echo $form->numberField($model,'elasku_1000_2000', array('class'=>'form-control', 'step' => 'any')); ?>
        <?php echo $form->error($model,'elasku_1000_2000'); ?>
    </div> 

    <div class="section fill mb5"> 
        <?php echo $form->labelEx($model,'elasku_2000_3000'); ?>
        <?php echo $form->numberField($model,'elasku_2000_3000', array('class'=>'form-control', 'step' => 'any')); ?>
        <?php echo $form->error($model,'elasku_2000_3000'); ?>
    </div> 

    <div class="section fill mb5"> 
        <?php echo $form->labelEx($model,'elasku_3000_6000'); ?>
        <?php echo $form->numberField($model,'elasku_3000_6000', array('class'=>'form-control', 'step' => 'any')); ?>
        <?php echo $form->error($model,'elasku_3000_6000'); ?>
    </div> 

    <div class="section fill mb5"> 
        <?php echo $form->labelEx($model,'elasku_6000_9000'); ?>
        <?php echo $form->numberField($model,'elasku_6000_9000', array('class'=>'form-control', 'step' => 'any')); ?>
        <?php echo $form->error($model,'elasku_6000_9000'); ?>
    </div> 

    <div class="section fill mb5"> 
        <?php echo $form->labelEx($model,'elasku_9000_plus'); ?>
        <?php echo $form->numberField($model,'elasku_9000_plus', array('class'=>'form-control', 'step' => 'any')); ?>
        <?php echo $form->error($model,'elasku_9000_plus'); ?>
    </div> 

 </div><div class="col-sm-3">

    <div class="section fill mb5"> 
        <?php echo $form->labelEx($model,'eonline_1000'); ?>
        <?php echo $form->numberField($model,'eonline_1000', array('class'=>'form-control', 'step' => 'any')); ?>
        <?php echo $form->error($model,'eonline_1000'); ?>
    </div> 

    <div class="section fill mb5"> 
        <?php echo $form->labelEx($model,'eonline_1000_2000'); ?>
        <?php echo $form->numberField($model,'eonline_1000_2000', array('class'=>'form-control', 'step' => 'any')); ?>
        <?php echo $form->error($model,'eonline_1000_2000'); ?>
    </div> 

    <div class="section fill mb5"> 
        <?php echo $form->labelEx($model,'eonline_2000_3000'); ?>
        <?php echo $form->numberField($model,'eonline_2000_3000', array('class'=>'form-control', 'step' => 'any')); ?>
        <?php echo $form->error($model,'eonline_2000_3000'); ?>
    </div> 

    <div class="section fill mb5"> 
        <?php echo $form->labelEx($model,'eonline_3000_6000'); ?>
        <?php echo $form->numberField($model,'eonline_3000_6000', array('class'=>'form-control', 'step' => 'any')); ?>
        <?php echo $form->error($model,'eonline_3000_6000'); ?>
    </div> 

    <div class="section fill mb5"> 
        <?php echo $form->labelEx($model,'eonline_6000_9000'); ?>
        <?php echo $form->numberField($model,'eonline_6000_9000', array('class'=>'form-control', 'step' => 'any')); ?>
        <?php echo $form->error($model,'eonline_6000_9000'); ?>
    </div> 

    <div class="section fill mb5"> 
        <?php echo $form->labelEx($model,'eonline_9000_plus'); ?>
        <?php echo $form->numberField($model,'eonline_9000_plus', array('class'=>'form-control', 'step' => 'any')); ?>
        <?php echo $form->error($model,'eonline_9000_plus'); ?>
    </div> 

 </div><div class="col-sm-3">

    <div class="section fill mb5"> 
        <?php echo $form->labelEx($model,'edico_1000'); ?>
        <?php echo $form->numberField($model,'edico_1000', array('class'=>'form-control', 'step' => 'any')); ?>
        <?php echo $form->error($model,'edico_1000'); ?>
    </div> 

    <div class="section fill mb5"> 
        <?php echo $form->labelEx($model,'edico_1000_2000'); ?>
        <?php echo $form->numberField($model,'edico_1000_2000', array('class'=>'form-control', 'step' => 'any')); ?>
        <?php echo $form->error($model,'edico_1000_2000'); ?>
    </div> 

    <div class="section fill mb5"> 
        <?php echo $form->labelEx($model,'edico_2000_3000'); ?>
        <?php echo $form->numberField($model,'edico_2000_3000', array('class'=>'form-control', 'step' => 'any')); ?>
        <?php echo $form->error($model,'edico_2000_3000'); ?>
    </div> 

    <div class="section fill mb5"> 
        <?php echo $form->labelEx($model,'edico_3000_6000'); ?>
        <?php echo $form->numberField($model,'edico_3000_6000', array('class'=>'form-control', 'step' => 'any')); ?>
        <?php echo $form->error($model,'edico_3000_6000'); ?>
    </div> 

    <div class="section fill mb5"> 
        <?php echo $form->labelEx($model,'edico_6000_9000'); ?>
        <?php echo $form->numberField($model,'edico_6000_9000', array('class'=>'form-control', 'step' => 'any')); ?>
        <?php echo $form->error($model,'edico_6000_9000'); ?>
    </div> 

    <div class="section fill mb5"> 
        <?php echo $form->labelEx($model,'edico_9000_plus'); ?>
        <?php echo $form->numberField($model,'edico_9000_plus', array('class'=>'form-control', 'step' => 'any')); ?>
        <?php echo $form->error($model,'edico_9000_plus'); ?>
    </div> 

 </div>
</div><!-- form -->

    <br>
    <div class="buttons"> 
        <?php echo CHtml::submitButton('Tallenna', array('class'=>'btn btn-primary myBgColors')); ?>
    </div> 

<?php $this->endWidget(); ?>


