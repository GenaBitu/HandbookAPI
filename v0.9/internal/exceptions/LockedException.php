<?php declare(strict_types=1);
namespace HandbookAPI;

@_API_EXEC === 1 or die('Restricted access.');

require_once($_SERVER['DOCUMENT_ROOT'] . '/api-config.php');
require_once($CONFIG->basepath . '/v0.9/internal/exceptions/Exception.php');

class LockedException extends Exception
{
    const TYPE = 'LockedException';
    const STATUS = 409;
    private $holder;

    public function __construct(string $holder)
    {
        parent::__construct('This resource is currently locked by a different user.');
        $this->holder = $holder;
    }

    public function handle() : array
    {
        return [
            'status' => static::STATUS,
            'type' => static::TYPE,
            'message' => $this->getMessage(),
            'holder' => $this->holder
        ];
    }
}
