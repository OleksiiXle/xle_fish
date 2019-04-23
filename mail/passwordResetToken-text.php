<?php
$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['adminx/user/reset-password', 'token' => $user->password_reset_token]);
?>

    Hello <?= $user->username ?>,
    Follow the link below to reset your password:

<?= $resetLink ?>