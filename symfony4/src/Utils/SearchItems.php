<?php
namespace App\Utils;

use Elasticsearch\ClientBuilder;

class SearchItems
{
    private $client;

    private $index_name = 's4-search';

    private $settings   = [
        'number_of_shards'      => 2,
        'number_of_replicas'    => 0
    ];

    private $mappings   = [
        'items' => [
            '_source' => [
                'enabled' => true
            ],
            'properties' => [
                'id'        => [
                    'type' => 'string'
                ],
                'item_id'   => [
                    'type' => 'integer'
                ],
                'type'      => [
                    'type' => 'string'
                ],
                'url'       => [
                    'type' => 'string'
                ],
                'title'     => [
                    'type' => 'string'
                ],
                'content'   => [
                    'type' => 'string'
                ],
                'status'    => [
                    'type' => 'integer'
                ],
            ]
        ]
    ];

    public function __construct()
    {
        $this->client   = ClientBuilder::create()->build();
    }

    public function createIndex()
    {
        $params = [
            'index' => $this->index_name,
            'body'  => [
                'settings' => $this->settings,
                'mappings' => $this->mappings
            ]
        ];

        try {
            $this->client->indices()->create($params);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }


    public function deleteIndex()
    {
        $params = [
            'index' => $this->index_name
        ];

        try {
            $this->client->indices()->delete($params);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function insertOrUpdate($id, $item)
    {
        $params = [
            'index' => $this->index_name,
            'type'  => 'items',
            'id'    => $id
        ];

        try {
            $this->client->get($params);
            $params['body']['doc'] = $item;

            return $this->client->update($params)['_shards']['successful'];

        } catch (\Exception $e) {
            $params['body'] = $item;

            return $this->client->index($params)['created'];
        }
    }


    public function getTotalCount()
    {
        $params = [
            'index' => $this->index_name,
            'type'  => 'items',
            'body' => [
                'query' => [
                    'match_all' => []
                ]
            ]
        ];

        return $this->client->count($params)['count'];
    }

    public function search($q)
    {
        $params = [
            'index' => $this->index_name,
            'type'  => 'items',
            'body' => [
                'query' => [
                    'match' => [
                        'title' => $q
                    ]
                ],
                'filter'    => [
                    'term'  => [
                        'status' => 1
                    ]
                ]
            ]
        ];

        try {
            $result = $this->client->search($params);

            if ($result['hits']['total'] > 0) {
                $items  = [];

                foreach ($result['hits']['hits'] as $item) {
                    $items[$item['_id']]    = $item['_source'];
                }

                return $items;
            }

            return [];
        } catch (\Exception $e) {
            return [];
        }
    }
}