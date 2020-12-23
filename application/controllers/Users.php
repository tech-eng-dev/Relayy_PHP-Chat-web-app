<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once (dirname(__FILE__) . "/ChatController.php");

class Users extends ChatController
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
	//$this->maintenance();return;
	
    	$this->loginCheck();

    	$this->roleCheck();    	

    	$chat_data = $this->getChatData();

    	$chat_data['body_class'] = 'users-page';

		$chat_data['page_title'] = 'User Management | Relayy';

		$chat_data['users'] = $this->muser->getUserlist(USER_STATUS_ALL);

		$chat_data['current'] = gf_cu_id();

		$chat_data['page'] = 0;

		$mygroup = $this->mgroup->get($this->cgroup);
		$chat_data['group'] = $this->cgroup;
		$chat_data['group_name'] = $mygroup[TBL_GROUP_NAME];
		$chat_data['group_image_name'] = $mygroup[TBL_GROUP_IMAGE];

		$groups = $this->mgroup->getGroupInfo();
        $group_class = new stdClass();
        $groupcodes = array();
        foreach($groups as $group){
        	$groupcodes[] = $group[TBL_GROUP_CODE];
            $group_class->{$group[TBL_GROUP_CODE]} = $group[TBL_GROUP_NAME];
        }
        $chat_data['groupnames'] = $group_class;
        $chat_data['groupcodes'] = $groupcodes;

        $iUsers = $this->mgroup->getInvitedNonGroupUsers();
        $chat_data['invitedusers'] = json_encode($iUsers);


        $invited_users = new StdClass();
        foreach($iUsers as $iUser){
            $invited_users->{$iUser[TBL_USER_INVITE_WHOM]} = $iUser[TBL_GROUP_NAME];
        }
        $chat_data['invited_group'] = $invited_users;
    
    	$this->load->view('templates/header-chat', $chat_data);

		$this->load->view('templates/left-sidebar', $chat_data);

		$this->load->view('users', $chat_data);

		$this->load->view('templates/right-sidebar', $chat_data);

		$this->load->view('templates/footer-chat', $chat_data);
	}

	

	public function ActionUpdate(){
		
		$now = new DateTime();
		$now->format('Y-m-d H:i:s');    // MySQL datetime format
		$currentTime = $now->getTimestamp();
		$cUser = $this->muser->getEmail($this->cemail);
		if(!$cUser) return;
		if($cUser->status == 4){
			$user_data['message'] = 'Sorry, your account has been deleted.';
			$user_data['page_title'] = 'Notify | Relayy';
			gf_unregisterCurrentUser();
			$this->load->view('notify', $user_data);		
		}
		else if($cUser->status == 0){
			$user_data['message'] = 'Sorry, your account is pending by admin now.';
			$user_data['page_title'] = 'Notify | Relayy';
			gf_unregisterCurrentUser();
			$this->load->view('notify', $user_data);		
		}
		else{
			$object = $this->muser->getEmail($this->cemail);            
            gf_registerCurrentUser($object);
            
			$this->muser->edit($cUser->id, array(TBL_USER_TIME=> $currentTime));
			$UserStates = $this->muser->getUserStates();
			$arr_data=array();
			foreach($UserStates as $userState){
				if($userState[TBL_USER_TIME] < $currentTime - 100 && $userState[TBL_USER_TIME] > $currentTime - 500){
					$data['id'] = $userState['id'];
					$data['state'] = 'away';
				}
				else if($userState[TBL_USER_TIME] < $currentTime - 500){
					$data['id'] = $userState['id'];
					$data['state'] = 'offline';
				}else{
					$data['id'] = $userState['id'];
					$data['state'] = 'online';
				}
				$arr_data[]=$data;
			}
			$user_data['user_states'] = $arr_data;		
			echo json_encode($user_data);
		}
		
	} 

	public function DeclineGroupInvite(){
		$invite = $this->mgroup->checkGroupInvite($this->cid);
		$this->mgroup->DeclineGroupInvite($this->cid);
		$group = $this->mgroup->get($invite->{TBL_USER_INVITE_GROUP});
		$inviter = $this->muser->get($invite->{TBL_USER_INVITE_WHO});
		$this->email->sendInviteDeclineEmail($inviter->{TBL_USER_EMAIL}, $this->cfname." ".$this->clname, $this->cemail, $group[TBL_GROUP_NAME]);
	}

	public function AcceptGroupInvite(){
		$res = $this->mgroup->checkGroupInvite($this->cid);
		$this->mgroup->DeclineGroupInvite($this->cid);
		$this->muser->updateUser($this->cid, array(TBL_USER_GROUP => $res->{TBL_USER_INVITE_GROUP}));

	}

	public function activity_feed(){
		$this->loginCheck();

    	$chat_data = $this->getChatData();

    	$chat_data['body_class'] = 'feed-page';

		$chat_data['page_title'] = 'Activity feeds | Relayy';

		if(strlen($this->cgroup) > 0){//group user
			$chat_data['feeds'] = $this->mfeed->getGroupFeeds($this->cgroup);
		}else if($this->ctype == 1){//admin
			$chat_data['feeds'] = $this->mfeed->get();	
		}
		else{//non-group user
			$chat_data['feeds'] = $this->mfeed->getNonGroupUserFeeds();
		}

		$now = new DateTime();
        $currentTime = $now->getTimestamp();

        $chat_data['download_time'] = $currentTime;

	   	$this->load->view('templates/header-chat', $chat_data);

		$this->load->view('templates/left-sidebar', $chat_data);

		$this->load->view('activity_feed', $chat_data);

		$this->load->view('templates/right-sidebar', $chat_data);

		$this->load->view('templates/footer-chat', $chat_data);
	}	

	public function updateFeeds(){
		$r_num = $this->input->post('recent_num');

		$this->loginCheck();

    	$chat_data = $this->getChatData();

    	$chat_data['body_class'] = 'feed-page';

		$chat_data['page_title'] = 'Activity feeds | Relayy';

		if(strlen($this->cgroup) > 0){
			$chat_data['feeds'] = $this->mfeed->getNewGroupFeeds($r_num, $this->cgroup);
		}
		else{
			$chat_data['feeds'] = $this->mfeed->getNewFeeds($r_num);
		}

		$now = new DateTime();
        $currentTime = $now->getTimestamp();

        $chat_data['download_time'] = $currentTime;

        $chat_data['new'] = 1;

		$this->load->view('update_feed', $chat_data);
	}

	public function LoadMoreFeeds(){
		$l_num = $this->input->post('last_num');

		$this->loginCheck();

    	$chat_data = $this->getChatData();

    	$chat_data['body_class'] = 'feed-page';

		$chat_data['page_title'] = 'Activity feeds | Relayy';

		if(strlen($this->cgroup) > 0){
			$chat_data['feeds'] = $this->mfeed->getMoreGroupFeeds($l_num, $this->cgroup);
		}
		else{
			$chat_data['feeds'] = $this->mfeed->getMoreFeeds($l_num);
		}

		$now = new DateTime();
        $currentTime = $now->getTimestamp();

        $chat_data['download_time'] = $currentTime;

        $chat_data['new'] = 0;

		$this->load->view('update_feed', $chat_data);
	}

	public function getServerTime(){
		$now = new DateTime();
        $currentTime = $now->getTimestamp();
        echo $currentTime;
	}

	public function Deactivate(){

		$uid = $this->input->post('id');
		$status = $this->input->post('status');
		$data_arr = array(TBL_USER_STATUS=> $status);
		$this->muser->updateUser($uid, $data_arr);
		echo "success";
	}

	public function deleteAccount(){
		$id = $this->input->post('id');
		$this->muser->delete($id);
		$user_data['message'] = 'Sorry, your account has been deleted.';
		$user_data['page_title'] = 'Notify | Relayy';
		gf_unregisterCurrentUser();
		$this->load->view('notify', $user_data);
		
	}

    public function check_code_and_email(){
        $code = $this->input->post('code');
        $email = $this->input->post('email');
        $email = str_replace('%40', '@', $email);
        $res = $this->muser->getEmail($email);
        
        if(!$res || ($res && $res->{TBL_USER_STATUS} == USER_STATUS_DELETE)){
            $res = $this->mcode->checkcode($code);
            $b_m = $this->mcode->checkModeratorCode($code);
            $b_l = $this->mcode->checkLeaderCode($code);
            
            if($b_l)  echo "no_group";
            else if($b_m)  echo 'Moderator_'.$b_m[TBL_INVITE_CODE];
            else if(!$res || $res[TBL_INVITE_REMAIN] == 0) echo "Invalid";
            else if($res[TBL_INVITE_TYPE] == 4) echo $res[TBL_INVITE_CODE];
            else echo "no_group";
        }else{
        	if($res->{TBL_USER_STATUS} == USER_STATUS_INIT) echo "pending_user";
        	else if($res->{TBL_USER_STATUS} == USER_STATUS_LIVE) echo "exist/".$res->{TBL_USER_FNAME}." ".$res->{TBL_USER_LNAME}."/".$email;
        }  
    }

	public function delete($id, $page) 
	{
		$this->loginCheck();

		$this->roleCheck();    	

		$userObj = $this->muser->get($id);

		echo $userObj->{TBL_USER_UID}."\\";

		//$this->email->removeUser($this->cemail, $this->cfname." ".$this->clname, $userObj->{TBL_USER_EMAIL});

		if($id == $this->cid || $this->ctype == 1) $this->muser->delete($id);
		else $this->muser->DeletefromGroup($id);

		if($page == 100){
			echo "success";
		} else if ($page == 0) {
			redirect(site_url('users'), 'get');
		} else if ($page == 1) {
			redirect(site_url('users/pending'), 'get');
		} else if ($page == 2) {
			redirect(site_url('users/activated'), 'get');
		} else {
			redirect(site_url('users/invited'), 'get');
		}
	}

	public function action($uid, $page) 
	{
		$this->loginCheck();

		$this->roleCheck();

		$userObj = $this->muser->changeStatus($uid);

		if ($userObj->{TBL_USER_STATUS} == USER_STATUS_LIVE) $this->email->approveUser($this->cemail, $this->cfname." ".$this->clname, $userObj->{TBL_USER_EMAIL});
		else $this->email->deproveUser($this->cemail, $this->cfname." ".$this->clname, $userObj->{TBL_USER_EMAIL});


		//page is always 0
		if ($page == 0) {
			redirect(site_url('users'), 'get');
		} else if ($page == 1) {
			redirect(site_url('users/pending'), 'get');
		} else if ($page == 2) {
			redirect(site_url('users/activated'), 'get');
		} else {
			redirect(site_url('users/invited'), 'get');
		}
	}

	public function getBioForActionPage(){
		$id = $this->input->post('id');
		$bio = $this->muser->getBiowithID($id);
		echo $bio;
	}

	public function invite($type, $email, $page) 
	{
		
		$emailAddress = str_replace('%40', '@', $email);
		$emailAddress = str_replace('%2c', ',', $emailAddress);		
		$emails = explode(",", $emailAddress);
		//=================check invited emails
		foreach($emails as $email){
			$User = $this->muser->getEmail($email);
			if(!$User) continue;
			
			if($User->{TBL_USER_STATUS} == 1 && $User->{TBL_USER_GROUP} === $this->cgroup){
				echo "group_user/".$User->{TBL_USER_FNAME}." ".$User->{TBL_USER_LNAME};
				exit;
			}
			else if($User->{TBL_USER_STATUS} == 1 && $User->{TBL_USER_GROUP} !== $this->cgroup){
				echo "live_user/".$User->{TBL_USER_FNAME}." ".$User->{TBL_USER_LNAME};
				exit;
			}
		}

		//=================
		$res = "";
		$code = $this->mcode->getWithID($this->cid);
		foreach($emails as $email){

	        $oldUser = $this->muser->getEmail($email);
	        $newID = NULL;
	        if($res !== "") $res = $res."/";
	        if ($oldUser) {
	            $newID = $oldUser->{TBL_USER_ID};
	            //admin can invite inactive user
	            if($this->ctype == 1){
	            	$this->muser->edit($newID, array(TBL_USER_STATUS=>USER_STATUS_INVITE, TBL_USER_TYPE => $type, TBL_USER_GROUP => "", TBL_USER_CODE => ""));	
	            } 
	            //moderator can invite inactive user
	        	else if($this->ctype == 4 && $type % 10 == 4){
	        		$this->muser->edit($newID, array(TBL_USER_STATUS => USER_STATUS_INVITE,
	        										 TBL_USER_TYPE => $type,
	        										 TBL_USER_GROUP => $this->cgroup,
	        										 TBL_USER_CODE => $code[TBL_INVITE_MCODE]));
	        	}
	        	else{
	        		$this->muser->edit($newID, array(TBL_USER_STATUS => USER_STATUS_INVITE,
	        										 TBL_USER_TYPE => $type,
	        										 TBL_USER_GROUP => $this->cgroup));
	        	}
	        	$this->email->inviteUser($this->cemail, $this->cfname." ".$this->clname, $this->inviteUserLink($newID, $email), $email);
	        } else {
	        	if($this->ctype == 1){
	        		$newID = $this->muser->add(array(
		                TBL_USER_TYPE => $type,
		                TBL_USER_STATUS => USER_STATUS_INVITE,
		                TBL_USER_EMAIL => strtolower($email),
		                TBL_USER_CODE => ""
		            )); 
	        	}
	        	else if($this->ctype == 4 && $type % 10 == 4){//invited as a moderator by moderator
	        		$newID = $this->muser->add(array(
		                TBL_USER_TYPE => $type,
		                TBL_USER_STATUS => USER_STATUS_INVITE,
		                TBL_USER_EMAIL => strtolower($email),
		                TBL_USER_GROUP => $code[TBL_INVITE_CODE],
		                TBL_USER_CODE => $code[TBL_INVITE_MCODE]
		            ));   
	        	}
	        	else if($this->ctype == 4 && $type % 10 != 4){
	        		$newID = $this->muser->add(array(
		                TBL_USER_TYPE => $type,
		                TBL_USER_STATUS => USER_STATUS_INVITE,
		                TBL_USER_EMAIL => strtolower($email),
		                TBL_USER_GROUP => $code[TBL_INVITE_CODE]
		            ));   
	            }
	            else{
	            	$newID = $this->muser->add(array(
		                TBL_USER_TYPE => $type,
		                TBL_USER_STATUS => USER_STATUS_INVITE,
		                TBL_USER_EMAIL => strtolower($email),
		                TBL_USER_CODE => $code[TBL_INVITE_CODE]
		            ));   
	            }
				$this->email->inviteUser($this->cemail, $this->cfname." ".$this->clname, $this->inviteUserLink($newID, $email), $email);
	        	
	        }
	        $res = $res.$newID;// add non-user's id to the chat occupants
		}
		if($page == 4){
			echo $res; // id1/id2/id3
			exit;	
		} 
		
		
	}

	public function InviteToGroup(){
		$res = $this->mgroup->get($this->cgroup);
		if(!$res){
			echo "group_empty";
			exit;	
		} 
		$email = $this->input->post('email');
		$user = $this->muser->getEmail($email);
		$res = $this->mgroup->inviteNonGroupUser($user->{TBL_USER_ID}, $this->cgroup, $user->{TBL_USER_TYPE}, $this->cid);
		$group = $this->mgroup->get($this->cgroup);

		$this->email->sendGroupInvitation($this->cfname." ".$this->clname, $user->{TBL_USER_FNAME}, $email, $group[TBL_GROUP_NAME], $this->cemail);
		if(!$res) echo "failed";
		else echo "success";
	}

	public function challengers(){
		$data = $this->getChatData();
		$data['body_class'] = 'challenge-page';
		$data['page_title'] = 'Top Entrepreneurs | Relayy';
		$challengers = array();
		$challengers = $this->mbusiness->getTopEntreps();
		$data['count'] = sizeof($challengers);
		$data['challengers'] = $challengers;

		$this->load->view('templates/header-chat', $data);

		$this->load->view('templates/left-sidebar', $data);

		$this->load->view('business_challenges', $data);

		$this->load->view('templates/right-sidebar', $data);

		$this->load->view('templates/footer-chat', $data);

	}

	public function add_user_manually(){
		$fname = $this->input->post('fname');
        $lname = $this->input->post('lname');
        $email = $this->input->post('email');
        $role = $this->input->post('role');
        $uid = $this->input->post('uid');
        $group = $this->input->post('group');

        $now = new DateTime();
        $currentTime = $now->getTimestamp();

        $data_arr = array();
        if($role == 4 && strlen($group) > 0){
        	$m_code = $this->mcode->getmcode($group);
        	$data_arr = array(
	    			TBL_USER_UID => $uid,
	                TBL_USER_FNAME => $fname,
	                TBL_USER_LNAME => $lname,
	                TBL_USER_EMAIL => $email,
	                TBL_USER_LINKEDIN_EMAIL => $email,
	                TBL_USER_STATUS => USER_STATUS_LIVE,
	                TBL_USER_BYADMIN => 1,
	                TBL_USER_TYPE  => $role,
	                TBL_USER_SIGNUP => $currentTime,
	                TBL_USER_GROUP => $group,
	                TBL_USER_CODE => $m_code
	    		);
        }else{
        	$data_arr = array(
	    			TBL_USER_UID => $uid,
	                TBL_USER_FNAME => $fname,
	                TBL_USER_LNAME => $lname,
	                TBL_USER_EMAIL => $email,
	                TBL_USER_LINKEDIN_EMAIL => $email,
	                TBL_USER_STATUS => USER_STATUS_LIVE,
	                TBL_USER_BYADMIN => 1,
	                TBL_USER_TYPE  => $role,
	                TBL_USER_SIGNUP => $currentTime,
	                TBL_USER_GROUP => $group,
	                TBL_USER_CODE => $group
	    		);
        }       

        $userObj = $this->muser->getEmail($email);
        if(!$userObj){
	    	$object = $this->muser->register($data_arr);
	    }
	    else{
	    	$this->muser->updateLinkedInData($email, $data_arr, 1);
	    }
	}

	public function DeleteFeed(){
		$num = $this->input->post('num');
		$this->mfeed->Delete($num);
		return "success";
	}

	private function roleCheck() {
		if (gf_cu_type() == USER_TYPE_ADVISOR || gf_cu_type() == USER_TYPE_ENTREP) 
		{
			redirect(site_url('profile'), 'get');
		}
	}
}