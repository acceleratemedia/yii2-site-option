# yii2-site-option

This package provides utilities to create one-off records for key-value pairs that can be used as configuraiton options for an application. It provides a simple schema as well as a component for accessing those values from the database. The component includes its own caching component that is configured during bootstrapping.

This package does not supply any UI elements to be able to implement or use site options. Each application using this functionality will have the build its own interfaces to implement site options in the way that makes the most sense in the context of the site.

To use this package it requires a migration for a database table that will hold key-pair values for site options. Add something like the following to a console application configuration to set up the schema:
```php
[
    'controllerMap' => [
        'migrate' => [
            'class' => yii\console\controllers\MigrateController::class,
            'migrationNamespaces' => [
                'siteoption\console\migrations',
            ]
        ],
    ],
]
```

The other main part of this package is a component that can be used to access the site options. Add the following to the config file of any applicaiton that needs to access the site options:
```php
[
    'bootstrap' => [
        'site-option-core' => \siteoption\Bootstrap::class
        // --- Cache configuraiton can be customized as well.
        // [
        //     'class' => \yii\caching\FileCache::class
        // ]
    ],
]
```