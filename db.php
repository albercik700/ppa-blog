<?php
class BlogManager extends mysqli{
	private $nazwa;
	private $haslo;
	private $email;
	private $data_rejestracji;
	function __construct($host,$user,$password,$dbname){
		parent::__construct($host,$user,$password,$dbname);
		if(mysqli_connect_errno()!=0){
			die("Błąd połączenia z bazą danych.");
		}
		//echo "Obiekt jest utworzony";
	}

	private function re_text($text){
		return preg_match("/^[A-z\d\.\-]{5,25}$/",$text);
	}

	private function re_email($text){
		return preg_match("/^[A-z\d\.\_\-]{3,65}@[A-z\.\-\d]+\.[A-z\.]{2,10}$/",$text);
	}

	private function username_av($username){
		//Sprawdza czy jest nazwa jest dostepna
		$stmt=$this->prepare("select distinct nazwa from uzytkownicy order by nazwa");
		$stmt->execute();
		$result=$stmt->get_result();
		while($row=$result->fetch_assoc()){
			if($row['nazwa']==$username)
				return 0;
		}
		return 1;
	}

	public function register($username,$password,$email){
		$password=hash("sha512",'31337|'.$password);
		$date=date("Y-m-d H:i:s");	
		if($this->re_text($username)!=0 and $this->re_email($email) and $this->username_av($username)!=0){
			$stmt=$this->prepare("insert into uzytkownicy(nazwa,pass,email,data_rejestracji) values(?,?,?,?)");
			$stmt->bind_param("ssss",$username,$password,$emila,$date);
			$stmt->execute();
			$result=$stmt->get_result();
			if(!$result){
				return 0;
			}else{
				return 1;
			}
		}
	}

	public function login($username,$password){
		$password=hash("sha512",'31337|'.$password);
		$stmt=$this->prepare('select nazwa,pass,email,data_rejestracji from uzytkownicy where nazwa=? limit 1');
		$stmt->bind_param("s",$username);
		$stmt->execute();
		$result=$stmt->get_result();
		$row= $result->fetch_assoc();
		if($this->re_text($username)==1 and $password==$row['pass'] and $row!=NULL){
			$nazwa=$row['nazwa'];
			$haslo=$row['pass'];
			$email=$row['email'];
			$data_rejestracji=$row['data_rejestracji'];
			$SESSION['login']=hash("md5",$nazwa).session_id();
			return 1;//True;
		}else{
			return 0;//False;
		}
	}

	public function logout(){
		session_destroy();
		session_start();
		session_regenerate_id();
	}
}
?>