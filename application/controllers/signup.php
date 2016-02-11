<?php

/*
 * This Controller has prepared for signup functionality of user and merchant.
 * User Function is for User signup
 *  Merchant Function is for Merchant signup
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Signup extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->library('session');
        $this->load->library('parser');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('session');
        $this->load->model('users_model');
        $this->load->model('merchant_model');
	$this->load->model('category_model');
	$this->load->model('deals_model');
	$this->load->model('brand_model');
        $this->load->library('form_validation');
    }

   public function merchant() {
                $data['brand_list'] = $this->brand_model->getBrandList();
                $data['shop_list'] = $this->deals_model->getShopList(); //change
                $data['categoryMegamenu'] = $this->category_model->getCategoryMegaMenu();
                $data['city_list'] = $this->users_model->getCityList();
        if (!$this->input->post()) {
            $this->load->view('merchant/merchantSignup',$data);
        } else {
            
            $merchant = array(
                'first_name'=>$this->input->post('first_name'),
                'last_name'=>$this->input->post('last_name'),
                'email_id'=>$this->input->post('email_id'),
                'password'=>md5($this->input->post('password')),
                'address'=>$this->input->post('address'),
                'city'=>$this->input->post('city'),
                'contact_no'=>$this->input->post('contact_no'),
                'type'=>'merchant'
            );
            
            $this->db->insert('merchant',$merchant);
            $lastinsId = mysql_insert_id();
            if($lastinsId){
               $data['succ_regis'] = 'success';
               $register_type = 'MERCHANT';
               $this->registerMail($this->input->post('email_id'), $lastinsId, $register_type);
               $this->load->view('merchant/merchantSignup',$data);
            }
        }
    }

     public function ajaxcheckEmail() {
        $reg_email = $this->input->post('email_id');
        $email_check = $this->users_model->emailCheck($reg_email);      
        $html = '';
        if (!empty($email_check)) {

            //$html.='<p style="color:red">Email already Exist.</p>';
            $html.= 'error';
        } else {
            //$html.='<p style="color:green">Email available.</p>';
            $html.= 'success';
        }
        
        echo $html;
        exit();
    }

    public function user() {
        $data['city_list'] = $this->users_model->getCityList();
        if ($this->input->post()) {

            $update_success = false;
            $this->form_validation->set_rules('email_id', 'password', 'trim|required|max_length[200]');
            $this->form_validation->set_message('max_length', 'Max 200 characters only allowed');
            $this->form_validation->set_message('required', '%s can\'t be blank');
            $signup_popup_data = $this->input->post();
            $name = explode(" ", $signup_popup_data['name']);
            $count = sizeof($name);
            $signup_data['first_name'] = $name[0];
            $signup_data['last_name'] = '';
            for ($i = 1; $i < $count; $i++) {
                $signup_data['last_name'] = $signup_data['last_name'] . ' ' . $name[$i];
            }
            $signup_data['password'] = md5($this->input->post('password'));
            $signup_data['email_id'] = $this->input->post('email_id');

            $folder = 'user_profile_images';
            if ($_FILES['profile_image']['name'] != "") {
                if (!is_dir(FCPATH . '/images/' . $folder)) {
                    mkdir(FCPATH . '/images/' . $folder);
                }
                $config['upload_path'] = 'images/' . $folder . '/'; // Location to save the image
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['overwrite'] = false;
                $config['remove_spaces'] = true;
                $config['max_size'] = '10000'; // in KB
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if ($this->upload->do_upload('profile_image')) {

                    $configThumb['image_library'] = 'gd2';
                    $configThumb['source_image'] = 'images/' . $folder . '/' . $this->upload->file_name;
                    $configThumb['create_thumb'] = false;
                    $configThumb['maintain_ratio'] = TRUE;
                    $configThumb['master_dim'] = 'width';
                    $configThumb['width'] = 150; // image re-size  properties
                    $configThumb['height'] = 150; // image re-size  properties 
                    $configThumb['new_image'] = 'images/' . $folder . '/' . 'thumb/thumb' . $this->upload->file_name; // image re-size  properties 
                    $this->load->library('image_lib', $configThumb); //codeigniter default function
                    $this->image_lib->resize();
                    $signup_data['profile_image'] = $this->upload->file_name;
                } else {
                    $data['profile_image'] = $this->upload->display_errors();
                }
            }
            $this->db->insert('users', $signup_data);
            $user_id = mysql_insert_id();
            $update_success = ($this->db->affected_rows() != 1) ? false : true;
            if ($update_success) {
                $register_type = 'USER';
                $this->registerMail($this->input->post('email_id'), $user_id, $register_type);
            }
        }
    }

    public function registerMail($email, $user_id, $register_type) {
        $encrept_id = $this->encrypt_url($user_id);
        $encrept_type = $this->encrypt_url($register_type);
        $data['confirmationURL'] = base_url() . 'signup/confirmRegistration/' . $encrept_id . '/' . $encrept_type;
        $mail_template = $this->parser->parse('mailtemplate/registerUser', $data, TRUE);
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
        $mail->setFrom('noreply@nisclient.com', 'SalesWhereWhen');
        $mail->addReplyTo($email, 'Admin SalesWhereWhen.');
        $mail->addAddress($email, 'SalesWhereWhen');
        $mail->Subject = 'Registration Confirmation Mail';        
        $confirmationURL = base_url() . 'signup/confirmRegistration/' . $encrept_id . '/' . $encrept_type;
        $mail->Body = $mail_template;
        $mail->AltBody = 'This is a plain-text message body';
        if (!$mail->send()) {
            //echo "Mailer Error: " . $mail->ErrorInfo; die;
            echo '<script> alert("Invalid Email Id ! PleaseEnter Valid Email."); </script>';
            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            $data['mail_send'] = true;
            echo '<script> alert("Please Check Your Mail to Confirm Registration..."); </script>';
            redirect('home/index');
        }
    }

    public function confirmRegistration() {
        $encrypt_user_id = $this->uri->segment(3);
        $encrypt_register_type = $this->uri->segment(4);

        $loginid = $this->decrypt_url($encrypt_user_id);
        $register_type = $this->decrypt_url($encrypt_register_type);

        $input = array(
            'status' => 1
        );
        if ($register_type == 'USER') {
            $update_user = $this->db->update('users', $input, array('id' => $loginid));
            if ($update_user) {
                
                $login = $this->users_model->getUserDetails($loginid);
                $this->session->set_userdata('user', $login);
                redirect('users/myAccount');
            }
        } elseif ($register_type == 'MERCHANT') {
            $update_user = $this->db->update('merchant', $input, array('id' => $loginid));
            if ($update_user) {
                redirect('login/merchant');
            }
        }
    }

    public function encrypt_url($string) {
        $key = "MAL_979805"; //key to encrypt and decrypts.
        $result = '';
        $test = "";
        for ($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) + ord($keychar));

            $test[$char] = ord($char) + ord($keychar);
            $result.=$char;
        }

        return urlencode(base64_encode($result));
    }

    public function decrypt_url($string) {
        $key = "MAL_979805"; //key to encrypt and decrypts.
        $result = '';
        $string = base64_decode(urldecode($string));
        for ($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) - ord($keychar));
            $result.=$char;
        }
        return $result;
    }

    public function random_password($length = 8) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
        $password = substr(str_shuffle($chars), 0, $length);
        return $password;
    }

    public function forgotPassword() {
        $data = '';
		$data['brand_list'] = $this->brand_model->getBrandList();
		$data['city_list'] = $this->users_model->getCityList();
		$data['shop_list'] = $this->deals_model->getShopList(); //change
		$data['categoryMegamenu'] = $this->category_model->getCategoryMegaMenu();
        if ($this->input->post()) {
            $email = $this->input->post('email_id');
            $checkemail = $this->users_model->emailCheck($email);
            if (isset($checkemail->email_id)) {
                $random_password = $this->random_password();              
                $user_email = $checkemail->email_id;
                $passwordReset = $this->users_model->passwordReset($random_password, $user_email);                
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
                $mail->setFrom('noreply@nisclient.com', 'SalesWhereWhen');
                $mail->addReplyTo($user_email, 'Admin SalesWhereWhen.');
                $mail->addAddress($user_email, 'SalesWhereWhen');
                $mail->Subject = 'Registration Confirmation Mail';
                $mail->Body = "Please use this password to login \n<strong>" . $random_password."</strong>";
                $mail->AltBody = 'This is a plain-text message body';
                if (!$mail->send()) {
                    //echo "Mailer Error: " . $mail->ErrorInfo; die;
                    echo '<script> alert("Invalid Email Id ! PleaseEnter Valid Email."); </script>';
                    echo "Mailer Error: " . $mail->ErrorInfo;
                } else {
                    $data['succ_email'] = true;
                    $this->load->view('users/forgotPassword', $data);
                }
            } else {
                $data['error_email'] = true;
                $this->load->view('users/forgotPassword', $data);
            }
        } else {
            $this->load->view('users/forgotPassword', $data);
        }
    }

	public function registerWithFacebook(){	
	define("APPID", "765342056868346");
        define("SECRET", "5ef09089f0622ae030b00f2bf6fd1123");
        require_once(FCPATH . '/application/libraries/facebook/facebook.php');
        $facebook = new Facebook(array(
            'appId' => APPID,
            'secret' => SECRET,
        ));
	
        $users = $facebook->getUser();       
        if ($users != "") {
            try {	
                $user_profile = $facebook->api('/'.$users);
		//print_r($user_profile);		
                $logoutUrl = $facebook->getLogoutUrl();
                $fuserid = $user_profile["id"];
                $email = $user_profile["email"];
		$first_name = $user_profile["first_name"];
		$last_name = $user_profile["last_name"];
		$gender = $user_profile["gender"];
		$register_with_fb = array(
			'facebook_login_id'=>$fuserid,
			'first_name'=>$first_name,
			'last_name'=>$last_name,
			'gender'=>$gender,
			'email_id'=>$email,
			'status'=>1			
			);
		
		$this->db->insert('users',$register_with_fb);		
		$user_data = $this->users_model->checkFacebookId($fuserid);		
                $current_url= current_url();                
		if($user_data){
				$this->session->set_userdata('user', $user_data);
                                redirect('home');
			}
                        else {
                            redirect('home');
                        }
		
            } catch (FacebookApiException $e) {
                $users = null;
            }
        }
}

}

?>
