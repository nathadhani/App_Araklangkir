back_to_this_page();
function back_to_this_page(){ 
    $("#ftitle").html('New');
    $("#btn-confirm").hide();
    $("#btn-cancel").hide();
    $("#btn-print").hide();
    reset_form_input();
    $("#status_name").html('');
    var sisa_stock = 0;
    var xtr_id = $("#tr_id").val();
    var statusId = '1';
    if( typeof(id_header) != 'undefined' && id_header !== null && id_header !== '' ) {
        $("#btn-confirm").show();
        $("#btn-cancel").show();                                
        get_header();                        
    } 
}

function get_header(){
    if( typeof(id_header) != 'undefined' && id_header !== null && id_header !== '' ) {    
        $.ajax({
            url: baseUrl + "transaction/transaction/getheader",
            type: 'POST',
            data: {'id' : id_header},
            datatype: 'json',
            success: function(data){
                if (data !== '[]' && data.length > 0){
                    var d = JSON.parse(data)[0];

                    $("#ftitle").html("Edit");
                    if (d.tr_id != 0 && d.tr_id != null && d.tr_id != '') {
                        $("#tr_id").html('<option value="' + d.tr_id + '">' + d.tr_name + '</option>').sel2dma();
                        $('#tr_id').prop('disabled', true);
                    } else {
                        $("#tr_id").html('').sel2dma();
                    }

                    $("#tr_number").val(d.tr_number);
                    $("#tr_date").val(bksfn.revDate(d.tr_date));
                    $("#description").val(d.description);
                    $("#status_name").html(d.status_name);
                    
                    statusId = d.status;
                    $("#total_transaksi").html('');
                    if ( $.fn.dataTable.isDataTable('#mainTable') ) {
                        $('#mainTable').dataTable().fnClearTable();
                        $('#mainTable').DataTable().destroy();            
                        get_detail(id_header);
                    } else {
                        get_detail(id_header);
                    }
                }               
            },
            error: function(xhr){
                alertify.error("error");
            }
        });        
    }        
}

function get_detail(id_header){
    t = $('#mainTable').DataTable({
        responsive:true,
        dom: 'lfrtip',
        lengthMenu: [[5, 10, -1], [5, 10, 'All']],
        ajax: {
            url: baseUrl + 'transaction/transaction/getdetail',
            type: 'POST',
            data: {'header_id' : id_header},
            dataSrc: ""
        },
        columns: [
            {data: '#', width: "4%", className: "dt-body-center", render: function (data, type, row, meta) {                    
                return meta.row + meta.settings._iDisplayStart + 1;
            }},
            {data: 'product_code', width: "50%", render: function (data, type, row, meta) {                    
                return data + ' - ' + row.product_name;
            }},
            {data: 'qty',  width: "11%", render: function (data, type, row, meta) {
                return formatRupiah(data)
            }},
            {data: 'price',  width: "10%", render: function (data, type, row, meta) {
                return formatRupiah(data)
            }},

            {data: 'subtotal',  width: "15%", render: function (data, type, row, meta) {
                return formatRupiah(data)
            }},
            {data: 'id', width: "10%", orderable: false, render: function (data, type, row, meta) {
                    return '<a style="cursor:pointer;font-weight:400;color:red;" title="hapus" onClick="delete_line_detail(' + data + ')"><i>remove</i></a>';
                }
            },
        ],           
        order: [[1, 'asc']],
        initComplete: function() {
            var table = $('#mainTable').DataTable();
            var table_length = table.data().count();
            if(Number(table_length) <= 0){   
                $("#btn-confirm").hide();
                $("#btn-cancel").show();
                reset_form_input();
            } else {
                var total_transaksi = 0;
                $.each(table.data(), function (i, d) {               
                    total_transaksi += Number(d.subtotal);
                });                
                $("#total_transaksi").html(formatRupiah(total_transaksi.toFixed(0)));
                $("#btn-print").show();                    
            }
        }
    });
}

function delete_line_detail(xid){
    if( typeof(xid) != 'undefined' && xid !== null && xid !== '' ) {
        $.ajax({
            url : baseUrl +  'transaction/transaction/delete_detail',
            type: 'POST',
            data: {'id' : xid },
            datatype: 'json',
            success: function(data){
                get_header();
                alertify.success("Delete item success");
            },
            error: function(xhr){                
                alertify.error("error");
            }
        });
    }       
}

function reset_form_input(){
    $("#product_id").html('').sel2dma();    
    $("#qty").val('');
    $("#price").val('');
    $("#subtotal").val('');
    $("#btn-add-detail").focus();
}

function add_item(){
    subtotal_input();
    if( formatRupiahtoNumber($("#subtotal").val()) > 0){
        $.post(baseUrl +'transaction/transaction/insert', $("#mainForm").serialize() + "&header_id=" + id_header + "&tr_id=" + xtr_id + "&tr_date=" + $("#tr_date").val() , function (obj) {
            if (obj.msg == 1) {            
                reset_form_input();
                if(id_header == null || id_header == ''){
                    id_header = obj.id_header;            
                    url = baseUrl + "transaction/transaction/index/"+id_header;
                    if(url !== ''){
                        $.ajax({
                            url: url,
                            type: 'POST',
                            success: function() {
                                window.open(url,'_self'); 
                            },
                            error: function(){
                                alertify.error("can't open page.!");
                            }
                        });    
                    }
                } else {
                    get_header();
                }
                alertify.success("Insert data success");                        
            } else {
                bksfn.errMsg(obj.msg);
            }
        }, "json").fail(function (xhr) {
            alertify.error("error");
        });
    } else {
        alertify.alert('Nilai Subtotal kosong!');
        return false;
    }
}

