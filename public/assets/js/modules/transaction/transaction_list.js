$("#btn-submit").on('click', function (e) {
    e.preventDefault();
    if ( $.fn.dataTable.isDataTable('#mainTable') ) {
        $('#mainTable').dataTable().fnClearTable();
        $('#mainTable').DataTable().destroy();            
        fetch_data();
    } else {
        fetch_data();
    }
});

function fetch_data(){
    t = $('#mainTable').DataTable({
        paging:true,
        ordering:false,
        info: false,
        responsive: true,
        scrollY: true,
        scrollX: true,
        scrollCollapse: true,
        lengthChange: false,
        dom: '<"top"i>rt<"bottom"flp><"clear">',
        lengthMenu: [[3, 5, -1], [3, 5, 'All']],
        ajax: {
            url: baseUrl + 'transaction/transaction_list/getdata',
            type: 'POST',
            data: {'tr_date' : $("#tr_date").val()},
            dataSrc: ""
        },
        columns: [
            {data: '#', width: "4%", className: "dt-body-center", render: function (data, type, row, meta) {                    
                return meta.row + meta.settings._iDisplayStart + 1;
            }},
            {data: 'tr_name', width: "5%", render: function (data, type, row, meta) {                    
                return data;
            }},
            {data: 'tr_number',  width: "11%", render: function (data, type, row, meta) {
                return data
            }},
            {data: 'total',  width: "10%", render: function (data, type, row, meta) {
                return formatRupiah(data)
            }},

            {data: 'status_name',  width: "15%", render: function (data, type, row, meta) {
                return data;
            }},
            {data: 'created', width: "15%", render: function (data, type, row, meta) {                    
                return data;
            }},
            {data: 'createdby_name', width: "25%", render: function (data, type, row, meta) {                    
                return data;
            }},
            {data: 'id', width: "10%", orderable: false, render: function (data, type, row, meta) {
                    return '<a title="Select" href="#" class="btn btn-danger btn-block btn-xs"><strong style="font-weight:bold;font-size:13px;">Select</strong></a>';
                }
            },
        ],           
        order: [[1, 'asc']],
        initComplete: function() {
            var table = $('#mainTable').DataTable();
            var table_length = table.data().count();
            if(Number(table_length) <= 0){   
                $('#mainTable_filter').hide();
                $('#mainTable_paginate').hide();
                $("#btn-confirm").hide();
                $("#btn-cancel").show();
                reset_form_input();            
            }
            if(Number(table_length) <= 3){   
                $('#mainTable_filter').hide();
                $('#mainTable_paginate').hide();                    
            }
        }
    });

    $('#mainTable').on('click', 'a[title^=Select]', function (e) {
        e.preventDefault();
        var elm = $(this).closest("tr");
        var d = t.row(elm).data();
        url = call_page_task(d.id);
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
    });
}