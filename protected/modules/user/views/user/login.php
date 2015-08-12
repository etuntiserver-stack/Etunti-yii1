<?php
$this->pageTitle=Yii::app()->name . ' - '.UserModule::t("Login");
$this->breadcrumbs=array(
	UserModule::t("Login"),
);
?>

<h1><?php echo UserModule::t("Kirjaudu"); ?></h1>

<?php if(Yii::app()->user->hasFlash('loginMessage')): ?>

<div class="success">
	<?php echo Yii::app()->user->getFlash('loginMessage'); ?>
</div>

<?php endif; ?>



<div class="row form">
  <div class="col-lg-3">
<?php echo CHtml::beginForm(); ?>

	
	<?php echo CHtml::errorSummary($model); ?>
	<!--
	<div class="row">
		<?php echo CHtml::activeLabelEx($model,'domain'); ?>
		<?php
		echo CHtml::activeDropDownList($model,'domain',
		array( 
		'' => 'Valitse',
		'sivex' => 'Sivex',
		'moppi' => 'Moppi',
		),array('class'=>'form-control'));
		?>
	</div>
	-->
	<div class="row">
		<?php echo CHtml::activeLabelEx($model,'domain'); ?>
		<?php echo CHtml::activeTextField($model,'domain', array('class'=>'form-control')) ?>
	</div>

	<div class="row">
		<?php echo CHtml::activeLabelEx($model,'username'); ?>
		<?php echo CHtml::activeTextField($model,'username', array('class'=>'form-control')) ?>
	</div>
	
	<div class="row">
		<?php echo CHtml::activeLabelEx($model,'password'); ?>
		<?php echo CHtml::activePasswordField($model,'password', array('class'=>'form-control')) ?>
	</div>
<!--	
	<div class="row">
		<p class="hint">
		<?php echo CHtml::link(UserModule::t("Register"),Yii::app()->getModule('user')->registrationUrl); ?> | <?php echo CHtml::link(UserModule::t("Lost Password?"),Yii::app()->getModule('user')->recoveryUrl); ?>
		</p>
	</div>
-->
	
	<div class="row rememberMe">
		<?php echo CHtml::activeCheckBox($model,'rememberMe'); ?>
		<?php echo CHtml::activeLabelEx($model,'rememberMe'); ?>
	</div>

	<div class="row submit">
		<?php echo CHtml::submitButton(UserModule::t("Login"),array('class'=>'btn btn-primary')); ?>
	</div>
	
<?php echo CHtml::endForm(); ?>
  </div>
</div><!-- form -->


<?php
$form = new CForm(array(
    'elements'=>array(
        'username'=>array(
            'type'=>'text',
            'maxlength'=>32,
        ),
        'password'=>array(
            'type'=>'password',
            'maxlength'=>32,
        ),
        'rememberMe'=>array(
            'type'=>'checkbox',
        )
    ),

    'buttons'=>array(
        'login'=>array(
            'type'=>'submit',
            'label'=>'Login',
        ),
    ),
), $model);
?>
