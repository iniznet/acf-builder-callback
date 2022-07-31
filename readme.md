# ACF Builder Callback

A package made for [ACF Builder](https://github.com/stoutlogic/acf-builder) extension to quickly create ACF configuration with sanitization and escape callback within builder.

### Simple Example
```php
$banner = new iniznet\AcfBuilderCallback\FieldsBuilder('banner');
$banner
    ->addText('title', [
        'label' => 'Title',
        'instructions' => 'Enter the title of the banner.',
        'required' => true,
        'maxlength' => 100,
        'placeholder' => 'Enter title',
        'sanitization_cb' => 'sanitize_greater_than_30',
        'escape_cb' => 'escape_greater_than_30',
    ])
    ->addWysiwyg('content')
    ->addImage('background_image')
    ->setLocation('post_type', '==', 'page')
        ->or('post_type', '==', 'post');

add_action('acf/init', function() use ($banner) {
   acf_add_local_field_group($banner->build());
});

/**
 * Handle the sanitization of the title field.
 * Ensures that the title is greater than 30 characters or nothing.
 * 
 * @param mixed         $value      The value of the title field.
 * @param int|string    $post_id    The post ID.
 * @param array         $field      The field settings.
 * @param mixed         $original   The original value before modification.
 * 
 * @return mixed                    The sanitized value.
 */
function sanitize_greater_than_30($value, $post_id, $field, $original) {
    if (strlen($value) > 30) {
        return $value;
    }
    return '';
}

/**
* Handle the escaping of the title field.
* Ensures that the title is greater than 30 characters or nothing.
* 
* @param mixed         $value      The value of the title field.
* @param int|string    $post_id    The post ID.
* @param array         $field      The field settings.
* 
* @return mixed                    The escaped value.
*/
function escape_greater_than_30($value, $post_id, $field, $original) {
    if (strlen($value) > 30) {
        return esc_html($value);
    }
    return '';
}
```

If you're using the [ACF Composer](https://github.com/Log1x/acf-composer)
```php
<?php

namespace App\Fields;

use Log1x\AcfComposer\Field;
use iniznet\AcfBuilderCallback\FieldsBuilder;

class Example extends Field
{
    /**
     * The field group.
     *
     * @return array
     */
    public function fields()
    {
        $example = new FieldsBuilder('example');

        $example
            ->setLocation('post_type', '==', 'post');

        $example
            ->addRepeater('items')
                ->addText('item', [
                    'label' => 'Item',
                    'instructions' => 'Enter the item.',
                    'required' => true,
                    'maxlength' => 100,
                    'placeholder' => 'Enter item',
                    'sanitization_cb' => function ($value) {
                        return strlen($value) > 30 ? $value : '';
                    },
                    'escape_cb' => function ($value) {
                        return strlen($value) > 30 ? esc_html($value) : '';
                    },
                ])
            ->endRepeater();

        return $example->build();
    }
}
```

## TODO
- [x] Field sanitization and escaping callback
- [ ] ?

## Requirements
PHP 7.4 through 8.0 Tested.

## Install
Use composer to install:
```
composer require iniznet/acf-builder-callback
```

If your project isn't using composer, you can require the `autoload.php` file.

## Tests
There are no tests for this package yet.

## Bug Reports
If you discover a bug in ACF Builder Callback, please [open an issue](https://github.com/iniznet/acf-builder-callback/issues).

## Contributing
Contributing whether it be through PRs, reporting an issue, or suggesting an idea is encouraged and appreciated.

## License
ACF Builder Callback is provided under the [MIT License](LICENSE.md).
