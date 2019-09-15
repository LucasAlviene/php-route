<?php
/*
* Classe Route no PHP
* @author Lucas Alviene <https://github.com/LucasAlviene>
* @link https://github.com/LucasAlviene/route-php
* @version 1.0
* @access public
*/
class Route{
	
	
	// ':email' => '([_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3}))'
	private $uri = "";
	private $routes = [];
	private $filters = [':id' => '([0-9]+)'];
	private $errors = [];
	
	/**
	* Constroi a Route e recebe a URI
	* @access public
	* @param String $uri
	* @return void
	*/
	
	public function __construct($uri){
		$this->uri = $uri;
	}
	
	/**
	* Adiciona Filtro para o URI
	* @access public
	* @param String $name
	* @param String $value
	* @return $this
	*/
	
	public function add_filter($name,$value){
		$this->filters[$name] = $value;
		return $this;
	}
	
	/**
	* Adiciona Erros
	* @access public
	* @param String $status
	* @param String $function
	* @return void
	*/
	
	public function add_error($status,$function){
		$this->errors[$status] = $function;
	}
	
	
	/**
	* Adiciona Route
	* @access public
	* @param String $path
	* @param String $function
	* @param Int $num_args
	* @return $this
	*/
	
	
	public function on($path,$function,$num_args = 0){
		if(!is_null($function)){
			$uri = substr($path, 0, 1) !== '/' ? '/'.$path : $path;
			$uri = substr($path, -1, 0) !== '/' ? $path.'/' : $path;
			
			preg_match_all("/:\w+/",$uri,$matchs);
			foreach($matchs[0] as $match){
				$uri = $this->run_filter($match,$uri);
			}
			$pattern = str_replace('/', '\/?', $uri);
			$route = '/^' . $pattern . '$/';

			$this->routes[$route] = ['function' => $function,'num_args' => $num_args];
		}
        return $this;
	}

	/**
	* Roda o Filtro
	* @access private
	* @param String $search
	* @param String $uri
	* @return String
	*/
	
	private function run_filter($search,$uri){
		$filters = $this->filters;
		$replace = "(\w+)";
		if(isset($filters[$search])){
			$replace = $filters[$search];
		}
		return str_replace($search,$replace."?",$uri);
	}
	
	/**
	* Roda o Error
	* @access public
	* @param String $status
	* @return mixed
	*/
	
	public function run_error($status){
		$errors = $this->errors;
		if(isset($errors[$status])){
			return call_user_func($errors[$status],$status);
		}
		return null;
	}
	
	/**
	* Roda o site
	* @access public
	* @return mixed
	*/
	
	public function run(){
		$uri = $this->uri;
		foreach ($this->routes as $route => $r_) {
            if (preg_match($route, $uri, $params)) {
                return call_user_func_array($r_['function'], array_slice($params, 1, (int) $r_['num_args']));
            }
        }
        return $this->run_error(404);
	}

}