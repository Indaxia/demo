<?php
namespace App\Common\Repository;

use Doctrine\ODM\MongoDB\Query\Builder as QueryBuilder;
use Doctrine\ODM\MongoDB\Aggregation\Builder as AggregationBuilder;
use Doctrine\ODM\MongoDB\DocumentManager;

/** 
 * Use this snippet over your repository for generating right PHPDoc type hinting:
 * @method findBy(array $fields, int $index, int $limit = null, string|array $orderBy = null, bool $inverseOrder = false, bool $getCountInstead = false): YOUR_CLASS[]|\Doctrine\MongoDB\CursorInterface|int
 * @method findOneBy(array $fields): YOUR_CLASS|null
 * @method save(YOUR_CLASS $document, bool $flushNow = true)
 * @method remove(YOUR_CLASS $document, bool $flushNow = true)
 */

/**
 * Provides useful methods for custom repositories. It implies that the repository can write to the database.
 */
trait RepositoryAsStorage {
    /**
     * @var DocumentManager
     */
    protected $dm;

    /**
     * @var string Current class
     */
    protected $class;

    /**
     * @param mixed $id
     * @return object|null document
     */
    public function find($id) {
        return $this->dm->find($this->class, $id);
    }

    /**
     * @param string[] $fields Where the key is a field name (or nested name using dot) and the value is criteria value
     * @param int $index Ho much documents to skip
     * @param int|null $limit Limit documents count to this value
     * @param string|array|null Sorts documents by this field(s) values
     * @param bool $inverseOrder Set to true to retrieve in descending order
     * @param bool $getCountInstead Return count instead of documents
     * 
     * @return object[]|\Doctrine\MongoDB\CursorInterface|int iterable and countable document collection. Returns int if $getCountInstead is true
     */
    public function findBy(
        array $fields, 
        int $index = 0, 
        int $limit = null, 
        $orderBy = null, 
        bool $inverseOrder = false,
        bool $getCountInstead = false
    ) {
        $qb = $this->createQueryBuilder();
        foreach($fields as $field => $value) {
            $qb->field($field)->equals($value);
        }
        if($index) {
            $qb->skip($index);
        }
        if($limit !== null) {
            $qb->limit($limit);
        }
        if($orderBy) {
            $qb->sort($orderBy, $inverseOrder ? -1 : 1);
        }
        if($getCountInstead) {
            $qb->count();
        }
        return $qb->getQuery()->execute();
    }

    /**
     * @param string[] $fields Where the key is a field name (or nested name using dot) and the value is criteria value
     * @return object|null
     */
    public function findOneBy(array $fields)
    {
        $result = $this->findBy($fields, 0, 1, null, false, false);
        return $result[0] ?? null;
    }

    /**
     * Creates or updates the document in the database
     * @param object $document
     * @param bool $flushNow Set to false to defer transaction (e.g. for multiple saves)
     */
    public function save($document, bool $flushNow = true)
    {
        $this->dm->persist($document);

        if($flushNow) {
            $this->dm->flush();
        }
    }

    /**
     * Removes the document from the database
     * @param object $document
     * @param bool $flushNow Set to false to defer transaction (e.g. for multiple removes)
     */
    public function remove($document, bool $flushNow = true)
    {
        $this->dm->remove($document);

        if($flushNow) {
            $this->dm->flush();
        }
    }

    /**
     * Counts all documents of current class
     * @return int
     */
    public function count(): int
    {
        return $this->createQueryBuilder()->count()->getQuery()->execute();
    }

    /**
     * Save all documents of all classes to the database
     */
    public function flush()
    {
        $this->dm->flush();
    }

    /**
     * Returns document class of current repository
     * @return string class
     */
    public function getClass(): string
    {
        return $this->class;
    }

    protected function using(DocumentManager $documentManager, string $class)
    {
        $this->dm = $documentManager;
        $this->class = $class;
    }

    /**
     * @return QueryBuilder
     */
    protected function createQueryBuilder()
    {
        return $this->dm->createQueryBuilder($this->class);
    }

    /**
     * @return AggregationBuilder
     */
    protected function createAggregationBuilder()
    {
        return $this->dm->createAggregationBuilder($this->class);
    }
}