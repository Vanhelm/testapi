<?php 
include_once('data/Connection.php');
//class API 
class Register
{
	//свойства для подключения к бд
	private $con;
	private $conn;
	//подключение к бд
	function __construct()
	{
		$this->conn = new Connection;
		$this->con = $this->conn->getConnectionCon();
	}

	//Метод для создания уникального платежного url, принимает два параметра $name, $price
	public function getCreatedURL($name, $price)
	{	
		//Получаем уникальный токе	
		$token = $this->conn->getToken();
		//Создаем уникальный платежный индефикатор на основе md5 и токена.
		//Можно передавать дополнительную переменную, которая будет только у пользователя (например его id)
		$session = md5($token.time()); 
		//Подготавливаем запрос
		$stmt = $this->con->prepare("INSERT INTO pay (session, name, pay, lifetime) VALUES (:session, :name, :pay, :lifetime)");
		//Передаем данные для подготовленного запроса
		$stmt->bindParam(':session', $session);
		$stmt->bindParam(':name', $name);
		$stmt->bindParam(':pay', $price);
		$stmt->bindParam(':lifetime', date('Y-m-d H:i:s', time()));
		//Выполняем запрос
		$stmt->execute();
		//Возращаем уникальную ссылку
		return '/pay.php?session='.$session;
	}
	//Получаем все данные по платежам
	public function getAllUrl()
	{	
		//Формируем запрос.
		$sql = "SELECT * FROM pay";
		//Выполняем его
		$query = $this->con->query($sql);
		//Получаем данные
		$session = $query->fetchAll(PDO::FETCH_OBJ);
		//Кодируем данные в json и возращаем
		return json_encode($session);
	}
	//Получаем данные за период от и до
	public function getFilterUrl($filter1, $filter2)
	{	
		//приводим данные из get запроса в вид для поиска в бд
		$filter1 = $filter1.' 00:00:00';
		$filter2 = $filter2.' 23:59:59';
		//Формируем запрос
		$sql = "SELECT * FROM pay WHERE lifetime > '$filter1' AND lifetime < '$filter2'";
		//Выполняем
		$query = $this->con->query($sql);
		//Получаем даннные
		$session = $query->fetchAll(PDO::FETCH_OBJ);
		//Кодируем данные в json и возращаем
		return json_encode($session);
	}


	
}
//Создаем объект
$register = new Register();
// Проверяем был ли передан post запрос
if($_SERVER['REQUEST_METHOD'] === 'POST'){
	//получаем данные
	$json = file_get_contents('php://input');
	//извлекаем данные в виде объекта
	$obj = json_decode($json);
	//Формируем данные для создания url
	$name = $obj->name;
	$price = $obj->price;	
	//Выводим данные созданного url, чтобы получить их
	echo $register->getCreatedURL($name, $price);
}

if($_GET['stat'] === 'url'){
	//Выводим данные всех платежей, чтобы получить их
	echo $register->getAllUrl();
}

if($_GET['filter1'] AND $_GET['filter2']){
	//Выводим данные выбранных платежей, чтобы получить их
	echo $register->getFilterUrl($_GET['filter1'], $_GET['filter2']);
}