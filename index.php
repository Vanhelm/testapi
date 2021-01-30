<?
include_once('data/Connection.php');

/**
 * 
 */
class Main
{
	//Свойства для подключения к бд
	private $con;
	private $conn;
	//Свойства публичного токена
	public $tokenUser;

	//Устаналвиаем подключения
	public function __construct()
	{
		$this->conn = new Connection;
		$this->con = $this->conn->getConnectionCon();
	}

	//Получаем персональный токен из бд
	public function getToken()
	{
		return $this->conn->getToken();
	}

	//Обновляем персональный токен в бд
	public function updateToken()
	{
		//Создаем токен на основе md5 принимающей слово 'demo' и текущее время
		$token = md5('demo'.time());
		//Подготавливаем запрос к БД
		$stmt = $this->con->prepare("UPDATE tokens SET token = :token");
		//Передаем переменную в подгатовленный запрос.
		$stmt->bindParam(':token', $token);
		//Выполняем запрос
		$stmt->execute();
		//Редирект на главную
		header('location: /');
	}
}

//Создаем объект
$tokenObj = new Main();
//Записываем в переменную токен, для его последующего вывода
$tokenUser = $tokenObj->getToken();

//Если данные переданы методом пост вызываем функцию обновления токена
if($_SERVER['REQUEST_METHOD'] === 'POST'){
	$tokenObj->updateToken();
}




?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Генератор токена</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
	<p class="h2 container mt-2">Token: <?=htmlspecialchars($tokenUser)?></p>
	<form action="/index.php" method="post" class="container mt-2">
		<button type="submit" class="btn btn-success">Создать токен</button>
	</form>
	<div class="container mt-2">
		<a href="/succes.php?stat=url" class="btn btn-info">Посмотреть счета</a>
		<a href="/format.php" class="btn btn-info">Выставить счёт</a>
	</div>
	
	
</body>
</html>