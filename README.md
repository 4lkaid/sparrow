# sparrow\db

PDO_MySQL Singleton.

# Install
```
composer require 4lkaid/sparrow:dev-main
```

# Usage
```php
<?php
require __DIR__ . '/vendor/autoload.php';

use sparrow\db;

$options = [
    'dbname'   => 'test',
    'username' => 'test',
    'password' => 'test',
];

$db = db::getInstance($options);

$db->query('select * from user');
$db->query('select * from user where name=:name', [':name' => 'root']);

$db->execution('insert into user (id, name, sex) VALUES (NULL, :name, :sex)', ['name' => 'kitty', 'sex' => 1]);

$db->execution('delete from user where id > :id', [':id' => 3]);

$db->execution('update user set name = :name where id = :id', [':name' => 'admin', ':id' => 1]);
```
