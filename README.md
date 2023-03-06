
# Shopify App with Laravel & React

This is an example template and setup guide for the Shopify application using Laravel and React.
We are going to use [Kyon147/laravel-shopify](https://github.com/Kyon147/laravel-shopify "Kyon147/laravel-shopify") in this example


## Pacakage Refrences

#### Main Utilities

| Pacakage Name | Version      | Description                |
| :------------ | :----------- | :------------------------- |
| `Laravel` | `^9.19` | Backend part |
| `PHP` | `^8.1` | 8.2 is unstable currently for this Pacakage. |
| `React` | `^18.2.0` | Frontend part |
| `Kyon147/laravel-shopify` | `^18.2.0` | Shopify Configuration Pacakage. |

#### Other Utilities

| Pacakage Name | Version      | Description                |
| :------------ | :----------- | :------------------------- |
| `Vite` | `^4.0.0` | Bundler |
| `@shopify/polaris` | `^10.29.0` | UI Framework |
| `@shopify/app-bridge` | `^3.7.2` | Shopify authetication Pacakage. |
| `@shopify/app-bridge-react` | `^3.7.2` | Shopify authetication Pacakage. |
| `@shopify/app-bridge-utils` | `^3.5.1` | App bridge utilities. |




## Installation

Install this project via git

```bash
  git clone https://github.com/sp-artisan/laravel-react-app.git
  cd laravel-react-app
```

```bash
  composer Install
  npm Install
  php artisan migrate
  php artisan storage:link
```


    
## Environment Variables

To run this project, you will need to add the following environment variables to your .env file

First copy the .env.example file into .env file. Then update required things as per your configurations and Below variables are related to shopify.

If you have not cloned the repo then adding below variable in to .env file can do the work. 


**Requiered variables**

`SHOPIFY_API_KEY`  
`SHOPIFY_API_SECRET`  
`SHOPIFY_API_SCOPES`  
`VITE_SHOPIFY_API_KEY="${SHOPIFY_API_KEY}"` 

**These are optional variables**

`SHOPIFY_APP_NAME="${APP_NAME}"`  
`SHOPIFY_DEBUG=true` Make it false when you are in the production. 
`SHOPIFY_APPBRIDGE_ENABLED=true` True for embeded apps.  
`SHOPIFY_API_VERSION` Respected shopify API version which you are using. 


**Billing Variables**

`SHOPIFY_BILLING_ENABLED` true or false  
`SHOPIFY_BILLING_FREEMIUM_ENABLED` true or false  


## Steps For manual setup.

### 1. Setup Shopify Composer pacakage.

`composer require kyon147/laravel-shopify `  

#### Providers & FacadesProviders & Facades
###### With Laravel's auto-discover feature, this is handled for you on installWith Laravel's auto-discover feature, this is handled for you on install.

`php artisan vendor:publish --tag=shopify-config `  

#### Change user model with below code.

```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Osiset\ShopifyApp\Contracts\ShopModel as IShopModel;
use Osiset\ShopifyApp\Traits\ShopModel;

class User extends Authenticatable implements IShopModel
{
    use Notifiable;
    use ShopModel;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}

```

#### Create tables of the App.

``` php 
php artisan migrate
```

#### In the web.php add this snippet.

```php
Route::get('/login', [AuthController::class, 'login'])->name('login');

// Please keep this route snippet last
Route::controller(AuthController::class)->group(function (Router $router) {
    $router->get('/', 'index')->middleware('verify.shopify')->name('home');
    $router->get('/{any}', 'index')->middleware('verify.shopify')->where('any', '(.+)?');
});
```

#### NOTE: Please make sure to make your view files as they are in the repo. (You can edit them but they must be present in their respective folders)

#### CSRF
You must disable CSRF as there is currently no solution for verifying session tokens with CSRF, there is a conflict due to new login creation each request.

Open \App\Http\Middleware\VerifyCsrfToken.php, and add or edit:

```php
protected $except = [
    '*',
];
```

#### Axios interceptor

If we have to send autheticated requests to the shopify then we must have session token which refreshes every 2 seconds.
So we are making an instance of axios here which will have latest session token everytime we hit request.
We must use this instance instead of axios.

Refer this shopify doc for more info.

```javascript
import axios from "axios";
import { getSessionToken } from "@shopify/app-bridge-utils";
import { createApp } from "@shopify/app-bridge";

const instance = axios.create();

const shopifConfig = {
    apiKey: __SHOPIFY_API_KEY,
    host: new URLSearchParams(location.search).get('host'),
    forceRedirect: true
}

const app = createApp(shopifConfig);

instance.interceptors.request.use(function (config) {
    return getSessionToken(app) // requires a Shopify App Bridge instance
        .then((token) => {
            // Append your request headers with an authenticated token
            config.headers.Authorization = `Bearer ${token}`
            return config
        })
})

// Export your Axios instance to use within your app
export default instance;

``` 



