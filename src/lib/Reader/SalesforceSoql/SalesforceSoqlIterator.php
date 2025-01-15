<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Reader\SalesforceSoql;

use AlmaviaCX\Bundle\IbexaImportExport\Item\Iterator\PaginatedQueryIterator;
use AlmaviaCX\Bundle\IbexaImportExport\Reader\ReaderIteratorInterface;
use AlmaviaCX\Bundle\IbexaImportExport\Salesforce\SalesforceApiClient;
use AlmaviaCX\Bundle\IbexaImportExport\Salesforce\SalesforceApiCredentials;
use SeekableIterator;

class SalesforceSoqlIterator extends PaginatedQueryIterator implements ReaderIteratorInterface, SeekableIterator
{
    public function __construct(
        protected SalesforceApiClient $apiClient,
        protected SalesforceApiCredentials $credentials,
        protected string $domain,
        protected string $version,
        string $queryString,
        protected string $countQueryString,
        int $batchSize = self::DEFAULT_BATCH_SIZE
    ) {
        parent::__construct($queryString, $batchSize);
    }

    protected function executeQuery(string $queryString): array
    {
        $response = $this->request($queryString);

        return $response['records'] ?? [];
    }

    public function count(): int
    {
        $response = $this->request($this->countQueryString);

        return $response['totalSize'] ?? 0;
    }

    protected function request(string $queryString): array
    {
        $path = sprintf(
            '/query?%s',
            http_build_query(['q' => $queryString])
        );

        return ($this->apiClient)(
            $this->domain,
            $this->version,
            $path,
            'GET',
            $this->credentials
        );
    }
}
