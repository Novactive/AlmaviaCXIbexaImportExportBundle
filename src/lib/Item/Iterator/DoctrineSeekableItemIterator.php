<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Item\Iterator;

use AlmaviaCX\Bundle\IbexaImportExport\Accessor\ArrayAccessor;
use AlmaviaCX\Bundle\IbexaImportExport\Reader\ReaderIteratorInterface;
use Doctrine\DBAL\Connection;
use Iterator;
use SeekableIterator;

/**
 * @implements Iterator<int, ArrayAccessor>
 * @implements SeekableIterator<int, ArrayAccessor>
 */
class DoctrineSeekableItemIterator extends PaginatedQueryIterator implements ReaderIteratorInterface, SeekableIterator
{
    public function __construct(
        protected Connection $connection,
        string $queryString,
        protected string $countQueryString,
        int $batchSize = self::DEFAULT_BATCH_SIZE
    ) {
        parent::__construct($queryString, $batchSize);
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    protected function executeQuery(string $queryString): array
    {
        return $this->connection->executeQuery($queryString)->fetchAllAssociative();
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    public function count(): int
    {
        return $this->connection->executeQuery($this->countQueryString)->fetchOne();
    }
}
