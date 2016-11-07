<?php

/*
	$sv= Mobile::model()->findbypk($id);
	if(!empty($sv->tietoja)) 
	  $tietoja = $sv->tietoja."\n"; 
	else 
	  $tietoja = "<perus>".$sv->kohde_kannasta."//".$sv->aloitan."//".$sv->loppui."</perus>";

	$sv->tietoja=$tietoja.Yii::app()->user->nimi." (".date("d.m.Y H:i")."):\n".$tilanne.", Vanha-".$sv->kohde_kannasta.", ".$sv->aloitan.", ".$sv->loppui.". Uusi-".$uusikohde.", ".$uusialoitus.", ".$uusilopetus;
	$sv->save();

//print_r($sv);
*/

		$sivu					= '';
		if($tilanne == 'Luetut') $sivu 		= 'Luetut';
		if($tilanne == 'Toteutuneet') $sivu 	= 'Toteutuneet';

		$model	= Mobile::model()->findbypk($id);
		if(isset($model->tietoja))
		{
			$tietoja = '';
			if(!empty($model->tietoja))
			{
				$tietoja = $model->tietoja;
				$tietoja .= Yii::t('main', 'Muutos').": ".date("d.m.Y H:i")."\n";
				$tietoja .= Yii::t('main', 'Järjestelmanvalvoja').": ".Yii::app()->user->nimi."\n";
				$tietoja .= Yii::t('main', 'Sivu').": ".$sivu."\n";
				$tietoja .= "\n";
				$tietoja .= Yii::t('main', 'Työntekijä').": ".$tekijan_nimi['uusi']."\n";
				$tietoja .= Yii::t('main', 'Osoite').": ".$kohde_kannasta['uusi']."\n";
				$tietoja .= Yii::t('main', 'Aloitus').": ".date("d.m.Y H:i", strtotime($aloitan['uusi']))."\n";
				$tietoja .= Yii::t('main', 'Lopetus').": ".date("d.m.Y H:i", strtotime($loppui['uusi']));
				$tietoja .= "\n----------------\n";
			} else {

				$tietoja = Yii::t('main', 'Alkuperäinen tieto').": ".date("d.m.Y H:i", strtotime($model->time))."\n";
				$tietoja .= "\n";
				$tietoja .= Yii::t('main', 'Työntekijä').": ".$tekijan_nimi['vanha']."\n";
				$tietoja .= Yii::t('main', 'Osoite').": ".$kohde_kannasta['vanha']."\n";
				$tietoja .= Yii::t('main', 'Aloitus').": ".date("d.m.Y H:i", strtotime($aloitan['vanha']))."\n";
				$tietoja .= Yii::t('main', 'Lopetus').": ".date("d.m.Y H:i", strtotime($loppui['vanha']));
				$tietoja .= "\n----------------\n";

				$tietoja .= Yii::t('main', 'Muutos').": ".date("d.m.Y H:i")."\n";
				$tietoja .= Yii::t('main', 'Järjestelmanvalvoja').": ".Yii::app()->user->nimi."\n";
				$tietoja .= Yii::t('main', 'Sivu').": ".$sivu."\n";
				$tietoja .= "\n";
				$tietoja .= Yii::t('main', 'Työntekijä').": ".$tekijan_nimi['uusi']."\n";
				$tietoja .= Yii::t('main', 'Osoite').": ".$kohde_kannasta['uusi']."\n";
				$tietoja .= Yii::t('main', 'Aloitus').": ".date("d.m.Y H:i", strtotime($aloitan['uusi']))."\n";
				$tietoja .= Yii::t('main', 'Lopetus').": ".date("d.m.Y H:i", strtotime($loppui['uusi']));
				$tietoja .= "\n----------------\n";
			}

			Mobile::model()->updateByPk($model->id, array('tietoja'=>$tietoja));

		}

?>
