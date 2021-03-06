<?php

namespace Pterodactyl\Http\Requests\Api\Application\Servers;

use Pterodactyl\Models\Server;
use Illuminate\Validation\Rule;
use Pterodactyl\Services\Acl\Api\AdminAcl;
use Illuminate\Contracts\Validation\Validator;
use Pterodactyl\Models\Objects\DeploymentObject;
use Pterodactyl\Http\Requests\Api\Application\ApplicationApiRequest;

class StoreServerRequest extends ApplicationApiRequest
{
    /**
     * @var string
     */
    protected $resource = AdminAcl::RESOURCE_SERVERS;

    /**
     * @var int
     */
    protected $permission = AdminAcl::WRITE;

    /**
     * Rules to be applied to this request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = Server::getCreateRules();

        return [
            'name' => $rules['name'],
            'description' => array_merge(['nullable'], $rules['description']),
            'user' => $rules['owner_id'],
            'egg' => $rules['egg_id'],
            'pack' => $rules['pack_id'],
            'docker_image' => $rules['image'],
            'startup' => $rules['startup'],
            'environment' => 'required|array',
            'skip_scripts' => 'sometimes|boolean',

            // Resource limitations
            'limits' => 'required|array',
            'limits.memory' => $rules['memory'],
            'limits.swap' => $rules['swap'],
            'limits.disk' => $rules['disk'],
            'limits.io' => $rules['io'],
            'limits.cpu' => $rules['cpu'],

            // Automatic deployment rules
            'deploy' => 'sometimes|required|array',
            'deploy.locations' => 'array',
            'deploy.locations.*' => 'integer|min:1',
            'deploy.dedicated_ip' => 'required_with:deploy,boolean',
            'deploy.port_range' => 'array',
            'deploy.port_range.*' => 'string',

            'start_on_completion' => 'sometimes|boolean',
        ];
    }

    /**
     * Normalize the data into a format that can be consumed by the service.
     *
     * @return array
     */
    public function validated()
    {
        $data = parent::validated();

        return [
            'name' => array_get($data, 'name'),
            'description' => array_get($data, 'description'),
            'owner_id' => array_get($data, 'user'),
            'egg_id' => array_get($data, 'egg'),
            'pack_id' => array_get($data, 'pack'),
            'image' => array_get($data, 'docker_image'),
            'startup' => array_get($data, 'startup'),
            'environment' => array_get($data, 'environment'),
            'memory' => array_get($data, 'limits.memory'),
            'swap' => array_get($data, 'limits.swap'),
            'disk' => array_get($data, 'limits.disk'),
            'io' => array_get($data, 'limits.io'),
            'cpu' => array_get($data, 'limits.cpu'),
            'skip_scripts' => array_get($data, 'skip_scripts', false),
            'allocation_id' => array_get($data, 'allocation.default'),
            'allocation_additional' => array_get($data, 'allocation.additional'),
            'start_on_completion' => array_get($data, 'start_on_completion', false),
        ];
    }

    /*
     * Run validation after the rules above have been applied.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     */
    public function withValidator(Validator $validator)
    {
        $validator->sometimes('allocation.default', [
            'required', 'integer', 'bail',
            Rule::exists('allocations', 'id')->where(function ($query) {
                $query->where('node_id', $this->input('node_id'));
                $query->whereNull('server_id');
            }),
        ], function ($input) {
            return ! ($input->deploy);
        });

        $validator->sometimes('allocation.additional.*', [
            'integer',
            Rule::exists('allocations', 'id')->where(function ($query) {
                $query->where('node_id', $this->input('node_id'));
                $query->whereNull('server_id');
            }),
        ], function ($input) {
            return ! ($input->deploy);
        });

        $validator->sometimes('deploy.locations', 'present', function ($input) {
            return $input->deploy;
        });

        $validator->sometimes('deploy.port_range', 'present', function ($input) {
            return $input->deploy;
        });
    }

    /**
     * Return a deployment object that can be passed to the server creation service.
     *
     * @return \Pterodactyl\Models\Objects\DeploymentObject|null
     */
    public function getDeploymentObject()
    {
        if (is_null($this->input('deploy'))) {
            return null;
        }

        $object = new DeploymentObject;
        $object->setDedicated($this->input('deploy.dedicated_ip', false));
        $object->setLocations($this->input('deploy.locations', []));
        $object->setPorts($this->input('deploy.port_range', []));

        return $object;
    }
}
