<?php
/**
 * OpenEyes
 *
 * (C) Moorfields Eye Hospital NHS Foundation Trust, 2008-2011
 * (C) OpenEyes Foundation, 2011-2012
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2008-2011, Moorfields Eye Hospital NHS Foundation Trust
 * @copyright Copyright (c) 2011-2012, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 *
 * Available properties
 * @var EyelogbookAccount $eyelogbook_account
 *
 */
?>

<div class="box admin">
	<h2>EyeLogbook account</h2>

	<?php
	if ($eyelogbook_account->isNewRecord || !empty($_POST)) {
		$this->renderPartial('_form', array(
			'eyelogbook_account' => $eyelogbook_account,
			'errors' => $errors,
		));
	} else {
		?>
		<div class="row field-row">
			<div class="large-12 column">
				<div class="alert-box info">
					This OpenEyes account is linked to an EyeLogbook account
				</div>
			</div>
		</div>
		<div class="row field-row">
			<div class="large-2 column"><label>Username:</label></div>
			<div class="large-5 column end"><label><?php echo $eyelogbook_account->username; ?></label></div>
		</div>
		<div class="row field-row">
			<div class="large-2 column"><label>Password:</label></div>
			<div class="large-5 column end"><label><i>saved in database</i></label></div>
		</div>

		<div class="row field-row">
			<div class="large-12 column">
				<?php $form = $this->beginWidget('BaseEventTypeCActiveForm', array(
					'id' => 'profile-form',
					'enableAjaxValidation' => false,
				))?>
				<button id="test_button" type="submit" class="primary">
					Edit
				</button>
				<button id="unlink_button" type="submit" class="warning" name="unlink" value="1">
					Unlink
				</button>
				<?php $this->endWidget() ?>
			</div>
		</div>
	<?php } ?>
</div>