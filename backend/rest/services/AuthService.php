<?php
require_once 'BaseService.php';
require_once __DIR__ . '/../dao/AuthDao.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class AuthService extends BaseService {
   private $auth_dao;

   public function __construct() {
       $this->auth_dao = new AuthDao();
       parent::__construct(new AuthDao);
   }


   public function get_user_by_email($email){
       return $this->auth_dao->get_user_by_email($email);
   }


   public function register($entity) {  
       if (empty($entity['email']) || empty($entity['password']) || empty($entity['first_name']) 
            || empty($entity['phone_number'])) {
           return ['success' => false, 'error' => 'Some required fields are missing.'];
       }

       $email_exists = $this->auth_dao->get_user_by_email($entity['email']);
       if($email_exists){
           return ['success' => false, 'error' => 'Email already registered.'];
       }

       if (strlen($entity['password']) < 8) {
            return ['success' => false, 'error' => 'Password must be at least 8 characters long.'];
        }

        if (!empty($entity['phone_number']) && !preg_match('/^\+?[0-9]{7,15}$/', $entity['phone_number'])) {
            $errors[] = 'Phone number must be between 7 and 15 digits, optionally starting with +.';
        }

       $entity['password_hash'] = password_hash($entity['password'], PASSWORD_BCRYPT);
       unset($entity['password']);
       $entity['user_role'] = 'customer';
       $entity = parent::create($entity);

       return ['success' => true, 'data' => $entity];             
   }


   public function login($entity) {  
       if (empty($entity['email']) || empty($entity['password'])) {
           return ['success' => false, 'error' => 'Email and password are required.'];
       }

       $user = $this->auth_dao->get_user_by_email($entity['email']);
       if(!$user){
           return ['success' => false, 'error' => 'Invalid username or password.'];
       }

       if(!$user || !password_verify($entity['password'], $user['password_hash']))
           return ['success' => false, 'error' => 'Invalid username or password.'];

       unset($user['password_hash']);
      
       $jwt_payload = [
           'user' => $user,
           'iat' => time(),
           'exp' => time() + (60 * 60 * 24)
       ];

       $token = JWT::encode(
           $jwt_payload,
           Config::JWT_SECRET(),
           'HS256'
       );

       return ['success' => true, 'data' => array_merge($user, ['token' => $token])];             
   }
}
