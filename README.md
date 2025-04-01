# Pages Lists

Improved pages plugin for Bludit CMS.

![Tested up to Bludit version 3.16.2](https://img.shields.io/badge/Bludit-3.16.2-e6522c.svg?style=flat-square "Tested up to Bludit version 3.16.2")
![Minimum PHP version is 7.4](https://img.shields.io/badge/PHP_Min-7.4-8892bf.svg?style=flat-square "Minimum PHP version is 7.4")
![Tested on PHP version 8.2.4](https://img.shields.io/badge/PHP_Test-8.2.4-8892bf.svg?style=flat-square "Tested on PHP version 8.2.4")

## Sidebar Pages List

The sidebar pages list requires no coding and is enabled by default. It can be disabled on the settings page. The HTML markup and the CSS classes for the list are nearly identical to the original Bludit pages plugin for those who have already written custom CSS for the sidebar pages list.

When enabled, the sidebar pages list has several options for customizing to your needs.

## Default Settings

The array below is the complete array of arguments used to construct a pages list. Any of these can be overridden with an array of arguments passed to a function call. These are also used by the sidebar pages list but array values are overridden by the plugin with settings values.

``` php
<?php
$defaults = [
	'wrap'       => false,
	'wrap_class' => 'list-wrap pages-list-wrap',
	'direction'  => 'vert', // horz or vert
	'list_class' => 'pages-list standard-content-list',
	'label'      => false,
	'label_el'   => 'h2',
	'links'      => true
];
?>
```

## Template Tags

The pages list function accepts an array of arguments to override the function defaults. It is also namespaced so the function must be preceded by the namespace or aliased.

Following is an example of displaying a default list in a theme template.
Note the PageLists namespace and backslash before the function call.

``` php
<?php PageLists\pages_list(); ?>
```

The following example demonstrates the addition of a list label.

``` php
<?php PageLists\pages_list( [ 'label' => $L->get( 'Content Types' ) ] ); ?>
```

The following example shows the post count, modifies the heading element, and changes the direction. Changing the direction of the list requires you to add CSS if you override the default classes.

``` php
<?php
$pages_list = [
	'label_el'  => 'h3',
	'direction' => 'horz'
];
echo PageLists\pages_list( $pages_list );
?>
```

The function is configurable by an array of parameters.
