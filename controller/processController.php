<?php

use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Common\Internal\Http\HttpFormatter;

include './tableController.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    switch ($_REQUEST["_request"]) {
        case 'CREATE_TABLE':
            try {
                $tableController = new tableController();
                $tableName = $tableController->clean($_POST["_tableName"]);
                $res = $tableController->__createTable($tableName);
                echo $tableController->__sendResponse(201, "Table Created...");
            } catch (ServiceException $e) {
                echo $tableController->__sendResponse($e->getCode(), $e->getMessage());
            }
            exit();
            break;

        case 'LIST_TABLE':
            try {
                $tableController = new tableController();
                $res = $tableController->__listTable();

                echo $tableController->__sendResponse(201, "Table Data Found...", $res);
            } catch (ServiceException $e) {
                echo $tableController->__sendResponse($e->getCode(), $e->getMessage());
            }
            exit();
            break;

        case 'LIST_TABLE_DATA':
            try {
                $tableController = new tableController();
                $res = $tableController->__listTable();
                $li = "";
                foreach ($res as $key) {
                    $li .= '<li class="list-group-item"><span class="link" data-table-name="' . $key . '">' . $key . '</span></li>';
                }
                echo $tableController->__sendResponse(201, "Table Data Found...", $li);
            } catch (ServiceException $e) {
                echo $tableController->__sendResponse($e->getCode(), $e->getMessage());
            }
            exit();
            break;

        case 'LIST_TABLE_SCHEMA_WITH_VALUE':
            try {
                $tableController = new tableController();
                $res = $tableController->querySubsetEntitiesSample($_POST['_tableName']);
                $result = $tableController->__getTableSchemaName($res);
                $card = '';
                if (count($result) > 0) {
                    foreach ($result as $k) {
                        $card .= '
                        <div class="col-md-4">
                            <div class="card shadow">
                            <div class="card-body">';
                        foreach ($k as $key => $value) {
                            if ($key != "Timestamp") {
                                $card .= '
                                <span class="text-success">' . $key . '</span> <span class="text-danger">=></span> <span class="text-info">' . $value . '</span>
                                <hr>
                            ';
                            } else {
                                $dt = json_decode(json_encode($k[$key]));
                                date_default_timezone_set('Asia/Kolkata');
                                $card .= '
                            <span class="text-dark">Date : ' . date('d-m-Y H:i:s', strtotime($dt->date)) . '</span>
                            <hr>
                            ';
                            }
                        }
                        $card .= "
                            </div>
                            </div>
                        </div>
                    ";
                    }
                } else {
                    $card .= '<img src="http://localhost/devil/file_storage/arjun/Table/public/images/no-result.gif" class="w-100">';
                }
                echo $tableController->__sendResponse(201, "Table Data Found...", $card);
            } catch (ServiceException $e) {
                echo $tableController->__sendResponse($e->getCode(), $e->getMessage());
            }
            exit();
            break;

        case 'CREATE_TABLE_SCHEMA':
            try {
                $tableController = new tableController();
                //    print_r($_POST);
               $tableName = $_POST['add_entity_table_name'];
               $table_id = $_POST['table_id'];
               $arrayData = [];
               for ($i=0; $i < count($_POST['thead']); $i++) { 
                    $arrayData[] = [
                        "thead" => $_POST['thead'][$i],
                        "thead_type" => $_POST['thead_type'][$i],
                        "tdata" => $_POST['tdata'][$i]
                    ];
               }
                $tableController->insertEntity($tableName, $arrayData, $table_id);
                echo $tableController->__sendResponse(201, "Table Data Created...", []);
            } catch (ServiceException $e) {
                echo $tableController->__sendResponse($e->getCode(), $e->getMessage());
            }
            exit();
            break;

        default:
            exit();
            break;
    }
}
