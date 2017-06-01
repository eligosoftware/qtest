<?php
/* @var $this yii\web\ViewAction */
$this->title='QTest';
$message=(isset($message)?$message:"empty message");
?>
<div class="jumbotron">
    <h3>
        <?=$message;?><br/>
        <a href="<?=Yii::$app->homeUrl;?>">Home page</a>
    </h3>
</div>
