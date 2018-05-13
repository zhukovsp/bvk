<?php
//sleep(1);
//ini_set('display_errors',1);
//error_reporting(E_ALL);
//file_put_contents( "debug_update_status111222.txt", print_r($formtype, true) . PHP_EOL , FILE_APPEND);
if ($formtype != 'subscribe'){
			if ($formtype == 'pay_by_deals') {
					function CheckCurlResponse($code){
					    $code = (int)$code;
					    $errors = array(
					        301 => 'Moved permanently',
					        400 => 'Bad request',
					        401 => 'Unauthorized',
					        403 => 'Forbidden',
					        404 => 'Not found',
					        500 => 'Internal server error',
					        502 => 'Bad gateway',
					        503 => 'Service unavailable'
					    );
					    try {
					        if ($code != 200 && $code != 204)
					            throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Undescribed error', $code);
					    } catch (Exception $E) {
					        die('Ошибка: ' . $E->getMessage() . PHP_EOL . 'Код ошибки: ' . $E->getCode());
					    }
					}

					$user = array(
					    'USER_LOGIN' => 'nikokislov@gmail.com',
					    'USER_HASH' => '3c2e45e9c588b04daac074f55e01bb94'
					);

					$subdomain = 'crmswimrocket';

					$link = 'https://' . $subdomain . '.amocrm.ru/private/api/auth.php?type=json';
					$curl = curl_init();

					curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($curl, CURLOPT_USERAGENT, 'amoCRM-API-client/1.0');
					curl_setopt($curl, CURLOPT_URL, $link);
					curl_setopt($curl, CURLOPT_POST, true);
					curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($user));
					curl_setopt($curl, CURLOPT_HEADER, false);
					curl_setopt($curl, CURLOPT_COOKIEFILE, dirname(__FILE__) . '/cookie.txt');
					curl_setopt($curl, CURLOPT_COOKIEJAR, dirname(__FILE__) . '/cookie.txt');
					curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
					curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

					$out = curl_exec($curl);
					$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
					curl_close($curl);
					CheckCurlResponse($code);

					$Response = json_decode($out, true);
					$Response = $Response['response'];
					if (isset($Response['auth'])) {
					//        echo 'auth ok <br/>';
					} else {
					//        echo 'auth fail  <br/>';
					}
					$deals_id = explode(",", $deals);
					$data_search = $invoice_id;
					//file_put_contents( "debug_update_status2.txt", print_r($deals_id, true) . PHP_EOL , FILE_APPEND);
					foreach($deals_id as $valuess ){

							//апдейтим сделку
							$leads=array(
						  	'id'=>$valuess,
								'last_modified' => time(),
								'custom_fields'=>array(
									array(
										'id'=>552893,//data search
										'values'=>array(
											array(
												'value'=>"time_set_leads_".$data_search,
											)
										)
									),
								),
							);

							$set['request']['leads']['update'][]=$leads;

							#Формируем ссылку для запроса
							$link='https://'.$subdomain.'.amocrm.ru/private/api/v2/json/leads/set';
							$curl=curl_init(); #Сохраняем дескриптор сеанса cURL
							#Устанавливаем необходимые опции для сеанса cURL
							curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
							curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
							curl_setopt($curl,CURLOPT_URL,$link);
							curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');
							curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($set));
							curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
							curl_setopt($curl,CURLOPT_HEADER,false);
							curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
							curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
							curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
							curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);

							$out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
							$code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
							curl_close($curl);
							CheckCurlResponse($code);
							$Response_leads_upd = json_decode($out, true);
							unset($set);
							//file_put_contents( "debug_update_status2.txt", print_r($Response_leads_upd, true) . PHP_EOL , FILE_APPEND);
					}
			} else {
					function CheckCurlResponse($code){
			        $code = (int)$code;
			        $errors = array(
			            301 => 'Moved permanently',
			            400 => 'Bad request',
			            401 => 'Unauthorized',
			            403 => 'Forbidden',
			            404 => 'Not found',
			            500 => 'Internal server error',
			            502 => 'Bad gateway',
			            503 => 'Service unavailable'
			        );
			        try {
			            if ($code != 200 && $code != 204)
			                throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Undescribed error', $code);
			        } catch (Exception $E) {
			            die('Ошибка: ' . $E->getMessage() . PHP_EOL . 'Код ошибки: ' . $E->getCode());
			        }
			    }

			    $user = array(
			        'USER_LOGIN' => 'nikokislov@gmail.com',
			        'USER_HASH' => '3c2e45e9c588b04daac074f55e01bb94'
			    );

			    $subdomain = 'crmswimrocket';

			    $link = 'https://' . $subdomain . '.amocrm.ru/private/api/auth.php?type=json';
			    $curl = curl_init();

			    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			    curl_setopt($curl, CURLOPT_USERAGENT, 'amoCRM-API-client/1.0');
			    curl_setopt($curl, CURLOPT_URL, $link);
			    curl_setopt($curl, CURLOPT_POST, true);
			    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($user));
			    curl_setopt($curl, CURLOPT_HEADER, false);
			    curl_setopt($curl, CURLOPT_COOKIEFILE, dirname(__FILE__) . '/cookie.txt');
			    curl_setopt($curl, CURLOPT_COOKIEJAR, dirname(__FILE__) . '/cookie.txt');
			    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
			    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

			    $out = curl_exec($curl);
			    $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			    curl_close($curl);
			    CheckCurlResponse($code);

			    $Response = json_decode($out, true);
			    $Response = $Response['response'];
			    if (isset($Response['auth'])) {
			//        echo 'auth ok <br/>';
			    } else {
			//        echo 'auth fail  <br/>';
			    }
