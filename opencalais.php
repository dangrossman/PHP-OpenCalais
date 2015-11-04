<?php

/**
* Open Calais Tags
*
* @version: 2.0
* @author: Dan Grossman http://www.dangrossman.info/
* @copyright: Copyright (c) 2012-2015 Dan Grossman. All rights reserved.
* @license: Licensed under the MIT license. See http://www.opensource.org/licenses/mit-license.php
*
*/

class OpenCalaisException extends Exception {}

/**
 * Class OpenCalais. Working with OpenCalais API
 */
class OpenCalais {

    public $outputFormat = 'application/json';
    public $contentType = 'text/html';

    private $api_url = 'https://api.thomsonreuters.com/permid/calais';
    private $api_token = '';
    private $entities = array();

    /**
     * @param string $api_token
     * @throws OpenCalaisException
     */
    public function __construct($api_token) {
        if (empty($api_token)) {
            throw new OpenCalaisException('An OpenCalais API token is required to use this class.');
        }
        $this->api_token = $api_token;
    }

    /**
     * Return entities by document
     * @param string $document
     * @return array
     * @throws OpenCalaisException
     */
    public function getEntities($document) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->api_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            array(
                'X-AG-Access-Token: ' . $this->api_token,
                'Content-Type: ' . $this->contentType,
                'outputFormat: ' . $this->outputFormat
            )
        );

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $document);

        $response = curl_exec($ch);
        // Remove non-utf8 characters from string, see http://stackoverflow.com/a/1401716
        $regex = <<<'END'
        /
          (
            (?: [\x00-\x7F]                 # single-byte sequences   0xxxxxxx
            |   [\xC0-\xDF][\x80-\xBF]      # double-byte sequences   110xxxxx 10xxxxxx
            |   [\xE0-\xEF][\x80-\xBF]{2}   # triple-byte sequences   1110xxxx 10xxxxxx * 2
            |   [\xF0-\xF7][\x80-\xBF]{3}   # quadruple-byte sequence 11110xxx 10xxxxxx * 3
            ){1,100}                        # ...one or more times
          )
        | .                                 # anything else
        /x
END;
        $response = preg_replace($regex, '$1', $response);

        $object = json_decode($response);
        if (empty($object)) {
            throw new OpenCalaisException('No response was received from the API.');
        } elseif (isset($object->fault)) {
            throw new OpenCalaisException('OpenCalais Error:' . $object->fault->faultstring);
        }

        foreach ($object as $item) {
            if (!empty($item->_typeGroup) && !empty($item->name)) {
                $this->entities[$item->_typeGroup][] = trim($item->name);
            }
        }

        return $this->entities;
    }
}
