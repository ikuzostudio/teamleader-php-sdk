<?php

namespace Teamleader\Entities\Deals;

use Teamleader\Actions\Storable;
use Teamleader\Model;
use JsonSerializable;

class Deal extends Model implements JsonSerializable
{
    use Storable;

    const TYPE = 'deal';

    protected $fillable = [
        'id',
        'lead', // { "customer": { "type": "contact", "id" : "" }, "contact_person_id" : "" }
        'title',
        'source_id',
        'department_id',
        'responsible_user_id',
        'phase_id',
        'estimated_value',
        'estimated_probability',
        'estimated_closing_date',
        'custom_fields',
    ];

    /**
     * @var string
     */
    protected $endpoint = 'deals';

    /**
     * @return mixed
     */
    public function insert()
    {
        $result = $this->connection()->post($this->getEndpoint() . '.create', $this->jsonWithNamespace());

        return $this->selfFromResponse($result);
    }

    public function move($phaseId)
    {
        $arguments = [
            'id'       => $this->attributes['id'],
            'phase_id' => $phaseId,
        ];

        $result = $this->connection()->post($this->getEndpoint() . '.move', json_encode($arguments, JSON_FORCE_OBJECT));

        return $result;
    }

    public function jsonSerialize(): object
    {
        return (object) [
            'type' => self::TYPE,
            'id' => $this->id,
        ];
    }
}
