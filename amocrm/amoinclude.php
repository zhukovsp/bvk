<?php
// функции в данном файле
// fncAmocrmAuth авторизация в АМО
// fncAmocrmGetAllUsers получает массив 
// fncAmocrmCompanSet - создание компании
// fncAmocrmCompanyList - получает список компаний по запросу
// fncAmocrmContactsSet - создание и обновление контакат
// fncAmocrmContactsGet - получает связь между контактами и сделками
// fncAmocrmContactsList - список контактов по запросу
// fncAmocrmContactsListByResponsibleID - список контактов ответственного пользователя
// fncAmocrmTasksCreate - создание задачи
// fncAmocrmLeadsCreate - создание сделки
// fncAmocrmNotesCreate - создание примечания
// fncAmocrmContactsListById - получает контакт по id
// fncAmocrmLeadsUpdate - Update сделки
// авторизация в АМО
function fncAmocrmAuth($strLogin, $strSubdomain, $strApiKey, $strCookieFileName) {

    # почти copy-paste из документации (((

    #Массив с параметрами, которые нужно передать методом POST к API системы
    $user=array(
      'USER_LOGIN'=>$strLogin, #Ваш логин (электронная почта)
      'USER_HASH'=>$strApiKey #Хэш для доступа к API (смотрите в профиле пользователя)
    );

    $subdomain=$strSubdomain; #Наш аккаунт - поддомен

    #Формируем ссылку для запроса
    $link='https://'.$subdomain.'.amocrm.ru/private/api/auth.php?type=json';

    $curl=curl_init(); #Сохраняем дескриптор сеанса cURL
    #Устанавливаем необходимые опции для сеанса cURL
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client');
    curl_setopt($curl,CURLOPT_URL,$link);
    curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');
    curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($user));
    curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
    curl_setopt($curl,CURLOPT_HEADER,false);
    curl_setopt($curl,CURLOPT_COOKIEFILE, $strCookieFileName); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
    curl_setopt($curl,CURLOPT_COOKIEJAR, $strCookieFileName); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
    curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
     
    $out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
    $code=curl_getinfo($curl,CURLINFO_HTTP_CODE); #Получим HTTP-код ответа сервера
    curl_close($curl); #Завершаем сеанс cURL

    $code=(int)$code;
    $errors=array(
      301=>'Moved permanently',
      400=>'Bad request',
      401=>'Unauthorized',
      403=>'Forbidden',
      404=>'Not found',
      500=>'Internal server error',
      502=>'Bad gateway',
      503=>'Service unavailable'
    );
    try
    {
      #Если код ответа не равен 200 или 204 - возвращаем сообщение об ошибке
      if($code!=200 && $code!=204)
        return array (
          'boolOk' => FALSE,
          'strErrDevelopUtf8' => 'AmoCRM error: ' . (isset($errors[$code]) ? $errors[$code] : 'Undescribed error ' . $code),
        );
    }
    catch(Exception $E)
    {
        return array (
          'boolOk' => FALSE,
          'strErrDevelopUtf8' => 'AmoCRM error: ' . $E->getMessage().PHP_EOL.'Код ошибки: '.$E->getCode(),
        );
    }
     
    /**
     * Данные получаем в формате JSON, поэтому, для получения читаемых данных,
     * нам придётся перевести ответ в формат, понятный PHP
     */
    $Response=json_decode($out,true);

    $Response=$Response['response'];
    if(isset($Response['auth'])) #Флаг авторизации доступен в свойстве "auth"
        return array (
          'boolOk' => TRUE,
        );
    return array (
      'boolOk' => FALSE,
      'strErrDevelopUtf8' => 'AmoCRM error: ' . 'Авторизация не удалась',
    );

    # ))) почти copy-paste из документации

} # function регистрации

