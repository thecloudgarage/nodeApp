<?php

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
define('AUTH_KEY',         ')!ZfEB!3{ees.D8$fB*7g{1RxO|t]`jbQ-_+*^KD|&n*{)vcDJAg6s^GnImv?[tP');
define('SECURE_AUTH_KEY',  'z-1+cZ$-2@YuG_,i`61oa2u^DRke_+OxIu1_)-6R:XiI >=<UxWp38M,s)$>X*X0');
define('LOGGED_IN_KEY',    '[XH4.}z/${X&v-4(jKO0-zrp$En+wOD^-`YU=Y!:fgXReq$J1a#,}!*nxX`T~1:y');
define('NONCE_KEY',        'tRvk{O58Vzd,fCSYZ!Z k<s|oj /~%tvY}U1i|&b]T{Gdw+U]-If$<VE?h/+t#`}');
define('AUTH_SALT',        '.%t+($_T 7/bo*]^o+<a>8XMdKYqkKPXqn{ A}jRHak|I}WX:stG$0jVqhDsrl6x');
define('SECURE_AUTH_SALT', 'gCLf@yV;]DgEuQ|?J]H-#x0`C4$;wt6Z4($XRaH>g4#(bQR3g#2Z=GQXpSL3+).K');
define('LOGGED_IN_SALT',   '7s@`LxPd/u+1I[y#vBkdRH/0IvyvDw} A:G9BP-3go!?dIw]EnYP*`O9n@!|}}c|');
define('NONCE_SALT',       'VCkV(0VkEw`:e2xr1[<uAfV&wW@CE><>_Yehu!ZXJ+)2L-F83-KI>EK/p703=r}R');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';