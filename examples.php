<?php
include_once 'route.class.php';

$route = new Route($_SERVER['REQUEST_URI']);

// Rota simples
$route->on("/",function(){
	echo "Home";
});
$route->on("/sobre",function(){
	echo "Sobre";
});

// Rota com Parâmetro 
function conta($id = ""){
	echo "<b>ID:</b> ".$id;;
}
$route->on("/conta/:id","conta",1);

// Filtro personalizado
$route->add_filter(":email","([_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3}))");
$route->on("/ativar/:email",function($email = ""){
    echo "O email ".$email." foi ativado";
},1);

// Chamada de Erros
function error($status = ""){
    echo "Error ".$status."<br>";	
}

// Adiciona a função Erro na Rota
$route->add_error(404,"error");
$route->add_error(401,"error");

// Chamando o erro 401
$route->run_error(401);

// Executa o site
$route->run();   