//GetAllUsers - получает массив пользователей из аккаунта АМО
	function fncAmocrmGetAllUsers(
		$strSubdomain,
		$strCookieFileName		
	) {
		$subdomain=$strSubdomain; #Наш аккаунт - поддомен
		#Формируем ссылку для запроса
		$link='https://'.$subdomain.'.amocrm.ru/private/api/v2/json/accounts/current';
		$curl=curl_init(); #Сохраняем дескриптор сеанса cURL
		#Устанавливаем необходимые опции для сеанса cURL
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
		curl_setopt($curl,CURLOPT_URL,$link);
		curl_setopt($curl,CURLOPT_HEADER,false);
		curl_setopt($curl,CURLOPT_COOKIEFILE, $strCookieFileName); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
		curl_setopt($curl,CURLOPT_COOKIEJAR, $strCookieFileName); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
		curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
		curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
 
		$out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
		$code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
		curl_close($curl);
		$code=(int)$code;
		$errors=array(
			301=>'Moved permanently',
			400=>'Bad request',
			401=>'Unauthorized',
			403=>'Forbidden',
			404=>'Not found',
			500=>'Internal server error',
			502=>'Bad gateway',
			503=>'Service unavailable'
		);
		try
		{
			#Если код ответа не равен 200 или 204 - возвращаем сообщение об ошибке
		if($code!=200 && $code!=204)
		throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Undescribed error',$code);
		}
		catch(Exception $E)
		{
			//die('Ошибка: '.$E->getMessage().PHP_EOL.'Код ошибки: '.$E->getCode());
			return array (
				'boolOk' => False,
				'arrResponse' => "Error",
			);
		}
 
		/**
		* Данные получаем в формате JSON, поэтому, для получения читаемых данных,
		* нам придётся перевести ответ в формат, понятный PHP
		*/
		$Response=json_decode($out,true);
		$Response=$Response['response']; #Response - объект класса StdClass			
		return array (
			'boolOk' => TRUE,
			'arrResponse' => $Response['account']['users'],
		);
	}
	//GetAllUsers - конец
function fncAmocrmGetNextUserFromGroup($strSubdomain,$strCookieFileName, $datafile, $groupid) {
		$amousers = fncAmocrmGetAllUsers($strSubdomain,	$strCookieFileName);		
		//$prevuser = @file_get_contents($_SERVER['DOCUMENT_ROOT'].'/files/amocusers1.txt');
		$prevuser = @file_get_contents($datafile);
		$i=0;
		$flag="";
		$previosid = "";				
		$ruserid = "";					
		$arrusers = $amousers['arrResponse'];
		$arrgroupusers = array();
		foreach($arrusers as $nodeuser) {
			$groups = "".$nodeuser['group_id'];
			if ($groups!="") {
				if ($groupid == $groups)  {										
					array_push($arrgroupusers,$nodeuser["id"]);
				}				
			}						
			$i++;
		}
		if (count($arrgroupusers)==0) {			
			$ruserid = "";
		}
		if (count($arrgroupusers)==1) {			
			$ruserid = $arrgroupusers[0];
		}
		if (count($arrgroupusers)>1) {
			$flag1406 = "";
			for($x=0; $x<count($arrgroupusers); $x++) {
				if($prevuser==$arrgroupusers[$x] || empty($prevuser)) {
					$flag1406 = "1";
					if ($x==(count($arrgroupusers)-1)) {												
						if ($flag=="") {
							$prevuser=$arrgroupusers[0];
						}
					} else {						
						if ($flag=="") {
							$prevuser=$arrgroupusers[$x+1];
						}
						$flag="1";
					}
				}
			}		
			if($flag1406 == "1") {
				$ruserid = $prevuser;
			} else {
				$x = rand(0,count($arrgroupusers));
				$ruserid = $arrgroupusers[$x];
			}
			
			@file_put_contents($datafile,$ruserid);
		}		
		return $ruserid;
	}
	//fncAmocrmGetNextUserFromGroup - конец	
