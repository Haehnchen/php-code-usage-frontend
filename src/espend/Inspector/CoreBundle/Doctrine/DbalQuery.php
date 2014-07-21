<?php

namespace espend\Inspector\CoreBundle\Doctrine;

use Doctrine\DBAL\Driver\Connection;

class DbalQuery {

    private $connection;

    public function __construct(Connection $connection) {
        $this->connection = $connection;
    }


    /**
     * Insert or Update and a key-value array into table:
     * array('field_name' => 'value', 'field_name_2' => 'value_2');
     *
     * @param string $table table name
     * @param array $item key-value array with table names and values
     * @internal param \Doctrine\DBAL\Driver\Connection $connection Pdo cconnection
     */
    public function executePdoUpsert($table, array $item) {
        // get prepared sql statement
        $statement = $this->connection->prepare(static::getPreparedUpsertSqlStatement($table, $item));

        // bind values and execute
        $statement->execute(array_values($item));
    }

    /**
     * Generates a pdo sql statement for a MySQL upsert
     * INSERT INTO `table` (`field_name`) VALUES (?) ON DUPLICATE KEY UPDATE VALUES(`card_id`)
     *
     * @param $table
     * @param $data
     * @return string A prepared sql statement with parameters to bind afterwards
     */
    public function getPreparedUpsertSqlStatement($table, $data) {

        // avoid table anme breaks and excape them
        // more then pdo/doctrine ever do @see: \Doctrine\DBAL\Connection::update
        $keys = array();
        foreach (array_keys($data) as $key) {
            $keys[] = $key;
        }

        $sql = "INSERT INTO `" . $table . "` (" . implode(', ', $keys) . ") VALUES(%s) ON DUPLICATE KEY UPDATE %s";

        $values = array();
        $values_tokens = array();

        foreach ($data as $key => $value) {
            $values[] = $key . ' = VALUES(`' . $key . '`)';
            $values_tokens[] = '?';
        }

        return sprintf($sql, implode(',', $values_tokens), implode(',', $values));

    }


} 