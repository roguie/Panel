<?php

return [
    'daemon_connection_failed' => 'There was an exception while attempting to communicate with the daemon resulting in a HTTP/:code response code. This exception has been logged.',
    'node' => [
        'servers_attached' => 'A node must have no servers linked to it in order to be deleted.',
        'daemon_off_config_updated' => 'The daemon configuration <strong>has been updated</strong>, however there was an error encountered while attempting to automatically update the configuration file on the Daemon. You will need to manually update the configuration file (core.json) for the daemon to apply these changes.',
    ],
    'allocations' => [
        'server_using' => 'A server is currently assigned to this allocation. An allocation can only be deleted if no server is currently assigned.',
        'too_many_ports' => 'Adding more than 1000 ports at a single time is not supported. Please use a smaller range.',
        'invalid_mapping' => 'The mapping provided for :port was invalid and could not be processed.',
        'cidr_out_of_range' => 'CIDR notation only allows masks between /25 and /32.',
    ],
    'nest' => [
        'delete_has_servers' => 'A Nest with active servers attached to it cannot be deleted from the Panel.',
        'egg' => [
            'delete_has_servers' => 'An Egg with active servers attached to it cannot be deleted from the Panel.',
            'invalid_copy_id' => 'The Egg selected for copying a script from either does not exist, or is copying a script itself.',
            'must_be_child' => 'The "Copy Settings From" directive for this Egg must be a child option for the selected Nest.',
            'has_children' => 'This Egg is a parent to one or more other Eggs. Please delete those Eggs before deleting this Egg.',
        ],
        'variables' => [
            'env_not_unique' => 'The environment variable :name must be unique to this Egg.',
            'reserved_name' => 'The environment variable :name is protected and cannot be assigned to a variable.',
        ],
        'importer' => [
            'json_error' => 'There was an error while attempting to parse the JSON file: :error.',
            'file_error' => 'The JSON file provided was not valid.',
            'invalid_json_provided' => 'The JSON file provided is not in a format that can be recognized.',
        ],
    ],
    'packs' => [
        'delete_has_servers' => 'Cannot delete a pack that is attached to active servers.',
        'update_has_servers' => 'Cannot modify the associated option ID when servers are currently attached to a pack.',
        'invalid_upload' => 'The file provided does not appear to be valid.',
        'invalid_mime' => 'The file provided does not meet the required type :type',
        'unreadable' => 'The archive provided could not be opened by the server.',
        'zip_extraction' => 'An exception was encountered while attempting to extract the archive provided onto the server.',
        'invalid_archive_exception' => 'The pack archive provided appears to be missing a required archive.tar.gz or import.json file in the base directory.',
    ],
    'subusers' => [
        'editing_self' => 'Editing your own subuser account is not permitted.',
        'user_is_owner' => 'You cannot add the server owner as a subuser for this server.',
        'subuser_exists' => 'A user with that email address is already assigned as a subuser for this server.',
    ],
    'databases' => [
        'delete_has_databases' => 'Cannot delete a database host server that has active databases linked to it.',
    ],
    'tasks' => [
        'chain_interval_too_long' => 'The maximum interval time for a chained task is 15 minutes.',
    ],
    'locations' => [
        'has_nodes' => 'Cannot delete a location that has active nodes attached to it.',
    ],
    'users' => [
        'node_revocation_failed' => 'Failed to revoke keys on <a href=":link">Node #:node</a>. :error',
    ],
    'deployment' => [
        'no_viable_nodes' => 'No nodes satisfying the requirements specified for automatic deployment could be found.',
        'no_viable_allocations' => 'No allocations satisfying the requirements for automatic deployment were found.',
    ],
];
