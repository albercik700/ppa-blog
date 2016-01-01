<?php
class BlogManager extends mysqli{
	private $id;
	private $nazwa;
	//private $haslo;
	private $email;
	private $data_rejestracji;
	private $sesja;
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
		$stmt=$this->prepare("select distinct nazwa from uzytkownicy order by nazwa");
		$stmt->execute();
		$result=$stmt->get_result();
		while($row=$result->fetch_assoc()){
			if($row['nazwa']==$username)
				return 0; //false
		}
		return 1; //true
	}

	public function register($username,$password,$email){
		if($this->re_text($username)==1 and $this->re_email($email)==1 and $this->username_av($username)==1 and strlen($password)>=8){
			$password=hash("sha512",'31337|'.$password);
			$date=date("Y-m-d H:i:s");	
			$stmt=$this->prepare("insert into uzytkownicy(nazwa,pass,email,aktywny,data_rejestracji) values(?,?,?,1,?)");
			$stmt->bind_param("ssss",$username,$password,$email,$date);
			$stmt->execute();
			$result=$stmt->get_result();
			echo $result;
			if(!$result)
				return 1; //true
		}
		return 0; //false
	}

	public function logIn($username,$password){
		$password=hash("sha512",'31337|'.$password);
		$stmt=$this->prepare('select * from uzytkownicy where nazwa=? limit 1');
		$stmt->bind_param("s",$username);
		$stmt->execute();
		$result=$stmt->get_result();
		$row= $result->fetch_assoc();
		if(!isset($_SESSION['login']) and $this->re_text($username)==1 and $password==$row['pass'] and !is_null($row)){
			$this->id=$row['id'];
			$this->nazwa=$row['nazwa'];
			//$this->haslo=$row['pass'];
			$this->email=$row['email'];
			$this->data_rejestracji=$row['data_rejestracji'];
			$this->sesja=session_id();
			$_SESSION['login']=session_id();
			$_SESSION['nazwa']=$this->nazwa;
			$_SESSION['email']=$this->email;
			$_SESSION['data_rejestracji']=$this->data_rejestracji;
			$poczatek_sesji=date("Y-m-d H:i:s");
			$koniec_sesji=date("Y-m-d H:i:s",strtotime("+25 minutes",strtotime($poczatek_sesji)));
			$stmt->prepare("insert into zalogowani(fk_uzytkownik,sesja,poczatek_sesji,koniec_sesji) values(?,?,?,?)");
			$stmt->bind_param("isss",$this->id,$this->sesja,$poczatek_sesji,$koniec_sesji);
			$stmt->execute();
			$result=$stmt->affected_rows;
			$stmt->close();
			if($result!=0)
				return 1;//True;
		}
		return 0;//False;
	}

	public function logOut(){
		session_destroy();
		$stmt=$this->prepare("delete from zalogowani where sesja = ?");
		$stmt->bind_param("s",$_SESSION['login']);
		$stmt->execute();
		unset($_SESSION['login']);
		unset($_SESSION['nazwa']);
		unset($_SESSION['data_rejestracji']);
		unset($_SESSION['email']);
		if($stmt->affected_rows==1)
			return 1;
		else
			return 0;
	}

	public function logStatus($var_sesja="None"){
		$query="select zal.sesja from zalogowani zal join uzytkownicy usr on zal.fk_uzytkownik=usr.id where sesja = ?";
		$stmt=$this->prepare($query);
		$stmt->bind_param("s",$var_sesja);
		$stmt->execute();
		$result=$stmt->get_result();
		$row=$result->fetch_assoc();
		/*echo "DB:".$row['sesja']."<br/>";
		echo '$_SESSION[\'login\']:'.$_SESSION['login']."<br/>";
		echo '$this->sesja:'.$this->sesja."<br/>";
		echo '$this->nazwa:'.$this->nazwa."<br/>";*/
		if($var_sesja!="" and $var_sesja==$row['sesja'])
			return 1;
		else
			return 0;
	}
}
?>