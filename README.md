# CMB - First steps
Welcome to CMB

This documentation has been made in order to help developers in the process of deployment and implementing new features. 

If you are an administrator please go to the  _CMB - Help Center_ .
Any doubt or inquire will be resolved contact us directly in [luigelo.davila@e.applus.com](mailto:luigelo.davila@e.applus.com) or using the CMB report system.

_*The follow steps are subject to change, and it will be change depending on your server configuration._


## CMB Core and Technology

This new CMB is based in [Laravel](https://laravel.com) a Full Open Source PHP framework.
Before start configuration please read the [Laravel Documentation](https://laravel.com/docs/) in order to understand the Laravel Projects Structure.

This are most essencial things you need to be familiar:
- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Requirements

- Nginx or Apache Webserver
- PHP 7.3
- NodeJS & NPM Package Manager
- Composer
- MySQL

## Installation

- Cloning Bitbucket Repository
- Via Compressed File (Deprecated)


  ### Cloning Bitbucket Repository
  Please contact with your manager in order to get access to the BitBucket private repository.
  You must create and config your account with your personal SSH Access Key. 
  
  If you don't know how to create one, read this documentation about 
  [how to set up a ssh key](https://support.atlassian.com/bitbucket-cloud/docs/set-up-an-ssh-key/).

        git clone git@bitbucket.org:applus-laboratories/cmb.git
        cd cmb 
        composer install 
        composer update 
        npm run dev

  ### Via Compressed File (Deprecated)
  **Don't use** this method in **Production Enviroments**.

  - [Dowload the compressed file](https://applusglobal.sharepoint.com/:u:/t/CMB/Ed8ArnkUzu9CkYeKYUIpVGMB-qYUCDvRq3pNMOyqwfEaVA?e=Oy7YqT)
  - Go to the route /data/cmb-laravel-app/web/app/ in the server and deploy the file there.
  - Give access to the files must be written _(storage/logs, bootstrap/cache)_


## CMB configuration
### Enviroment configuration
Take a look the **.env.example** file and use it as a model. Copy the file to the root directory and rename it to **.env**. You need to change your servername, database connexion, smtp email service.

    APP_NAME=CMB
    APP_COMPANY="Applus+ Laboratories"
    APP_ENV=YOUR_ENVIRONMENT
    APP_KEY=(Excute php artisan key:generate)
    APP_DEBUG=true
    APP_URL=(APP_ROOT_URL)
    
    DB_CONNECTION=mysql
    DB_HOST=(DB HOST)
    DB_PORT=3306
    DB_DATABASE=cmb
    DB_USERNAME=(USERNAME GIVEN BY SUPPORT)
    DB_PASSWORD=(PASSWORD GIVEN BY SUPPORT)
    
    EMC_DB_CONNECTION=mysql
    EMC_DB_HOST=(DB HOST)
    EMC_DB_PORT=3306
    EMC_DB_DATABASE=emc
    EMC_DB_USERNAME=(USERNAME GIVEN BY SUPPORT)
    EMC_DB_PASSWORD=(PASSWORD GIVEN BY SUPPORT)
    
    
    CLIMA_DB_CONNECTION=mysql
    CLIMA_DB_HOST=(DB HOST)
    CLIMA_DB_PORT=3306
    CLIMA_DB_DATABASE=clima
    CLIMA_DB_USERNAME=(USERNAME GIVEN BY SUPPORT)
    CLIMA_DB_PASSWORD=(PASSWORD GIVEN BY SUPPORT)
    
    
    NVH_DB_CONNECTION=mysql
    NVH_DB_HOST=(DB HOST)
    NVH_DB_PORT=3306
    NVH_DB_DATABASE=nvh
    NVH_DB_USERNAME=(USERNAME GIVEN BY SUPPORT)
    NVH_DB_PASSWORD=(PASSWORD GIVEN BY SUPPORT)

### Cross App Functions

By default, the CMB is divided by independent departments. Each one of them are placed in the **public** folder and uses
individual databases these are the default routes:
- *public/departments/emc*
- *public/departments/nvh*
- *public/departments/clima*

When you log into one of these applications it will create automatically a Full Domain Session in order to access the
content in the Main Laravel Core App. That will manage new implementations.

#### Why this methodology?
Process and content delivery are important, the core of each department is based on **SoPlanning - Planner Tool** this made
new implementations and management difficult for the development team.
This is why we use Laravel as Core and new implementations out of the main planner tool.

The *Laravel core app* will manage things as:

- Statistics
- Data Review
- Search

And by the other hand each one of the **planning tools** will manage all the scheduler,
planning instances and assignations.



### Test the CMB

The CMB is configured please test your system before deploy to the server you can use our artisan commands*

*Available Soon

## Contributing

Thank you for considering contributing to the Applus+ Laboratories CMB! If you need more information please contact the repo owner.

## Security Vulnerabilities

If you discover a security vulnerability within CMB, please send an e-mail to the actual administrator via [luigelo.davila@applusglobal.com](mailto:luigelo.davila@applusglobal.com). All security vulnerabilities will be promptly addressed.

## License
The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
