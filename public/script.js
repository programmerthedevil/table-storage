class TableScript {
    static APP_URL = 'http://localhost/devil/file_storage/arjun/Table/controller/processController.php';

    static create(_tableName) {
        $(".overlay").show();
        $.ajax({
            method: "POST",
            url: this.APP_URL,
            data: { _request: "CREATE_TABLE", _tableName: _tableName },
            dataType: "json",
            success: function (response) {
                console.log(response);
                $(".overlay").hide();
                if (response["status"] == 201) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Yupp...',
                        text: response["msg"]
                    }).then(() => {
                        TableScript.index();
                    })
                } else if (response["status"] == 409) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response["msg"]
                    }).then(() => {
                        return false;
                    })
                }
            },
            error: function (err) {
                console.log(err);
                $(".overlay").hide();
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: err["msg"]
                }).then(() => {
                    return false;
                })
            }
        });
    }

    static index() {
        $(".overlay").show();
        $.ajax({
            method: "POST",
            url: this.APP_URL,
            data: { _request: "LIST_TABLE" },
            dataType: "json",
            success: function (response) {

                $(".overlay").hide();
                if (response["status"] == 201) {
                    let li = "";
                    let op = "";
                    response["data"].forEach(element => {
                        li += '<li class="list-group-item"><span class="link tableName" data-table-name="' + element + '">' + element + '</span></li>';
                        op += '<option value="' + element + '">' + element + '</option>';
                    });

                    $("#table-data").html(li);
                    $("#add_entity_table_name").html(op);
                }
            },
            error: function (err) {

                $(".overlay").hide();
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: err["msg"]
                }).then(() => {
                    return false;
                })
            }
        });
    }

    static indexOfTableSchemaWithData(_tableName) {
        $(".overlay").show();
        $.ajax({
            method: "POST",
            url: this.APP_URL,
            data: { _request: "LIST_TABLE_SCHEMA_WITH_VALUE", _tableName:_tableName },
            dataType: "json",
            success: function (response) {
                console.log(response["data"]);
                $(".overlay").hide();
                if (response["status"] == 201) {
                   $("#table-schema-data").html(response['data']);
                }
            },
            error: function (err) {
                console.log(err);
                $(".overlay").hide();
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: err["msg"]
                }).then(() => {
                    return false;
                })
            }
        });
    }

    static listTableData(_tableName) {
        $(".overlay").show();
        $.ajax({
            method: "POST",
            url: this.APP_URL,
            data: { _request: "LIST_TABLE_DATA", _tableName: _tableName },
            dataType: "json",
            success: function (response) {
                // console.log(response)
                $(".overlay").hide();
                if (response["status"] == 201) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Yupp...',
                        text: response["msg"]
                    }).then(() => {
                        return false;
                    })
                } else if (response["status"] == 409) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response["msg"]
                    }).then(() => {
                        return false;
                    })
                }
            },
            error: function (err) {
                console.log(err)
                $(".overlay").hide();
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: err["msg"]
                }).then(() => {
                    return false;
                })
            }
        });
    }

    static createTableData(_tableData) {
        $.ajax({
            method: "POST",
            url: this.APP_URL,
            data: _tableData,
            dataType: "json",
            success: function (response) {
                // console.log(response)
                $(".overlay").hide();
                if (response["status"] == 201) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Yupp...',
                        text: response["msg"]
                    }).then(() => {
                        $("#create-table-schema").trigger('reset');
                        window.location.reload();
                    })
                } else if (response["status"] == 409) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response["msg"]
                    }).then(() => {
                        return false;
                    })
                }
            },
            error: function (err) {
                // console.log(err)
                $(".overlay").hide();
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: err["msg"]
                }).then(() => {
                    return false;
                })
            }
        });
    }

    static allowOnlyAlphaNumeric(input) {
        let code = input.charCodeAt();
        if ((code >= 48 && code <= 57) || (code >= 65 && code <= 90) || (code >= 97 && code <= 122)) {
            return true;
        } else {
            return false;
        }
    }
}




//=============== Index Table List ==========//
TableScript.index();

//================= Get Table Schema ===============//
$("body").delegate(".tableName", "click", function() {
    let tableName = $(this).attr('data-table-name');
    TableScript.indexOfTableSchemaWithData(tableName);
})


//============ Create Table ===========//
$("#create-table").on("submit", function () {
    // let thead = $("input[name='thead[]']").map(function () { return $(this).val(); }).get();
    let table_name = $("#table_name").val();

    TableScript.create(table_name);
    TableScript.index();
});


//============ Create Table Schema ===========//
$("#create-table-schema").on("submit", function () {
    TableScript.createTableData($("#create-table-schema").serialize());
});








$(".add").click(function () {
    $('#table tr:last').after(`
        <tr class="card mt-2">
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
    `);
})