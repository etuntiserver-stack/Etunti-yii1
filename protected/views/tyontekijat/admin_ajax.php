<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'tyontekijat-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,

                    'pagerCssClass' => 'dataTables_paginate paging_bootstrap',
                    'itemsCssClass' => 'table table-striped table-bordered table-hover',

	'columns'=>array(
		'id',
		'imei',
		//'laiten_puh',
		'tekijan_nimi',
		//'tekijan_henkilotunnus',
		'tekijan_puh',
		'tekijan_email',
		'tyoryhma',
		//'aktiivinen',
		/*

		'tekijan_lanka_puh',
		'tekijan_katuosoite',
		'tekijan_pnumero',
		'tekijan_ptoimipaikka',

		'tyoehtosopimus',
		'tekijan_kulunvalvonta',
		'tekijan_pankkitili',
		'tekijan_konttori',

		'tekijan_tietoja',
		'tekijan_muisti',
		'salasana',
		'online_varauksen_valmina',
		'kortit',
		'ayjasenyys',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>



