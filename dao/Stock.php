<?php
namespace DAO;

/**
 * Class Stock
 * @package DAO
 */
class Stock extends DataAccessObject
{
    /**
     * @var int
     */
    public $id;
    /**
     * @var string
     */
    public $productName;
    /**
     * @var int
     */
    public $productId;
    /**
     * @var int
     */
    public $quantity;
    /**
     * @var string
     */
    public $type;
    /**
     * @var string
     */
    public $created;

    /**
     * @param int $id
     * @return null
     */
    public function load($id)
    {
        $sqlResult = $this->databaseLink->query(
            sprintf('SELECT * FROM `stock` WHERE `id` = %d LIMIT 1', $id)
        );

        $stock = $sqlResult->fetch_assoc();

        $this->id = (int)$stock['id'];
        $this->productName = (string)$stock['product_name'];
        $this->productId = (int) $stock['product_id'];
        $this->quantity = (int) $stock['quantity'];
        $this->type = (string) $stock['type'];
        $this->created = (string) $stock['created'];
    }

    /**
     * @return bool|\mysqli_result
     */
    public function save()
    {
      return $this->databaseLink->query(
          sprintf('
            INSERT INTO `stock` (`id`, `product_id`, `product_name`, `quantity`, `type`, `created`)
            VALUES ("%1$d", "%2$d", "%3$s", "%4$d", "%5$s", "%6$s")
            ON DUPLICATE KEY UPDATE
              `product_id` = %2$d,
              `product_name` = "%3$s",
              `quantity` = %4$d,
              `type` = "%4$s"',
              $this->id,
              $this->productId,
              $this->databaseLink->sanitizeString($this->productName),
              $this->quantity,
              $this->databaseLink->sanitizeString($this->type),
              $this->databaseLink->sanitizeString($this->created)
          )
      );
    }
}
