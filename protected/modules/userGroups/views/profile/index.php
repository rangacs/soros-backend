<div class="view">
	<b>Hobbies: </b>
	<?php

	echo $model->hobbies;
	echo '<br/>';
	if (!file_exists('avatars/'.Yii::app()->user->name.'.jpg'))
		echo CHtml::image('avatars/noimg.jpg');
	else
		echo CHtml::image('avatars/'.Yii::app()->user->name.'.jpg');
	?>
</div>