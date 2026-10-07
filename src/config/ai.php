<?php

return [
    /*
    |--------------------------------------------------------------------------
    | AI Assistant Master Switch
    |--------------------------------------------------------------------------
    |
    | When set to false, all AI assistant endpoints and UI triggers are disabled.
    | Normal CRUD operations in the portal continue to work completely unaffected.
    |
    */
    'enabled' => env('AI_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | AI Provider & Model Configuration
    |--------------------------------------------------------------------------
    |
    | Default provider is Google Gemini API. The model and API key are configured
    | server-side only through environment variables.
    |
    */
    'provider' => env('AI_PROVIDER', 'gemini'),
    'base_url' => env('AI_BASE_URL'),
    'model' => env('AI_MODEL', 'gemini-3.5-flash-lite'),
    'api_key' => env('AI_API_KEY', env('GEMINI_API_KEY')),

    /*
    |--------------------------------------------------------------------------
    | Request Limits & Timeouts
    |--------------------------------------------------------------------------
    |
    | Strict timeouts prevent hanging HTTP requests. Max output tokens prevent
    | excessively verbose output from the AI model.
    |
    */
    'timeout_seconds' => (int) env('AI_TIMEOUT_SECONDS', 20),
    'max_output_tokens' => (int) env('AI_MAX_OUTPUT_TOKENS', 2048),
    'rate_limit_per_minute' => (int) env('AI_RATE_LIMIT_PER_MINUTE', 15),

    /*
    |--------------------------------------------------------------------------
    | Fallback Models (Auto-Failover on Rate Limit / 429)
    |--------------------------------------------------------------------------
    |
    | When primary model encounters rate limit or temporary provider overload,
    | the service automatically tries the fallback models before returning an error.
    |
    */
    'fallback_models' => env('AI_FALLBACK_MODELS')
        ? array_filter(array_map('trim', explode(',', env('AI_FALLBACK_MODELS'))))
        : null,
];
