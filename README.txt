<?php
/**
 * Simple CPT & Fields - Read Me
 *
 * ## Installation
 * 1. Upload the `simple-cpt-fields` folder to the `/wp-content/plugins/` directory.
 * 2. Activate the plugin through the 'Plugins' menu in WordPress.
 *
 * ## Usage
 *
 * ### Creating Custom Post Types and Taxonomies
 * 1. Navigate to **Simple CPT & Fields** in the admin dashboard.
 * 2. Use the **Custom Post Types** form to register new post types.
 * 3. Use the **Custom Taxonomies** form to register new taxonomies and attach them to post types.
 *
 * ### Adding Custom Fields
 * 1. Navigate to **Simple CPT & Fields**.
 * 2. Use the **Custom Fields** form to add fields to specific Post Types.
 * 3. Supported types: Text, Text Area, Number, Email, URL, Date, Color.
 *
 * ### displaying Fields with Elementor
 * 1. Edit a page or template with Elementor.
 * 2. Use the **SCF Field Display** widget to output a field's value.
 * 3. OR use any widget that supports Dynamic Tags (e.g. Heading, Text Editor), click the Dynamic Tag icon, and select **SCF Field**.
 *
 * ### Displaying Fields with Shortcode
 * You can display a field value anywhere using the shortcode:
 * `[scf_field key="my_field_key"]`
 *
 * For a specific post ID:
 * `[scf_field key="my_field_key" post_id="123"]`
 *
 * ### Using in PHP Templates
 * ```php
 * $value = scf_get_field( 'my_field_key' );
 * echo $value;
 * ```
 */
