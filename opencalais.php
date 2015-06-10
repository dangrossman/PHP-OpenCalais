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

class OpenCalais {

    private $api_url = 'https://api.thomsonreuters.com/permid/calais';
    private $api_token = '';
    
    public $outputFormat = 'application/json';
    public $contentType = 'text/html';


    private $document = '';
    private $entities = array();

    public function OpenCalais($api_token) {
        if (empty($api_token)) {
            throw new OpenCalaisException('An OpenCalais API token is required to use this class.');
        }
        $this->api_token = $api_token;
    }

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
        $response = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response);

        $object = json_decode($response);
        if (empty($object)) {
            throw new OpenCalaisException('No response was received from the API.');
        }

        foreach ($object as $item) {
            if (!empty($item->_typeGroup) && !empty($item->name)) {
                $this->entities[$item->_typeGroup][] = trim($item->name);
            }
        }

        return $this->entities;

    }


}