//Создание компании	
function fncAmocrmCompanSet(
    $strSubdomain,
    $strCookieFileName,
    $arrContactsSet,
    $addORupdate # 'add' или 'update'
) {

    # почти copy-paste из документации (((

    $contacts['request']['contacts'][$addORupdate] = $arrContactsSet;

    $subdomain=$strSubdomain; #Наш аккаунт - поддомен
    #Формируем ссылку для запроса
    $link='https://'.$subdomain.'.amocrm.ru/private/api/v2/json/company/set';

    $curl=curl_init(); #Сохраняем дескриптор сеанса cURL
    #Устанавливаем необходимые опции для сеанса cURL
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client');
    curl_setopt($curl,CURLOPT_URL,$link);
    curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');
    curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($contacts));
    curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
    curl_setopt($curl,CURLOPT_HEADER,false);
    curl_setopt($curl,CURLOPT_COOKIEFILE, $strCookieFileName); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
    curl_setopt($curl,CURLOPT_COOKIEJAR, $strCookieFileName); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
    curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
     
    $out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
    $code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
    
    $code=(int)$code;
    $errors=array(
      301=>'Moved permanently',
      400=>'Bad request',
      401=>'Unauthorized',
      403=>'Forbidden',
      404=>'Not found',
      500=>'Internal server error',
      502=>'Bad gateway',
      503=>'Service unavailable'
    );
    try
    {
      #Если код ответа не равен 200 или 204 - возвращаем сообщение об ошибке
      if($code!=200 && $code!=204)
        return array (
          'boolOk' => FALSE,
          'strErrDevelopUtf8' => 'AmoCRM error: ' . (isset($errors[$code]) ? $errors[$code] : 'Undescribed error ' . $code),
        );
    }
    catch(Exception $E)
    {
        return array (
          'boolOk' => FALSE,
          'strErrDevelopUtf8' => 'AmoCRM error: ' . $E->getMessage().PHP_EOL.'Код ошибки: '.$E->getCode(),
        );
    }
     
    /**
     * Данные получаем в формате JSON, поэтому, для получения читаемых данных,
     * нам придётся перевести ответ в формат, понятный PHP
     */
    $Response=json_decode($out,true);

    return array (
      'boolOk' => TRUE,
      'arrResponse' => $Response['response'],
    );

    # ))) почти copy-paste из документации

} # function создания компании
#-----------
function fncAmocrmContactsSet(
    $strSubdomain,
    $strCookieFileName,
    $arrContactsSet,
    $addORupdate # 'add' или 'update'
) {

    # почти copy-paste из документации (((

    $contacts['request']['contacts'][$addORupdate] = $arrContactsSet;

    $subdomain=$strSubdomain; #Наш аккаунт - поддомен
    #Формируем ссылку для запроса
    $link='https://'.$subdomain.'.amocrm.ru/private/api/v2/json/contacts/set';
    
    $curl=curl_init(); #Сохраняем дескриптор сеанса cURL
    #Устанавливаем необходимые опции для сеанса cURL
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client');
    curl_setopt($curl,CURLOPT_URL,$link);
    curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');
    curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($contacts));
    curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
    curl_setopt($curl,CURLOPT_HEADER,false);
    curl_setopt($curl,CURLOPT_COOKIEFILE, $strCookieFileName); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
    curl_setopt($curl,CURLOPT_COOKIEJAR, $strCookieFileName); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
    curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
     
    $out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
    $code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
    
    $code=(int)$code;
    $errors=array(
      301=>'Moved permanently',
      400=>'Bad request',
      401=>'Unauthorized',
      403=>'Forbidden',
      404=>'Not found',
      500=>'Internal server error',
      502=>'Bad gateway',
      503=>'Service unavailable'
    );
    try
    {
      #Если код ответа не равен 200 или 204 - возвращаем сообщение об ошибке
      if($code!=200 && $code!=204)
        return array (
          'boolOk' => FALSE,
          'strErrDevelopUtf8' => 'AmoCRM error: ' . (isset($errors[$code]) ? $errors[$code] : 'Undescribed error ' . $code),
        );
    }
    catch(Exception $E)
    {
        return array (
          'boolOk' => FALSE,
          'strErrDevelopUtf8' => 'AmoCRM error: ' . $E->getMessage().PHP_EOL.'Код ошибки: '.$E->getCode(),
        );
    }
     
    /**
     * Данные получаем в формате JSON, поэтому, для получения читаемых данных,
     * нам придётся перевести ответ в формат, понятный PHP
     */
    $Response=json_decode($out,true);

    return array (
      'boolOk' => TRUE,
      'arrResponse' => $Response['response'],
    );

    # ))) почти copy-paste из документации

} # function
//конец функции добавления контакта
//добавление задачи
function fncAmocrmTasksCreate(
    $strSubdomain,
    $strCookieFileName,
    $arrTasksCreate
) {

    # почти copy-paste из документации (((

    $tasks['request']['tasks']['add'] = $arrTasksCreate;

    $subdomain=$strSubdomain; #Наш аккаунт - поддомен
    #Формируем ссылку для запроса
    $link='https://'.$subdomain.'.amocrm.ru/private/api/v2/json/tasks/set';
    
    $curl=curl_init(); #Сохраняем дескриптор сеанса cURL
    #Устанавливаем необходимые опции для сеанса cURL
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client');
    curl_setopt($curl,CURLOPT_URL,$link);
    curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');
    curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($tasks));
    curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
    curl_setopt($curl,CURLOPT_HEADER,false);
    curl_setopt($curl,CURLOPT_COOKIEFILE, $strCookieFileName); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
    curl_setopt($curl,CURLOPT_COOKIEJAR, $strCookieFileName); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
    curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
     
    $out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
    $code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
    
    $code=(int)$code;
    $errors=array(
      301=>'Moved permanently',
      400=>'Bad request',
      401=>'Unauthorized',
      403=>'Forbidden',
      404=>'Not found',
      500=>'Internal server error',
      502=>'Bad gateway',
      503=>'Service unavailable'
    );
    try
    {
      #Если код ответа не равен 200 или 204 - возвращаем сообщение об ошибке
      if($code!=200 && $code!=204)
        return array (
          'boolOk' => FALSE,
          'strErrDevelopUtf8' => 'AmoCRM error: ' . (isset($errors[$code]) ? $errors[$code] : 'Undescribed error ' . $code),
        );
    }
    catch(Exception $E)
    {
        return array (
          'boolOk' => FALSE,
          'strErrDevelopUtf8' => 'AmoCRM error: ' . $E->getMessage().PHP_EOL.'Код ошибки: '.$E->getCode(),
        );
    }
     
    /**
     * Данные получаем в формате JSON, поэтому, для получения читаемых данных,
     * нам придётся перевести ответ в формат, понятный PHP
     */
    $Response=json_decode($out,true);

    return array (
      'boolOk' => TRUE,
      'arrResponse' => $Response['response'],
    );

    # ))) почти copy-paste из документации

} # function
//конец функции добавления задачи
//добавление сделки
#-----------
function fncAmocrmLeadsCreate(
    $strSubdomain,
    $strCookieFileName,
    $arrLeadsCreate
) {
	
	//if ($_COOKIE["_ga"]) {
	//	$ga=$_COOKIE["_ga"];
	//	$arGa=explode('.', $ga);
		//print_r($arGa);
	//	if ($arGa[2] && $arGa[3]) {
	//	$gaR=$arGa[2].".".$arGa[3];
	//			$arrLeadsCreate[0]["custom_fields"][] = array(
	//							'id'=>309602,
	//							'values'=>array(
	//								array(
	//									'value'=>$gaR,											
	//								)
	//							));
	//	}						
	//}
	//$arrLeadsCreate["trackingId"]= $_COOKIE["_ga"];
	//$arrLeadsCreate["clientId"]= "710169055.1462963313";

	//echo "<pre>";print_r($arrLeadsCreate);echo "</pre>"; exit;

    # почти copy-paste из документации (((	
    $leads['request']['leads']['add'] = $arrLeadsCreate;

    $subdomain=$strSubdomain; #Наш аккаунт - поддомен
    #Формируем ссылку для запроса
    $link='https://'.$subdomain.'.amocrm.ru/private/api/v2/json/leads/set';
    
    $curl=curl_init(); #Сохраняем дескриптор сеанса cURL
    #Устанавливаем необходимые опции для сеанса cURL
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client');
    curl_setopt($curl,CURLOPT_URL,$link);
    curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');
    curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($leads));
    curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
    curl_setopt($curl,CURLOPT_HEADER,false);
    curl_setopt($curl,CURLOPT_COOKIEFILE, $strCookieFileName); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
    curl_setopt($curl,CURLOPT_COOKIEJAR, $strCookieFileName); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
    curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
     
    $out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
    $code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
    
    $code=(int)$code;
    $errors=array(
      301=>'Moved permanently',
      400=>'Bad request',
      401=>'Unauthorized',
      403=>'Forbidden',
      404=>'Not found',
      500=>'Internal server error',
      502=>'Bad gateway',
      503=>'Service unavailable'
    );
    try
    {
      #Если код ответа не равен 200 или 204 - возвращаем сообщение об ошибке
      if($code!=200 && $code!=204)
        return array (
          'boolOk' => FALSE,
          'strErrDevelopUtf8' => 'AmoCRM error: ' . (isset($errors[$code]) ? $errors[$code] : 'Undescribed error ' . $code),
        );
    }
    catch(Exception $E)
    {
        return array (
          'boolOk' => FALSE,
          'strErrDevelopUtf8' => 'AmoCRM error: ' . $E->getMessage().PHP_EOL.'Код ошибки: '.$E->getCode(),
        );
    }
     
    /**
     * Данные получаем в формате JSON, поэтому, для получения читаемых данных,
     * нам придётся перевести ответ в формат, понятный PHP
     */
    $Response=json_decode($out,true);

    return array (
      'boolOk' => TRUE,
      'arrResponse' => $Response['response'],
    );

    # ))) почти copy-paste из документации

} # function добавление сделки

