# Slim3 Mailer

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/22e680c56faa40a493455089195bf841)](https://www.codacy.com/app/andrewdyer/slim3-mailer?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=andrewdyer/slim3-mailer&amp;utm_campaign=Badge_Grade)

Email support for the Slim Framework using Twig and 
[Swift Mailer](https://github.com/swiftmailer/swiftmailer). Mailable classes will
massively  tidy up your controller methods or routes, and will make sending email 
a breeze.

## License

Licensed under MIT. Totally free for private or commercial projects.

## Installation

```bash
composer require andrewdyer/slim3-mailer
```

## Usage

Attach a new instance of `Anddye\Mailer\Mailer` to your applications container so 
it can be accessed anywhere you need. `Mailer` takes two arguements; an instance of 
`Slim\Views\Twig` and an optional array of SMTP settings.

```php
$app = new \Slim\App;
    
$container = $app->getContainer();
       
$container["mailer"] = function($container) {
    $twig = $container["view"];
    $mailer = new \Anddye\Mailer\Mailer($twig, [
        "host"      => "",  // SMTP Host
        "port"      => "",  // SMTP Port
        "username"  => "",  // SMTP Username
        "password"  => ""   // SMTP Password
    ]);
        
    // Set the details of the default sender
    $mailer->setDefaultFrom("no-reply@mail.com", "Webmaster");
    
    return $mailer;
};
    
$app->run();
```

If your application doesn't use Twig views already, you will need to also attach 
this to your container.

```php
$container["view"] = function ($container) {
    $view = new \Slim\Views\Twig(__DIR__ . "/../resources/views");
    $basePath = rtrim(str_ireplace("index.php", "", $container["request"]->getUri()->getBasePath()), "/");
    $view->addExtension(new \Slim\Views\TwigExtension($container["router"], $basePath));
    
    return $view;
};
``` 

### Supported Options

| Option | Type | Description |
| --- | --- | --- |
| host | string | The host to connect to. |
| port | integer | The port to connect to. |
| username | string | The username to authenticate with. |
| password | string | The password to authenticate with. |

### Sending the Email (Basic Example)

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

### Sending with a Mailable

Using mailable classes are a lot more elegant than the basic usage example above. Building 
up the mail in a mailable class cleans up controllers and routes, making things look 
a more tidy and less cluttered as well as making things so much more manageable.

Mailable classes are required to extend the base `Anddye\Mailer\Mailable` class;

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

Now in your controller or route, you set the recipients address and name, passing 
just a single argument into the `sendMessage` method - a new instance of the mailable 
class;

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