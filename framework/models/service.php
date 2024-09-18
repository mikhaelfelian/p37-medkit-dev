<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php

/**
 * Class Models of Service
 * To read from web service
 * @author mike
 */
class service extends CI_Model {

    private $table_name;

    public function __construct() {
        parent::__construct();
    }

    public function read($url) {
        $data = array('result' => '');

        $options = array(
            'http' => array(
                'method' => "GET",
                'header' => "X-Parse-Application-Id: 8qTrM6iEcfD6IMjeZK4juIvgF2qNHiUnbVkIZJiz\r\n" .
                            "X-Parse-Master-Key: ZB5BtyLUhB5cPzWAIvS0PaAHcZ8EOFiAbqcWPlYv\r\n"
            ),
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        return $result;
    }

}
