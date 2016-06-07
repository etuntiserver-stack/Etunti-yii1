<?php

class LoginController extends Controller
{
	public $defaultAction = 'login';

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		if (Yii::app()->user->isGuest) {
			$model=new UserLogin;
			// collect user input data
			if(
				isset($_POST['UserLogin']) 
				and $_POST['UserLogin']['domain'] != 'superadmin'
				and $_POST['UserLogin']['domain'] != 'etusivu'
			)
			{

			$mod=Administrators::model()->find(" adm_login = '".$_POST['UserLogin']['username']."' and adm_salasana = '".md5($_POST['UserLogin']['password'])."' ");

			  if(isset($mod->id))
			  {

			    Yii::app()->user->setState('id', $mod->id);
			    Yii::app()->user->setState('adminID', $mod->id);
			    Yii::app()->user->setState('adminStatus', $mod->status);
			    Yii::app()->user->setState('username', $mod->adm_login);
			    Yii::app()->user->setState('nimi', $mod->adm_nimi);
			    Yii::app()->user->setState('domain', $_POST['UserLogin']['domain']);



			    $domainit=Domainit::model()->find(" domain = '".$_POST['UserLogin']['domain']."' ");
			    if(isset($domainit->paketti))
			    Yii::app()->user->setState('adminPaketti', $domainit->paketti);

			    $this->redirect(Yii::app()->request->baseUrl.'/index.php/site/etusivu');
			  } else {
			    $this->redirect(Yii::app()->request->baseUrl.'/index.php/user/login');
			  }
			exit;
			}

			if(
				isset($_POST['UserLogin']) 
				and ($_POST['UserLogin']['domain'] == 'superadmin' or $_POST['UserLogin']['domain'] == 'etusivu')
			)
			{

				$model->attributes=$_POST['UserLogin'];
				// validate user input and redirect to previous page if valid
				if($model->validate()) {
				Yii::app()->user->setState($_POST['UserLogin']['domain'], true);
					$this->lastViset();
					if (Yii::app()->user->returnUrl=='/index.php')
						$this->redirect(Yii::app()->request->baseUrl.'/index.php/user/profile');
					else
						$this->redirect(Yii::app()->request->baseUrl.'/index.php/user/profile'); 
				}
			}

			// display the login form
			$this->render('/user/login',array('model'=>$model));
		} else
			$this->redirect(Yii::app()->controller->module->returnUrl);
	}
	
	private function lastViset() {
		$lastVisit = User::model()->notsafe()->findByPk(Yii::app()->user->id);
		$lastVisit->lastvisit = time();
		$lastVisit->save();
	}

}
