<?php

final class AppsHelpers
{
    # Static Methods #

    public static function isValidHash(array $params, $hash, $secret)
    {
        if (isset($params['hash'])) {
            unset($params['hash']);
        }

        $params = http_build_query($params);
        $calculatedHash = hash_hmac('sha256', $params, $secret);

        return ($hash == $calculatedHash);
    }

    public static function isValidHashForString($s, $hash, $secret) {
        $calculatedHash = base64_encode(hash_hmac('sha256', $s, $secret, true));
        return ($hash == $calculatedHash);
    }

    # End #
}

