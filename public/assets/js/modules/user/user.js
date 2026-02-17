$("#mainForm").submit(function (e) {
    e.preventDefault();
}).on('reset', function (e) {
    $("#ftitle").html('Add');
    $("#fullname").html('');
    $("#username").focus();
    $("#status").iCheck('checked');
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
            $.post('user/update', $("#mainForm").serialize() + "&id=" + $("body").data("id"), function (obj) {
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
            $.post('user/insert', $("#mainForm").serialize(), function (obj) {
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
        url: baseUrl + 'user/user/getdata',
        type: 'POST',
        data: {},
        dataSrc: ""
    },
    columns: [
        {data: '#', width: "4%", className: "dt-body-center", render: function (data, type, row, meta) {                    
            return meta.row + meta.settings._iDisplayStart + 1;
        }},
        {data: 'fullname', width: "55%", render: function (data, type, row, meta) {                    
            return data;
        }},
        {data: 'username', width: "16%", render: function (data, type, row, meta) {                    
            return data;
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
    $("#mainForm")[0].reset();
    window.scroll(0, 0);
    var elm = $(this).closest("tr");
    var d = t.row(elm).data();
    $("#ftitle").html('Edit');
    $("#fullname").val(d.fullname);
    $("#username").val(d.username).focus();
    $('#password').prev().removeClass('mandatory');
    $('#password').removeAttr('data-validation');
    $("#status").iCheck(d.status == 1 ? 'check' : 'uncheck');
    $("body").data("id", d.id);
});

$('#mainTable').on('click', 'a[title^=Delete]', function (e) {
    e.preventDefault();
    var elm = $(this).closest("tr");
    var d = t.row(elm).data();
    $.ajax({
        url : baseUrl +  'user/user/delete',
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