function fncAmocrmLeadsUpdate(
    $strSubdomain,
    $strCookieFileName,
    $arrLeadsCreate
) {	
    # почти copy-paste из документации (((	
    $leads['request']['leads']['update'] = $arrLeadsCreate;

    $subdomain=$strSubdomain; #Наш аккаунт - поддомен
    #Формируем ссылку для запроса
    $link='https://'.$subdomain.'.amocrm.ru/private/api/v2/json/leads/set';
    
    $curl=curl_init(); #Сохраняем дескриптор сеанса cURL
    #Устанавливаем необходимые опции для сеанса cURL
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client');
    curl_setopt($curl,CURLOPT_URL,$link);
    curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');
    curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($leads));
    curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
    curl_setopt($curl,CURLOPT_HEADER,false);
    curl_setopt($curl,CURLOPT_COOKIEFILE, $strCookieFileName); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
    curl_setopt($curl,CURLOPT_COOKIEJAR, $strCookieFileName); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
    curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
     
    $out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
    $code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
    
    $code=(int)$code;
    $errors=array(
      301=>'Moved permanently',
      400=>'Bad request',
      401=>'Unauthorized',
      403=>'Forbidden',
      404=>'Not found',
      500=>'Internal server error',
      502=>'Bad gateway',
      503=>'Service unavailable'
    );
    try
    {
      #Если код ответа не равен 200 или 204 - возвращаем сообщение об ошибке
      if($code!=200 && $code!=204)
        return array (
          'boolOk' => FALSE,
          'strErrDevelopUtf8' => 'AmoCRM error: ' . (isset($errors[$code]) ? $errors[$code] : 'Undescribed error ' . $code),
        );
    }
    catch(Exception $E)
    {
        return array (
          'boolOk' => FALSE,
          'strErrDevelopUtf8' => 'AmoCRM error: ' . $E->getMessage().PHP_EOL.'Код ошибки: '.$E->getCode(),
        );
    }
     
    /**
     * Данные получаем в формате JSON, поэтому, для получения читаемых данных,
     * нам придётся перевести ответ в формат, понятный PHP
     */
    $Response=json_decode($out,true);

    return array (
      'boolOk' => TRUE,
      'arrResponse' => $Response['response'],
    );

    # ))) почти copy-paste из документации

} # function добавление сделки

