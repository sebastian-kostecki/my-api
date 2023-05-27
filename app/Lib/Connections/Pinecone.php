<?php

namespace App\Lib\Connections;

use \Probots\Pinecone\Client;

class Pinecone
{
    protected Client $client;
    protected string $index;

    public function __construct(string $index)
    {
        $this->client = new Client(env('PINECONE_API_KEY'), env('PINECONE_ENVIRONMENT'));
        $this->index = $index;
    }

    public function upsertVectors(int $id, array $vectors, array $metadata = [])
    {
        $response = $this->client->index($this->index)->vectors()->upsert(vectors: [
            'id' => (string)$id,
            'values' => $vectors,
            'metadata' => $metadata
        ]);

//        if($response->successful()) {
//            //
//        }
    }

    public function queryVectors(array $embedding)
    {
        $response = $this->client->index($this->index)->vectors()->query(
            vector: $embedding,
            topK: 4,
        );
        if ($response->successful()) {
            return $response->json();
        }
        return false;
    }
}
