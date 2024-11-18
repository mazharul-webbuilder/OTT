<?php

/**
 * Search dynamically
*/
if (!function_exists('getSearchQuery')) {
    function getSearchQuery($query, $queryString, ...$columns)
    {
        return $query->where(function ($query) use ($queryString, $columns){
            foreach ($columns as $column) {
                $query->orWhere($column, 'like', "%$queryString%");
            }
        });
    }
}

/**
 * Get Verification Code
 */
if (!function_exists('getVerificationCode')){
    function getVerificationCode($length = 6): int
    {
        for ($code = '', $i=0; $i<$length; $i++){
            $code .= rand(min:1, max: 9);
        }
        return $code;
    }
}
