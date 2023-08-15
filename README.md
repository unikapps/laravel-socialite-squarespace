# Laravel socialite squarespace provider

```bash
composer require unikapps/laravel-socialite-squarespace
```

## Installation & Basic Usage

Please see the [Base Installation Guide](https://socialiteproviders.com/usage/), then follow the provider specific
instructions below.

### Add configuration to `config/services.php`

```php
'squarespace' => [    
  'client_id' => env('SQUARESPACE_CLIENT_ID'),  
  'client_secret' => env('SQUARESPACE_CLIENT_SECRET'),  
  'redirect' => env('SQUARESPACE_REDIRECT_URI') 
],
```

### Add provider event listener

Configure the package's listener to listen for `SocialiteWasCalled` events.

Add the event to your `listen[]` array in `app/Providers/EventServiceProvider`. See
the [Base Installation Guide](https://socialiteproviders.com/usage/) for detailed instructions.

```php
protected $listen = [
    \SocialiteProviders\Manager\SocialiteWasCalled::class => [
        // ... other providers
        \Unikapps\LaravelSocialiteSquarespace\SquarespaceExtendSocialite::class.'@handle',
    ],
];
```

### Usage

You should now be able to use the provider like you would regularly use Socialite (assuming you have the facade
installed):

```php
$scopes = ['website.products.read', 'website.profiles.read'];
return Socialite::driver('squarespace')->scopes($scopes)->redirect();
```

### Returned User fields

- ``id`` as Squarespace instance id
- ``name`` as website display name
- ``email`` Account email is not accessible at this point, we fake it by returning: {subdomain}@squarespace.com