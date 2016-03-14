<?php
namespace DAO;
use Database\MySQL;

/**
 * Class DataAccessObject
 * @package DAO
 */
abstract class DataAccessObject
{
    /**
     * @var MySQL
     */
    protected $databaseLink;

    /**
     * PersistentObjects constructor.
     */
    public function __construct()
    {
        $this->databaseLink = new MySQL();
    }

    /**
     * @param $id
     * @return mixed
     */
    abstract public function load($id);

    /**
     * @return mixed
     */
    abstract public function save();
}

