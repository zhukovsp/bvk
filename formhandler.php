<?php
//Настрoйка oтправки
$to_name = $_SERVER['HTTP_HOST']; //Отправитель
$to      = 'zhukov.sp@gmail.com'; //Пoлучатель
$headers = "From: \"$to_name\" <info@".$_SERVER['HTTP_HOST'].">\n";
$headers .= "Content-type: text/plain; charset=\"utf-8\"";
///////

if ($_POST){
	$message = '';
	if ($_POST['name']!=NULL) $message .= "\r\n Name: ".$_POST['name'];
	if ($_POST['phone']!=NULL) $message .= "\r\n Phone: ".$_POST['phone'];

	$subject = 'Запись на тренировку в Крылатское';
	mail($to, $subject, $message, $headers);
	echo "Спасибо. Ваша заявка принята";
}
else{
	header("Location: /");
}
?>
