<?php include './layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-9">
            <h1 class="text-right"><span class="text-info">Azure</span> Table <span class="text-info">Cosmos</span> Database </h1>
        </div>
        <div class="col-md-3 text-right">
            <div class="dropdown mt-2">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                    Change Storage
                </button>
                <div class="dropdown-menu">
                    <a target="_blank" class="dropdown-item" href="http://localhost/devil/file_storage/arjun/FileShare/">File Share</a>
                    <a target="_blank" class="dropdown-item" href="http://localhost/devil/file_storage/arjun/Blob/">Blob Storage</a>
                    <a target="_blank" class="dropdown-item" href="#">Cosmos DB</a>
                    <a target="_blank" class="dropdown-item" href="http://localhost/devil/file_storage/arjun/Queue/">Queue Storage</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid card">
    <div class="row card-body shadow">

        <div class="col-lg-3">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Create Table</h4>
                </div>
                <div class="card-body">
                    <form onsubmit="return false" id="create-table" method="post">
                        <input type="hidden" name="_request" value="CREATE_TABLE">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group" style="margin-bottom: 0rem;">
                                    <h5 for="">Table Name</h5>
                                    <input type="text" name="table_name" id="table_name" placeholder="Enter Table Name..." class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <div class="form-group">
                                    <input type="submit" class="btn btn-success" value="Create Table">
                                </div>
                            </div>
                        </div>
                </div>
                </form>
            </div>

            <div class="card mt-2">
                <div class="card-header">
                    <h4 class="card-title">Create Schema To Table</h4>
                </div>
                <div class="card-body">
                    <form onsubmit="return false" id="create-table-schema" method="post">
                        <input type="hidden" name="_request" value="CREATE_TABLE_SCHEMA">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group" style="margin-bottom: 0rem;">
                                    <h5 for="">Select Table Name</h5>
                                    <select name="add_entity_table_name" id="add_entity_table_name" class="form-control"></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group" style="margin-bottom: 0rem;">
                                    <h5 for="">Table ID</h5>
                                    <input type="text" class="form-control" name="table_id">
                                </div>
                            </div>
                            <div class="col-md-12 table-responsive">
                                <span class="add badge bg-success text-white px-2 py-1 my-2" style="cursor:pointer;">Add Row</span>
                                <div class="form-group table-responsive">
                                    <h5 for="">Schema Name</h5>
                                    <table class="table table-sm" id="table">
                                        <tr class="card">
                                            <th class="card-body">
                                                <span style="font-size: 12px;">Column Name</span>
                                                <input type="text" name="thead[]" placeholder="Enter Schema Name..." class="form-control form-control-sm">
                                                <span style="font-size: 12px;">Select Data Type</span>
                                                <select name="thead_type[]" class="form-control form-control-sm">
                                                    <option value="string">String</option>
                                                    <option value="number">Number</option>
                                                    <option value="boolean">Boolean</option>
                                                    <option value="datetime">Datetime</option>
                                                </select>
                                                <span style="font-size: 12px;">Enter Data</span>
                                                <input type="text" name="tdata[]" placeholder="Enter Schema Name..." class="form-control form-control-sm">
                                            </th>
                                        </tr>
                                    </table>

                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <div class="form-group">
                                    <input type="submit" class="btn btn-success" value="Add Schema To Table">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>

        <div class="col-lg-2">
            <ul class="list-group" style="border-top-left-radius: .25rem;border-top-right-radius: .25rem;">
                <li class="list-group-item active">Table List</li>
            </ul>
            <ul class="list-group" id="table-data"></ul>
        </div>

        <div class="col-lg-7">
            <div class="row" id="table-schema-data">
            </div>
        </div>


    </div>



</div>
</div>
















<?php include './layouts/footer.php'; ?>