function fncAmocrmNotesCreate(
    $strSubdomain,
    $strCookieFileName,
    $arrNotesCreate
) {

    # почти copy-paste из документации (((

    $notes['request']['notes']['add']= $arrNotesCreate;

    $subdomain=$strSubdomain; #Наш аккаунт - поддомен
    #Формируем ссылку для запроса
    $link='https://'.$subdomain.'.amocrm.ru/private/api/v2/json/notes/set';
    
    $curl=curl_init(); #Сохраняем дескриптор сеанса cURL
    #Устанавливаем необходимые опции для сеанса cURL
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client');
    curl_setopt($curl,CURLOPT_URL,$link);
    curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');
    curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($notes));
    curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
    curl_setopt($curl,CURLOPT_HEADER,false);
    curl_setopt($curl,CURLOPT_COOKIEFILE, $strCookieFileName); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
    curl_setopt($curl,CURLOPT_COOKIEJAR, $strCookieFileName); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
    curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
     
    $out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
    $code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
    
    $code=(int)$code;
    $errors=array(
      301=>'Moved permanently',
      400=>'Bad request',
      401=>'Unauthorized',
      403=>'Forbidden',
      404=>'Not found',
      500=>'Internal server error',
      502=>'Bad gateway',
      503=>'Service unavailable'
    );
    try
    {
      #Если код ответа не равен 200 или 204 - возвращаем сообщение об ошибке
      if($code!=200 && $code!=204)
        return array (
          'boolOk' => FALSE,
          'strErrDevelopUtf8' => 'AmoCRM error: ' . (isset($errors[$code]) ? $errors[$code] : 'Undescribed error ' . $code),
        );
    }
    catch(Exception $E)
    {
        return array (
          'boolOk' => FALSE,
          'strErrDevelopUtf8' => 'AmoCRM error: ' . $E->getMessage().PHP_EOL.'Код ошибки: '.$E->getCode(),
        );
    }
     
    /**
     * Данные получаем в формате JSON, поэтому, для получения читаемых данных,
     * нам придётся перевести ответ в формат, понятный PHP
     */
    $Response=json_decode($out,true);

    return array (
      'boolOk' => TRUE,
      'arrResponse' => $Response['response'],
    );

    # ))) почти copy-paste из документации

} # function создать примечание

