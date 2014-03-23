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
<?php $form = $this->beginWidget('BaseEventTypeCActiveForm', array(
	'id' => 'profile-form',
	'enableAjaxValidation' => false,
	'layoutColumns' => array(
		'label' => 2,
		'field' => 5
	)
))?>

<?php if (!Yii::app()->params['profile_user_can_edit']) { ?>
	<div class="alert-box alert">
		User editing of basic information is administratively disabled.
	</div>
<?php } ?>

<?php $this->renderPartial('//base/_messages') ?>
<?php $this->renderPartial('//elements/form_errors', array('errors' => $errors)) ?>

<?php echo $form->textField($eyelogbook_account, 'username', array('readonly' => !Yii::app()->params['profile_user_can_edit'], 'autocomplete' => 'off', 'class' => 'eyelogbook_input')); ?>
<?php echo $form->passwordField($eyelogbook_account, 'password', array('readonly' => !Yii::app()->params['profile_user_can_edit'], 'class' => 'eyelogbook_input')) ?>

<?php if (Yii::app()->params['profile_user_can_edit']) { ?>

	<div class="row field-row">
		<div class="large-5 large-offset-2 column">
			<?php echo EventAction::button('Save', 'save')->toHtml(); ?>
			<button id="test_button" class="secondary inactive" type="button">Test</button>
			<img class="loader" src="<?php echo Yii::app()->assetManager->createUrl('img/ajax-loader.gif') ?>" alt="loading..." style="display: none;" />
		</div>
	</div>
	<div class="row field-row">
		<div class="large-5 large-offset-2 column">
			<div id="eyelogbook_test_alertbox" class="alert-box" style="display: none;"></div>
		</div>
	</div>
<?php } ?>

<?php $this->endWidget() ?>

<?php
Yii::app()->clientScript->registerScript('empty_password_on_focus',
	"$('#EyelogbookAccount_password').focus(function(){
		$(this).val('');
	});", CClientScript::POS_READY);

Yii::app()->clientScript->registerScript('enable_test_button',
	"var button = $('#test_button');

	$(button).attr('disabled','disabled');

	$('.eyelogbook_input').on('keyup blur change', function(){
		if ($('#EyelogbookAccount_username').val().length == 0 || $('#EyelogbookAccount_password').val().length == 0) {
			$(button).addClass('inactive');
			$(button).attr('disabled','disabled');
		} else {
			$(button).removeClass('inactive');
			$(button).removeAttr('disabled');
		};
	});", CClientScript::POS_READY);

$url = CJavaScript::encode(Yii::app()->createUrl('eyelogbook/testUserCredentials'));
$csrfTokenName = Yii::app()->request->csrfTokenName;
$csrfToken = Yii::app()->request->csrfToken;
$postData = CJavaScript::encode(
					   array(
						   'username' => 'testing',
						   'password' => 'testing',
						   $csrfTokenName => $csrfToken
					   ));

Yii::app()->clientScript->registerScript('click_test_button',
	"$('#test_button').click(function(){

		var timeout = 2000;
		var button = $('#test_button');
		var alert_box = $('#eyelogbook_test_alertbox');

		function requestFinished(success,message){

			message = message.replace(/\"/g,'');

			$(alert_box).text(message);

			var box_class = (success) ? 'info' : 'warning';

			$(alert_box).removeClass('info warning');
			$(alert_box).addClass(box_class);
			$(alert_box).show();

			if (success) $(button).attr('disabled','disabled');

			setTimeout(function() {
				$(alert_box).fadeOut('fast', function(){
					if (success) $(button).addClass('inactive');
				});
			}, timeout);
		}

		$.ajax({
			url: {$url},
			type: 'POST',
			data: {
				username: $('#EyelogbookAccount_username').val(),
				password: $('#EyelogbookAccount_password').val(),
				{$csrfTokenName}: '{$csrfToken}'
			},
			dataType: 'json',
			beforeSend: function(){
				$('#test_button').hide();
				$('.loader').show();
			}
		}).done(function(data){
			if (data[0] == 200) requestFinished(true,data[1]);
			else requestFinished(false,data[1]);
		}).fail(function(){
			requestFinished(false,'There was a problem. Try again later.');
		}).always(function(){
			$('.loader').hide();
			$('#test_button').show();
		});
	});", CClientScript::POS_READY);
?>