<?php
@_API_EXEC === 1 or die('Restricted access.');

require_once($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/API/v0.9/internal/Database.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/API/v0.9/internal/exceptions/MissingArgumentException.php');

$addLesson = function($skautis, $data, $endpoint)
{
	$SQL = <<<SQL
INSERT INTO lessons (id, name, body)
VALUES (?, ?, ?);
SQL;

	if(!isset($data['name']))
	{
		throw new OdyMaterialyAPI\MissingArgumentException(OdyMaterialyAPI\MissingArgumentException::POST, 'name');
	}
	$name = $endpoint->xssSanitize($data['name']);
	$body = '';
	if(isset($data['body']))
	{
		$body = $data['body'];
	}
	$uuid = Uuid::uuid4();
	$id = $uuid->getBytes();

	$db = new OdymaterialyAPI\Database();
	$db->prepare($SQL);
	$db->bind_param('sss', $id, $name, $body);
	$db->execute();
	return ['status' => 201, 'response' => $uuid];
};
