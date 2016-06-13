<?php $this->renderPartial('/site/header'); ?>


<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/etusivu_2.css">
<ul class="steps expanded even-4">
    <li class="disabled"><?php echo CHtml::link('Etusivu', Yii::app()->request->baseUrl.'/index.php/site/index'); ?></li>
    <li class="active"><?php echo CHtml::link('Ajankohtaista', Yii::app()->request->baseUrl.'/index.php/site/ajankohtaista'); ?></li>
    <li class="disabled"><?php echo CHtml::link('Asiakkaat', Yii::app()->request->baseUrl.'/index.php/site/asiakkaat'); ?></li>
    <li class="disabled"><?php echo CHtml::link('Yritys', Yii::app()->request->baseUrl.'/index.php/site/yritys'); ?></li>
    <li class="disabled"><?php echo CHtml::link('Yhteystiedot', Yii::app()->request->baseUrl.'/index.php/site/yhteystiedot'); ?></li>
</ul>

<style>
.summary, .empty{ display:none }
</style>

        <!-- Services -->
        <section class="esittely">
            <div class="paddings">
                <div class="container">

                    <div class="row">
                        <div class="col-md-12 lead">
                       

                        <h1 class="title-subtitle text-left">
			<?php echo Yii::t('main', 'Ajankohtaista'); ?>
                        </h1>
<?php 

       		$criteria = new CDbCriteria();
	        $criteria->order = " id DESC ";

		$dataProvider=new CActiveDataProvider('Uutiset', array(
			'criteria'=>$criteria,
			//'pagination'=>false
		));

		$dataProvider->pagination->pageSize = 5;

		$this->widget('zii.widgets.CListView', array(
			'dataProvider'=>$dataProvider,
			'itemView'=>'_uutiset',
		));

?>

			<hr>

                        <h1 class="title-subtitle text-left">
			<?php echo Yii::t('main', 'Blogi'); ?>
                        </h1>

<?php 

       		$criteria = new CDbCriteria();
	        $criteria->order = " id DESC ";

		$dataProvider=new CActiveDataProvider('Blog', array(
			'criteria'=>$criteria,
			//'pagination'=>false
		));

		$dataProvider->pagination->pageSize = 10;

		$this->widget('zii.widgets.CListView', array(
			'dataProvider'=>$dataProvider,
			'itemView'=>'_blog',
		));

?>



                        </div>
                    </div>



                </div>
                <!-- End Container-->
            </div>
        </section>        <!-- Services -->


<?php $this->renderPartial('/site/footer'); ?>
