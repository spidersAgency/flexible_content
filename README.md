# Flexible Content
## Instalation
1. Install this plugin by uploading to wp-content/plugins
2. Create `flex-content.php` file in your active theme

## Filters
`sa_flex_content_post_type`
In which post types Flexible Content will be used
**default: `['post']`**

`sa_flex_content_field_name`
Flexible Content field name
**default: 'flexible_content'**

`sa_flex_content_template_file`
File in which all the magic happens - read more here https://www.advancedcustomfields.com/resources/get_row_layout/
**default: `get_template_directory(). '/flex_content.php'`**

## Example flex-content.php
```php
$flex[ 'fields' ] = get_row( true );

if ( 'text' == get_row_layout() ) {
    $tpl = 'text';
} elseif ( 'osoba' == get_row_layout() ) {
    $tpl = 'person';
} 

Timber::render( 'views/flex/'.$tpl.'.twig', $flex );
unset( $flex );
```

## Rebuilding posts
If you want to rebuild all the posts go to `options > Flexible Content` and click *Submit*.

## Changelog
1.1 - Added posibility to rebuid all posts
1.0 - Inital version