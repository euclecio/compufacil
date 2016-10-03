# CompuFácil Challenge

### 1. Installing dependencies
**Composer**

    curl -sS https://getcomposer.org/installer | php
    mv composer.phar /usr/local/bin/composer

### 2. Cloning repository
**SSH:** `git clone git@github.com:euclecio/compufacil.git`

**HTTP:** `git clone https://github.com/euclecio/compufacil.git`

`cd compufacil`

### 3. Run `composer install` to install dependencies

### 4. Database settings

You must duplicate the file `config/autoload/doctrine_orm.local.php.dist` to `config/autoload/doctrine_orm.local.php` and set the database access

4.1. To create database run `composer orm:create`

4.2. To fill database with test data run `composer orm:fixture`

4.3. To drop database run `composer orm:fixture`

# **Testing**

## 1 - Getting news feed 

    GET /news?user=1
    Return code: 200
    [
        {"id":1,"message":"Lorem ipsum dolor sit asimet", ...},
        {"id":2,"message":"Lorem ipsum dolor sit asimet", ...},
        {"id":4,"message":"Lorem ipsum dolor sit asimet", ...},
        {"id":7,"message":"Lorem ipsum dolor sit asimet", ...}
    ];
    
## 2 - Getting friends list

    GET /user?byUser=1
    Return code: 200
    [
        {"id":2,"email":"user2@example.com","name":"User Two", ...},
        {"id":2,"email":"user2@example.com","name":"User Two", ...}
    ];
    
## 3 - Updating status
1-Available/2-Away/3-Busy
    
    PATCH /user/1
    {
        "status": "2"
    }
    Return code: 200
    {
        "id": 2,
        "email": "user2@example.com",
        "name": "User Two",
        "status": "2",
        "created": {
            "date": "2016-10-02 21:33:58.000000",
            "timezone_type": 3,
            "timezone": "America/Sao_Paulo"
        },
        "access": {
            "date": "2016-10-02 21:33:58.000000",
            "timezone_type": 3,
            "timezone": "America/Sao_Paulo"
        },
        "news": {}
    };
    
    
## Testes PHPUnit
    
`composer phpunit` - Run PHPUnit

`composer test` - It will drop database, create, import fixture data, and run PHPUnit
