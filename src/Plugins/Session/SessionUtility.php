<?php

namespace App\Plugins\Session;

class SessionUtility
{
    /**
     * Determines whether the session is started
     *
     * @return bool
     */
    public static function isSessionStarted(): bool
    {
        if (php_sapi_name() !== 'cli') {
            if (version_compare(phpversion(), '5.4.0', '>=')) {
                return session_status() === PHP_SESSION_ACTIVE;
            } else {
                return session_id() === '' ? FALSE : TRUE;
            }
        }
        return FALSE;
    }
}