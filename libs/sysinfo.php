<?php
/**
 * Model for the display of system information.
 *
 * @since  1.6
 */
class AdminModelSysInfo extends Object
{
    /**
     * Some PHP settings
     *
     * @var    array
     * @since  1.6
     */
    protected $php_settings = array();

    /**
     * Some system values
     *
     * @var    array
     * @since  1.6
     */
    protected $info = array();

    /**
     * PHP info
     *
     * @var    string
     * @since  1.6
     */
    protected $php_info = null;

    /**
     * Array containing the phpinfo() data.
     *
     * @var    array
     *
     * @since  3.5
     */
    protected $phpInfoArray;

    /**
     * Private/critical data that we don't want to share
     *
     * @var    array
     *
     * @since  3.5
     */
    protected $privateSettings = array(
        'phpInfoArray' => array(
            'CONTEXT_DOCUMENT_ROOT',
            'Cookie',
            'DOCUMENT_ROOT',
            'extension_dir',
            'error_log',
            'Host',
            'HTTP_COOKIE',
            'HTTP_HOST',
            'HTTP_ORIGIN',
            'HTTP_REFERER',
            'HTTP Request',
            'include_path',
            'mysql.default_socket',
            'MYSQL_SOCKET',
            'MYSQL_INCLUDE',
            'MYSQL_LIBS',
            'mysqli.default_socket',
            'MYSQLI_SOCKET',
            'PATH',
            'Path to sendmail',
            'pdo_mysql.default_socket',
            'Referer',
            'REMOTE_ADDR',
            'SCRIPT_FILENAME',
            'sendmail_path',
            'SERVER_ADDR',
            'SERVER_ADMIN',
            'Server Administrator',
            'SERVER_NAME',
            'Server Root',
            'session.name',
            'session.save_path',
            'upload_tmp_dir',
            'User/Group',
            'open_basedir',
        ),
        'other' => array(
            'db',
            'dbprefix',
            'fromname',
            'live_site',
            'log_path',
            'mailfrom',
            'memcache_server_host',
            'memcached_server_host',
            'open_basedir',
            'Origin',
            'proxy_host',
            'proxy_user',
            'proxy_pass',
            'secret',
            'sendmail',
            'session.save_path',
            'session_memcache_server_host',
            'session_memcached_server_host',
            'sitename',
            'smtphost',
            'tmp_path',
            'open_basedir',
        )
    );

    /**
     * System values that can be "safely" shared
     *
     * @var    array
     *
     * @since  3.5
     */
    protected $safeData;

    /**
     * Remove sections of data marked as private in the privateSettings
     *
     * @param   array   $dataArray  Array with data tha may contain private informati
     * @param   string  $dataType   Type of data to search for an specific section in the privateSettings array
     *
     * @return  array
     *
     * @since   3.5
     */
    protected function cleanPrivateData($dataArray, $dataType = 'other')
    {
        $dataType = isset($this->privateSettings[$dataType]) ? $dataType : 'other';

        $privateSettings = $this->privateSettings[$dataType];

        if (!$privateSettings) {
            return $dataArray;
        }

        foreach ($dataArray as $section => $values) {
            if (is_array($values)) {
                $dataArray[$section] = $this->cleanPrivateData($values, $dataType);
            }

            if (in_array($section, $privateSettings, true)) {
                $dataArray[$section] = $this->cleanSectionPrivateData($values);
            }
        }

        return $dataArray;
    }

    /**
     * Offuscate section values
     *
     * @param   mixed  $sectionValues  Section data
     *
     * @return  mixed
     *
     * @since   3.5
     */
    protected function cleanSectionPrivateData($sectionValues)
    {
        if (!is_array($sectionValues)) {
            //if (strstr($sectionValues, JPATH_ROOT)) {
            //    $sectionValues = 'xxxxxx';
            //}

            return strlen($sectionValues) ? 'xxxxxx' : '';
        }

        foreach ($sectionValues as $setting => $value) {
            $sectionValues[$setting] = strlen($value) ? 'xxxxxx' : '';
        }

        return $sectionValues;
    }

    /**
     * Method to get the PHP settings
     *
     * @return  array  Some PHP settings
     *
     * @since   1.6
     */
    public function &getPhpSettings()
    {
        if (!empty($this->php_settings)) {
            return $this->php_settings;
        }

        $this->php_settings = array(
            'safe_mode'          => ini_get('safe_mode') == '1',
            'display_errors'     => ini_get('display_errors') == '1',
            'short_open_tag'     => ini_get('short_open_tag') == '1',
            'file_uploads'       => ini_get('file_uploads') == '1',
            'magic_quotes_gpc'   => ini_get('magic_quotes_gpc') == '1',
            'register_globals'   => ini_get('register_globals') == '1',
            'output_buffering'   => (bool) ini_get('output_buffering'),
            'open_basedir'       => ini_get('open_basedir'),
            'session.save_path'  => ini_get('session.save_path'),
            'session.auto_start' => ini_get('session.auto_start'),
            'disable_functions'  => ini_get('disable_functions'),
            'xml'                => extension_loaded('xml'),
            'zlib'               => extension_loaded('zlib'),
            'zip'                => function_exists('zip_open') && function_exists('zip_read'),
            'mbstring'           => extension_loaded('mbstring'),
            'iconv'              => function_exists('iconv'),
            'mcrypt'             => extension_loaded('mcrypt'),
            'max_input_vars'     => ini_get('max_input_vars'),
        );

        return $this->php_settings;
    }

