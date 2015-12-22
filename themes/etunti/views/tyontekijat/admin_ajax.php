<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'tyontekijat-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,


        'pagerCssClass' => 'dataTables_paginate paging_bootstrap',
        'itemsCssClass' => 'table table-hover',


	'columns'=>array(
		'id',
		'imei',
		'tekijan_nimi',
		'tekijan_puh',
		'tekijan_email',
		'tyoryhma',

array(

        'value' => '
	   CHtml::link("", Yii::app()->createUrl("tyontekijat/update",array("id"=>$data->id)),array("class"=>"fa fa-pencil-square-o"))
	',
        'type'  => 'raw',
	//'visible'=>Yii::app()->user->avetak,
    ),

	),
)); ?>
