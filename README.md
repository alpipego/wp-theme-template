## wp-theme-template

My starting point for creating a wordpress theme, built with foundation5-grid, grunt, libsass, bourbon

* Changed scss/foundation/components/_grid.scss line 98 `collapse: false` to `collapse: true`
* `git-archive.sh` is an archiving script, that best works when a tag is checked out. It then creates a zip file in the format `REPO_NAME`.`TAG`.zip

# Usage

Check the `Path` class and see if it reflects your folder structure. The assumption is either that you use the default wordpress structure (with all files inside the theme folder in `wp-content/themes/`) or an alternative in the form:

```
    wordpress
        assets
            img
            css
            js
            themes
        extensions
        uploads
        wp-admin
        wp-includes
        ...
```

Define them with the help of these constants:

``` php
define('WP_SITEURL', '' );

define ('WP_CONTENT_FOLDERNAME', 'assets');
define ('WP_CONTENT_DIR', ABSPATH . WP_CONTENT_FOLDERNAME) ;
define ('WP_CONTENT_URL', WP_SITEURL . WP_CONTENT_FOLDERNAME);
define ('WP_PLUGIN_DIR', ABSPATH . '/extensions');
define ('WP_PLUGIN_URL', WP_SITEURL .'extensions');
define( 'UPLOADS', '/uploads' );
```

If you choose a different structure for these files you should reflect these changes in the `Path` class 
