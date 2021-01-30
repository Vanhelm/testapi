<?
include_once('data/Connection.php');

class Pay
{
	//Ошибки
	public $errors = [];
	// Данные из пост
	public $data = [];
	//для подключения к бд
	private $con;
	private $conn;
	//Для обработки платежа данные
	private $amount;

	function __construct()
	{
		$this->conn = new Connection;
		$this->con = $this->conn->getConnectionCon();

	}
	//Алгоритм Луна
	private function moon(int $number)
	{
		//переворачиваем и убираем буквы
		$number = strrev(preg_replace('/[^\d]/','',$number));
		//изначально сумма 0
		$sum = 0;
		for ($i = 0, $j = strlen($number); $i < $j; $i++){
        // использовать четные цифры как есть
			if (($i % 2) == 0){
				$val = $number[$i];
			}else{
            // удвоить нечетные цифры и вычесть 9, если они больше 9
				$val = $number[$i] * 2;
				if ($val > 9){
					$val -= 9;
				}
			}
			//обновляем сумму
			$sum += $val;
		}

    // число корректно, если сумма кратна 10
		if(($sum % 10) == 0){
			return true;
		}else{
			return false;
		}
	}
	//Метод за валидацию карты
	public function validate($post)
	{
		// Формирование ключей
		$keys = ['card', 'mouth', 'year', 'ccv'];
		// Перебор на пустые элементы
		foreach ($keys as $key) {
			if(trim($post[$key]) !== ''){
				$this->data[$key] = $post[$key];
			}else{
				$this->errors[$key] = "Все поля должны быть заполнены";
			}
		}
		// Если нет ошибок проверяем на алгорит луна заполнение карты
		if(!$this->errors){
			if(!$this->moon($this->data['card'])){
				$this->errors['card'] = "ошибка в заполнении номера карты";
			}
		}
		// Если нет ошибок после прошлого пункта проверка месяца
		if(!$this->errors){
			if($this->data['mouth'] < 1 OR $this->data['mouth'] > 12){
				$this->errors['mouth'] = "Введите корректно месяц от 1 до 12";
			}
		}
		// Если нет ошибок после прошлого пункта проверка года
		if(!$this->errors){
			if($this->data['year'] < 2021){
				$this->errors['mouth'] = "Введите корректно год от 2021";
			}
		}
		// Если нет ошибок после прошлого пункта проверка ccv
		if(!$this->errors){
			if($this->data['ccv'] < 100 OR $this->data['ccv'] > 999){
				$this->errors['ccv'] = "ccv состоит из 3-ех цифр";
			}
		}
		// Если обновляем данные о платеже. И редирект на уведомление о платеже
		if(!$this->errors){
			$stmt = $this->con->prepare("UPDATE pay SET status = 1 WHERE session=:session");
			$stmt->bindParam(':session', $post['session']);
			$stmt->execute();
			header('location: /succes.php?succes=true');
		}
	}

	public function getAmount($session)
	{
		$stmt = $this->con->prepare("SELECT * FROM pay WHERE session= :session");
		$stmt->bindParam(':session', $session);
		$stmt->execute();
		$this->amount = $stmt->fetch(PDO::FETCH_OBJ);
		$this->checkTime($this->amount->lifetime, $session);
		return $this->amount;
	}

	private function checkTime($time)
	{
		//Переводим время из БД в timestamp
		$currentDate = strtotime($time);
		//Добавляем 5 минут 
		$futureDate = $currentDate+(60*5);
		//Возращаем обратно формат
		$formatDate = date("Y-m-d H:i:s", $futureDate);
		//Приводим все к одному типу, и сравниваем
		if(date("Y-m-d H:i:s", time()) > $formatDate){
			//Можно написать удаление из базы, но так как нужна статистика не стал
			//Если запись появилась больше, чем 300 секунд назад(5 минут), то обнулить переменную
			//amount
			return $this->amount = null;
		}
	}
}


$pay = new Pay();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
	$pay->validate($_POST);
}

if($_GET['session']){
	$price = $pay->getAmount($_GET['session']);
}	
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Оплата</title>
	<link rel="stylesheet" href="https://bootstraptema.ru/plugins/2015/bootstrap3/bootstrap.min.css" />

	<style>
		.credit-card-div span {
			padding-top:10px;
		}
		.credit-card-div img {
			padding-top:30px;
		}
		.credit-card-div .small-font {
			font-size:9px;
		}
		.credit-card-div .pad-adjust {
			padding-top:10px;
		}
	</style>
</head>
<body>
	<div class="container">	
		<div class="row ">
			<div class="col-md-4 col-md-offset-4">
				<?php if ($price): ?>
					<h1 class="h1">Товар: <?=$price->name?></h1>
					<h2 class="h1">Цена: <?=$price->pay?></h2>
					<form class="credit-card-div" method="post" action="/pay.php?session=<?=$price->session?>">
						<?php if (!empty($pay->errors)) : ?>
							<?php foreach ($pay->errors as $value): ?>
								<p class="text-danger"><?=$value?></p>
							<?php endforeach ?>
						<?php endif ?>
						<div class="panel panel-default" >
							<div class="panel-heading">
								<input type="text" hidden value="<?=$_GET['session']?>" name="session">
								<div class="row ">
									<div class="col-md-12">
										<input type="text" class="form-control" placeholder="Введите номер карты" name="card" value="<?=$pay->data['card']?>" onkeyup="this.value = this.value.replace(/[^\d]/g,'');"/>
									</div>
								</div>

								<div class="row ">
									<div class="col-md-3 col-sm-3 col-xs-3">
										<span class="help-block text-muted small-font" > Месяц</span>
										<input type="text" class="form-control" placeholder="MM" name="mouth" value="<?=$pay->data['mouth']?>"  onkeyup="this.value = this.value.replace(/[^\d]/g,'');"/>
									</div>
									<div class="col-md-3 col-sm-3 col-xs-3">
										<span class="help-block text-muted small-font" > Год</span>
										<input type="text" class="form-control" placeholder="YY" name="year" value="<?=$pay->data['year']?>" onkeyup="this.value = this.value.replace(/[^\d]/g,'');"/>
									</div>
									<div class="col-md-3 col-sm-3 col-xs-3">
										<span class="help-block text-muted small-font" > CCV</span>
										<input type="text" class="form-control" placeholder="CCV" name="ccv" value="<?=$pay->data['ccv']?>" onkeyup="this.value = this.value.replace(/[^\d]/g,'');"/>
									</div>
									<div class="col-md-3 col-sm-3 col-xs-3">
										<img src="https://bootstraptema.ru/snippets/form/2016/form-card/card.png" class="img-rounded" />
									</div>
								</div>

								<div class="row ">
									<div class="col-md-6 col-sm-6 col-xs-6 pad-adjust">
										<input type="submit" class="btn btn-danger" value="Отмена" />
									</div>
									<div class="col-md-6 col-sm-6 col-xs-6 pad-adjust">
										<input type="submit" class="btn btn-success btn-block" value="Оплатить" />
									</div>
								</div>

							</div>
						</div>
					</form><!-- ./credit-card-div -->
					<?else : ?>
					<p class="h2 text-danger text-center">Время жизни страницы истекло или токен неверный</p>
					<a href="/" class="btn btn-danger btn-block">На главную</a>
				<? endif ?>


			</div> 
		</div>
	</div><!-- /.container -->
</body>
</html>


