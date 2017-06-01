<?php

namespace app\models;

use Yii;
use yii\base\Model;

class PasswordRecovery extends Model{
   public $password;
   public $password_confirm;
   public $token;
   public $reCaptcha;

   public function attributeLabels() {
       return [
           'reCaptcha'=>'',
           'password'=>'Password',
           'password_confirm'=>'Confirm Password'
       ];
   }
   public function rules()
    {
        return [
            // password_confirm and password are both required
            
            [['password', 'password_confirm'], 'required'],
            ['reCaptcha','required','message'=>'Verify you\'re not robot'],
            [['password','password_confirm'],'string','min'=>7],
            ['password_confirm','compare','compareAttribute'=>'password','message'=>'Password\s don\'t match'],

        ];
    }
    public function scenarios() {
        return[
            'default'=>['password','password_confirm','token','reCaptcha']
        ];
    }
    public function recover()
    {
        if ($this->validate()) {
        
            //change password and sigin in user
            
            $member=  Member::findByPasswordResetToken($this->token);
            if($member){
                $member->scenario=  Member::SCENARIO_LOGIN;
                $member->setPassword($this->password);
                $member->removePasswordResetToken();
                $member->save();
                return Yii::$app->user->login($member, 3600*24*30);
                
            }
            else{
                throw new \yii\web\BadRequestHttpException();
            }
            return false;
        }
    }
}
