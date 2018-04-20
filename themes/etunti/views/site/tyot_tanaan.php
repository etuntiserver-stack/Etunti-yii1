<?php 
	$criteria = new CDbCriteria();
	$criteria->select = "  COUNT(*) as count ";
	$criteria->condition = " 
		DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') = CURDATE()
		AND kohde!=''
		AND tyoajanmerkinta NOT LIKE '%Ei lasketa%'
	";

		// <-- Tyoryhma
		$checkOikeus = "tyoryhmat_4_".Yii::app()->user->adminStatus;
		$site = Yii::app()->createController('Site');
		if( $site[0]->checkOikeusFields($checkOikeus) == 0 ){
			$tt = Yii::app()->createController('Tyontekijat');
			$tt_arr = $tt[0]->TyoryhmatTyontekijatHelper();
			$ids = implode(",", $tt_arr);
			if( count($tt_arr) > 0 ){
				$criteria->addCondition (" tid IN ($ids) ");
			} else {
				$criteria->condition = " 1!=1 ";
			}
		}
		//     Tyoryhma -->

	$s = Tyovuoroot::model()->find($criteria);

	$criteria = new CDbCriteria();
	$criteria->select = "  COUNT(*) as count ";
	$criteria->condition = " status=1 ";

		// <-- Tyoryhma
		$checkOikeus = "tyoryhmat_4_".Yii::app()->user->adminStatus;
		$site = Yii::app()->createController('Site');
		if( $site[0]->checkOikeusFields($checkOikeus) == 0 ){
			$tt = Yii::app()->createController('Tyontekijat');
			$tt_arr = $tt[0]->TyoryhmatTyontekijatHelper();
			$ids = implode(",", $tt_arr);
			if( count($tt_arr) > 0 ){
				$criteria->addCondition (" tid IN ($ids) ");
			} else {
				$criteria->condition = " 1!=1 ";
			}
		}
		//     Tyoryhma -->

	$a = Mobile::model()->find($criteria);	

	$criteria = new CDbCriteria();
	$criteria->select = "  COUNT(*) as count ";
	$criteria->condition = " 
		DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = CURDATE() and status=3
	";

		// <-- Tyoryhma
		$checkOikeus = "tyoryhmat_4_".Yii::app()->user->adminStatus;
		$site = Yii::app()->createController('Site');
		if( $site[0]->checkOikeusFields($checkOikeus) == 0 ){
			$tt = Yii::app()->createController('Tyontekijat');
			$tt_arr = $tt[0]->TyoryhmatTyontekijatHelper();
			$ids = implode(",", $tt_arr);
			if( count($tt_arr) > 0 ){
				$criteria->addCondition (" tid IN ($ids) ");
			} else {
				$criteria->condition = " 1!=1 ";
			}
		}
		//     Tyoryhma -->

	$t = Mobile::model()->find($criteria);


	$ss = 0;
	if(isset($s->count))
	$ss = $s->count;

	$aa = 0;
	if(isset($a->count))
	$aa = $a->count;

	$tt = 0;
	if(isset($t->count))
	$tt = $t->count;

$bd = '
		<input type="hidden" id="tanaan_sun" value="'.$ss.'">
		<input type="hidden" id="tanaan_al" value="'.$aa.'">
		<input type="hidden" id="tanaan_tehdyt" value="'.$tt.'">

                      <table class="table mbn tc-med-1 tc-bold-last">
                        <thead>
                          <tr class="hidden">
                            <th>#</th>
                            <th>First Name</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>
                              <span class="fa fa-circle text-warning fs14 mr10"></span>'.Yii::t('main','Suunnitellut').'</td>
                            <td>'.$ss.'</td>
                          </tr>
                          <tr>
                            <td>
                              <span class="fa fa-circle text-info fs14 mr10"></span>'.Yii::t('main','Käynnissä').'</td>
                            <td>'.$aa.'</td>
                          </tr>
                          <tr>
                            <td>
                              <span class="fa fa-circle text-primary fs14 mr10"></span>'.Yii::t('main','Tehdyt').'</td>
                            <td>'.$tt.'</td>
                          </tr>
                        </tbody>
                      </table>
';
echo json_encode($bd);
?>