//переменные
	$data_search = "";
	$utmsource = "";
	$utmmedium = "";
	$utmterm = "";
	$utmcontent = "";
	$utmcamp = "";
	$fio = "";
	$sourceph = "";
	$email = "";
	$type = "";
	$leadname = "";
	$site = "";
	$strQstn = "";
	$utmsource = $_COOKIE['utm_source'];
	$utmmedium = $_COOKIE['utm_medium'];
	$utmterm = $_COOKIE['utm_term'];
	$utmcontent = $_COOKIE['utm_content'];
	$utmcamp = $_COOKIE['utm_campaign'];
	if ($utmsource=="") {
		$utmsource = "ND";
	}
	if ($utmterm=="") {
		$utmterm="ND";
	}
	if ($utmcontent=="") {
		$utmcontent="ND";
	}
	if ($utmcamp=="") {
		$utmcamp="ND";
	}
	if ($utmmedium=="") {
		$utmmedium="ND";
	}
	$keyword = $amo_tag;
	$leadname = $subject;
	$vc = $sum;
	$position = str_replace("&quot;" , " ", $desc);
	//$type = $service_id;
	$email = $mail;
	$sourceph = $phone;
	//$site = $products; // массив
	$site = $product;
	$site_qty = $product_qty;
	//$data_search = file_get_contents("amocrm/data_post.txt");
	$data_search = $invoice_id;
	//file_put_contents( "debug_update_status111.txt", print_r($data_search, true) . PHP_EOL , FILE_APPEND);
			if ($birthday != '')
			{
			$birthday1 = explode(".", $birthday);
			$birthday_print = $birthday1[2].'/'.$birthday1[0].'/'.$birthday1[1];
			}
			$leadtype = $amo_status_id; //входящие
			if ($name == '')
		{
			$fio = 'Клиент';
		}
			else
		{
				$fio = $name;
		}
		if ($promocode != ''){$strQstn = 'Использован промокод: '.$promocode;}
			if ( $giftcertificate == 'gift' ) {
		$strQstn = 'Получатель: '.$fio_friend.'. Пожелание: '.$txt_wishes;
		}

// ищем контакт
// поработаем с тлф
		$sourceph = preg_replace('~\D+~','',$sourceph);// убрали все, кроме цифр
			if(mb_strlen($sourceph) == 11) {// в начале или 7 или 8
				$toDelete = 1; // сколько знаков надо убрать от начала строки
				mb_internal_encoding("UTF-8");
				$sourceph = mb_substr( $sourceph, $toDelete);
			}
		$link = 'https://' . $subdomain . '.amocrm.ru/private/api/v2/json/contacts/list?query=' . $sourceph;

		$curl = curl_init();

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_USERAGENT, 'amoCRM-API-client/1.0');
        curl_setopt($curl, CURLOPT_URL, $link);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_COOKIEFILE, dirname(__FILE__) . '/cookie.txt');
        curl_setopt($curl, CURLOPT_COOKIEJAR, dirname(__FILE__) . '/cookie.txt');
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

        $out = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        CheckCurlResponse($code);

        $Response_contact = json_decode($out, true);
		//file_put_contents( "debug_update_status10.txt", print_r($Response_contact, true) . PHP_EOL , FILE_APPEND);
