<?php

namespace App\Lib\Connections;

use PHPUnit\Logging\Exception;
use Qdrant\Config;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Http\GuzzleClient;
use Qdrant\Models\PointsStruct;
use Qdrant\Models\PointStruct;
use Qdrant\Models\Request\CreateCollection;
use Qdrant\Models\Request\SearchRequest;
use Qdrant\Models\Request\VectorParams;
use Qdrant\Models\VectorStruct;
use Qdrant\Models\Filter\Condition\MatchString;
use Qdrant\Models\Filter\Filter;
use Qdrant\Qdrant as Client;
use Qdrant\Response;

class Qdrant
{
    protected Client $client;
    protected string $collectionName;

    public function __construct($collectionName = "")
    {
        $config = new Config('qdrant');
        $this->client = new Client(new GuzzleClient($config));
        $this->collectionName = $collectionName ?? "";
    }

    /**
     * @param int $size
     * @param string $name
     * @return bool
     * @throws InvalidArgumentException
     */
    public function createCollection(string $name, int $size): bool
    {
        $createCollection = new CreateCollection();
        $createCollection->addVector(new VectorParams($size, VectorParams::DISTANCE_COSINE), 'ada');
        $response = $this->client->collections()->create($name, $createCollection);
        if (isset($response['result']) && $response['result'] === true) {
            return true;
        }
        return false;
    }

    /**
     * @param string $name
     * @return array
     * @throws InvalidArgumentException
     */
    public function getCollection(string $name): array
    {
        $response = $this->client->collections()->info($name);
        return $response['result'];
    }

    /**
     * @param int $id
     * @param array $embedding
     * @param array $payload
     * @return string
     */
    public function insertVector(int $id, array $embedding, array $payload = []): string
    {
        try {
            $points = new PointsStruct();
            $points->addPoint(
                new PointStruct(
                    $id,
                    new VectorStruct($embedding, 'ada'),
                    $payload
                )
            );
            $response = $this->client->collections($this->collectionName)->points()->upsert($points);
            return $response['status'];
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @param array $embedding
     * @return array
     * @throws InvalidArgumentException
     */
    public function search(array $embedding): array
    {
        $searchRequest = (new SearchRequest(new VectorStruct($embedding, 'ada')))
            ->setLimit(3)
            ->setParams([
                'hnsw_ef' => 128,
                'exact' => false,
            ]);

        $response = $this->client->collections($this->collectionName)->points()->search($searchRequest);
        return $response['result'];
    }
}
