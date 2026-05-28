<h1 align="center">Slim3 Mailer</h1>

<p align="center">
    <a href="https://packagist.org/packages/andrewdyer/slim3-mailer"><img src="https://poser.pugx.org/andrewdyer/slim3-mailer/downloads?style=for-the-badge" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/andrewdyer/slim3-mailer"><img src="https://poser.pugx.org/andrewdyer/slim3-mailer/v?style=for-the-badge" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/andrewdyer/slim3-mailer"><img src="https://poser.pugx.org/andrewdyer/slim3-mailer/license?style=for-the-badge" alt="License"></a>
</p>

Email support for the Slim Framework using Twig and 
[Swift Mailer](https://github.com/swiftmailer/swiftmailer). Mailable classes will
massively  tidy up your controller methods or routes, and will make sending email 
a breeze.

## ⚠️ This package has been archived

`andrewdyer/slim3-mailer` is no longer maintained. A modern replacement is available at [`andrewdyer/mailer`](https://github.com/andrewdyer/mailer), which is framework-agnostic, supports PHP 8.3, and replaces SwiftMailer with Symfony Mailer.

See the [migration guide](#migrating-to-andrewdyermailer) below for upgrade instructions.

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
$app = new Slim\App;
    
$container = $app->getContainer();
       
$container['mailer'] = function($container) {
    $twig = $container['view'];
    $mailer = new Anddye\Mailer\Mailer($twig, [
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
    $view = new Slim\Views\Twig(__DIR__ . '/../resources/views');
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));
    
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
$app->get('/', function ($request, $response) use($container) {
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
$app->get('/', function ($request, $response) use($container) {
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
| attachFile(string $path) | Path to a file to set as an attachment. |
| detachFile(string $path) | Path to a file to remove as an attachment. |
| setBcc(string $address, string $name = '') | Set the Bcc of the message. |
| setBody($body) | Set the body of the message. |
| setCc(string $address, string $name = '') | Set the Cc of the message |
| setDate(DateTimeInterface $dateTime) | Set the date at which this message was created. |
| setFrom(string $address, string $name = '') | Set the sender of the message. |
| setReplyTo(string $address, string $name = '') | Set the ReplyTo of the message. |
| setPriority(int $priority) | Set the priority of the message. |
| setSubject(string $subject) | Set the subject of the message. |
| setTo(string $address, string $name = '') | Set the recipent of the message. |


## Migrating to andrewdyer/mailer

[`andrewdyer/mailer`](https://github.com/andrewdyer/mailer) is the modern replacement for this package. The core concept of mailable classes is retained, but the API has been updated to reflect current PHP and dependency standards.

### What has changed

| slim3-mailer                                           | mailer                                                                         |
| ------------------------------------------------------ | ------------------------------------------------------------------------------ |
| Depends on `slim/slim` and `slimphp/twig-view`         | Framework-agnostic, accepts any `Twig\Environment`                             |
| Uses `swiftmailer/swiftmailer` (abandoned)             | Uses `symfony/mailer` via a swappable transport interface                      |
| `Mailable::build()` configures the message             | `Mailable::envelope()` and `Mailable::content()` separate routing from content |
| SMTP settings passed as an array                       | Transport injected via DSN string                                              |
| `$mailer->setTo()->sendMessage(new WelcomeMailable())` | `$mailer->send(new WelcomeMail())`                                             |

### Installation

Remove the old package and install the new one:

```bash
composer remove andrewdyer/slim3-mailer
composer require andrewdyer/mailer
```

### Updating a mailable class

**Before:**

```php
use Anddye\Mailer\Mailable;

class WelcomeMailable extends Mailable
{
    public function __construct(private $user) {}

    public function build()
    {
        $this->setSubject('Welcome to the Team!');
        $this->setView('emails/welcome.html.twig', [
            'user' => $this->user,
        ]);

        return $this;
    }
}
```

**After:**

```php
use AndrewDyer\Mailer\Mailable;
use AndrewDyer\Mailer\Values\Address;
use AndrewDyer\Mailer\Values\Content;
use AndrewDyer\Mailer\Values\Envelope;

class WelcomeMail extends Mailable
{
    public function __construct(private readonly User $user) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            to:      new Address($this->user->email, $this->user->name),
            subject: 'Welcome to the Team!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails/welcome.html.twig',
            data: ['user' => $this->user],
        );
    }
}
```

### Updating the mailer setup in Slim 3

**Before:**

```php
$container['mailer'] = function ($container) {
    $mailer = new Anddye\Mailer\Mailer($container['view'], [
        'host'     => '',
        'port'     => '',
        'username' => '',
        'password' => '',
        'protocol' => '',
    ]);

    $mailer->setDefaultFrom('no-reply@mail.com', 'Webmaster');

    return $mailer;
};
```

**After:**

```php
use AndrewDyer\Mailer\Mailer;
use AndrewDyer\Mailer\Drivers\SymfonyTransport;
use AndrewDyer\Mailer\Values\Address;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$container['mailer'] = function ($container) {
    $mailer = new Mailer(
        twig:        $container['view']->getTwig(),
        transport:   new SymfonyTransport('smtp://username:password@host:port'),
        defaultFrom: new Address('no-reply@mail.com', 'Webmaster'),
    );

    return $mailer;
};
```

Note that `getTwig()` unwraps the underlying `Twig\Environment` from `slim/twig-view`, which is all `andrewdyer/mailer` requires.

### Sending a mailable

**Before:**

```php
$container['mailer']->setTo($user->email, $user->name)->sendMessage(new WelcomeMailable($user));
```

If you are having general issues with this library, then please feel free to contact me on [Twitter](https://twitter.com/andyer92).

If you believe you have found an issue, please report it using the [issue tracker](https://github.com/andrewdyer/slim3-mailer/issues), or better yet, fork the repository and submit a pull request.

If you're using this package, I'd love to hear your thoughts!

## Useful Links

* [Slim Framework](https://www.slimframework.com)
* [Slim Framework Twig View](https://github.com/slimphp/Twig-View)
* [Swift Mailer](https://github.com/swiftmailer/swiftmailer)
