$("#mainForm").submit(function (e) {
    e.preventDefault();
}).on('reset', function (e) {
    $("#ftitle").html('Add');
    $("#product_code").html('').focus();
    $("#product_name").html('');
    $("#uom").html('');
    $("#price").val(0);
    $("#status").iCheck('check');
});

$.validate({
    form: "#mainForm",
    validateOnBlur: false,
    onError: function () {
        $('.help-block').remove();
        bksfn.errMsg("Please fill form");
    },
    onSuccess: function () {
        if ($("#ftitle").html().substr(0, 4) == "Edit") {
            $.post('product/update', $("#mainForm").serialize() + "&id=" + $("body").data("id"), function (obj) {
                if (obj.msg == 1) {
                    $("#mainForm")[0].reset();
                    t.ajax.reload();
                    alertify.success("Edit Data Success");
                } else {
                    bksfn.errMsg(obj.msg);
                }
            }, "json").fail(function () {
                bksfn.errMsg();
            });
        } else {
            $.post('product/insert', $("#mainForm").serialize(), function (obj) {
                if (obj.msg == 1) {
                    $("#mainForm")[0].reset();
                    t.ajax.reload();
                    alertify.success("Insert Data Success");
                } else {
                    bksfn.errMsg(obj.msg);
                }
            }, "json").fail(function () {
                bksfn.errMsg();
            });
        }
    }
});

t = $('#mainTable').DataTable({
    responsive:true,
    dom: 'lfrtip',
    lengthMenu: [[5, 10, -1], [5, 10, 'All']],
    ajax: {
        url: baseUrl + 'product/product/getdata',
        type: 'POST',
        data: {},
        dataSrc: ""
    },
    columns: [
        {data: '#', width: "4%", className: "dt-body-center", render: function (data, type, row, meta) {                    
            return meta.row + meta.settings._iDisplayStart + 1;
        }},
        {data: 'product_code', width: "15%", render: function (data, type, row, meta) {                    
            return data;
        }},
        {data: 'product_name', width: "45%", render: function (data, type, row, meta) {                    
            return data;
        }},
        {data: 'uom',  width: "8%", render: function (data, type, row, meta) {
            return data;
        }},
        {data: 'price',  width: "10%", render: function (data, type, row, meta) {
            return formatRupiah(data);
        }},
        {data: 'status', className: "dt-body-center", width: "5%", render: function (data, type, row, meta) {
            var act = (data == '1') ? '<span class="label label-success"><i class="fa fa-check"></i></span>' : '<span class="label label-danger"><i class="fa fa-times"></i></span>';
            return act;
        }},
        {data: 'id', width: "15%", orderable: false, render: function (data, type, row, meta) {
                return '<a title="Edit" href="#" class="btn btn-default btn-sm"><i class="fa fa-edit"></i></a>&nbsp;<a title="Delete" href="#" class="btn btn-default btn-sm"><i class="fa fa-remove"></i></a>';
            }
        },
    ],           
    order: [[1, 'asc']]
});

$('#mainTable').on('click', 'a[title^=Edit]', function (e) {
    e.preventDefault();
    window.scroll(0, 0);
    var elm = $(this).closest("tr");
    var d = t.row(elm).data();
    $("#ftitle").html('Edit');
    $("#product_code").val(d.product_code).focus();
    $("#product_name").val(d.product_name);
    $("#uom").val(d.uom);
    $("#price").val(d.price);
    $("#status").iCheck(d.status == 1 ? 'check' : 'uncheck');
    $("body").data("id", d.id);
});

$('#mainTable').on('click', 'a[title^=Delete]', function (e) {
    e.preventDefault();
    var elm = $(this).closest("tr");
    var d = t.row(elm).data();
    $.ajax({
        url : baseUrl +  'product/product/delete',
        type: 'POST',
        data: {'id' : d.id },
        datatype: 'json',
        success: function(data){
            t.ajax.reload();
            alertify.success("Delete success");
        },
        error: function(xhr){                
            alertify.error("error");
        }
    });
});
