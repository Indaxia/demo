<?php
namespace App\Common\Repository;

interface RepositoryAsStorageInterface {
    /**
     * @param $id
     * @return object|null document
     */
    public function find($id);

    /**
     * @param string[] $fields Where the key is a field name (or nested name using dot) and the value is criteria value
     * @param int $index Ho much documents to skip
     * @param int|null $limit Limit documents count to this value
     * @param string|array|null Sorts documents by this field(s) values
     * @param bool $inverseOrder Set to true to retrieve in descending order
     * @param bool $getCountInstead Return count instead of documents
     * 
     * @return object|\Doctrine\MongoDB\CursorInterface|int iterable and countable document collection. Returns int if $getCountInstead is true
     */
    public function findBy(
        array $fields, 
        int $index = 0, 
        int $limit = null, 
        $orderBy = null, 
        bool $inverseOrder = false,
        bool $getCountInstead = false
    );

    /**
     * @param string[] $fields Where the key is a field name (or nested name using dot) and the value is criteria value
     * @return object|null
     */
    public function findOneBy(array $fields);

    /**
     * Creates or updates the document in the database
     * @param object $document
     * @param bool $flushNow Set to false to defer transaction (e.g. for multiple saves)
     */
    public function save($document, bool $flushNow = true);

    /**
     * Removes the document from the database
     * @param object $document
     * @param bool $flushNow Set to false to defer transaction (e.g. for multiple removes)
     */
    public function remove($document, bool $flushNow = true);

    /**
     * Counts all documents of current class
     * @return int
     */
    public function count(): int;

    /**
     * Save all documents of all classes to the database
     */
    public function flush();

    /**
     * Returns document class of current repository (for recursive purposes)
     * @return string class
     */
    public function getClass(): string;
}