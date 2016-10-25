<?php $this->renderPartial('/site/header'); ?>



<ul class="steps expanded even-4" id="stepsScroll">
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
                       

<?php if(!isset($_GET['blog'])) : ?>

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

		$dataProvider->pagination->pageSize = 20;

		$this->widget('zii.widgets.CListView', array(
			'dataProvider'=>$dataProvider,
			'itemView'=>'_uutiset',
		));
?>

			<hr>
<?php endif; ?>


                        <h1 class="title-subtitle text-left">
			<?php echo Yii::t('main', 'Blogi'); ?>
                        </h1>

<?php 

       		$criteria = new CDbCriteria();
	        $criteria->order = " id DESC ";

		if(isset($_GET['blog']))
	        $criteria->addCondition (" id='".$_GET['blog']."' ");

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


<script type="text/javascript">
$(document).ready(function(){



    $("html, body").delay(2000).animate({
        scrollTop: 700
    }, 2000);


$(document).delegate(".lahetaKommento","click",function(){
   	var blog_id = $(this).attr('for');
   	var nimimerkki = $('#'+blog_id).find('.nimimerkki').val();
   	var teksti = $('#'+blog_id).find('.teksti').val();
	if(teksti === '')
	{
		$('#'+blog_id).find('.teksti').css({"border" : "1px red solid"}).focus();
		return false;
	}



        $.ajax({
           url: 'uusi_kommento',
           type: "POST",
           data: { "blog_id" : blog_id, "nimimerkki" : nimimerkki, "teksti" : teksti },
           success: function(data){
		console.log(data);

		if(data === 'ok')
		window.location.reload();

           }
        });


});


});
</script>