function getstockbyid(){
    xtr_id = $("#tr_id").val();
    if(xtr_id == 2){
        if($("#product_id").val() !== null && $("#product_id").val() !== ''){
            $.ajax({
                url: baseUrl + 'transaction/transaction/getstockbyid',
                type: 'POST',
                data: {'product_id' : $("#product_id").val(), 'period' : $("#tr_date").val() },
                datatype: 'json',
                success: function(data){
                    console.log(data);
                    if (data !== undefined) {
                        if (data !== '[]' && data.length > 0){
                            var d = JSON.parse(data)[0];
                            sisa_stock = (d.ending_stock === null ? 0 : Number(d.ending_stock));
                            if(sisa_stock > 0){
                                $("#qty").val(sisa_stock);
                                subtotal_input();
                            }
                        } else {
                            sisa_stock = 0;                         
                        }
                    }
                },
                error: function(xhr){
                    alertify.error("error");
                }
            });
        }
    }
}

function subtotal_input() {
    var xtotal  = Math.round(($('#qty').val() * $('#price').val()));
    $('#subtotal').val(formatRupiah(xtotal.toString()));        
}

$("#btn-add-detail").on('click', function (e) {
    e.preventDefault();   
    xtr_id = $("#tr_id").val();
    if ( $("#tr_id").val() === null || $("#tr_id").val() === '' ){
        bksfn.errMsg("Transaksi IN / OUT belum di pilih!");
        $("#product_id").focus();
    } else if ( $("#product_id").val() === null || $("#product_id").val() === '' ){
        bksfn.errMsg("product belum di pilih!");
        $("#product_id").focus();
    } else if( $("#qty").val() === 0 || $("#qty").val() === '' ) {
        bksfn.errMsg("jumlah qty belum di input!");
        $("#qty").focus();
    } else if( $("#price").val() === 0 || $("#price").val() === '' ){
        bksfn.errMsg("harga belum di input!");
        $("#price").focus();
    } else {
        subtotal_input();
        if(xtr_id == 1){ // Trx IN
            add_item();
        }
        if(xtr_id == 2){ // Trx IN
            getstockbyid();
            if(sisa_stock > 0){                    
                var qty_input = parseInt(formatRupiahtoNumber( ($("#qty").val() == null || $("#qty").val() == '' ? 0 : $("#qty").val()) ));
                if( sisa_stock < qty_input ){
                    alertify.alert('Stok kurang, hanya tersedia ' + sisa_stock + '!');
                    $("#qty").val(sisa_stock);
                    subtotal_input();
                    return false;
                }
                if( sisa_stock >= qty_input ){
                    add_item();
                }
            } else {
                reset_form_input();
                alertify.alert('Stok kosong!');
                return false;
            }
        }
    }
});

$("#btn-reset-detail").on('click', function (e) {
    e.preventDefault();
    reset_form_input();        
});

$("#btn-confirm").on('click', function (e) {
    e.preventDefault();
    var table = $('#mainTable').DataTable();
    var table_length = table.data().count();
    if(Number(table_length) <= 0){
        bksfn.errMsg("belum ada data diinput!");
    } else {
        $.ajax({
            url: baseUrl + 'transaction/transaction/confirm_task',
            type: 'POST',
            beforeSend: function(){
                $(".ajax-loader").height($(document).height());
                $('.ajax-loader').css("visibility", "visible");
            },
            data: {'id' : id_header, 'tr_id' : $("#tr_id").val(), 'description' : $("#description").val()},
            datatype: 'json',
            success: function(data) {
                if(data.length > 0){
                    try {
                        back_to_this_page();
                        alertify.success('confirm transaction success!');
                    } catch (e) {
                        alertify.error("error");
                    }                        
                }                        
            },
            complete: function(){
                $('.ajax-loader').css("visibility", "hidden");
            },
            error: function(xhr){
                alertify.error("error");
            }
        });        
    }        
});

$("#btn-cancel").on('click', function (e) {
    e.preventDefault();
    $.ajax({
        url: baseUrl + 'transaction/transaction/cancel_trx',
        type: 'POST',
        data: {'id' : id_header},
        datatype: 'json',
        success: function() {
            back_to_this_page();
            alertify.success('CANCEL Transaction Success!');
        },
        error: function(xhr){
            alertify.error("error");
        }
    });
});

$('#product_id').on('change',function(){
    if($(this).val() != null && $(this).val() != ''){
        getstockbyid();
        subtotal_input(); 
    } else {
        $('#qty').val('');
        $('#price').val('');
        $('#subtotal').val('');
    }
});

$("#qty").keyup(function(e) {
    e.preventDefault();
    $(this).val($(this).val());    
    if($(this).val() != null && $(this).val() != ''){
        $(this).val($(this).val());
        subtotal_input();
    } else {
        $('#price').val('');
        $('#subtotal').val('');
    }
});

$("#price").keyup(function(e) {
    e.preventDefault();
    if($(this).val() != null && $(this).val() != ''){
        $(this).val($(this).val());
        subtotal_input();
    } else {
        $('#subtotal').val('');
    }
});