//Получает контакт по id контакта
function fncAmocrmContactsListById(
    $strSubdomain,
    $strCookieFileName,
    $query1512 = ''
) {

    # почти copy-paste из документации (((
	
    $subdomain=$strSubdomain; #Наш аккаунт - поддомен
    #Формируем ссылку для запроса
    $link='https://'.$subdomain.'.amocrm.ru/private/api/v2/json/contacts/list';
    if (
        $query1512 != ''
    ) {
        $link .= '?id=' . urlencode($query1512);
    } # if
    
    $curl=curl_init(); #Сохраняем дескриптор сеанса cURL
    #Устанавливаем необходимые опции для сеанса cURL
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client');
    curl_setopt($curl,CURLOPT_URL,$link);
    curl_setopt($curl,CURLOPT_HEADER,false);
    curl_setopt($curl,CURLOPT_COOKIEFILE, $strCookieFileName); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
    curl_setopt($curl,CURLOPT_COOKIEJAR, $strCookieFileName); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
    curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);

    $strout=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
    $code=curl_getinfo($curl,CURLINFO_HTTP_CODE);

    $code=(int)$code;
	//$resout = gettype($out);
	@file_put_contents("curl.txt",$strout);
	//$out2 = quotemeta($out);
	// --- 628 - 600,500,525 -> 530
	$outpos = strpos($strout,'"custom_fields":[{');
	$outpos = $outpos - 1;
	$out2 = substr($strout, 0, $outpos);	
	$out2 .= '}]}}';

    $errors=array(
      301=>'Moved permanently',
      400=>'Bad request',
      401=>'Unauthorized',
      403=>'Forbidden',
      404=>'Not found',
      500=>'Internal server error',
      502=>'Bad gateway',
      503=>'Service unavailable'
    );
    try
    {
      #Если код ответа не равен 200 или 204 - возвращаем сообщение об ошибке
      if($code!=200 && $code!=204)
        return array (
          'boolOk' => FALSE,
          'strErrDevelopUtf8' => 'AmoCRM error: ' . (isset($errors[$code]) ? $errors[$code] : 'Undescribed error ' . $code),
        );
    }
    catch(Exception $E)
    {
        return array (
          'boolOk' => FALSE,
          'strErrDevelopUtf8' => 'AmoCRM error: ' . $E->getMessage().PHP_EOL.'Код ошибки: '.$E->getCode(),
        );
    }
     
    /**
     * Данные получаем в формате JSON, поэтому, для получения читаемых данных,
     * нам придётся перевести ответ в формат, понятный PHP
     */
	
	
    $Response=json_decode($out2,true);
	
    return array (
      'boolOk' => TRUE,
      'arrResponse' => $Response['response'],
    );
	
    # ))) почти copy-paste из документации

} # function
//Получает контакт по id контакта

