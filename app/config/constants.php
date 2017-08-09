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
define("SESSION_PREPEND", "10020_front_"); // session prepend
define("SUPER_ADMIN_USER_ID", "1"); // super-admin id
define("SUPER_ADMIN_USER", "1"); // super-admin type
define("ADMIN_USER", "2"); // admin or sub-admin type
define("B2B_USER", "3"); // b2b type
define("B2C_USER", "4"); // b2c type
define("SUBSCRIBERS", "5"); // subscribers type

define("REL_BASE_PATH", dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR);
define("CSS_PATH", "css".DIRECTORY_SEPARATOR);
define("JS_PATH", "js".DIRECTORY_SEPARATOR);
define("IMAGE_PATH", "images".DIRECTORY_SEPARATOR);
define("REL_IMAGE_UPLOAD_PATH",REL_BASE_PATH."upload_files".DIRECTORY_SEPARATOR);

define("B2B_DEPOSIT_ACC", "deposit");
define("B2B_CREDIT_ACC", "credit");

define("EXT", ".php");
define("OTHER_COUNTRY", "0X");
define("NO_REGION", "XXX");
define("DEFAULT_LANG", "fa");
define("IRAN", "IR");
define("IRR", "IRR");
define("fIRR","ریال ");
define("USD", "USD");
define("USDFAR","دلار آمریکا");
define("DEFAULT_CURRENCY", IRR);
define("IRR_fCURRENCY", fIRR);
define("USDFAR_CURRENCY",USDFAR);
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

define("STATIC_CONTACT_SLUG", "contact-us");
define("STATIC_ABOUT_SLUG", "about-us");
define("STATIC_PAGE_TYPE", "1");
define("LINK_PAGE_TYPE", "2");

// default image paths
define("B2B_DEFAULT_IMG", "b2c/images/default.png");
define("B2C_DEFAULT_IMG", "b2b/images/default.png");
define("PERPAX_TYPE", 1);
define("BOOK_TYPE_RESERVE", "reserve");
define("BOOK_TYPE_BOOK", "book");
define("ONEWAY", "OneWay");
define("ROUNDTRIP", "Return");
define("MULTICITY", "OpenJaw");
define("IORDI", "I");
define("IORDD", "D");
define("CIP_IN", "in");
define("CIP_OUT", "out");

// Services
define("INTERNATIONAL_FLIGHTS", "1");
define("DOMESTIC_FLIGHTS", "2");
define("HOTEL", "3");
define("DEFAULT_FLIGHT_ORIGIN", "THR");
define("DEFAULT_FLIGHT_DESTINATION", "MHD");

define("DOMESTIC_INFANT_PRICE", 500000);
define("TOMAN", 10);
define("EPOCH_BY", 1000);

define("TYPE_JSON", "JSON");
define("TYPE_STRING", "STRING");

define("GREGORIAN_EPOCH", "1721425.5");
define("PERSIAN_EPOCH", "1948320.5");

// Page Types
define("QUICK_LINK_PAGE", "1");
define("TRAVELLER_TOOL_PAGE", "2");
define("LEGAL_PAGE", "3");
define("ABOUT_COMPANY_PAGE", "4");

// APIs
define("PARTOCRS", "1");

// Booking status
define("PARTO_BOOK_STATUS", 10);
define("PARTO_PENDING_STATUS", 11);
define("PARTO_WAIT_STATUS", 12);
define("PARTO_TKT_PROCESS_STATUS", 20);
define("PARTO_TICKETED_STATUS", 21);
define("PARTO_TKTD_CHANGED_STATUS", 22);
define("PARTO_TKTD_SCHEDULE_CHANGED_STATUS", 23);
define("PARTO_CANCEL_STATUS", 30);
define("PARTO_URGENT_STATUS", 40);
define("PARTO_API_URGENT_STATUS", 41);
define("PARTO_TIME_LIMIT_CHANGE_STATUS", 42);

// Payment Status
define("IRANKISH_INVALID_CHARS", -20);
define("IRANKISH_TRANS_REFLUXED", -30);
define("IRANKISH_ILLEGAL_QUERY", -50);
define("IRANKISH_ERR_REQ", -51);
define("IRANKISH_TRANS_NOT_FOUND", -80);
define("IRANKISH_ERR_INTERNAL_BANK", -81);
define("IRANKISH_TRANS_VERIFIED", -90);
define("IRANKISH_OK", 100);
define("IRANKISH_USER_CANCELLED", 110);
define("IRANKISH_LOW_BALANCE", 120);
define("IRANKISH_ERR_CARD_INFO", 130);
define("IRANKISH_ERR_CARD_PASSWORD", 131);
define("IRANKISH_ERR_CARD_BLOCKED", 132);
define("IRANKISH_ERR_CARD_EXPIRED", 133);
define("IRANKISH_ERR_TIMEOUT", 140);
define("IRANKISH_ERR_BANK_INTERNAL", 150);
define("IRANKISH_ERR_CVV_EXP_DATE", 160);
define("IRANKISH_TRANS_PERMISSION_DENIED", 166);
define("IRANKISH_AMOUNT_LIMIT", 200);
define("IRANKISH_DAY_LIMIT", 201);
define("IRANKISH_MONTH_LIMIT", 202);
