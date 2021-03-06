<?php declare(strict_types=1);
namespace HandbookAPI;

@_API_EXEC === 1 or die('Restricted access.');

require_once($_SERVER['DOCUMENT_ROOT'] . '/api-config.php');
require_once($CONFIG->basepath . '/v0.9/internal/exceptions/Exception.php');

class AuthenticationException extends Exception
{
    const TYPE = 'AuthenticationException';
    const STATUS = 403;

    public function __construct()
    {
        parent::__construct('Authentication failed.');
    }
}
