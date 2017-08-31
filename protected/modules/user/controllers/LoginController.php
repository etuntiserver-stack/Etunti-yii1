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
				Yii::app()->request->getPost('UserLogin')
				and trim(Yii::app()->request->getPost('UserLogin')['domain']) != 'superadmin'
				and trim(Yii::app()->request->getPost('UserLogin')['domain']) != 'etusivu'
				and trim(Yii::app()->request->getPost('UserLogin')['domain']) != ''
			)
			{

			$domain = trim(Yii::app()->request->getPost('UserLogin')['domain']);

	       		$criteria = new CDbCriteria();
		        $criteria->condition = " 
				adm_login='".Yii::app()->request->getPost('UserLogin')['username']."' 
				AND token=''
				AND adm_salasana!=''
			";
			$mod=Administrators::model()->find($criteria);



			  if(isset($mod->id))
			  {

				// <-- Check password
				$login = false;

				if( strlen($mod->adm_salasana) < 60 ){

		        		if(md5(Yii::app()->request->getPost('UserLogin')['password']) == $mod->adm_salasana)
					$login = true;

				} elseif( strlen($mod->adm_salasana) == 60 ){

					if (password_verify(Yii::app()->request->getPost('UserLogin')['password'], $mod->adm_salasana))
					$login = true;
				}
				
				if(!$login)
				{
					Yii::app()->user->setFlash('danger', "Tarkasta yritys- ja käyttäjätunnus sekä salasana");
					$this->redirect(Yii::app()->request->baseUrl.'/index.php/site/index');
					exit;
				}
				//     Check password -->


			    Yii::app()->user->setState('id', $mod->id);
			    Yii::app()->user->setState('adminID', $mod->id);
			    Yii::app()->user->setState('adminStatus', $mod->status);
			    Yii::app()->user->setState('username', $mod->adm_login);
			    Yii::app()->user->setState('nimi', $mod->adm_nimi);
			    Yii::app()->user->setState('domain', $domain);



			    $domainit=Domainit::model()->find(" domain = '".$domain."' ");
			    if(isset($domainit->paketti))
			    Yii::app()->user->setState('adminPaketti', $domainit->paketti);

			    $this->redirect(Yii::app()->request->baseUrl.'/index.php/site/etusivu');
			  } else {
			    Yii::app()->user->setFlash('danger', "Tarkasta yritys- ja käyttäjätunnus sekä salasana");
			    $this->redirect(Yii::app()->request->baseUrl.'/index.php/site/index');
			  }
			exit;
			}

			if(
				isset($_POST['UserLogin']) 
				and (trim($_POST['UserLogin']['domain']) == 'superadmin' or trim($_POST['UserLogin']['domain']) == 'etusivu')
			)
			{

				$model->attributes=$_POST['UserLogin'];
				// validate user input and redirect to previous page if valid
				if($model->validate()) {
				Yii::app()->user->setState(trim($_POST['UserLogin']['domain']), true);
					$this->lastViset();
					if (Yii::app()->user->returnUrl=='/index.php')
						$this->redirect(Yii::app()->request->baseUrl.'/index.php/user/profile');
					else
						$this->redirect(Yii::app()->request->baseUrl.'/index.php/user/profile'); 
				}
			}

			// display the login form
			//$this->render('/user/login',array('model'=>$model));

			    Yii::app()->user->setFlash('danger', "Tarkasta yritys- ja käyttäjätunnus sekä salasana");
			    $this->redirect(Yii::app()->request->baseUrl.'/index.php/site/index');

		} else
			$this->redirect(Yii::app()->controller->module->returnUrl);
	}
	
	private function lastViset() {
		$lastVisit = User::model()->notsafe()->findByPk(Yii::app()->user->id);
		$lastVisit->lastvisit = time();
		$lastVisit->save();
	}

}
