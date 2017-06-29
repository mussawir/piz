<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'joycare_wp11');

/** MySQL database username */
define('DB_USER', 'joycare_wp11');

/** MySQL database password */
define('DB_PASSWORD', 'J~9g0KSv#vY0lF9tJE&06&~7');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'oiRiibzOKH5ke2nTJlnapllk8eLn6vkKu1CCoEjfkqKu8nrRvpYsnrlqQKJrQdtR');
define('SECURE_AUTH_KEY',  'sBoG17Jy49zddbCmmjxfxbEXY5JLKxRirydO6QoJN3TKgM0JgDrBCClK1cirHu18');
define('LOGGED_IN_KEY',    'If2OOz0CyI5pmTZQnlrgjBK9TfCpIOmCIPvFNuq6NoaQI5p8qOgsMcSOCwXWvs4P');
define('NONCE_KEY',        'iYow5GJVBnNZqJRhHUmkH5Eh57JGT0MWVgzDq1rmYgN7PPqnrrboE9HD7vDkkkYz');
define('AUTH_SALT',        'X9RQdYYImIUEjJ0rs1WzwV0HKxmdBS802YEnipxqpHPMdWeDwvAi4zUnDwITXybA');
define('SECURE_AUTH_SALT', 'DIwnbkbfIKLm55z8b3BGLcAjmu6WT4YuRlJ0LQHTbTLkNQVLSzQgwKFyFDCtgq5y');
define('LOGGED_IN_SALT',   'w7lgWp6wSjEH7esCbwI2kZ06UivxzaropXzMKH2fc3929jaUC4oMe37tZ69aUOFB');
define('NONCE_SALT',       'dx18XWXyNXasBDR452ypp33XtH9NawBdS4JkLHo0FtJ5b49zEWXw4j9LqsXw3o4h');

/**
 * Other customizations.
 */
define('FS_METHOD','direct');define('FS_CHMOD_DIR',0777);define('FS_CHMOD_FILE',0666);
define('WP_TEMP_DIR',dirname(__FILE__).'/wp-content/uploads');

/**
 * Turn off automatic updates since these are managed upstream.
 */
define('AUTOMATIC_UPDATER_DISABLED', true);


/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
