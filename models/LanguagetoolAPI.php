<?php

namespace models;

class LanguagetoolAPI
{
    protected const url = 'http://java:8081/v2/check';

    public static function sendRequest($text)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,self::url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(
            [
                'text' => $text,
                'language' => 'ru',
                'enabledOnly' => 'false',
                'disabledRules' => 'WHITESPACE_RULE,COMMA_PARENTHESIS_WHITESPACE',
                'disabledCategories' => 'STYLE'
            ]
        ));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec ($ch);
        //curl_close ($ch);

        //echo $server_output;
        //die();

         $matches = (json_decode($server_output, true))['matches'];

         $output = [];
         foreach ($matches as $match) {
             $output[$match['line']]['text'] = $match['dialogue'];
             $output[$match['line']]['errors'][] = [
                 'message' => $match['message'],
                 'replace' => $match['replacements'],
                 'offset'  => $match['offset'],
                 'length'  => $match['length'],
                 'type'    => $match['rule']['category']['name'],
             ];
         }

         return $output;

    }
}