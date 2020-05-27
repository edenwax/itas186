<?php
/**
 * Enforce a minimal level of database functionality in implementing classes
 *
 * @author dutchukm
 */
interface ActiveRecord {

    /**
     * Save the current object as a new record into the database.
     * Return the auto_incremented ID generated by the database.
     *
     * @return int
     */
    public function save(): int;

    /**
     * Delete (from the database) the record corresponding to
     * the current memory object, and set the memory object
     * fields to null
     */
    public function delete(): void;

    /**
     * Save the current object to an existing record in the database.
     */
    public function update(): void;

    /**
     * Fetch the record from the database whose ID is $id and hydrate the current
     * memory object's fields with that data.
     *
     * @param type $id
     * @return type Return a hydrated object or null if no match
     */
    public static function find(int $id): ActiveRecord;

    /**
     * Fetch, from the database, all records and return them as a
     * (possibly empty) array of objects.
     *
     * @return array An array of ActiveRecord objects
     */
    public static function findAll(): array;

}