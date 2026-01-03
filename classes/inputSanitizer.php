<?php

class inputSanitizer
{
    public function sanitizeUsername($username)
    {
        $username = filter_var($username, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        return ($username);
    }

    function hashPassword($passcode)
    {
        $hashedPassword = password_hash($passcode, PASSWORD_BCRYPT);
        return ($hashedPassword);
    }
}
