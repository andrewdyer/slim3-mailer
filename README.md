# Slim3 Mailer

## License

Licensed under MIT. Totally free for private or commercial projects.

## Installation

```bash
composer require andrewdyer/slim3-mailer
```

## Usage

```php
$app = new \Slim\App;
    
$container = $app->getContainer();
    
$container["view"] = function ($container) {
    $view = new \Slim\Views\Twig(__DIR__ . "/../resources/views");
    $basePath = rtrim(str_ireplace("index.php", "", $container["request"]->getUri()->getBasePath()), "/");
    $view->addExtension(new Slim\Views\TwigExtension($container["router"], $basePath));
    
    return $view;
};
    
$container["mailer"] = function($container) {
    $twig = $container->get("view");
    $mailer = new \Anddye\Mailer\Mailer($twig, [
        "host"      => "",  // SMTP Host
        "port"      => "",  // SMTP Port
        "username"  => "",  // SMTP Username
        "password"  => ""   // SMTP Password
    ]);
        
    // Set the details of the default sender
    $mailer->setDefaultFrom("admin@mail.com", "Webmaster");
    
    return $mailer;
};
    
$app->run();
```