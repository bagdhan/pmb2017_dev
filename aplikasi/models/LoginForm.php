<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\usermanager\models\User;
use linslin\yii2\curl;

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
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
		echo '<pre>';
			print_r($this->validateApi());
			die("sukses");
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }
	
	public function validateApi()
    {
        $curl = new curl\Curl();
        $dateFormatted = date("Y-m-d", strtotime($this->password));
        $jsonData = json_encode([
            'BillKey' => $this->username,
            'TanggalLahir' => $dateFormatted
        ]);
        $response = $curl->setHeaders([
                'Content-Type' => 'application/json'
            ])->setRawPostData($jsonData)
        ->post('http://172.17.5.227:8080/authenticate');
        $data = json_decode( $response, true );
        return $data;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $name = User::findByUsername($this->username);
            $email = User::findByEmail($this->username);
            $this->_user = empty($name) ? $email : $name;
        }

        return $this->_user;
    }
}
