<?php

declare(strict_types=1);

return [
    /*
     * ------------------------------------------------------------------------
     * Default Firebase project
     * ------------------------------------------------------------------------
     */

    'default' => env('FIREBASE_PROJECT', 'app'),

    /*
     * ------------------------------------------------------------------------
     * Firebase project configurations
     * ------------------------------------------------------------------------
     */

    'projects' => [
        'app' => [

            /*
             * ------------------------------------------------------------------------
             * Credentials / Service Account
             * ------------------------------------------------------------------------
             *
             * In order to access a Firebase project and its related services using a
             * server SDK, requests must be authenticated. For server-to-server
             * communication this is done with a Service Account.
             *
             * If you don't already have generated a Service Account, you can do so by
             * following the instructions from the official documentation pages at
             *
             * https://firebase.google.com/docs/admin/setup#initialize_the_sdk
             *
             * Once you have downloaded the Service Account JSON file, you can use it
             * to configure the package.
             *
             * If you don't provide credentials, the Firebase Admin SDK will try to
             * auto-discover them
             *
             * - by checking the environment variable FIREBASE_CREDENTIALS
             * - by checking the environment variable GOOGLE_APPLICATION_CREDENTIALS
             * - by trying to find Google's well known file
             * - by checking if the application is running on GCE/GCP
             *
             * If no credentials file can be found, an exception will be thrown the
             * first time you try to access a component of the Firebase Admin SDK.
             *
             */

            'credentials' => [
                'type' => 'service_account',
                'project_id' => 'durbar-ott-fbf55',
                'private_key_id' => '5c6c5cd85962d85bbb67cc40216a82d7598c8cd1',
                'private_key' => '-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQC0iWRnOOyXN8HJ\nrAdSX/adXrfmGIvz+FDmVk+/hZ0yB8+px30dyKLRpI8XAU+YSUxFrqpbMDRDZP+3\nYt8fBxWD2Eackhdieft4YWK11d6ALbmACineWW8o/k0P5rQAq9k1aF4Pgf7UuXrt\nsTxhHbokyxpZIb/f2FffOeIT0zcg4vfljmqib/5TMO6dUUk7LiFVtLcGrvBE7NCl\nupSaRG/cvW7lkaKmyd+rXkMcUFd6BF8ZhXwV036bKOxSPR4B63iFhfE4GGescAuY\nr/ldguucl8pmmSdlswr28XtLe6xENL9kekxu687nO7L2ULRcogC8f9CqMrhvukhQ\ncGSkJHYrAgMBAAECggEARjYOoaQ+QtsQ1w07tboTb4VoMKjIOQQP9lyH3Dyq80ud\nY/JswroGNFDywygj295Zttnkb91R3gn390qQ6oC3SdTBNWANjyNQfLMnD/+Sbjga\nJeUN/ma6t+8aaj43L/GlYkNqHU7Zm8Jcv/cHO9+zRl7kqthYxkXdereIqFwi2vsy\n2yCkYTZFD2FLcmFre79sC6ecsOQ9DGFgZu225ZnG1RKJBUgefI4mJoFgEQ2hJNh+\nyjptDvjWJrp3CtCuBZgO4HB/oCiVvYS+G25jV+RHoeMnw1LkP7apifg6422ulWBG\nOJU2WUuqZCqDVn8dhFONWXawusfMdPS5ZYFlvHAVpQKBgQDbp3tqDEmypT1AJs23\nFIXaCuIEjTGlXl3JbXTpsQPsENPeETQ1i3SJ9E5UD1FgTi4lb3WSR4PU7+NhteAk\nO6XEozpzdjhHj55I92WwF7XtzUuXK+HpuuKfQ8KLcJy2IAtEgGxKKsCduOav/vuo\nLujCaYkGfPlaP4uz0SuPBypxBQKBgQDSaOW+SqlPAzz10yW5kAGZGyWePE+wEas9\nRjjnPPP+hbmqD/C7GQbfepyANFEZd5p3XkRUrnzFDwn2QELfYsOhoPbLGwzeOhz/\n6aDMdNN/8zKSK/q/L3PyYWa5N+ED8PqArDR38sfp+Yk4klV22lyQfXC2r7ExLtG0\nEY8O5YWxbwKBgFl9wgVx3jDfq7XQWW3m+aXdWzp5gCmc6d8gLkrr6Oor7PYD0l6i\nY6e1Fpie6QuwrpJn9+HSKz79QYnUvO5mMuKLkkvqdYGKXPXjfdWhw2iQhOShZ1h/\n4Mb8p22CQwbcxVpybCxHgNSBudMossVR31keAErZbgo766ImbXXHeJ41AoGAfj3z\nwhWXudFujOuP0eMmIk+YZXU4NqKTRGNSluMKXear++4ueINOjV1Kct1w3z/UjNQh\nWVGLiMw6JOZveQoP6HTkPf5P3TGu3pi1Ipbhov4ulGcQvg7hmUZ5VJ3DQxMdqYAo\nxuMCsU5H14ps/q+LJCIvM1z8ik7WAR0b4UsyyL8CgYEAmv3PwnS0jbhsx4aGltnM\nnLZB67/K7IGIqrKF67IgZW85Np2/ulEXHKGn8pYDnMya7iAoTl7bewKLfUlvqZSh\nYc5imRX3z7/upQs9qMt0P2hiRqZoB46BMQ6QAkMIhcE+53GSUYEpzNyH813gfR7B\nYCnuc/Dp4veYb1A+maOc+ks=\n-----END PRIVATE KEY-----\n',
                'client_email' => 'firebase-adminsdk-kw9fu@durbar-ott-fbf55.iam.gserviceaccount.com',
                'client_id' => '112144401814388139802',
                'auth_uri' => 'https://accounts.google.com/o/oauth2/auth',
                'token_uri' => 'https://oauth2.googleapis.com/token',
                'auth_provider_x509_cert_url' => 'https://www.googleapis.com/oauth2/v1/certs',
                'client_x509_cert_url' => 'https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-kw9fu%40durbar-ott-fbf55.iam.gserviceaccount.com',
                'universe_domain' => 'googleapis.com',
            ],

            /*
             * ------------------------------------------------------------------------
             * Firebase Auth Component
             * ------------------------------------------------------------------------
             */

            'auth' => [
                'tenant_id' => env('FIREBASE_AUTH_TENANT_ID'),
            ],

            /*
             * ------------------------------------------------------------------------
             * Firestore Component
             * ------------------------------------------------------------------------
             */

            'firestore' => [

                /*
                 * If you want to access a Firestore database other than the default database,
                 * enter its name here.
                 *
                 * By default, the Firestore client will connect to the `(default)` database.
                 *
                 * https://firebase.google.com/docs/firestore/manage-databases
                 */

                // 'database' => env('FIREBASE_FIRESTORE_DATABASE'),
            ],

            /*
             * ------------------------------------------------------------------------
             * Firebase Realtime Database
             * ------------------------------------------------------------------------
             */

            'database' => [

                /*
                 * In most of the cases the project ID defined in the credentials file
                 * determines the URL of your project's Realtime Database. If the
                 * connection to the Realtime Database fails, you can override
                 * its URL with the value you see at
                 *
                 * https://console.firebase.google.com/u/1/project/_/database
                 *
                 * Please make sure that you use a full URL like, for example,
                 * https://my-project-id.firebaseio.com
                 */

                'url' => env('FIREBASE_DATABASE_URL'),

                /*
                 * As a best practice, a service should have access to only the resources it needs.
                 * To get more fine-grained control over the resources a Firebase app instance can access,
                 * use a unique identifier in your Security Rules to represent your service.
                 *
                 * https://firebase.google.com/docs/database/admin/start#authenticate-with-limited-privileges
                 */

                // 'auth_variable_override' => [
                //     'uid' => 'my-service-worker'
                // ],

            ],

            'dynamic_links' => [

                /*
                 * Dynamic links can be built with any URL prefix registered on
                 *
                 * https://console.firebase.google.com/u/1/project/_/durablelinks/links/
                 *
                 * You can define one of those domains as the default for new Dynamic
                 * Links created within your project.
                 *
                 * The value must be a valid domain, for example,
                 * https://example.page.link
                 */

                'default_domain' => env('FIREBASE_DYNAMIC_LINKS_DEFAULT_DOMAIN'),
            ],

            /*
             * ------------------------------------------------------------------------
             * Firebase Cloud Storage
             * ------------------------------------------------------------------------
             */

            'storage' => [

                /*
                 * Your project's default storage bucket usually uses the project ID
                 * as its name. If you have multiple storage buckets and want to
                 * use another one as the default for your application, you can
                 * override it here.
                 */

                'default_bucket' => env('FIREBASE_STORAGE_DEFAULT_BUCKET'),

            ],

            /*
             * ------------------------------------------------------------------------
             * Caching
             * ------------------------------------------------------------------------
             *
             * The Firebase Admin SDK can cache some data returned from the Firebase
             * API, for example Google's public keys used to verify ID tokens.
             *
             */

            'cache_store' => env('FIREBASE_CACHE_STORE', 'file'),

            /*
             * ------------------------------------------------------------------------
             * Logging
             * ------------------------------------------------------------------------
             *
             * Enable logging of HTTP interaction for insights and/or debugging.
             *
             * Log channels are defined in config/logging.php
             *
             * Successful HTTP messages are logged with the log level 'info'.
             * Failed HTTP messages are logged with the log level 'notice'.
             *
             * Note: Using the same channel for simple and debug logs will result in
             * two entries per request and response.
             */

            'logging' => [
                'http_log_channel' => env('FIREBASE_HTTP_LOG_CHANNEL'),
                'http_debug_log_channel' => env('FIREBASE_HTTP_DEBUG_LOG_CHANNEL'),
            ],

            /*
             * ------------------------------------------------------------------------
             * HTTP Client Options
             * ------------------------------------------------------------------------
             *
             * Behavior of the HTTP Client performing the API requests
             */

            'http_client_options' => [

                /*
                 * Use a proxy that all API requests should be passed through.
                 * (default: none)
                 */

                'proxy' => env('FIREBASE_HTTP_CLIENT_PROXY'),

                /*
                 * Set the maximum amount of seconds (float) that can pass before
                 * a request is considered timed out
                 *
                 * The default time out can be reviewed at
                 * https://github.com/kreait/firebase-php/blob/6.x/src/Firebase/Http/HttpClientOptions.php
                 */

                'timeout' => env('FIREBASE_HTTP_CLIENT_TIMEOUT'),

                'guzzle_middlewares' => [],
            ],
        ],
    ],
];
