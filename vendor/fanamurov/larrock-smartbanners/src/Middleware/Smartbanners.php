<?php

namespace Larrock\ComponentSmartbanners\Middleware;

use Closure;
use URL;
use View;

class Smartbanners
{
	protected $banners;
	protected $partners;
	protected $server;
	protected $host;
	protected $url_load;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
    	if(env('SMARTBANNERS') === true){
			$this->banners = env('SMARTBANNERS_BANNERS', 2);
			$this->partners = env('SMARTBANNERS_PARTNERS', 0);
			$this->host = env('SMARTBANNERS_HOST', 0);
			$this->url_load = URL::current();
			$this->server = env('SMARTBANNERS_SERVER', 'http://martds.ru');
			$this->connect();
		}

        return $next($request);
    }

	/**
	 * Соединение с сервером smartbanners. Получение json-массива баннеров
	 * @return bool|mixed
	 */
	protected function connect()
	{
		$url = $this->server .'/smartbanners/get/'. $this->host .'/'. $this->banners .'/'. $this->partners .'?url_load='. $this->url_load;
		$ch = curl_init(); // инициализируем сессию curl
		curl_setopt($ch, CURLOPT_URL,$url); // указываем URL, куда отправлять POST-запрос
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);// разрешаем перенаправление
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // указываем, что результат запроса следует передать в переменную, а не вывести на экран
		curl_setopt($ch, CURLOPT_TIMEOUT, 4); // таймаут соединения
		curl_setopt($ch, CURLOPT_REFERER, $_SERVER['SERVER_NAME']); //Referrer
		$result = curl_exec($ch); // выполняем запрос

		$info = curl_getinfo($ch);
		curl_close($ch); // завершаем сессию
		if($info['http_code'] === 200){
			if($load_data = json_decode($result, true)){
				if(is_array($load_data) && isset($load_data[0]['id'])){
					return $this->parse_banners($load_data);
				}
			}
		}else{
			return NULL;
		}

		//Обработка ошибок CURL
		//print_r(curl_getinfo($ch));
		//$result['error']['n'] = curl_errno($ch);
		//$result['error']['mes'] = curl_error($ch);
	}

	protected function parse_banners(array $load_data)
	{
		foreach($load_data as $get_banner_key => $get_banner_value){
			//Загрузка картинки баннера
			if($banner_src = $this->upload_banner($get_banner_value['image'])){
				//dd($banner_src);
				$title_def = $get_banner_value['title'];
				$get_banner_value['image'] = $banner_src;
				//Замена тэгов ссылок на ссылки
				$title = str_replace('[link_start]', '<a target="a_blank" href="'. $get_banner_value['banner_url'] .'">', strip_tags(htmlspecialchars($get_banner_value['title'])));
				$get_banner_value['title'] = str_replace('[link_end]', '</a>', $title);

				$alt_title = str_replace('[link_start]', '', $title_def);
				$get_banner_value['alt_title'] = str_replace('[link_end]', '', $alt_title);
				$load_data[$get_banner_key] = $get_banner_value;
			}
		}
		return $this->render($load_data);
	}

	/**
	 * Проверка/Загрузка картинок для баннеров
	 * @param string    $image_path	Путь до картинки
	 * @param string    $force	Принудительное обновление картинок
	 * @return bool|string
	 */
	protected function upload_banner($image_path, $force = NULL)
	{
		$image_name = explode('/', $image_path);
		$image_name = array_reverse($image_name);
		$image_name = array_shift($image_name);
		$path_to_new_file = public_path() .'/media/sbanners/'. $image_name;
		$src_new_file = '/media/sbanners/'. $image_name;
		if($force){
			@unlink($path_to_new_file);
		}

		// Проверка на существование картинки баннера
		if ( !file_exists(public_path() .'/media/sbanners/'. $image_name)) {
			if( !file_exists(public_path() .'/media/sbanners')){
				@mkdir(public_path() .'/media/sbanners', 0755);
			}

			//Файла нет, значит скачиваем и ложим к нам
			$file = $this->server . '/public/images/smartbanners/big/'. $image_name;

			if ( @copy($file, $path_to_new_file)) {
				return $src_new_file;
			}
		}
		return $src_new_file;
	}

	protected function render(array $parse_data)
	{
		return View::share('smartbanners', View::make('larrock::front.smartbanners.block', ['data' => $parse_data])->render());
	}
}
