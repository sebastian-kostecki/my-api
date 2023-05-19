<?php

namespace App\Lib\Connections;

use Brd6\NotionSdkPhp\ClientOptions;
use Brd6\NotionSdkPhp\Client;
use Brd6\NotionSdkPhp\Exception\ApiResponseException;
use Brd6\NotionSdkPhp\Exception\HttpResponseException;
use Brd6\NotionSdkPhp\Exception\InvalidPaginationResponseException;
use Brd6\NotionSdkPhp\Exception\InvalidResourceException;
use Brd6\NotionSdkPhp\Exception\InvalidResourceTypeException;
use Brd6\NotionSdkPhp\Exception\RequestTimeoutException;
use Brd6\NotionSdkPhp\Exception\UnsupportedPaginationResponseTypeException;
use Brd6\NotionSdkPhp\Resource\Database;
use Brd6\NotionSdkPhp\Resource\Database\DatabaseRequest;
use Brd6\NotionSdkPhp\Resource\Page;
use Brd6\NotionSdkPhp\Resource\Pagination\AbstractPaginationResults;
use Http\Client\Exception;

class Notion
{
    protected Client $client;

    public function __construct()
    {
        $options = (new ClientOptions())
            ->setAuth(env('NOTION_TOKEN'));

        $this->client = new Client($options);
    }

    /**
     * @param string $databaseId
     * @return Database
     * @throws ApiResponseException
     * @throws Exception
     * @throws HttpResponseException
     * @throws RequestTimeoutException
     * @throws InvalidResourceException
     * @throws InvalidResourceTypeException
     */
    public function getDatabase(string $databaseId): Database
    {
        return $this->client->databases()->retrieve($databaseId);
    }

    /**
     * @param string $databaseId
     * @param $filter
     * @return AbstractPaginationResults
     * @throws ApiResponseException
     * @throws HttpResponseException
     * @throws InvalidPaginationResponseException
     * @throws RequestTimeoutException
     * @throws UnsupportedPaginationResponseTypeException
     * @throws Exception
     */
    public function getDatabaseItemsByFilter(string $databaseId, array $filter, array $sorts = []): AbstractPaginationResults
    {
        $databaseRequest = new DatabaseRequest();
        $databaseRequest->setFilter($filter);
        if ($sorts) {
            $databaseRequest->setSorts($sorts);
        }
        return $this->client->databases()->query($databaseId, $databaseRequest);
    }

    /**
     * @param string $pageId
     * @return Page
     * @throws ApiResponseException
     * @throws Exception
     * @throws HttpResponseException
     * @throws InvalidResourceException
     * @throws InvalidResourceTypeException
     * @throws RequestTimeoutException
     */
    public function getPage(string $pageId): Page
    {
        return $this->client->pages()->retrieve($pageId);
    }

    /**
     * @param string $databaseId
     * @param array $newItems
     * @return void
     * @throws ApiResponseException
     * @throws Exception
     * @throws HttpResponseException
     * @throws InvalidPaginationResponseException
     * @throws RequestTimeoutException
     * @throws UnsupportedPaginationResponseTypeException
     */
    public function updateDatabaseItems(string $databaseId, array $newItems): void
    {
        $response = $this->client->databases()->query($databaseId);
        $item = json_encode($newItems);

        foreach ($response->getResults() as $record) {
            foreach ($record->getProperties() as $property) {
                if ($property->getType() == 'checkbox') {

                    $curl = curl_init();

                    curl_setopt_array($curl, [
                        CURLOPT_URL => "https://api.notion.com/v1/pages/" . $record->getId(),
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "PATCH",
                        CURLOPT_POSTFIELDS => $item,
                        CURLOPT_HTTPHEADER => [
                            "Authorization: Bearer " . env('NOTION_TOKEN'),
                            "Notion-Version: 2022-06-28",
                            "accept: application/json",
                            "content-type: application/json"
                        ],
                    ]);

                    curl_exec($curl);
                    curl_error($curl);
                    curl_close($curl);
                }
            }
        }
    }
}
