<?php
$result=  Yii::$app->getRequest()->get('result');
$email=  Yii::$app->getRequest()->get('email');

/* @var $this yii\web\ViewAction */
$this->title='QTest';
?>
<div class="jumbotron">
    <?php if ($result=='success'):?>
    <h3>
    We have sent you confirmation link! Check your email (<?= $email;?>).
    </h3>
    <?php else:?>
        <h3>
            Some errors occured while sending email, please check username/email.
            </h3>
        <?php 
 endif;?>
</div>
