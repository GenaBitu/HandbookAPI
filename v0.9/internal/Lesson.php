<?php declare(strict_types=1);
namespace HandbookAPI;

@_API_EXEC === 1 or die('Restricted access.');

require_once($_SERVER['DOCUMENT_ROOT'] . '/api-config.php');
require_once($CONFIG->basepath . '/vendor/autoload.php');
require_once($CONFIG->basepath . '/v0.9/internal/Helper.php');

class Lesson implements \JsonSerializable
{
    public $id;
    public $name;
    public $version;
    public $competences = [];
    public $lowestCompetence;

    public function __construct(string $id, string $name, float $version)
    {
        $this->id = \Ramsey\Uuid\Uuid::fromBytes($id);
        $this->name = Helper::xssSanitize($name);
        $this->version = round($version * 1000);
    }

    public function jsonSerialize() : array
    {
        $ucomp = [];
        foreach ($this->competences as $competence) {
            $ucomp[] = \Ramsey\Uuid\Uuid::fromBytes($competence);
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'version' => $this->version,
            'competences' => $ucomp
        ];
    }
}

// Lesson comparison function used in usort. Assumes that both Lessons have their competences field sorted low-to-high.
function Lesson_cmp(Lesson $first, Lesson $second) : int
{
    if (empty($first->competences)) {
        if (empty($second->competences)) {
            return 0;
        }
        return -1;
    }
    if (empty($second->competences)) {
        return 1;
    }
    if ($first->lowestCompetence === $second->lowestCompetence) {
        return 0;
    }
    return ($first->lowestCompetence < $second->lowestCompetence) ? -1 : 1;
}
