
# Shopify App with Laravel & React

This is an example template and setup guide for the Shopify application using Laravel and React.
We are going to use [Kyon147/laravel-shopify](https://github.com/Kyon147/laravel-shopify "Kyon147/laravel-shopify") in this example


## Pacakage Refrences

#### Main Utilities

| Pacakage Name | Version      | Description                |
| :------------ | :----------- | :------------------------- |
| `Laravel` | `^10.3` | Backend part |
| `PHP` | `^8.1` | 8.2 is unstable currently for this Pacakage. |
| `React` | `^18.2.0` | Frontend part |
| `Kyon147/laravel-shopify` | `^18.0.0` | Shopify Configuration Pacakage. |

#### Other Utilities

| Pacakage Name | Version      | Description                |
| :------------ | :----------- | :------------------------- |
| `Vite` | `^4.0.0` | Bundler |
| `@shopify/polaris` | `^10.29.0` | UI Framework |
| `@shopify/app-bridge` | `^3.7.7` | Shopify authetication Pacakage. |
| `@shopify/app-bridge-react` | `^3.7.7` | Shopify authetication Pacakage. |

Note : @shopify/app-bridge-utils is not deprecated you can use this from @shopify/app-bridge/utilities.

Make sure you change getSessionToken import in the instance.js file.
`import { getSessionToken } from "@shopify/app-bridge/utilities";`

Please follow this wiki for more. [Wiki Link](https://github.com/sp-artisan/shopify-laravel-react-app/wiki)
