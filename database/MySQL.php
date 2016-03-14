<?php
namespace Database;
include $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';
include 'DatabaseInterface.php';

/**
 * Class MySQL
 * @package Database
 */
class MySQL implements DatabaseInterface
{
    /**
     * @var \mysqli
     */
    protected $link;

    /**
     * MySQL constructor.
     */
    public function __construct()
    {
        $this->connect();
    }

    /**
     * MySQL destructor.
     */
    function __destruct()
    {
        $this->disconnect();
    }

    /**
     * @return mixed
     */
    public function connect()
    {
        $this->link = mysqli_connect(HOST, USER, PASSWORD, DATABASE_NAME)
            or die("Database connect error :" . mysqli_error($this->link));
    }

    /**
     * @return mixed
     */
    public function disconnect()
    {
        mysqli_close($this->link);
    }

    /**
     * @param string $q
     * @return bool|\mysqli_result
     */
    public function query($q)
    {
        return $this->link->query($q);
    }

    /**
     * @param string $string
     * @return string
     */
    public function sanitizeString($string)
    {
        return $this->link->real_escape_string($string);
    }
}
