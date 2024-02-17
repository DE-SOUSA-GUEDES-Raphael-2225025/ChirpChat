<?php

namespace chirpchat\utils;

class Notification
{

    public static function createErrorMessage(string $message): void
    {
        $_SESSION['notification'] = [
            'type' => 'ERROR',
            'message' => $message,
        ];
    }

    public static function createSuccessMessage(string $message): void
    {
        $_SESSION['notification'] = [
            'type' => 'SUCCESS',
            'message' => $message,
        ];
    }

    public static function createInformationMessage(string $message): void
    {
        $_SESSION['notification'] = [
            'type' => 'INFO',
            'message' => $message,
        ];
    }
}
