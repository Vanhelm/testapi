<?php

//Обхявляем переменную результ. Она отвечает за отображение. Если она пустая выводится ошибка
$result = null;
//Запускать curl если forma была отправлена. Здесь необходимо доработать безопасность. При передачи параметров
if($_SERVER['REQUEST_METHOD'] === 'POST'){
	//Переменные 
	$name = $_POST['name'];
	$price = $_POST['price'];
	//Засовываем в массив
	$data = ["name" => $name, "price" => $price];
	//Делаем json из массива
	$data_string = json_encode ($data, JSON_UNESCAPED_UNICODE);
	//Инициализируем curl
	$curl = curl_init('http://admin/register.php');
	//Метод передачи POST
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
	//Передаем даанные
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
	// для возврата результата передачи в качестве строки из curl_exec() вместо прямого вывода в браузе
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	//Устанавливаем заголовки
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Content-Length: ' . strlen($data_string))
);
	//Выполняем запрос
	$result = curl_exec($curl);
	//Закрываем подключение curl
	curl_close($curl);
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Сформировать ссылку</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link rel="stylesheet" href="/css/style.css">
</head>
<body>
	<form class="form-signin" method="post" action="/format.php">
		<div class="text-center mb-4">
			<h1 class="h3 mb-3 font-weight-normal">Сформировать ссылку</h1>
			<p>Создай свою уникальную ссылку оплаты, перейди по ней для оплаты <code>не забудь оплатить</code></p>
		</div>

		<div class="form-label-group">
			<input type="text" id="inputEmail" name="name" class="form-control" placeholder="Email address" required autofocus>
			<label for="inputEmail">Наименование товара</label>
		</div>

		<div class="form-label-group">
			<input type="text" id="inputPassword" name="price" class="form-control" placeholder="Password" required>
			<label for="inputPassword">Цена товара</label>
		</div>
		<button class="btn btn-lg btn-primary btn-block" type="submit">Создать</button>
		<p class="mt-5 mb-3 text-muted text-center">&copy; 2021</p>
		<a href="<?=$result?>" class="h3 justify-content-center d-flex text-success" target="_blank"><?if($result) :?>Перейти к оплате <?endif?></a>
	</form>
</body>
</html>