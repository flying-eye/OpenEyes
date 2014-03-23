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

/**
 * This is the model class for table "eyelogbook_account".
 *
 * The followings are the available columns in table 'eyelogbook_account':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property integer $user_id
 *
 * The followings are the available model relations:
 * @property User $user
 */

class EyelogbookAccount extends BaseActiveRecord
{
	/**
	 * @var string this key is used to add a little extra security for when transferring password to eyelogbook
	 */
	private static $eyelogbook_password_hash_key = 'Sr7E52Pl';

	/**
	 * Returns the static model of the specified AR class.
	 * @return EventIssue the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_eyelogbook_account';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, password', 'required', 'message' => 'cannot be blank'),
			array('username', 'unique', 'message' => 'already linked to another account'),
			array('username', 'match', 'pattern' => '/^[a-z0-9\._@]$/i', 'message' => 'only numbers, letters and some symbols (. _ @) are allowed'),
			array('username, password', 'filter', 'filter' => 'strip_tags'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'user_id')
		);
	}

	public function beforeSave()
	{
		$this->user_id = Yii::app()->user->id;

		if (!empty($this->password)) {
			$this->password = md5($this->password . $this->username . self::$eyelogbook_password_hash_key);
		}

		return parent::beforeSave();
	}
}