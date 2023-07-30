# laravel-boilter-plate
Laravel Boiler Plate to start development with ease. This package will help you to start development with some extensions of 

## How to install

Run the below command in the Laravel root folder

```
composer require bensondevs/laravel-boiler-plate
```

## Features

### Generate a Service class
This feature will create a service class in the folder of `app/Services`

```
php artisan make:service SomeModuleService
```

### Generate a Repository class
This feature will create a repository class in the folder of `app/Repositories`

```
php artisan make:repository MyModelNameRepository
```

### Generate an Enum Class
This feature will create an enum class in the folder of `app/Enums`

```
php artisan make:enum MyChoicesEnum
```

### Generate a Contract class
This feature will create a contract class in the folder of `app/Contracts`

```
php artisan make:contract YourContract
```

### Generate a Helper File
This feature will create a helper file and automatically register it to the `composer.json` to enable the developers to use it immediately without sweating

```
php artisan make:helper SomeAspectHelper
```

### Built-in Helpers
To see the helpers created please check in `src/Helpers`
