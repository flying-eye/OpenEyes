<div class="box admin">
	<h2>Profile</h2>
	<ul class="navigation admin">
		<?php
		$links = array();
		if (Yii::app()->params['profile_user_can_edit']) {
			$links['Basic information'] = '/profile/info';
		}
		if (Yii::app()->params['profile_user_can_change_password']) {
			$links['Change password'] = '/profile/password';
		}
		$links['Sites'] = '/profile/sites';
		$links['Firms'] = '/profile/firms';

		/** @var User $user */
		$user = User::model()->findByPk(Yii::app()->user->id);

		if (Yii::app()->params['allow_eyelogbook_integration'] && $user->is_doctor) {
			$links['EyeLogbook account'] = '/profile/eyelogbook';
		}
		foreach ($links as $title => $uri) {?>
			<li<?php if (Yii::app()->getController()->action->id == preg_replace('/^\/admin\//','',$uri)) {?> class="active"<?php }?>>
				<?php if (Yii::app()->getController()->action->id == preg_replace('/^\/admin\//','',$uri)) {?>
					<span class="viewing"><?php echo $title?></span>
				<?php } else {?>
					<?php echo CHtml::link($title,array($uri))?>
				<?php }?>
			</li>
		<?php }
		?>
	</ul>
</div>
