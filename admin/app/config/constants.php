<?php defined("BASEPATH") OR exit("No direct script access allowed");

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define("FILE_READ_MODE", 0644);
define("FILE_WRITE_MODE", 0666);
define("DIR_READ_MODE", 0755);
define("DIR_WRITE_MODE", 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define("FOPEN_READ", "rb");
define("FOPEN_READ_WRITE", "r+b");
define("FOPEN_WRITE_CREATE_DESTRUCTIVE", "wb"); // truncates existing file data, use with care
define("FOPEN_READ_WRITE_CREATE_DESTRUCTIVE", "w+b"); // truncates existing file data, use with care
define("FOPEN_WRITE_CREATE", "ab");
define("FOPEN_READ_WRITE_CREATE", "a+b");
define("FOPEN_WRITE_CREATE_STRICT", "xb");
define("FOPEN_READ_WRITE_CREATE_STRICT", "x+b");

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
define("SHOW_DEBUG_BACKTRACE", TRUE);

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
define("EXIT_SUCCESS", 0); // no errors
define("EXIT_ERROR", 1); // generic error
define("EXIT_CONFIG", 3); // configuration error
define("EXIT_UNKNOWN_FILE", 4); // file not found
define("EXIT_UNKNOWN_CLASS", 5); // unknown class
define("EXIT_UNKNOWN_METHOD", 6); // unknown class member
define("EXIT_USER_INPUT", 7); // invalid user input
define("EXIT_DATABASE", 8); // database error
define("EXIT__AUTO_MIN", 9); // lowest automatically-assigned error code
define("EXIT__AUTO_MAX", 125); // highest automatically-assigned error code

/* custom constants */
define("PROJECT_NAME", "10020.ir"); // site title
define("SALT", "PrInCe_s@lt_Ke\|$"); // plain partial Salt
define("SESSION_PREPEND", "10020ir_admin_"); // session prepend
define("SUPER_ADMIN_USER_ID", "1"); // super-admin id
define("SUPER_ADMIN_USER", "1"); // super-admin type
define("ADMIN_USER", "2"); // admin or sub-admin type
define("B2B_USER", "3"); // b2b type
define("B2C_USER", "4"); // b2c type
define("SUBSCRIBERS", "5"); // subscribers type
define("CALL_CENTER_STAFF", "6"); // call center type

// assets paths
define("REL_BASE_PATH", dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR);
define("CSS_PATH", "css".DIRECTORY_SEPARATOR);
define("JS_PATH", "js".DIRECTORY_SEPARATOR);
define("IMAGE_PATH", "images".DIRECTORY_SEPARATOR);
define("REL_IMAGE_UPLOAD_PATH",REL_BASE_PATH."../upload_files".DIRECTORY_SEPARATOR);

// credit and deposit constants
define("B2B_DEPOSIT_ACC", "1");
define("B2B_CREDIT_ACC", "2");
define("B2B_DEPOSIT_ACC_NAME", "deposit");
define("B2B_CREDIT_ACC_NAME", "credit");

define("OTHER_COUNTRY", "0X");
define("NO_REGION", "XXX");
define("DEFAULT_LANG", "en");
define("DEFAULT_CURRENCY", "USD");
define("DEFAULT_EXT", ".html");

define("MENU_STATUS", "1");
define("METATAG_TYPE_NAME", "name");
define("METATAG_TYPE_HTTP", "http-equiv");


// Transaction Types
define("TRANX_ADD_CREDIT_ADMIN", "1");
define("TRANX_REQUEST_CREDIT_B2B", "2");
define("TRANX_ADD_DEPOSIT_ADMIN", "3");
define("TRANX_REQUEST_DEPOSIT_B2B", "4");
define("TRANX_FORCE_SETTLEMENT_B2B", "5");
define("TRANX_REQUEST_FULL_SETTLEMENT_B2B", "6");
define("TRANX_PARTIAL_SETTLEMENT", "7");

// Static pages constants
define("STATIC_CONTACT_SLUG", "contact-us");
define("STATIC_ABOUT_SLUG", "about-us");
define("STATIC_PAGE_TYPE", "1");
define("LINK_PAGE_TYPE", "2");

// default image paths
define("B2B_DEFAULT_IMG", "b2c/images/default.png");
define("B2C_DEFAULT_IMG", "b2b/images/default.png");

// promocode constants
define("PAGE_NA", "NA");
define("DEFAULT_DISCOUNT", "1"); // Percentage Amount

//email template contstants
define("MODIFIER_ADM", "Admin");
define("MODIFIER_SELF", "You");

define("ACCOUNT_ADM", "Admin");
define("ACCOUNT_B2C", "B2C");

// Page Types
define("QUICK_LINK_PAGE", "1");
define("TRAVELLER_TOOL_PAGE", "2");
define("LEGAL_PAGE", "3");
define("ABOUT_10020_PAGE", "4");

// REDIRECTION
define("REDIRECTION_URL", "http://sepehr360.com");
define("GREGORIAN_EPOCH", "1721425.5");
define("PERSIAN_EPOCH", "1948320.5");
