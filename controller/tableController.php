<?php
include '../vendor/autoload.php';

use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Table\Models\EdmType;
use MicrosoftAzure\Storage\Table\Models\Entity;
use MicrosoftAzure\Storage\Table\Models\Filters\Filter;
use MicrosoftAzure\Storage\Table\Models\QueryEntitiesOptions;
use MicrosoftAzure\Storage\Table\TableRestProxy;
use MicrosoftAzure\Storage\Table\Models\QueryTablesOptions;


class tableController
{
    private
        $_connectionString,
        $_tableClient;

    public function __construct()
    {
        $this->_connectionString = 'DefaultEndpointsProtocol=https;AccountName=itnodcdevstorage;AccountKey=W+tRXXd14a8YAHOhgpZysri47FQD5BmcYSGQVhmqsrjGzzq+E0HvA91q8pz79X32NsE43ylJ1FAF+AStr33YgA==';
        $this->_tableClient = TableRestProxy::createTableService($this->_connectionString);
    }

    public function __createTable($mytable)
    {
        try {
            // Create table.
            $this->_tableClient->createTable($mytable);
        } catch (ServiceException $e) {
            $code = $e->getCode();
            $error_message = $e->getMessage();
            echo $code . ": " . $error_message . PHP_EOL;
        }
    }

    public function insertEntity($mytable, $arrayData, $table_id)
    {
        $entity = new Entity();
        $entity->setPartitionKey("pk");
        $entity->setRowKey($table_id);
        foreach ($arrayData as $key) {
            $type = '';
            switch ($key['thead_type']) {
                case 'string':
                    $type = EdmType::STRING;
                    break;
                case 'number':
                    $type = EdmType::DOUBLE;
                    break;
                case 'boolean':
                    $type = EdmType::BOOLEAN;
                    break;

                case 'datetime':
                    $type = EdmType::DATETIME;
                    break;
            }
            $entity->addProperty($key["thead"], $type, $key['tdata']);
        }

        try {
            $this->_tableClient->insertEntity($mytable, $entity);
        } catch (ServiceException $e) {
            $code = $e->getCode();
            $error_message = $e->getMessage();
            echo $code . ": " . $error_message . PHP_EOL;
        }
    }

    public function getSingleEntitySample($mytable)
    {
        try {
            $result = $this->_tableClient->getEntity($mytable, "pk", 4);
        } catch (ServiceException $e) {
            $code = $e->getCode();
            $error_message = $e->getMessage();
            echo $code . ": " . $error_message . "<br />";
        }
        // $result->getEntities();
        return $result->getEntity()->getPartitionKey();
    }

    public function getEntities($mytable)
    {
        try {
            $result = $this->_tableClient->queryEntities($mytable);
        } catch (ServiceException $e) {
            $code = $e->getCode();
            $error_message = $e->getMessage();
            echo $code . ": " . $error_message . "<br />";
        }
        // $result->getEntities();
        return $ents = $result->getEntities();
        // $t = [];
        // foreach ($ents as $ent) {
        //     $t[] = $ent->getProperties();
        // }
        // return $t;
    }



    public function queryAllEntitiesInPartition($mytable)
    {
        $filter = "PartitionKey eq 'pk'";

        try {
            $result = $this->_tableClient->queryEntities($mytable, $filter);
        } catch (ServiceException $e) {
            $code = $e->getCode();
            $error_message = $e->getMessage();
            echo $code . ": " . $error_message . "<br />";
        }

        $entities = $result->getEntities();

        foreach ($entities as $entity) {
            echo $entity->getPartitionKey() . ":" . $entity->getRowKey() . "<br />" . "\n";
        }
    }

    public function __getTableSchemaName($queryEntities)
    {
        try {
            $arr = [];
            for ($i = 0; $i < count($queryEntities); $i++) {
                $result = $queryEntities[$i]->getProperties();
                $schema = [];
                foreach ($result as $key => $value) {
                    $schema[$key] = $value->getValue();
                }
                $arr[] = $schema;
            }

            return $arr;
        } catch (ServiceException $e) {
            $code = $e->getCode();
            $error_message = $e->getMessage();
            echo $code . ": " . $error_message . PHP_EOL;
        }
    }

    public function querySubsetEntitiesSample($mytable)
    {
        $filter = Filter::applyQueryString("RowKey ge '0'");
        $options = new QueryEntitiesOptions();

        $options->setFilter($filter);
        try {
            $result = $this->_tableClient->queryEntities($mytable, "");
        } catch (ServiceException $e) {
            $code = $e->getCode();
            $error_message = $e->getMessage();
            echo $code . ": " . $error_message . PHP_EOL;
        }

        return $entities = $result->getEntities();
        // $table = "<table border='1' cellpadding='5'><tr><th>Sr#</th><th>Property Name</th><th>Property Desc</th></tr>";
        // foreach ($entities as $entity) {
        //     $Key = $entity->getRowKey();
        //     $name = $entity->getProperty("PropertyName")->getValue();
        //     $desc = $entity->getProperty("PropertyDesc")->getValue();

        //     $table .= "
        //         <tr>
        //             <td>" . $Key . "</td>
        //             <td>" . $name . "</td>
        //             <td>" . $desc . "</td>
        //         </tr>
        //     ";
        // }
        // $table .= "</table>";

        // return $table;
    }

    public function __sendResponse($status, $msg = "", $data = [])
    {
        return json_encode([
            "status" => $status,
            "msg" => $msg,
            "data" => $data
        ]);
    }

    public function __listTable()
    {
        $queryTablesOptions = new QueryTablesOptions();
        $queryTablesOptions->setPrefix("");
        $tablesListResult = $this->_tableClient->queryTables($queryTablesOptions);
        $tableName = [];
        foreach ($tablesListResult->getTables() as $table) {
            $tableName[] = $table;
        }

        return $tableName;
    }

    public function clean($string)
    {
        $string = str_replace(' ', '', $string); // Replaces all spaces with hyphens.
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }
}


// echo "<pre>";
// $tableController = new tableController();

// print_r($tableController->insertEntity("arjuntable"));

// $data = $tableController->querySubsetEntitiesSample("arjuntable");
// $d = $tableController->__getTableSchemaName($data);
// $a = json_decode(json_encode($d[0]["Timestamp"]));
// echo $a->date;



// print_r($tableController->getEntities("arjuntable"));
