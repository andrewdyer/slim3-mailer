# Slim3 Mailer

Mailable support for the Slim Framework using Twig and [Swift Mailer](https://github.com/swiftmailer/swiftmailer). Mailables will massively tidy up your controller methods or routes, and will make sending email a breeze.

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
    $view->addExtension(new \Slim\Views\TwigExtension($container["router"], $basePath));
    
    return $view;
};
    
$container["mailer"] = function($container) {
    $twig = $container["view"];
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

### Supported Options

| Option | Type | Description |
| --- | --- | --- |
| host | string | The host to connect to. |
| port | integer | The port to connect to. |
| username | string | The username to authenticate with. |
| password | string | The password to authenticate with. |

```php
$app->get("/", function (Request $request, Response $response) use($container) {
    $user = new stdClass;
    $user->name = "John Doe";
    $user->email = "johndoe@mail.com";
    
    $container["mailer"]->sendMessage("emails/welcome.html.twig", ["user" => $user], function($message) use($user) {
        $message->setTo($user->email, $user->name);
        $message->setSubject("Welcome to the Team!");
    });
    
    $response->getBody()->write("Mail sent!");
    
    return $response;
});
```

```php
use Anddye\Mailer\Mailable;
    
class WelcomeMailable extends Mailable
{
    
    protected $user;
    
    public function __construct($user)
    {
        $this->user = $user;
    }
    
    public function build()
    {
        $this->setSubject("Welcome to the Team!");
        $this->setView("emails/welcome.html.twig", [
            "user" => $this->user
        ]);
        
        return $this;
    }
    
}
```

```php
$app->get("/", function (Request $request, Response $response) use($container) {
    $user = new stdClass;
    $user->name = "John Doe";
    $user->email = "johndoe@mail.com";
    
    $container["mailer"]->setTo($user->email, $user->name)->sendMessage(new WelcomeMailable($user));
     
    $response->getBody()->write("Mail sent!");
    
    return $response;
});
```