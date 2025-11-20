<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Enable basic features
add_theme_support( 'title-tag' );
add_theme_support( 'post-thumbnails' );

// Remove WordPress bloat (since Base47 is a shell)
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
remove_action( 'wp_head', 'rest_output_link_wp_head' );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );

// Keep admin bar working
add_theme_support( 'admin-bar', array( 'callback' => '__return_true' ) );

// No CSS from theme – we want clean output
function base47_disable_theme_css() {
    wp_dequeue_style( 'base47-style' );
}
add_action( 'wp_enqueue_scripts', 'base47_disable_theme_css', 100 );