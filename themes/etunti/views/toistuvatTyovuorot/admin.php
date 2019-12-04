<?php
/* @var $this ToistuvatTyovuorotController */
/* @var $model ToistuvatTyovuorot */
?>

<h1>Toistuvat Työvuorot</h1>


<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'toistuvat-tyovuorot-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		//'id',
		//'time',
		'TyontekijanNimi',
		'OsoiteFunc',
		'pfrom',
		'pto',
		'viikkoja',
		'viikko_paivat',
                array(
                        'header'=>'Työparit',
                        'name'=>'TyopaariFunc',
			'type'=>'raw',
                ),
		/*
		'tid',
		'kohde',
		'pvm',
		'alku',
		'loppu',
		'kesto',
		'tyoajanmerkinta',
		'status',
		'tietoja',
		'tyopaari',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
