# PHP Route

Código para criar rotas simples e complexas usando REGEX.
**Obs:** É a primeira versão do código e meu primeiro repositório, com o tempo darei upgrades, fique a vontade para contribuir.

## Instalação

Baixar o repositório ou baixar apenas o arquivo route.class.php e incluir no arquivo, exemplo no arquivo [examples.php](https://github.com/LucasAlviene/php-route/blob/master/examples.php).

## Utilização

### Adicionar .htaccess

Você pode usar do jeito que quiser, porém recomendamos usar com URL amigável utilizando o seguinte código no **.htaccess**.

```apacheconf 
    <IfModule mod_rewrite.c>
        RewriteEngine On
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteBase /
        RewriteRule . /index.php
    </IfModule>
```
### Básico

Para URL simples.
```php
<?php
    include_once 'route.class.php';
    
    $route = new Route($_SERVER['REQUEST_URI']);

    $route->on("/",function(){
        echo "Home";
    });

    $route->on("/sobre",function(){
        echo "Sobre";
    });
```

### Com Parâmetro 

Parâmetros mais complexos ou simples, pode user regex ou filtros, como no exemplo abaixo que usamos o filtro **:id**.
```php
<?php
    include_once 'route.class.php';
    
    $route = new Route($_SERVER['REQUEST_URI']);

    function conta($id = ""){
        echo "<b>ID:</b> ".$id;;
    }
    $route->on("/conta/:id","conta",1);

    $route->run();   
```

### Filtros

Adicionar filtros para simplificar nas rotas.
Nesse caso é adicionado o filtro **:email** com REGEX.

```php
<?php
    include_once 'route.class.php';
    
    $route = new Route($_SERVER['REQUEST_URI']);

    $route->add_filter(":email","([_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3}))");

    $route->on("/ativar/:email",function($email = ""){
        echo "O email ".$email." foi ativado";
    },1);

    $route->run();   

```

### Páginas de Erros

Você pode adicionar páginas de status code, uma dela o code 404, para caso a rota não exista.

```php
<?php
    include_once 'route.class.php';
    
    $route = new Route($_SERVER['REQUEST_URI']);

    $route->add_error(404,function($status = ""){
        echo "Erro ".$status;
    });

    $route->run();   

```

## Documentação

### __construct

- **$uri** URL Principal

```php
public function __construct($uri)
```
### ON

- **$path** Rota da URL
- **$function** Função/Class a ser chamada
- **$num_args** Número de parâmetros da Função/Class
```php
public function on($path,$function,$num_args = 0);
```

### add_filter

- **$name** Nome do Filtro
- **$value** O REGEX que será aplicado
```php
public function add_filter($name,$value);
```

### add_error

- **$status** Status Code do Error
- **$value** Função/Class a ser chamada
```php
public function add_error($status,$function);
```