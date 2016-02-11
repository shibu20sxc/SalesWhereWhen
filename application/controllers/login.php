<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper('language');
        $this->lang->load('admin', 'english');
        $this->load->library('session');
        $this->load->model('users_model');
		$this->load->model('role_model');
    }

    public function index() {
        if (!$this->input->post()) {
            $this->load->view('admin/login');
        } else {
            $logindata['email'] = $this->input->post('email');
            $logindata['password'] = md5($this->input->post('password'));
            $logindata['type'] = $this->input->post('type');
            $result = $this->users_model->login($logindata);
            if ($result) {
                if ($result->status == '1' && $result->type =='admin') {
                    $newdata = (object) array(
                        'logged_in' => TRUE,
                        'logged_id' => $result->id
                    );
					$role_result = $this->role_model->fetchRoleDetails($result->type);
					$this->session->set_userdata('role_details', $role_result);
                    $this->session->set_userdata('admin', $newdata);
                    redirect('admin');
                } else {
                    $data['login_error'] = true;
                    $this->load->view('admin/login', $data);
                }
            } else {
                $data['login_error'] = true;
                $this->load->view('admin/login', $data);
            }
        }
    }

    public function forgetPassword() {

        if (!$this->input->post()) {
            $this->load->view('admin/forget_password');
        } else {
            $email = $this->input->post('email');
            $data['check_email'] = $this->user_model->checkEmail($email);  //validate Email 
            $password = chr(rand(65, 91)) . chr(rand(65, 91)) . chr(rand(65, 91)) . chr(rand(65, 91)) . rand(1000, 9999);
            if ($data['check_email']) {
                date_default_timezone_set('Etc/UTC');
                require_once($_SERVER['DOCUMENT_ROOT'] . '/application/libraries/phpmailer/PHPMailerAutoload.php');
                $mail = new PHPMailer();
                $mail->isSMTP();
                $mail->SMTPDebug = 0;
                $mail->Debugoutput = 'html';
                $mail->Host = "mail.nisclient.com";
                $mail->SMTPSecure = "ssl";
                $mail->Port = 465;
                $mail->SMTPAuth = true;
                $mail->Username = "noreply@nisclient.com";
                $mail->Password = "@admin2013";
                $mail->setFrom('noreply@nisclient.com', 'CouterBling');
                $mail->addReplyTo($email, 'Admin CoutureBling.');
                $mail->addAddress($email, 'CoutureBling');
                $mail->Subject = 'Reset Password Mail';
                $mail->Body = "Use this password to login       " . $password;
                $mail->AltBody = 'This is a plain-text message body';
                if (!$mail->send()) {
                    echo "Mailer Error: " . $mail->ErrorInfo;
                } else {

                    $data['update_password'] = $this->user_model->updatePassword($password, $email);
                    if ($data['update_password']) {
                        $data['change_password'] = true;
                    } else {
                        $data['change_password'] = false;
                    }
                }
            } else {

                $data['invalid_email'] = true;
            }

            $this->load->view('admin/forget_password', $data);
        }
    }
   /*merchant function is used for merchant login in frontend*/
    public function merchant(){
        if (!$this->input->post()) {
            $this->load->view('merchant/merchantLogin');
        }else{
            $logindata['email'] = $this->input->post('email');
            $logindata['password'] = md5($this->input->post('password'));
            $logindata['type'] = $this->input->post('type');
            //print_r($logindata);die;
            $result = $this->users_model->login($logindata);
            if ($result) {
                if ($result->status == '1' && $result->type =='merchant') {
                    $newdata = (object) array(
                        'logged_in' => TRUE,
                        'logged_id' => $result->id
                    );
					$role_result = $this->role_model->fetchRoleDetails($result->type);
					$this->session->set_userdata('role_details', $role_result);
                    $this->session->set_userdata('admin', $newdata);
                    redirect('admin');
                } else {
                    $data['login_error'] = true;
                    $this->load->view('admin/login', $data);
                }
            } else {
                $data['login_error'] = true;
                $this->load->view('admin/login', $data);
            }
        }
    }
    /*login function is used for user and merchant login in frontend*/
      public function user(){
        if ($this->input->post()) { 
            $html = '';
            $input = array(
                'email_id'=>$this->input->post('email_id'),
                'password'=>md5($this->input->post('password'))
            );
            
            $curren_url = $this->input->post('current_url');
            $login = $this->users_model->user_login($input,'users');
            $this->session->set_userdata('user', $login);             
            if ($login){            
                 $html.='success'; 
            }
            else {  
                
                   $html.='<p style="color:red">Invalid UsernName OR Password.</p>';                     
            }
            echo $html;
        }
    }
  public function loginWithFacebook() {
        define("APPID", "765342056868346");
        define("SECRET", "5ef09089f0622ae030b00f2bf6fd1123");	
	require_once($_SERVER['DOCUMENT_ROOT'] . '/application/libraries/facebook/facebook.php');
        $facebook = new Facebook(array(
            'appId' => APPID,
            'secret' => SECRET,
        ));	 
        $users = $facebook->getUser();
	
        if ($users != "") {
            try {
                $user_profile = $facebook->api('/'.$users);
		//print_r($user_profile);  die;               
                $logoutUrl = $facebook->getLogoutUrl(); 
		$fuserid = $user_profile["id"];
                $email = $user_profile["email"];
		$first_name = $user_profile["first_name"];
		$last_name = $user_profile["last_name"];
		$gender = $user_profile["gender"];
		$user_data = $this->users_model->checkFacebookId($fuserid);

		//print_r($user_data);
		if(!empty($user_data)){
		$this->session->set_userdata('user', $user_data);
                //print_r($user_data);die;
		redirect('home');
		}
                else{
                    redirect('home');
                }
            } catch (FacebookApiException $e) {
                error_log($e);
    			$user = null;
            }
        }
    }

}

/* End of file login.php */
/* Location: ./application/controllers/login.php */
