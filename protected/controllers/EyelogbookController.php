<?php
/**
 * OpenEyes
 *
 * (C) Moorfields Eye Hospital NHS Foundation Trust, 2008-2011
 * (C) OpenEyes Foundation, 2011-2013
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2008-2011, Moorfields Eye Hospital NHS Foundation Trust
 * @copyright Copyright (c) 2011-2013, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 */

class EyelogbookController extends BaseController
{
	public function accessRules()
	{
		return array(
			array('allow',
				'users' => array('@')
			),
		);
	}

	public function actionAccount()
	{
		/** @var User $user */
		$user = User::model()->findByPk(Yii::app()->user->id);
		if (!$user->is_doctor)
			$this->redirect(array('/profile/info'));

		Yii::app()->assetManager->registerCssFile('css/admin.css');
		Yii::app()->assetManager->registerScriptFile('js/profile.js');
		$this->layout = 'profile';

		$errors = array();

		$new = false;
		$eyelogbook_account = (EyelogbookAccount::model()->find('user_id=:user_id', array(':user_id'=>Yii::app()->user->id)));
		if (!$eyelogbook_account) {
			$eyelogbook_account = new EyelogbookAccount();
			$new = true;
		}

		if (isset($_POST['unlink'])) {
			if (!$eyelogbook_account->delete()) {
				$errors = $eyelogbook_account->getErrors();
			} else {
				$eyelogbook_account = new EyelogbookAccount();
				Yii::app()->user->setFlash('success', "Your EyeLogbook account has successfully been unlinked.");
			}
		} else if (!empty($_POST['EyelogbookAccount'])) {
			$eyelogbook_account->attributes = $_POST['EyelogbookAccount'];

			if (!$eyelogbook_account->save()) {
				$errors = $eyelogbook_account->getErrors();
			} else {
				$descriptor = ($new) ? 'created' : 'updated';
				Yii::app()->user->setFlash('success', "Your EyeLogbook account link has been {$descriptor}.");
			}
		}

		$this->render('view',array(
			'eyelogbook_account' => $eyelogbook_account,
			'errors' => $errors,
		));
	}

	/**
	 */
	public function actionTestUserCredentials()
	{
		Yii::import('application.vendors.*');
		require_once('Zend/Http/Client.php');

		$client = new Zend_Http_Client();

		$client->setUri('https://www.eyelogbook.co.uk/mobileAPI/openeyes/testUserCredentials.php');
		$client->setConfig(array(
			'timeout' => 5
		));

		$client->setParameterPost('username', $_POST['username']);
		$client->setParameterPost('password', $_POST['password']);

		$response = $client->request('POST');

		echo CJSON::encode(array($response->getStatus(), $response->getBody()));
	}
}