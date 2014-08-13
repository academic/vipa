<?php

namespace Ojstr\Common\Helper;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

ini_set('session.save_handler', 'files');
ini_set('session.save_path', 'app/cache');
session_start();

class TestHelper extends WebTestCase {

    function __construct() {
        parent::__construct();
    }

}
