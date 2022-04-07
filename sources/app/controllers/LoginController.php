<?php
declare(strict_types=1);

use ERP\User;

class LoginController extends AbstractController
{
    public function post()
    {
        /**
         * @todo make authent with login and passed password
         * if i had more time, i'll use sha1 or sha256 encryption with salt and make a comparison with value in database.
         * After that i'll retrieve user's role (permission) and put info in generated a Token (using a free library like https://packagist.org/packages/firebase/php-jwt or not) 
         * and add it to reponse header.
         * 
         * At any api call, i'll do token validations check:
         * - integritys
         * - valid user
         * - expiration time
         * - access permission
         */
    }
}