function fncAmocrmCompanyList(
    $strSubdomain,
    $strCookieFileName,
    $query1512 = ''
) {

    # почти copy-paste из документации (((
	
    $subdomain=$strSubdomain; #Наш аккаунт - поддомен
    #Формируем ссылку для запроса
    $link='https://'.$subdomain.'.amocrm.ru/private/api/v2/json/company/list';
    if (
        $query1512 != ''
    ) {
        $link .= '?query=' . urlencode($query1512);
    } # if
    $curl=curl_init(); #Сохраняем дескриптор сеанса cURL
    #Устанавливаем необходимые опции для сеанса cURL
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client');
    curl_setopt($curl,CURLOPT_URL,$link);
    curl_setopt($curl,CURLOPT_HEADER,false);
    curl_setopt($curl,CURLOPT_COOKIEFILE, $strCookieFileName); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
    curl_setopt($curl,CURLOPT_COOKIEJAR, $strCookieFileName); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
    curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
    $out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
	$code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
	curl_close($curl); 
    //$resout = gettype($out);
	// --- 628 - 600,500,525 -> 530
    $errors=array(
      301=>'Moved permanently',
      400=>'Bad request',
      401=>'Unauthorized',
      403=>'Forbidden',
      404=>'Not found',
      500=>'Internal server error',
      502=>'Bad gateway',
      503=>'Service unavailable'
    );
    try
    {
      #Если код ответа не равен 200 или 204 - возвращаем сообщение об ошибке
      if($code!=200 && $code!=204)
        return array (
          'boolOk' => FALSE,
          'strErrDevelopUtf8' => 'AmoCRM error: ' . (isset($errors[$code]) ? $errors[$code] : 'Undescribed error ' . $code),
        );
    }
    catch(Exception $E)
    {
        return array (
          'boolOk' => FALSE,
          'strErrDevelopUtf8' => 'AmoCRM error: ' . $E->getMessage().PHP_EOL.'Код ошибки: '.$E->getCode(),
        );
    }
     
    /**
     * Данные получаем в формате JSON, поэтому, для получения читаемых данных,
     * нам придётся перевести ответ в формат, понятный PHP
     */

    $Response=json_decode($out,true);
	if ($out=="") {
		return array (
      		'boolOk' => FALSE,
      		'arrResponse' => "",
    	);
	} else {
    	return array (
      		'boolOk' => TRUE,
      		'arrResponse' => $Response['response'],
    	);
	}
	
    # ))) почти copy-paste из документации

} # 
#-----------

