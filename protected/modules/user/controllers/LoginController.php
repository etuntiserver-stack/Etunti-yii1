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
			if(isset($_POST['UserLogin']) and $_POST['UserLogin']['domain'] != 'superadmin')
			{

			$mod=Administrators::model()->find(" adm_login = '".$_POST['UserLogin']['username']."' and adm_salasana = '".md5($_POST['UserLogin']['password'])."' ");

			  if(isset($mod->id))
			  {

			$model->attributes=$_POST['UserLogin'];
			$model->username='demo';
			$model->password='111111';
			$model->validate();


			    Yii::app()->user->setState('id', $mod->id);
			    Yii::app()->user->setState('adminID', $mod->id);
			    Yii::app()->user->setState('username', $mod->adm_login);


			    $domainit=Domainit::model()->find(" domain = '".$_POST['UserLogin']['domain']."' ");
			    if(isset($domainit->paketti))
			    Yii::app()->user->setState('adminPaketti', $domainit->id);

			    $this->redirect("/index.php/sivexkuitti/index");
			  } else {
			    $this->render('/index.php/user/login',array('model'=>$model));
			  }
			exit;
			}

			if(isset($_POST['UserLogin']) and $_POST['UserLogin']['domain'] == 'superadmin') {

				$model->attributes=$_POST['UserLogin'];
				// validate user input and redirect to previous page if valid
				if($model->validate()) {
				Yii::app()->user->setState('superadmin', true);
					$this->lastViset();
					if (Yii::app()->user->returnUrl=='/index.php')
						$this->redirect("/index.php/user/profile");
					else
						$this->redirect("/index.php/user/profile"); //Yii::app()->user->returnUrl
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
