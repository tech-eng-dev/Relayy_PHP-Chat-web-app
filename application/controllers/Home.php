<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/ChatController.php");

class Home extends ChatController
{
	public function __construct()
	{
		parent::__construct(); 
	}

	public function index()
	{

		if ( gf_isLogin() )
		{
			redirect(site_url('questions'), 'get');
			
			return;	
		}
		$CI =& get_instance();
		$invitePass = $CI->session->userdata('invite_pass');
		$inviteID = $CI->session->userdata('invite_id');
		$inviteEmail = $CI->session->userdata('invite_email');

		if($invitePass === 'invite'){
			$arr = array(
	            'invite_pass' => 'pass'
	        );                                              
	        $CI->session->set_userdata($arr);

			$user = $this->muser->get($inviteID);      		
		
	    	$data['body_class'] = 'invite-page';

			$data['page_title'] = 'Welcome! Relayy';

	    	$data['current_section'] = 'invite';

	    	$data['current_id'] = $inviteID;

	    	$data['current_email'] = urldecode($inviteEmail);

	        if(!$user){
	            $user_data['message'] = 'Invalid URL';
	            $user_data['page_title'] = 'Notify | Relayy';
	            gf_unregisterCurrentUser();
	            $this->load->view('notify', $user_data);        
	        }
	        else{
	        	$data['current_type'] = $user->{TBL_USER_TYPE};
	        	$this->load->view('templates/header-home', $data);
	    		$this->load->view('invite', $data);
	    		$this->load->view('templates/footer', $data);
	        }
		}
		else{
			$data['body_class'] = 'home';

			$data['page_title'] = 'Welcome! Relayy';

	    	$data['current_section'] = 'home';

	    	$data['js_home'] = 1;
	    
	    	$this->load->view('templates/header-home');
			
			$this->load->view('home', $data);

			$this->load->view('templates/footer', $data);
		}
		
    	
	}

	public function login() 
	{

		if ( gf_isLogin() )
		{
			redirect(site_url('questions'), 'get');
			
			return;	
		}

		$data['body_class'] = 'home';

		$data['page_title'] = 'Welcome! Relayy';

    	$data['current_section'] = 'home';

    	$data['js_home'] = 2;
    
    	$this->load->view('templates/header-home');
		
		$this->load->view('home', $data);

		$this->load->view('templates/footer', $data);	
	}

	public function callback(){
		$code = $this->input->get('code');

		$state = $this->input->get('state');

		$data['code'] = $code;

		$data['state'] = $state;

		$data['body_class'] = 'home';

		$data['page_title'] = 'Welcome! Relayy';

    	$data['current_section'] = 'home';

    	$data['js_home'] = 2;
    
    	$this->load->view('templates/header-home');
		
		$this->load->view('home', $data);

		$this->load->view('templates/footer', $data);	




	}

	

	public function channel($email, $did)
	{
	 	if ( gf_isLogin() )
	 	{
	 		redirect(site_url('chat/channel/'.$did), 'get');
			
	 		return;	
	 	}

	 	$data['body_class'] = 'home';

	 	$data['page_title'] = 'Welcome! Relayy';

     	$data['current_section'] = 'home';

     	$data['js_home'] = 2;
        
        $data['email'] = urldecode($email);
        
        $data['did'] = $did;
    
    	$this->load->view('templates/header-home');
		
	 	$this->load->view('home', $data);

	 	$this->load->view('templates/footer', $data);	
	}

	public function checkUser(){
		$email = $this->input->post('email');
		$this->load->model('muser');
        $user = $this->muser->getEmail($email);
        if(!$user) echo "not_active";
        else if($user->{TBL_USER_STATUS} == 1){
        	echo $user->{TBL_USER_TYPE};
        }
        else if($user->{TBL_USER_STATUS} == 2){
        	echo "invited_user";
        }
        else{
        	echo "not_active";
        }
	}

    public function link() {
        
        $email = $this->input->post('email');
        $this->load->model('muser');
        $user = $this->muser->getEmail($email);
        if ($user) {
            echo $user->{TBL_USER_STATUS};
            exit;
        }        
        echo "11";
    }

    public function onboarding_advisor(){
    	$data['body_class'] = 'home';
		$data['page_title'] = 'Welcome! Relayy';
		$data = $this->getChatData();
    	$this->load->view('onboarding_advisor_1', $data);
    }

    public function onboarding_entrepreneur(){
    	$data['body_class'] = 'home';
		$data['page_title'] = 'Welcome! Relayy';
		$data = $this->getChatData();
    	$this->load->view('onboarding_entrep_1', $data);
    }

    public function onboarding_moderator(){
    	$data['body_class'] = 'home';
		$data['page_title'] = 'Welcome! Relayy';
		$data = $this->getChatData();
    	$this->load->view('onboarding_advisor_1', $data);
    }

    public function switchTo_onboarding_entrep_2(){
    	$chat_data = $this->getChatData();
    	$this->load->view('onboarding_entrep_2', $chat_data);
    }

