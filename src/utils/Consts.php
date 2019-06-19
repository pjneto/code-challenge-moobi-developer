<?php 

/** SUCCESS CODES */
define("OK", 1);
define("PRODUCT_SAVE", 2);
define("PRODUCT_EDIT", 3);
define("PRODUCT_DELETE", 4);


/** ERROR CODES */
define("ERROR", -1);
define("ERR_PRODUCT_SAVE", -2);
define("ERR_PRODUCT_EDIT", -3);
define("ERR_PRODUCT_DELETE", -4);
define("ERR_ORDER_SAVE", -5);
define("ERR_ORDER_EDIT", -6);
define("ERR_ORDER_DELETE", -7);
define("ERR_ORDER_ITEM_SAVE", -8);
define("ERR_ORDER_ITEM_EDIT", -9);
define("ERR_ORDER_ITEM_DELETE", -10);

/** COD STATUS OF PRODUCT */
define("PRO_INACTIVE", 0);
define("PRO_ACTIVE", 1);

/** COD PAYMENTS */
define("PAY_CASH", 0);
define("PAY_CREDIT_CARD", 1);
define("PAY_BANK_SLIP", 2);

/** COD STATUS OF ORDER */
define("ORD_PAYED", 0);
define("ORD_CANCELLED", 1);
define("ORD_OPEN", 2);