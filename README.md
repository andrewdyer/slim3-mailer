# Slim3 Mailer

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/22e680c56faa40a493455089195bf841)](https://www.codacy.com/app/andrewdyer/slim3-mailer?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=andrewdyer/slim3-mailer&amp;utm_campaign=Badge_Grade)
[![Latest Stable Version](https://poser.pugx.org/andrewdyer/slim3-mailer/version)](https://packagist.org/packages/andrewdyer/slim3-mailer)
[![Total Downloads](https://poser.pugx.org/andrewdyer/slim3-mailer/downloads)](https://packagist.org/packages/andrewdyer/slim3-mailer)
[![Latest Unstable Version](https://poser.pugx.org/andrewdyer/slim3-mailer/v/unstable)](//packagist.org/packages/andrewdyer/slim3-mailer)
[![License](https://poser.pugx.org/andrewdyer/slim3-mailer/license)](https://packagist.org/packages/andrewdyer/slim3-mailer)
[![composer.lock available](https://poser.pugx.org/andrewdyer/slim3-mailer/composerlock)](https://packagist.org/packages/andrewdyer/slim3-mailer)

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
       
$container['mailer'] = function($container) {
    $twig = $container['view'];
    $mailer = new \Anddye\Mailer\Mailer($twig, [
        'host'      => '',  // SMTP Host
        'port'      => '',  // SMTP Port
        'username'  => '',  // SMTP Username
        'password'  => '',  // SMTP Password
        'protocol'  => ''   // SSL or TLS
    ]);
        
    // Set the details of the default sender
    $mailer->setDefaultFrom('no-reply@mail.com', 'Webmaster');
    
    return $mailer;
};
    
$app->run();
```

If your application doesn't use Twig views already, you will need to also attach 
this to your container.

```php
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(__DIR__ . '/../resources/views');
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new \Slim\Views\TwigExtension($container['router'], $basePath));
    
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
| protocol | string | The encryption method, either SSL or TLS. |

### Sending the Email (Basic Example)

```php
$app->get('/', function (Request $request, Response $response) use($container) {
    $user = new stdClass;
    $user->name = 'John Doe';
    $user->email = 'johndoe@mail.com';
    
    $container['mailer']->sendMessage('emails/welcome.html.twig', ['user' => $user], function($message) use($user) {
        $message->setTo($user->email, $user->name);
        $message->setSubject('Welcome to the Team!');
    });
    
    $response->getBody()->write('Mail sent!');
    
    return $response;
});
```
**welcome.html.twig**

```html
<h1>Hello {{ user.name }}</h1>
    
<p>Welcome to the Team!</p>
    
<p>Love, Admin</p>
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
        $this->setSubject('Welcome to the Team!');
        $this->setView('emails/welcome.html.twig', [
            'user' => $this->user
        ]);
        
        return $this;
    }
    
}
```

Now in your controller or route, you set the recipients address and name, passing 
just a single argument into the `sendMessage` method - a new instance of the mailable 
class;

```php
$app->get('/', function (Request $request, Response $response) use($container) {
    $user = new stdClass;
    $user->name = 'John Doe';
    $user->email = 'johndoe@mail.com';
    
    $container['mailer']->setTo($user->email, $user->name)->sendMessage(new WelcomeMailable($user));
     
    $response->getBody()->write('Mail sent!');
    
    return $response;
});
```

### Methods

| Method | Description |
| --- | --- |
| `attachFile(string $path)` | Path to a file to set as an attachment. |
| `detachFile(string $path)` | Path to a file to remove as an attachment. |
| `setBcc(string $address, string $name = '')` | Set the Bcc of the message. |
| `setBody($body)` | Set the body of the message. |
| `setCc(string $address, string $name = '')` | Set the Cc of the message |
| `setDate(DateTimeInterface $dateTime)` | Set the date at which this message was created. |
| `setFrom(string $address, string $name = '')` | Set the sender of the message. |
| `setReplyTo(string $address, string $name = '')` | Set the ReplyTo of the message. |
| `setPriority(int $priority)` | Set the priority of the message. |
| `setSubject(string $subject)` | Set the subject of the message. |
| `setTo(string $address, string $name = '')` | Set the recipent of the message. |

## Support

If you are having general issues with this library, then please feel free to contact me on [Twitter](https://twitter.com/andyer92).

If you believe you have found an issue, please report it using the [issue tracker](https://github.com/andrewdyer/slim3-mailer/issues), or better yet, fork the repository and submit a pull request.

If you're using this package, I'd love to hear your thoughts!

## Useful Links

* [Slim Framework](https://www.slimframework.com)
* [Slim Framework Twig View](https://github.com/slimphp/Twig-View)
* [Swift Mailer](https://github.com/swiftmailer/swiftmailer)
