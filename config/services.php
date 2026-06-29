<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'openai' => [
        'key' => env('OPENAI_API_KEY'),
        'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
        'base_url' => env('OPENAI_BASE_URL', 'https://api.openai.com/v1'),
        'assistant_model' => env('OPENAI_ASSISTANT_MODEL', 'gpt-5.4-mini'),
    ],

    'whatsapp' => [
        'access_token' => env('WHATSAPP_ACCESS_TOKEN'),
        'business_account_id' => env('WHATSAPP_BUSINESS_ACCOUNT_ID'),
        'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID'),
        'phone_number' => env('WHATSAPP_PHONE_NUMBER'),
        'api_version' => env('WHATSAPP_API_VERSION', 'v21.0'),
        'webhook_verify_token' => env('WHATSAPP_WEBHOOK_VERIFY_TOKEN'),
        'app_secret' => env('WHATSAPP_APP_SECRET'),
        'notifications_enabled' => env('WHATSAPP_NOTIFICATIONS_ENABLED', true),
        'default_language' => env('WHATSAPP_DEFAULT_LANG', 'es_MX'),
        'auto_reply_enabled' => env('WHATSAPP_AUTO_REPLY_ENABLED', false),
        'auto_reply_message' => env(
            'WHATSAPP_AUTO_REPLY_MESSAGE',
            'Hola, somos ENCLAII. ¿Cómo podemos ayudarte?'
        ),
        'auto_reply_cooldown_hours' => env('WHATSAPP_AUTO_REPLY_COOLDOWN_HOURS', 24),
    ],

    'whatsapp' => [
        'access_token' => env('WHATSAPP_ACCESS_TOKEN'),
        'business_account_id' => env('WHATSAPP_BUSINESS_ACCOUNT_ID'),
        'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID'),
        'phone_number' => env('WHATSAPP_PHONE_NUMBER'),
        'api_version' => env('WHATSAPP_API_VERSION', 'v21.0'),
        'webhook_verify_token' => env('WHATSAPP_WEBHOOK_VERIFY_TOKEN'),
        'app_secret' => env('WHATSAPP_APP_SECRET'),
        'notifications_enabled' => env('WHATSAPP_NOTIFICATIONS_ENABLED', true),
        'default_language' => env('WHATSAPP_DEFAULT_LANG', 'es_MX'),
        'auto_reply_enabled' => env('WHATSAPP_AUTO_REPLY_ENABLED', false),
        'auto_reply_message' => env(
            'WHATSAPP_AUTO_REPLY_MESSAGE',
            'Hola, somos ENCLAII. ¿Cómo podemos ayudarte?'
        ),
        'auto_reply_cooldown_hours' => env('WHATSAPP_AUTO_REPLY_COOLDOWN_HOURS', 24),
    ],

];
