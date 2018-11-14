[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/magros/laravel-model-encryption/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/magros/laravel-model-encryption/?branch=master)
[![Downloads](https://poser.pugx.org/magros/laravel-model-encryption/downloads.svg)](https://packagist.org/packages/magros/laravel-model-encryption)
[![License](https://poser.pugx.org/magros/laravel-model-encryption/license.svg)](https://packagist.org/packages/magros/laravel-model-encryption)
[ ![Codeship Build Status](https://app.codeship.com/projects/08e35650-c9f0-0136-121d-22114b8966aa/status?branch=master)](https://app.codeship.com/projects/315063)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/magros/laravel-model-encryption/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)

# laravel-model-encryption
A trait to encrypt data models in Laravel, this automatically encrypt and decrypt model data overriding getAttribute an setAttribute methods of Eloquent Model.
## How to install
Run composer installation
```bash
    composer install magros/laravel-model-encryption
```
    
Add ServiceProvider to your app/config.php file
```php
    'providers' => [
        ...
        \Magros\Encryptable\EncryptServiceProvider::class,
    ],
```
Publish configuration file, this will create config/encrypt.php 
```bash
     php artisan vendor:publish --provider=Magros\Encryptable\EncryptServiceProvider
``` 

## How to use

1.  You must add `ENCRYPT_KEY` and `ENCRYPT_PREFIX` in your .env file or set it in your `config/encrypt.php` file

2. Use the `Magros\Encryptable\Encryptable` trait:
    
    ```php
    use Magros\Encryptable\Encryptable;
    ```  
    
3. Set the `$encryptable` array on your Model.

    ```php
    protected $encryptable = ['encrypted_property'];
    ```
    
4. Here's a complete example:

    ```php
    <?php
    
    namespace App;
    
    use Illuminate\Database\Eloquent\Model;
    use Magros\Encryptable\Encryptable;
    
    class User extends Model
    {
    
        use Encryptable;
    
        /**
         * The attributes that should be encrypted when stored.
         *
         * @var array
         */
        protected $encryptable = [ 'email', 'address' ];
    }
    ```
5. Optional. Encrypt your current data

    If you have current data in your database you can encrypt it with the: `php artisan encryptable:encryptModel 'App\User'` command.
    
    Additionally you can decrypt it using the:`php artisan encryptable:decryptModel 'App\User'` command.
    
    Note: You must implement first the `Encryptable` trait and set `$encryptable` attributes
6. If you are using exists and unique rules with encrypted values replace it with exists_encrypted and unique_encrypted 
    ```php      
   $validator = validator(['email'=>'foo@bar.com'], ['email'=>'exists_encrypted:users,email']);
    ```
7. You can still use `where` functions 
   ```php      
   $validator = User::where('email','foo@bar.com')->first();
   ```
   Automatically `foo@bar.com` will be encrypted and pass it to the query builder.
   

