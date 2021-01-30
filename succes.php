<?
//Проверяем откуда получены данные для формирования url curl
if ($_GET['stat'] === 'url'){
	$url = "http://admin/register.php?stat=url";
}
if($_GET['filter1'] AND $_GET['filter2']){
	$url = "http://admin/register.php?filter1=".$_GET['filter1']."&filter2=".$_GET['filter2'];
}

//Если get существует получаем данные из curl
if(isset($_GET)){
	//  Запускаем curl

	$ch = curl_init();
// Вернет ответ, если false, напечатает ответ
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Устанавливаем url
	curl_setopt($ch, CURLOPT_URL,$url);
// Выполняем
	$result=curl_exec($ch);
// закрываем
	curl_close($ch);

// раскадируем json
	$html = json_decode($result);
}




?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Статистика</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
	<?php if ($_GET['succes'] === 'true'): ?>
		<h3 class="text-center text-success">Платеж прошёл успешно</h3>
	<?php endif ?>

	<?php if ($_GET['stat'] === 'url' OR ($_GET['filter1'] AND $_GET['filter2'])): ?>
		<div class="container d-flex flex-column align-items-center">
			<form action="/succes.php?stat=url" class="mt-3 d-flex flex-column align-items-center">
				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text" id="basic-addon1">1 период</span>
					</div>
					<input type="date" class="form-control" name='filter1' value="<?=$_GET['filter1']?>"  aria-label="Username" aria-describedby="basic-addon1" required>
				</div>
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text" id="basic-addon1">2 период</span>
					</div>
					<input type="date" class="form-control" name='filter2' value="<?=$_GET['filter2']?>" aria-label="Username" aria-describedby="basic-addon1" required>
				</div>
				<button type="submit" class="btn btn-primary mt-3 mb-3">Применить фильтр</button>
			</form>
			<a href="/succes.php?stat=url" class="btn btn-primary mt-3 mb-3">Сбросить фильтр</a>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Стоимость</th>
						<th>Наименование</th>
						<th>Оплачен</th>
						<th>URl</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($html as  $value): ?>
						<tr>
							<th><?=htmlspecialchars($value->pay)?></th>
							<th><?=htmlspecialchars($value->name)?></th>
							<th>
								<?php if ((int)$value->status === 1): ?>
									<p class="text-success">ДА</p
										<?php else: ?>
											<p class="text-danger">НЕТ</p
											<?php endif ?>
										</th>
										<td><a class="" href="<?='/pay.php?session='.$value->session?>">Попробовать оплатить</a></td>
									</tr>
								<?php endforeach ?>
							</tbody>
						</table>



					</div>	
				<?php endif ?>

				<div class="container d-flex justify-content-center mt-4">
					<a href="/" class="btn btn-primary">На главную</a>
				</div>

			</body>
			</html>