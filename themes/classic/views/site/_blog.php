<?php
/* @var $this BlogController */
/* @var $data Blog */
//'style'=>'width: 200px'

	$criteria=new CDbCriteria;
	$criteria->order=" id DESC ";
	$criteria->condition=" blog_id='".$data->id."' ";
	$blogComments = BlogComments::model()->findAll($criteria);
?>

<div class="row small">
 <div class="col-md-12">
 <p>



     <div class="">

	<h1 class="title-subtitle text-left">
	<span><?php echo CHtml::link($data->otsikko, Yii::app()->request->baseUrl.'/index.php/site/ajankohtaista?blog='.$data->id); ?></span>
	</h1>


	<div class="row">
	 <div class="col-sm-2">
	  <?php echo CHtml::encode(date("d.m.Y", strtotime($data->time))); ?><br>
	  <?php echo CHtml::link(CHtml::image(
		Yii::app()->request->baseUrl.'/tiedostot/etusivu/'.$data->kuva,"kuva",array('class'=>'img-thumbnail'))
		, Yii::app()->request->baseUrl.'/index.php/site/ajankohtaista?blog='.$data->id); ?>
	 </div>

	 <div class="col-sm-8 col-sm-offset-1">
	  <span><?php 

 	  $strlen = strlen($data->teksti);
	   if($strlen > 400 and !isset($_GET['blog']))
	    $t = substr($data->teksti,0,400).'... <br>
		<div class="row">
		  <div class="pull-right">
		'.CHtml::link(Yii::t('main', 'lue lisää'), Yii::app()->request->baseUrl.'/index.php/site/ajankohtaista?blog='.$data->id).'
		  </div>
		</div>'
		;
	   else
	    $t = $data->teksti;
		echo str_replace("\n", "<br>", $t); 

		?></span>
	  <p><b><?php echo CHtml::encode($data->luoja); ?></b></p>
	<br>
	<?php
	if(isset($blogComments[0]))
	{
		echo '<hr>';
		foreach($blogComments as $d)
		{
			echo '
			<div class="row">
			  <div class="col-sm-3">
				'.date("d.m.Y", strtotime($d->time)).'<br>
				'.date("H:i", strtotime($d->time)).'
			  </div><div class="col-sm-9">
				<label>'.$d->nimimerkki.':</label><br>
				'.str_replace("\n", "<br>", $d->teksti).'
			  </div>
			</div><hr>
			';
		}
	}
	?>
	<br>


	<?php
	   	if(isset($_GET['blog']))
		{
			echo '
			<p>
			<label>'.Yii::t('main', 'VASTAA').'</label>
			<div id="'.$data->id.'">
			<div class="row">
			 <div class="col-sm-4">
			  <input type="text" class="form-control nimimerkki" rows="4" placeholder="'.Yii::t('main', 'Nimimerkki').'">
			 </div>
			</div>
			<br>
			<textarea class="form-control teksti" rows="4" placeholder="'.Yii::t('main', 'Kirjoita kommento tähään').'"></textarea>
			<br>
			  <button class="btn btn-primary lahetaKommento" for="'.$data->id.'">'.Yii::t('main', 'Lähetä').'</button>
			</p>
			</div>
			';
		}
	?>
	 </div>
	</div>


    </div>



 </p>
 </div>
</div>
<hr>

