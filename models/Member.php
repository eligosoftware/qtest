<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\base\Security;

/**
 * Description of Member
 *
 * @author ilgarrasulov
 */
class Member extends ActiveRecord implements IdentityInterface{
    
    const  SCENARIO_LOGIN = "login";
    const  SCENARIO_REGISTER = "register";
    const  SCENARIO_REGISTER_OAUTH = "register_oauth";


    public function scenarios() {
        return [
            'default' => ['username','password'],
            self::SCENARIO_LOGIN => ['username','password'],
            self::SCENARIO_REGISTER =>['username','email','password','active'],
            self::SCENARIO_REGISTER_OAUTH =>['username','email','active','oauth_type','oauth_id'],
        ];
    }
    
    public static function tableName() {
        return "member";
    }

    public function setPassword($password){
        $this->password=  sha1($password);
        //$this->password= Yii::$app->security->generatePasswordHash($password);
    }
    public function generateAuthKey(){
        $this->auth_key= Yii::$app->security->generateRandomKey();
    }
    public function generatePasswordResetToken(){
        $this->password_reset_token = Yii::$app->security->generateRandomString().'_'.  time();
    }
    public function removePasswordResetToken(){
        $this->password_reset_token = NULL;
    }
        public function getAuthKey() {
        return $this->auth_key;
    }
    public function beforeSave($insert) {
        if(parent::beforeSave($insert))
        {
            if($this->isNewRecord){
                $this->auth_key=  Yii::$app->security->generateRandomString();
                
            }
            return TRUE;
        }
        return FALSE;
    }
    public function getId() {
        return $this->getPrimaryKey();
    }

    public function validateAuthKey($authKey) {
        return $this->auth_key===$authKey;
    }
    public function validatePassword($password) {
        return $this->password===sha1($password);
      //      return $this->password===sha1($password);  
    }
    public function isActive(){
        return $this->active==1;
    }

    public static function findIdentity($id) {
        return static::findOne($id);
    }
    public static function findByUsername($username,$native_only=FALSE) {
        
        if(filter_var($username, FILTER_VALIDATE_EMAIL))
            {
            $array=['email'=>$username];
            }
            else{
               $array=['username'=>$username];
            }
            if ($native_only) {
                $array+=['oauth_type'=>'native'];
            }
            return static::findOne($array);
    }

        public static function findByEmail($email,$native_only=FALSE) {
            $array=['email'=>$email];
            if ($native_only) {
                $array+=['oauth_type'=>'native'];
            }
       return static::findOne($array);
    }
    public static function findIdentityByAccessToken($token, $type = null) {
        return static::findOne(['access_token'=>$token]);
    }
    public function rules() {
        return [
            [['username','password'],'required'],
            [['username','password'],'string','max'=>100],
            ['password','string','min'=>7],
           // ['password','match','pattern'=>'/?=.*[A-Z]/']
        ];
    }
    
    

        public static function findByPasswordResetToken($token){
        $expire=  Yii::$app->params['user.passwordResetTokenExpire'];
        $parts=  explode('_', $token);
        $timestamp=(int)end($parts);
        
        if($timestamp+$expire<  time()){
            //password expired
            return null;
        }
    return static::findOne(['password_reset_token'=>$token]);
    }
    public static function token_expired($token){
        $expire=  Yii::$app->params['user.passwordResetTokenExpire'];
        $parts=  explode('_', $token);
        $timestamp=(int)end($parts);
        
        if($timestamp+$expire<  time()){
            //password expired
            return true;
        }
        return false;
    }

    public function attributeLabels() {
        return [
            'userid'=>'Userid',
            'username'=>'Username',
            'password'=>'Password'
        ];
    }
    public static function registerOAuthUser($attributes,$oauth_type){
        
        $attributes+=['oauth_type'=>$oauth_type];        
        
        $member=Member::findOne(['email'=>$attributes['email'],'oauth_type'=>$oauth_type]);
        
        if(!$member){
            $member=new Member();
            $member->scenario=  Member::SCENARIO_REGISTER_OAUTH;
            $member->load($member->turnToData($attributes));
            $member->save();
        }
        
        return $member;
    }
    
    public function getName(){
        
        $arr=  explode(" ", $this->username);
        return $arr[0];
        
    }

        public function turnToData($attributes){
        
        $data['email']=  $attributes['email'];
        $data['username']=  $attributes['name'];
        $data['active']=1;
        $data['oauth_type']=$attributes['oauth_type'];
        $data['oauth_id']=$attributes['id'];

        
        
        return ['Member'=>$data];
    }
//put your code here
}
