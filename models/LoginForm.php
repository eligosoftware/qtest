<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;

    public function attributeLabels() {
        return[
           'username'=>'Username or email',
            'password'=>'Password',
            'rememberMe'=>'Remember Me'
        ];
    }
    public function get_user(){
        return $this->_user;
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->password='';
                $this->addError($attribute, 'Incorrect username or password (or username is not active yet)');
            }
            else if(!$user->isActive()) {
                $this->addError('not_active','Account is not active');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            if(filter_var($this->username, FILTER_VALIDATE_EMAIL))
            {
                $this->_user = Member::findByEmail($this->username,true);
            }
            else{
               $this->_user = Member::findByUsername($this->username,true);
            }
            
         
            
        }

        return $this->_user;
    }
}