if ($Response_contact == '') // контакт не найден
{
$manager_tasks = $amo_new_deal_task_userid;
$manager_leads = $amo_new_deal_task_userid;
}
else
{
	if ($amo_new_contact_userid == $amo_new_deal_task_userid)
	{
$manager_tasks = $Response_contact['response']['contacts'][0]['responsible_user_id'];
$manager_leads = $Response_contact['response']['contacts'][0]['responsible_user_id'];
	}
	else
	{
$manager_tasks = $amo_new_deal_task_userid;
$manager_leads = $amo_new_deal_task_userid;
	}
}
// добавим сделку
$leads['request']['leads']['add'][]=array(
				'name'=>$leadname,
				'price' => $vc,
                'status_id'=>$leadtype,
                'responsible_user_id'=>$manager_leads,
				'tags'=>$keyword,
                'custom_fields'=>array(
						array(
								'id'=>547183,
								'values'=>array(
									array(
										'value'=>$utmsource,
									)
								)
							),
						array(
								'id'=>547187,
								'values'=>array(
									array(
										'value'=>$utmmedium,
									)
								)
							),
						array(
								'id'=>547185,
								'values'=>array(
									array(
										'value'=>$utmcamp,
									)
								)
							),
						array(
								'id'=>547189,
								'values'=>array(
									array(
										'value'=>$utmterm,
									)
								)
							),
							array(
								'id'=>547191,
								'values'=>array(
									array(
										'value'=>$utmcontent,
									)
								)
							),
							array(
								'id'=>551837,//группа
								'values'=>array(
									array(
										'value'=>$position,
									)
								)
							),
							array(
								'id'=>547927,//кол-во
								'values'=>array(
									array(
										'value'=>$site_qty,
									)
								)
							),
							array(
								'id'=>550533,//услуга - список
								'values'=>array(
									array(
										'value'=>$site,
									)
								)
							),
							// array(
							// 	'id'=>548001,//услуга - мультисписок
							// 	'values'=>$site,
							// ),
							array(
								'id'=>552893,//data search
								'values'=>array(
									array(
										'value'=>"time_set_leads_".$data_search,
									)
								)
							),
						),
            );
//file_put_contents( "debug_update_status7.txt", print_r($leads, true) . PHP_EOL , FILE_APPEND);
$link='https://'.$subdomain.'.amocrm.ru/private/api/v2/json/leads/set';
$curl=curl_init(); #Сохраняем дескриптор сеанса cURL
curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
curl_setopt($curl,CURLOPT_URL,$link);
curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');
curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($leads));
curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
curl_setopt($curl,CURLOPT_HEADER,false);
curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
$out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
$code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
curl_close($curl);
CheckCurlResponse($code);
$Response_sdelka = json_decode($out, true);
//file_put_contents( "debug_update_status8.txt", print_r($Response_sdelka, true) . PHP_EOL , FILE_APPEND);
$leads_id = $Response_sdelka['response']['leads']['add'][0]['id'];
$data_search = '';
//file_put_contents( "amocrm/data_post.txt", '0');
// добавим задачу
$tasktime = time()+3600; //+1 час
				$arrtask['request']['tasks']['add'][]=array(
						'element_id' => $leads_id, # id сделки
						'responsible_user_id' => $manager_tasks,
						'element_type' => 2, # 3 значит, что в element_id - компания 2 - сделка
						'task_type' => 1, // контроль оплаты
						'text' => 'Обработать обращение с сайта!',
						'complete_till' => $tasktime,
				);