    public function switchTo_onboarding_advisor_2(){
    	$chat_data = $this->getChatData();
    	$this->load->view('onboarding_advisor_2', $chat_data);
    }

    public function save_Interesting_in(){
    	$chat_data = $this->getChatData();
    	$interesting = $this->input->post('interesting_in');
    	$this->mbusiness->saveBusinessData(array(TBL_BUSINESS_INTERESTING => $interesting, TBL_BUSINESS_ID => $this->cid));
    	$this->load->view('onboarding_entrep_2', $chat_data);
    }

    public function save_advisor_Skill(){
    	$chat_data = $this->getChatData();
    	$skill = $this->input->post('skill');
    	$this->mbusiness->saveBusinessData(array(TBL_BUSINESS_SKILL => $skill, TBL_BUSINESS_ID => $this->cid));
    	$this->load->view('onboarding_advisor_2', $chat_data);
    }

    public function save_advisor_interesting(){
    	$chat_data = $this->getChatData();
    	$interesting = $this->input->post('interesting_in');
    	$this->mbusiness->saveBusinessData(array(TBL_BUSINESS_INTERESTING => $interesting, TBL_BUSINESS_ID => $this->cid));
    	echo "success";
    }

    public function save_Challenge(){

    	//============ 
    	$challenge = $this->input->post('challenge');
    	$this->mbusiness->saveBusinessData(array(TBL_BUSINESS_CHALLENGE => $challenge, TBL_BUSINESS_ID => $this->cid));
    	echo "success";
    }

    public function linkedin_callback(){
    	$code = $this->input->get('code');
    	$state = $this->input->get('state');
		$URL = 'https://www.linkedin.com/oauth/v2/accessToken?grant_type=authorization_code&code='.$code.'&redirect_uri='.urlencode(site_url()).'home%2Flinkedin_callback&client_id='.gf_linkedIn_api().'&client_secret='.gf_linkedin_secret_key();
    	$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $URL);
		$result = curl_exec($ch);
		$res = json_decode($result);
		if(isset($res->access_token)){
			$_SESSION['access_token'] = $res->access_token;
			curl_close($ch);
			$this->fetch('GET', '/v1/people/~:(first-name,last-name,picture-url,headline,location,industry,positions,public-profile-url,summary,email-address)');
		}else{
			$this->index();
		}
		
	}

	public function fetch($method, $resource, $body = '') {
		$params = array('oauth2_access_token' => $_SESSION['access_token'],
						'format' => 'json',
				  );
		// Need to use HTTPS
		$url = 'https://api.linkedin.com' . $resource . '?' . http_build_query($params);
		// Tell streams to make a (GET, POST, PUT, or DELETE) request
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		$LData = json_decode(curl_exec($ch));
		curl_close($ch);
		// Get LinkedIn Profile (LData) by here ============================================
		$data['body_class'] = 'home';

		$data['page_title'] = 'Welcome! Relayy';

    	$data['current_section'] = 'home';

    	$data['LData'] = "Fetched";

    	$data['fname'] = isset($LData->firstName)?$LData->firstName:"";

    	$data['lname'] = isset($LData->lastName)?$LData->lastName:"";

    	$data['email'] = isset($LData->emailAddress)?$LData->emailAddress:"";

    	$data['headline'] = isset($LData->headline)?$LData->headline:"";

    	$data['pictureUrl'] = isset($LData->pictureUrl)?$LData->pictureUrl:"";

    	$ln = isset($LData->location->name)?$LData->location->name:"";
    	$lcc = isset($LData->location->country->code)?$LData->location->country->code:"";
    	$data['location'] = $ln.", ".$lcc;

    	$data['publicUrl'] = isset($LData->publicProfileUrl)?$LData->publicProfileUrl:"";

    	$data['log_state'] = $_SESSION['log_state'];

        for($index = 0; $index < $LData->positions->_total; $index++){
          if($LData->positions->values[$index]->isCurrent){
            $data['companyInfo'] = json_encode($LData->positions->values[$index]);
            break;
          }
        }

        if($_SESSION['log_state'] < 2){
        	$this->load->view('templates/header-home');
			$this->load->view('home', $data);
			$this->load->view('templates/footer', $data);
        }
        else{
        	$CI =& get_instance();
        	$inviteID = $CI->session->userdata('invite_id');
        	$user = $this->muser->get($inviteID);
        	$data['body_class'] = 'invite-page';
			$data['page_title'] = 'Welcome! Relayy';
	    	$data['current_section'] = 'invite';
	    	$data['current_id'] = $inviteID;
	    	
        	$data['current_type'] = $user->{TBL_USER_TYPE};
        	$this->load->view('templates/header-home');
			$this->load->view('invite', $data);
			$this->load->view('templates/footer', $data);
        }
    	
	}
    	

    public function save_log_state(){
    	$log_state = $this->input->post('log_state');
    	$_SESSION['log_state'] = $log_state;
    	echo "Saved successfully!";
    }
     
	public function logout()
	{
		
	}
}