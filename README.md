# yii2-site-options

This module requres migrations to be run to set up the database. The following
command can be used to apply all migrations for this module:
```
php yii migrate --migrationPath='@vendor/brianvb/yii2-site-option/src/console/migrations', \
	--migrationTable=m_bvb_site_option \
	--interactive=0
```