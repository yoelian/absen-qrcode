<?php

return [
    // Compatibility config expected by some NativePHP internals.
    // Keep this in sync with nativephp-internal.secret.
    'secret' => env('NATIVEPHP_SECRET'),
];
