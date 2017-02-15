<?php

class WebhookListener{

	private $headers;
	
	public function __construct(){
		$this->headers = getallheaders();
		$this->payload = file_get_contents('php://input');
		$this->jsonbody = json_decode($this->payload);
		$this->getRepo();
		if($this->confirmSender() && $this->wasMaster()){
			$this->grabLatestCode();
		}else if(!$this->wasMaster()){
			return $this->error(200, 'Not master branch');
		}
	}

	private function wasMaster(){
		if(!isset($this->jsonbody->ref)) return false;
		return $this->jsonbody->ref == "refs/heads/master";
	}
	
	private function confirmSender(){
		if(!isset($this->headers['X-Hub-Signature']))
			return $this->error(400, 'Data missing');
		list($algo, $hash) = explode('=', $this->headers['X-Hub-Signature'], 2);
		$payload = file_get_contents('php://input');
		if($hash !== hash_hmac($algo, $payload, $this->secret))
			return $this->error(403, 'Could not verify sender');
		return true;
	}

	private function error($code, $str){
		$this->respond($code, [
			'success' => false,
			'msg' => $str
		]);
	}

	private function success($str){
		$this->respond(200, [
			'success' => true,
			'msg' => $str
		]);
	}

	private function respond($code, $arr){
		header('Content-Type: application/json');
		http_response_code($code);
		echo json_encode($arr);
		exit();
	}

	private function getRepo(){
		$Repos = include __DIR__."/RepoLinks.php";

		if(!$this->jsonbody || !$this->jsonbody->repository){
			return $this->error(400, 'Request body missing');
		}
		
		if(!isset($Repos[$this->jsonbody->repository->full_name]))
			return $this->error(501, 'Repo not found');

		$this->repo_path = $Repos[$this->jsonbody->repository->full_name]['path'];
		$this->secret = $Repos[$this->jsonbody->repository->full_name]['secret'];
		return true;
	}

	private function grabLatestCode(){
		$output = exec("cd {$this->repo_path} && git pull origin master");
		$this->success($output);
	}

	
}


?>