$link='https://'.$subdomain.'.amocrm.ru/private/api/v2/json/tasks/set';
$curl=curl_init(); #Сохраняем дескриптор сеанса cURL
curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
curl_setopt($curl,CURLOPT_URL,$link);
curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');
curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($arrtask));
curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
curl_setopt($curl,CURLOPT_HEADER,false);
curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
$out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
$code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
curl_close($curl);
CheckCurlResponse($code);
$Response_tasks = json_decode($out, true);
// добавим примечание
$notes['request']['notes']['add']=array(
 array(
    'element_id'=>$leads_id,
    'element_type'=>2,
    'note_type'=>4,
	'text'=>$strQstn,
  ),
);
$link='https://'.$subdomain.'.amocrm.ru/private/api/v2/json/notes/set';
$curl=curl_init(); #Сохраняем дескриптор сеанса cURL
#Устанавливаем необходимые опции для сеанса cURL
curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
curl_setopt($curl,CURLOPT_URL,$link);
curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');
curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($notes));
curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
curl_setopt($curl,CURLOPT_HEADER,false);
curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
$out = curl_exec($curl);
$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);
CheckCurlResponse($code);
$Response = json_decode($out, true);
//file_put_contents( "debug_update_status9.txt", print_r($notes, true) . PHP_EOL , FILE_APPEND);

		if ($Response_contact == '') // контакт не найден
{
// добавим контакт
// поработаем с тлф
		$sourceph = preg_replace('~\D+~','',$sourceph);// убрали все, кроме цифр
			if(mb_strlen($sourceph) == 11) {// в начале или 7 или 8
				$toDelete = 1; // сколько знаков надо убрать от начала строки
				mb_internal_encoding("UTF-8");
				$sourceph = mb_substr( $sourceph, $toDelete);
			}
$sourceph = '+7'.$sourceph;
//file_put_contents( "debug_update_status8.txt", print_r($sourceph, true) . PHP_EOL , FILE_APPEND);
$contact['request']['contacts']['add'][]=array(
	  'name'=> $fio,
	  'linked_leads_id'=>array($leads_id,),
	  'last_modified' => time(),
	  'responsible_user_id'=>$amo_new_contact_userid,
	  'custom_fields'=>array(
							array(
									'id'=>508257,
									'values'=>array(
										array(
											'value'=>$sourceph,
											'enum'=>'MOB',
										)
									)
								),
							array(
									'id'=>508259,
									'values'=>array(
										array(
											'value'=>$email,
											'enum'=>'WORK',
										)
									)
								),
								array(
									'id'=>508395,
									'values'=>array(
										array(
											'value'=>$birthday_print,
										)
									)
								),
							)
	);

	#Формируем ссылку для запроса
	$link='https://'.$subdomain.'.amocrm.ru/private/api/v2/json/contacts/set';
	$curl=curl_init(); #Сохраняем дескриптор сеанса cURL
	#Устанавливаем необходимые опции для сеанса cURL
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
	curl_setopt($curl,CURLOPT_URL,$link);
	curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');
	curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($contact));
	curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
	curl_setopt($curl,CURLOPT_HEADER,false);
	curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
	curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
	curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);

	$out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
	$code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
	curl_close($curl);
CheckCurlResponse($code);
$Response_contact2 = json_decode($out, true);
}
else // контакт найден
{
$contact_id=$Response_contact['response']['contacts'][0]['id'];
$leads_id11=$Response_contact['response']['contacts'][0]['linked_leads_id'];
//$leads_id111 = implode(",", $leads_id11);
$leads_id11[count($leads_id11)] = $leads_id;
//$leads_id[count($leads_id)] = $leads_id11;
//$leads_id_array=array_push($leads_id, $leads_id11);
$contact=array(
	  	'id'=>$contact_id,
		'linked_leads_id' => $leads_id11,
		'last_modified' => time()
	);

$set['request']['contacts']['update'][]=$contact;

	#Формируем ссылку для запроса
	$link='https://'.$subdomain.'.amocrm.ru/private/api/v2/json/contacts/set';
	$curl=curl_init(); #Сохраняем дескриптор сеанса cURL
	#Устанавливаем необходимые опции для сеанса cURL
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
	curl_setopt($curl,CURLOPT_URL,$link);
	curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');
	curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($set));
	curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
	curl_setopt($curl,CURLOPT_HEADER,false);
	curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
	curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
	curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);

	$out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
	$code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
	curl_close($curl);
CheckCurlResponse($code);
$Response_contact3 = json_decode($out, true);
}
}
}