    /**
     * Method to get the system information
     *
     * @return  array  System information values
     *
     * @since   1.6
     */
    public function &getInfo()
    {
        if (!empty($this->info)) {
            return $this->info;
        }

        $this->info = array(
            'php'        => php_uname(),
            'phpversion' => phpversion(),
            'server'     => isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : getenv('SERVER_SOFTWARE'),
            'sapi_name'  => php_sapi_name(),
            'useragent'  => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "",
        );

        return $this->info;
    }

    /**
     * Check if the phpinfo function is enabled
     *
     * @return  boolean True if enabled
     *
     * @since   3.4.1
     */
    public function phpinfoEnabled()
    {
        return !in_array('phpinfo', explode(',', ini_get('disable_functions')));
    }

    /**
     * Method to get the PHP info
     *
     * @return  string  PHP info
     *
     * @since   1.6
     */
    public function &getPHPInfo()
    {
        if (!$this->phpinfoEnabled()) {
            $this->php_info = JText::_('COM_ADMIN_PHPINFO_DISABLED');

            return $this->php_info;
        }

        if (!is_null($this->php_info)) {
            return $this->php_info;
        }

        ob_start();
        date_default_timezone_set('UTC');
        phpinfo(INFO_GENERAL | INFO_CONFIGURATION | INFO_MODULES);
        $phpInfo = ob_get_contents();
        ob_end_clean();
        preg_match_all('#<body[^>]*>(.*)</body>#siU', $phpInfo, $output);
        $output = preg_replace('#<table[^>]*>#', '<table class="table table-striped adminlist">', $output[1][0]);
        $output = preg_replace('#(\w),(\w)#', '\1, \2', $output);
        $output = preg_replace('#<hr />#', '', $output);
        $output = str_replace('<div class="center">', '', $output);
        $output = preg_replace('#<tr class="h">(.*)<\/tr>#', '<thead><tr class="h">$1</tr></thead><tbody>', $output);
        $output = str_replace('</table>', '</tbody></table>', $output);
        $output = str_replace('</div>', '', $output);
        $this->php_info = $output;

        return $this->php_info;
    }

    /**
     * Get phpinfo() output as array
     *
     * @return  array
     *
     * @since   3.5
     */
    public function getPhpInfoArray()
    {
        // Already cached
        if (null !== $this->phpInfoArray) {
            return $this->phpInfoArray;
        }

        $phpInfo = $this->getPhpInfo();

        $this->phpInfoArray = $this->parsePhpInfo($phpInfo);

        return $this->phpInfoArray;
    }

    /**
     * Parse phpinfo output into an array
     * Source https://gist.github.com/sbmzhcn/6255314
     *
     * @param   string  $html  Output of phpinfo()
     *
     * @return  array
     *
     * @since   3.5
     */
    protected function parsePhpInfo($html)
    {
        $html = strip_tags($html, '<h2><th><td>');
        $html = preg_replace('/<th[^>]*>([^<]+)<\/th>/', '<info>\1</info>', $html);
        $html = preg_replace('/<td[^>]*>([^<]+)<\/td>/', '<info>\1</info>', $html);
        $t = preg_split('/(<h2[^>]*>[^<]+<\/h2>)/', $html, -1, PREG_SPLIT_DELIM_CAPTURE);
        $r = array();
        $count = count($t);
        $p1 = '<info>([^<]+)<\/info>';
        $p2 = '/' . $p1 . '\s*' . $p1 . '\s*' . $p1 . '/';
        $p3 = '/' . $p1 . '\s*' . $p1 . '/';

        for ($i = 1; $i < $count; $i++) {
            if (preg_match('/<h2[^>]*>([^<]+)<\/h2>/', $t[$i], $matchs)) {
                $name = trim($matchs[1]);
                $vals = explode("\n", $t[$i + 1]);

                foreach ($vals AS $val) {
                    if (preg_match($p2, $val, $matchs)) {
                        // 3cols
                        $r[$name][trim($matchs[1])] = array(trim($matchs[2]), trim($matchs[3]),);
                    } elseif (preg_match($p3, $val, $matchs)) {
                        // 2cols
                        $r[$name][trim($matchs[1])] = trim($matchs[2]);
                    }
                }
            }
        }

        return $r;
    }

    /**
     * Execute and display a template script.
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  mixed  A string if successful, otherwise an Error object.
     *
     * @since   1.6
     */
    public function display($tpl = null)
    {
        //$this->php_settings = $this->get('PhpSettings');
        //$this->info         = $this->get('info');
        //$this->php_info     = $this->get('PhpInfo');
        $this->php_settings = $this->getPhpSettings();
        $this->info         = $this->getInfo();
        //$this->php_info     = $this->getPhpInfoArray();
        $this->php_info     = $this->getPHPInfo();
        require($tpl);
    }

    public function loadTemplate($path)
    {
        require($path);
    }
}
