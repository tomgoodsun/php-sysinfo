<?php
class JText
{
    private static $msg_list = array(
        'COM_ADMIN_PHP_BUILT_ON' => "PHP Built On",
        'COM_ADMIN_PHP_VERSION' => "PHP Version",
        'COM_ADMIN_SAFE_MODE' => "Safe Mode",
        'COM_ADMIN_SETTING' => "Setting",
        'COM_ADMIN_SYSTEM_INFORMATION' => "System Information",
        'COM_ADMIN_USER_AGENT' => "User Agent",
        'COM_ADMIN_VALUE' => "Value",
        'COM_ADMIN_WEBSERVER_TO_PHP_INTERFACE' => "WebServer to PHP Interface",
        'COM_ADMIN_DISPLAY_ERRORS' => "Display Errors",
        'COM_ADMIN_FILE_UPLOADS' => "File Uploads",
        'COM_ADMIN_MAGIC_QUOTES' => "Magic Quotes",
        'COM_ADMIN_OPEN_BASEDIR' => "Open basedir",
        'COM_ADMIN_OUTPUT_BUFFERING' => "Output Buffering",
        'COM_ADMIN_REGISTER_GLOBALS' => "Register Globals",
        'COM_ADMIN_SESSION_SAVE_PATH' => "Session Save Path",
        'COM_ADMIN_SHORT_OPEN_TAGS' => "Short Open Tags",
        'COM_ADMIN_DISABLED_FUNCTIONS' => "Disabled Functions",
        'COM_ADMIN_ICONV_AVAILABLE' => "Iconv Available",
        'COM_ADMIN_MAX_INPUT_VARS' => "Maximum Input Variables",
        'COM_ADMIN_MBSTRING_ENABLED' => "Multibyte String (mbstring) Enabled",
        'COM_ADMIN_MCRYPT_ENABLED' => "Mcrypt Enabled",
        'COM_ADMIN_SESSION_AUTO_START' => "Session Auto Start",
        'COM_ADMIN_XML_ENABLED' => "XML Enabled",
        'COM_ADMIN_ZIP_ENABLED' => "Native ZIP Enabled",
        'COM_ADMIN_ZLIB_ENABLED' => "Zlib Enabled",
        'COM_ADMIN_PHP_INFORMATION' => "PHP Information",
        'COM_ADMIN_RELEVANT_PHP_SETTINGS' => "Relevant PHP Settings",
    );

    public static function _($label)
    {
        //$args = func_get_args();
        //return call_user_func_array('sprintf', $this->initPrintfArgs($args));
        return isset(self::$msg_list[$label]) ? self::$msg_list[$label] : $label;
    }
}

