<?php
class JHtml
{
    public static function _($label)
    {
        $parts = explode('.', $label);
        $prefix = 'JHtml';
        $file = $parts[0];
        $func = $parts[1];
        $className = $prefix . ucfirst($file);

        $toCall = array($className, $func);
        if (is_callable($toCall)) {
            //static::register($key, $toCall);
            $args = func_get_args();

            // Remove function name from arguments
            array_shift($args);

            return static::call($toCall, $args);
        }
    }

    /**
     * Function caller method
     *
     * @param   callable  $function  Function or method to call
     * @param   array     $args      Arguments to be passed to function
     *
     * @return  mixed   Function result or false on error.
     *
     * @see     https://secure.php.net/manual/en/function.call-user-func-array.php
     * @since   1.6
     * @throws  InvalidArgumentException
     */
    protected static function call($function, $args)
    {
        if (!is_callable($function)) {
            throw new InvalidArgumentException('Function not supported', 500);
        }

        // PHP 5.3 workaround
        $temp = array();

        foreach ($args as &$arg) {
            $temp[] = &$arg;
        }

        return call_user_func_array($function, $temp);
    }
}
