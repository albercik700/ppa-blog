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
			$_SESSION['id']=$this->id;
			$_SESSION['login']=session_id();
			$_SESSION['nazwa']=$this->nazwa;
			$_SESSION['email']=$this->email;
			$_SESSION['data_rejestracji']=$this->data_rejestracji;
			$poczatek_sesji=date("Y-m-d H:i:s");
			$koniec_sesji=date("Y-m-d H:i:s",strtotime("+25 minutes",strtotime($poczatek_sesji)));
			$adres_ip=$_SERVER['REMOTE_ADDR'];
			$stmt->prepare("insert into zalogowani(fk_uzytkownik,sesja,poczatek_sesji,koniec_sesji,adres_ip) values(?,?,?,?,?)");
			$stmt->bind_param("issss",$this->id,$this->sesja,$poczatek_sesji,$koniec_sesji,$adres_ip);
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
		unset($_SESSION['id']);
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

	public function showLoggedIn(){
		$query="select usr.nazwa from zalogowani zal join uzytkownicy usr on zal.fk_uzytkownik=usr.id ";
		$stmt=$this->prepare($query);
		$stmt->execute();
		$result=$stmt->get_result();
		$out="";
		while($row=$result->fetch_assoc()){
			$out=$out.$row['nazwa'].", ";
		}
		return $out;
	}

	public function getUser(){
		if(isset($_SESSION['login']) && $this->logStatus($_SESSION['login'])==1){
			$this->id=$_SESSION['id'];
			$this->nazwa=$_SESSION['nazwa'];
			$this->email=$_SESSION['email'];
			$this->data_rejestracji=$_SESSION['data_rejestracji'];
			$this->sesja=$_SESSION['login'];
		}
		return array('nazwa'=>$this->nazwa,'email'=>$this->email,'data rejestracji'=>$this->data_rejestracji,'ostatnie logowanie'=>$this->lastLogin($this->id));
	}

	public function lastLogin($userID="0"){
		if(isset($_SESSION['login']) && $this->logStatus($_SESSION['login'])==1){
			$stmt=$this->prepare("select min(x.data_zdarzenia) as last_log from (select * from historia_zdarzen where fk_uzytkownik=? and fk_zdarzenie=2 order by id desc limit 2)x");
			$stmt->bind_param("d",$userID);
			$stmt->execute();
			$result=$stmt->get_result();
			return $result->fetch_assoc()['last_log'];
		}
		return "";
	}

	public function updateUser($passwd,$email){
		if(isset($_SESSION['login']) && $this->logStatus($_SESSION['login'])==1){
			if($this->re_email($email)==1 && strlen($passwd)>=8){
				$passwd=hash("sha512",'31337|'.$passwd);
				$stmt=$this->prepare("update uzytkownicy set pass=?,email=? where id=?");
				$stmt->bind_param("ssd",$passwd,$email,$_SESSION['id']);
				$stmt->execute();
				$result=$stmt->affected_rows;
				$stmt->close();
				$this->email=$_SESSION['email'];
				if($result!=0)
					return 1;//True;
			}
			return 0;//False;
		}
	}

	public function getStatus(){
		$stmt=$this->prepare("select id,nazwa from status");
		$stmt->execute();
		$result=$stmt->get_result();
		$tab=array();
		while($row=$result->fetch_assoc()){
			$tab[$row['id']]=$row['nazwa'];
		}
		return $tab;
	}

	public function getTags(){
		$stmt=$this->prepare("select id,nazwa from tagi");
		$stmt->execute();
		$result=$stmt->get_result();
		$tab=array();
		while($row=$result->fetch_assoc()){
			$tab[$row['id']]=$row['nazwa'];
		}
		return $tab;
	}

	public function addPost($title,$status,$tagi,$tresc){
		if(isset($_SESSION['login']) && $this->logStatus($_SESSION['login'])==1){
			$data_wpisu=date("Y-m-d H:i:s");
			$adres_ip=$_SERVER['REMOTE_ADDR'];
			$stmt=$this->prepare("insert into wpisy(fk_uzytkownik,fk_status,temat,data_wpisu,tresc,adres_ip) values(?,?,?,?,?,?)");
			$stmt->bind_param("ddssss",$_SESSION['id'],$status,$title,$data_wpisu,$tresc,$adres_ip);
			$stmt->execute();
			$result=$stmt->affected_rows;
			echo $result;
			if($result>0 && count($tagi)>0){
				echo "inside";
				$output=$this->query("select max(id) as id from wpisy where fk_uzytkownik=".$_SESSION['id'])->fetch_assoc()['id'];
				foreach($tagi as $tag){
					echo "inside tags";
					$stmt=$this->prepare("insert into wpisy_tagi(fk_wpis,fk_uzytkownik,fk_tag) values(?,?,?)");
					$stmt->bind_param("ddd",$output,$_SESSION['id'],$tag);
					$stmt->execute();
					$result=$stmt->affected_rows;
					echo $result;
				}
				return 1;//True
			}
			$stmt->close();
		}
		return 0;//False;
	}

	public function editPost($id,$title,$status,$tresc,$tagi=array()){
		if(isset($_SESSION['login']) && $this->logStatus($_SESSION['login'])==1){
			foreach($this->showPost($id) as $wpis){
				if($wpis->getAuthor()==$_SESSION['nazwa']){
					$stmt=$this->prepare("update wpisy set fk_status=?,temat=?,tresc=? where id=?");
					$stmt->bind_param("issd",$status,$title,$tresc,$wpis->getID());
					$stmt->execute();
					$result=$stmt->affected_rows;
					$stmt->prepare("delete from wpisy_tagi where fk_wpis=?");
					$stmt->bind_param("d",$wpis->getID());
					$stmt->execute();
					$result=$stmt->affected_rows;
					foreach($tagi as $v){
						$stmt=$this->prepare("insert into wpisy_tagi(fk_wpis,fk_uzytkownik,fk_tag) values(?,?,?)");
						$stmt->bind_param("ddd",$wpis->getID(),$_SESSION['id'],$v);
						$stmt->execute();
						$result=$stmt->affected_rows;
						if($result!=0)
							continue;
					}
					$stmt->close();
					return 1;//True	
				}
			}
		}
		return 0;//False;
	}

	private function getPostCategory($postID){
		$stmt=$this->prepare("select cat.id,cat.nazwa from wpisy_tagi JOIN tagi cat on cat.id=wpisy_tagi.fk_tag where wpisy_tagi.fk_wpis=?");
		$stmt->bind_param("i",$postID);
		$stmt->execute();
		$result=$stmt->get_result();
		$out=array();
		while($row=$result->fetch_assoc()){
			$out[$row['id']]=$row['nazwa'];
		}
		return $out;
	}

	public function showPosts($userID=0,$limit=9999,$offset=0){
		$output=array();
		if($userID==0){
			$stmt=$this->prepare("select count(*) as ilosc from (select posts.*,users.nazwa as uzytkownik,history.last_edit from wpisy posts join uzytkownicy users on posts.fk_uzytkownik=users.id left join (select fk_uzytkownik, max(data_zdarzenia) as last_edit from historia_zdarzen)history on users.id=history.fk_uzytkownik where posts.fk_status=2)x");
			$stmt->execute();
			$result=$stmt->get_result();
			$count=$result->fetch_assoc()['ilosc'];
			array_push($output,$count);
			$stmt->prepare("select posts.*,users.nazwa as uzytkownik,history.last_edit,status.nazwa,status.nazwa as status from wpisy posts
				join uzytkownicy users on posts.fk_uzytkownik=users.id
				left join (select fk_uzytkownik, max(data_zdarzenia) as last_edit from historia_zdarzen)history on users.id=history.fk_uzytkownik join status on posts.fk_status=status.id where posts.fk_status=2 order by posts.data_wpisu desc limit ? offset ?");
			$stmt->bind_param("ii",$limit,$offset);
			$stmt->execute();
			$result=$stmt->get_result();
			while($row=$result->fetch_assoc()){
				$wpis= new Wpis($row['id'],$row['temat'],$row['uzytkownik'],$row['data_wpisu'],$row['last_edit'],$row['tresc'],$this->getPostCategory($row['id']),$row['status']);
				array_push($output,$wpis);
				unset($wpis);
			}
		}else{
			$stmt=$this->prepare("select count(*) as ilosc from (select posts.*,users.nazwa as uzytkownik,history.last_edit from wpisy posts
				join uzytkownicy users on posts.fk_uzytkownik=users.id
				left join (select fk_uzytkownik, max(data_zdarzenia) as last_edit from historia_zdarzen)history on users.id=history.fk_uzytkownik where users.id=?)x");
			$stmt->bind_param("i",$userID);
			$stmt->execute();
			$result=$stmt->get_result();
			$count=$result->fetch_assoc()['ilosc'];
			array_push($output,$count);
			$stmt->prepare("select posts.*,users.nazwa as uzytkownik,history.last_edit,status.nazwa as status  from wpisy posts
				join uzytkownicy users on posts.fk_uzytkownik=users.id
				left join (select fk_uzytkownik, max(data_zdarzenia) as last_edit from historia_zdarzen)history on users.id=history.fk_uzytkownik join status on posts.fk_status=status.id where users.id=? order by posts.data_wpisu desc limit ? offset ?");
			$stmt->bind_param("iii",$userID,$limit,$offset);
			$stmt->execute();
			$result=$stmt->get_result();
			while($row=$result->fetch_assoc()){
				$wpis= new Wpis($row['id'],$row['temat'],$row['uzytkownik'],$row['data_wpisu'],$row['last_edit'],$row['tresc'],$this->getPostCategory($row['id']),$row['status']);
				array_push($output,$wpis);
				unset($wpis);
			}
		}
		return $output;
	}

	public function showPost($postID=0){
		$output=array();
		$stmt=$this->prepare("select posts.*,users.nazwa as uzytkownik,history.last_edit,status.nazwa as status from wpisy posts join uzytkownicy users on posts.fk_uzytkownik=users.id left join (select fk_uzytkownik, max(data_zdarzenia) as last_edit from historia_zdarzen)history on users.id=history.fk_uzytkownik join status on posts.fk_status=status.id where posts.id=?");
		$stmt->bind_param("i",$postID);
		$stmt->execute();
		$result=$stmt->get_result();
		while($row=$result->fetch_assoc()){
			$wpis= new Wpis($row['id'],$row['temat'],$row['uzytkownik'],$row['data_wpisu'],$row['last_edit'],$row['tresc'],$this->getPostCategory($row['id']),$row['status']);
			array_push($output,$wpis);
			unset($wpis);
		}
		return $output;
	}
}

class Wpis{
	private $id;
	private $title;
	private $author;
	private $createdate;
	private $editdate;
	private $content;
	private $category;
	private $status;
	function __construct($wpisID,$wpisTitle,$wpisUser,$wpisCreateDate,$wpisEditDate="",$wpisContent,$wpisCategory=array(),$wpisStatus){
		$this->id=$wpisID;
		$this->title=$wpisTitle;
		$this->author=$wpisUser;
		$this->createdate=$wpisCreateDate;
		$this->editdate=$wpisEditDate;
		$this->content=$wpisContent;
		$this->category=$wpisCategory;
		$this->status=$wpisStatus;
	}

	public function getID(){
		return $this->id;
	}

	public function getTitle(){
		return $this->title;
	}

	public function getAuthor(){
		return $this->author;
	}

	public function getCreateDate(){
		return $this->createdate;
	}

	public function getEditDate(){
		return $this->editdate;
	}

	public function getContent(){
		return $this->content;
	}

	public function getCategory(){
		return $this->category;
	}

	public function getStatus(){
		return $this->status;
	}
}
?>