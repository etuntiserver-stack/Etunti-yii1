<?php

	$bd = '<table class="table mbn tc-med-1 tc-bold-last">
                    <thead>
                      <tr class="hidden">
                        <th>#</th>
                        <th>First Name</th>
                      </tr>
                    </thead>
                    <tbody>';
       		    $criteria = new CDbCriteria();
       		    $criteria->select = " id,aloitan,loppui,kohde_kannasta  ";
       		    $criteria->order = " id DESC  ";
       		    $criteria->group = "kohde_kannasta";
       		    $criteria->condition = "status=1";
		    $m = Mobile::model()->findAll($criteria);
		    if(isset($m[0]))
		    {
			foreach($m as $data)
			{

 			  $data->loppui = date("d.m.Y H:i",time());
			  $data->aloitan = date("d.m.Y H:i",strtotime($data->aloitan));
			  $kesto =  strtotime($data->loppui) - strtotime($data->aloitan);

			  $bd .= '
                      <tr>
                        <td>
                          <span class=""></span> '.CHtml::link($data->kohde_kannasta, array('/mobile/update', 'id' => $data->id)).'</td>
                        <td>'.$this->sprint($kesto).'</td>
                      </tr>
			  ';
			}
	
		    }
	$bd .= '</tbody>
        </table>';
	echo json_encode($bd);
?>
