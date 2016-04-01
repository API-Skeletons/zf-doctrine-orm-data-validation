Doctrine ORM Data Validation
============================

For one reason or another you will build an ORM on top of an existing database.  The existing database may
not have referential integrity for all it's relationships.

You now have a database with an ORM and there is probably bad data in the database which does not conform
to the relationships defined in the ORM.

This tool is intended for devleopment only.


Foreign Key Validation
----------------------

Create a report by scanning every relationship defined in your ORM for data
which is missing it's foreign key.  Nullable relationships are not evaluated.

```sh
php index.php orm:data-validation:relationship --object-manager="doctrine.entitymanager.orm_default"
```

This will output TSV with the Count, Child Field, Child Entity, Parent Entity for all relationships where data is found in the child which references data not found in the parent.


Configure Zend Framework 2 Module
---------------------------------

```php
composer require "api-skeletons/zf-doctrine-orm-data-validation": "^1.0"
```

Add to `config/development.config.php.dist`:
```php
return array(
    'modules' => array(
        'ZF\\Doctrine\\ORM\\DataValidation'
        ...
    ),
```

Enable development mode to copy `development.config.php.dist` to `development.config.php`

