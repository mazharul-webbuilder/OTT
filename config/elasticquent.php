<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Custom Elasticsearch Client Configuration
    |--------------------------------------------------------------------------
    |
    | This array will be passed to the Elasticsearch client.
    | See configuration options here:
    |
    | http://www.elasticsearch.org/guide/en/elasticsearch/client/php-api/current/_configuration.html
    */

    //http://157.245.49.230:9200 Live Hosts
    //localhost:9200 Local Hosts
    'config' => [
        'hosts'     => ['http://localhost:9200'],
        // 'hosts'     => [
        //                     // '165.22.52.42',
        //                     'host' => '165.22.52.42',
        //                     'scheme' => 'http',
        //                     'port' => 443,

        //                  ],
        'retries'   => 1,
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Index Name
    |--------------------------------------------------------------------------
    |
    | This is the index name that Elasticquent will use for all
    | Elasticquent models.
    */

    'default_index' => 'ec_products',

);
