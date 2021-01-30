<?php
//Устанвливаем временную зону Москвы
date_default_timezone_set('Europe/Moscow');

class Connection
{
	//Хост или IP базы данных MySql
	private $host = 'localhost';
	//Имя базы данных
	private $dbname = 'country';
	//Логин для подключения к бд в phpmyadmin/ имя пользователя
	private $user = 'root';
	//Пароль к бд
	private $password = 'root';
	//Задаем переменную для получения токена
	private $tokenUser;
	//Метод подключения к бд
	public function getConnectionCon()
	{
		//Вывод кода для подключения. Если успешно - подключение. Если нет - то выведется ошибка.
		try {
			//передаем параметры для подключения в объект PDO
			$db = new PDO("mysql:host=$this->host; dbname=$this->dbname", $this->user, $this->password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
			//обработка исключения
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
		//Возращаем соедение
		return $db;
	}
	//Метод для получения персонального токена
	public function getToken()
	{
		//Передаем query запрос в бд
		$query = $this->getConnectionCon()->query("SELECT token FROM tokens");
		//Получаем одну запись
		$this->tokenUser = $query->fetch(PDO::FETCH_OBJ);
		//Возращаем токен
		return $this->tokenUser->token;
	}
}