#-----------
function fncAmocrmContactsListByResponsibleID(
    $strSubdomain,
    $strCookieFileName,
    $strresponsibleid = ''
) {	
	//example - domitex.amocrm.ru/private/api/v2/json/contacts/list?responsible_user_id=628743
    # почти copy-paste из документации (((

    $subdomain = $strSubdomain; #Наш аккаунт - поддомен
    #Формируем ссылку для запроса
    $link='https://'.$subdomain.'.amocrm.ru/private/api/v2/json/contacts/list';
    if (
        $strresponsibleid != ''
    ) {
        $link .= '?responsible_user_id=' . urlencode($strresponsibleid);
    } # if
    
    $curl=curl_init(); #Сохраняем дескриптор сеанса cURL
    #Устанавливаем необходимые опции для сеанса cURL
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client');
    curl_setopt($curl,CURLOPT_URL,$link);
    curl_setopt($curl,CURLOPT_HEADER,false);
    curl_setopt($curl,CURLOPT_COOKIEFILE, $strCookieFileName); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
    curl_setopt($curl,CURLOPT_COOKIEJAR, $strCookieFileName); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
    curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
     
    $out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
    $code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
    
    $code=(int)$code;
    $errors=array(
      301=>'Moved permanently',
      400=>'Bad request',
      401=>'Unauthorized',
      403=>'Forbidden',
      404=>'Not found',
      500=>'Internal server error',
      502=>'Bad gateway',
      503=>'Service unavailable'
    );
    try
    {
      #Если код ответа не равен 200 или 204 - возвращаем сообщение об ошибке
      if($code!=200 && $code!=204)
        return array (
          'boolOk' => FALSE,
          'strErrDevelopUtf8' => 'AmoCRM error: ' . (isset($errors[$code]) ? $errors[$code] : 'Undescribed error ' . $code),
        );
    }
    catch(Exception $E)
    {
        return array (
          'boolOk' => FALSE,
          'strErrDevelopUtf8' => 'AmoCRM error: ' . $E->getMessage().PHP_EOL.'Код ошибки: '.$E->getCode(),
        );
    }
     
    /**
     * Данные получаем в формате JSON, поэтому, для получения читаемых данных,
     * нам придётся перевести ответ в формат, понятный PHP
     */
    $Response=json_decode($out,true);

    return array (
      'boolOk' => TRUE,
      'arrResponse' => $Response['response'],
    );

    # ))) почти copy-paste из документации

} # function
#-----------
#-----------
function fncAmocrmContactsList(
    $strSubdomain,
    $strCookieFileName,
    $query = ''
) {

    # почти copy-paste из документации (((

    $subdomain=$strSubdomain; #Наш аккаунт - поддомен
    #Формируем ссылку для запроса
    $link='https://'.$subdomain.'.amocrm.ru/private/api/v2/json/contacts/list';
    if (
        $query != ''
    ) {
        $link .= '?query=' . urlencode($query);
    } # if
    
    $curl=curl_init(); #Сохраняем дескриптор сеанса cURL
    #Устанавливаем необходимые опции для сеанса cURL
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client');
    curl_setopt($curl,CURLOPT_URL,$link);
    curl_setopt($curl,CURLOPT_HEADER,false);
    curl_setopt($curl,CURLOPT_COOKIEFILE, $strCookieFileName); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
    curl_setopt($curl,CURLOPT_COOKIEJAR, $strCookieFileName); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
    curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
     
    $out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
    $code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
    
    $code=(int)$code;
    $errors=array(
      301=>'Moved permanently',
      400=>'Bad request',
      401=>'Unauthorized',
      403=>'Forbidden',
      404=>'Not found',
      500=>'Internal server error',
      502=>'Bad gateway',
      503=>'Service unavailable'
    );
    try
    {
      #Если код ответа не равен 200 или 204 - возвращаем сообщение об ошибке
      if($code!=200 && $code!=204)
        return array (
          'boolOk' => FALSE,
          'strErrDevelopUtf8' => 'AmoCRM error: ' . (isset($errors[$code]) ? $errors[$code] : 'Undescribed error ' . $code),
        );
    }
    catch(Exception $E)
    {
        return array (
          'boolOk' => FALSE,
          'strErrDevelopUtf8' => 'AmoCRM error: ' . $E->getMessage().PHP_EOL.'Код ошибки: '.$E->getCode(),
        );
    }
     
    /**
     * Данные получаем в формате JSON, поэтому, для получения читаемых данных,
     * нам придётся перевести ответ в формат, понятный PHP
     */
    $Response=json_decode($out,true);

    return array (
      'boolOk' => TRUE,
      'arrResponse' => $Response['response'],
    );

    # ))) почти copy-paste из документации

} # function
#-----------




?>