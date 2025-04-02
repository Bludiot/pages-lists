<?php
/**
 * Functions
 *
 * @package    Pages Lists
 * @subpackage Core
 * @category   Functions
 * @since      1.0.0
 */

namespace PageLists;

// Stop if accessed directly.
if ( ! defined( 'BLUDIT' ) ) {
	die( 'You are not allowed direct access to this file.' );
}

/**
 * Static pages list
 *
 * @since  1.0.0
 * @param  mixed $args Arguments to be passed.
 * @param  array $defaults Default arguments.
 * @global object $site; The Site class.
 * @return string Returns the list markup.
 */
function pages_list( $args = null, $defaults = [] ) {

	// Access global variables.
	global $site;

	// Plugin instance.
	$plugin = new \Pages_Lists();

	// Default arguments.
	$defaults = [
		'source'     => 'system', // system or plugin
		'wrap'       => false,
		'wrap_class' => 'list-wrap pages-list-wrap',
		'direction'  => 'vert', // horz or vert
		'separator'  => false, // false or string
		'list_class' => 'pages-list standard-content-list',
		'label'      => false,
		'label_el'   => 'h2',
		'links'      => true
	];

	// Maybe override defaults.
	if ( is_array( $args ) && $args ) {
		if ( isset( $args['direction'] ) && 'horz' == $args['direction'] && ! isset( $args['list_class'] ) ) {
			$defaults['list_class'] = 'pages-list inline-content-list';
		}
		$args = array_merge( $defaults, $args );
	} else {
		$args = $defaults;
	}

	// Label wrapping elements.
	$get_open  = str_replace( ',', '><', $args['label_el'] );
	$get_close = str_replace( ',', '></', $args['label_el'] );

	$label_el_open  = "<{$get_open}>";
	$label_el_close = "</{$get_close}>";

	// List markup.
	$html = '';
	if ( $args['wrap'] ) {
		$html = sprintf(
			'<div class="%s">',
			$args['wrap_class']
		);
	}
	if ( $args['label'] ) {
		$html .= sprintf(
			'%1$s%2$s%3$s',
			$label_el_open,
			$args['label'],
			$label_el_close
		);
	}
	$html .= sprintf(
		'<ul class="%s">',
		$args['list_class']
	);

	// Conditional source of the pages to list.
	$static = buildStaticPages();
	$select = false;
	if ( 'plugin' == $args['source'] && 'select' == $plugin->pages_display() ) {
		$static = $plugin->pages_select();
		$select = true;
	}

	$last = end( $static );
	foreach ( $static as $page ) {

		// Skip the `home` page key.
		if ( $page == 'home' ) {
			continue;
		}

		// Build page from manual selection.
		if ( $select ) {
			$page = buildPage( $page );
		}

		// Skip 404 page.
		if ( $page->key() == $site->pageNotFound() ) {
			continue;
		}

		// Pages separator.
		$sep = null;
		if ( is_string( $args['separator'] ) && 'horz' == $args['direction'] ) {
			$sep = $args['separator'];

			// No separator after the last page.
			if ( $select && $page->key() == $last ) {
				$sep = null;
			} elseif ( $page == $last ) {
				$sep = null;
			}
		}

		// Item class.
		$classes = [ 'static-page' ];
		if ( $page->hasChildren() ) {
			$classes[] = 'parent-page';
		} elseif ( $page->isChild() ) {
			$classes[] = 'child-page';
		}
		$classes = implode( ' ', $classes );

		$html .= "<li class='{$classes}'>";
		if ( $args['links'] ) {
			$html .= '<a href="' . $page->permalink() . '">';
		}
		$html .= $page->title();
		if ( $args['links'] ) {
			$html .= '</a>';
		}
		$html .= '</li>' . $sep;
	}
	$html .= '</ul>';

	if ( $args['wrap'] ) {
		$html .= '</div>';
	}
	return $html;
}

/**
 * Sidebar list
 *
 * @since  1.0.0
 * @global object $L The Language class.
 * @return string Returns the list markup.
 */
function sidebar_list() {

	// Access global variables.
	global $L;

	// Get the plugin object.
	$plugin = new \Pages_Lists;

	// Override default function arguments.
	$args = [
		'wrap'       => true,
		'wrap_class' => 'list-wrap pages-list-wrap-wrap plugin plugin-pages-list'
	];

	if ( 'select' == $plugin->pages_display() ) {
		$args['source'] = 'plugin';
	}

	$args['label_el'] = $plugin->label_wrap();

	if ( ! empty( $plugin->label() ) ) {
		$args['label'] = $plugin->label();
	}

	if ( 'horz' == $plugin->list_view() ) {
		$args['direction'] = 'horz';
		if ( $plugin->separator() ) {
			$args['separator'] = ' | ';
		}
	}

	// Return a modified list.
	return pages_list( $args );
}
