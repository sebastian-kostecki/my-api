<?php

namespace App\Lib\Connections;

use App\Lib\Apis\Qdrant as Api;
use App\Lib\Connections\Qdrant\Collections;
use App\Lib\Connections\Qdrant\Points;

class Qdrant
{
    public Api $api;
    public string $databaseName;

    public function __construct(string $databaseName)
    {
        $this->api = new Api();
        $this->databaseName = $databaseName;
    }

    /**
     * @return Collections
     */
    public function collections(): Collections
    {
        return new Collections($this);
    }

    /**
     * @return Points
     */
    public function points(): Points
    {
        return new Points($this);
    }
}
