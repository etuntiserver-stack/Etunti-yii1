<?php
/* @var $this ValikkootController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Valikkoots',
);
/*
$this->menu=array(
	array('label'=>'Create Valikkoot', 'url'=>array('create')),
	array('label'=>'Manage Valikkoot', 'url'=>array('admin')),
);
*/
?>
<legend>
<h1><?php echo Yii::t('main','ALASVETOVALIKOT'); ?></h1>
</legend>


<div id="result"></div>

<script type="text/javascript">
$(document).ready(function(){

        $.ajax({
           url: 'index_ajax',
           success: function(html){
		$('#result').html(html);
           }
        });


});
</script>
