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
        responsive:true,
        dom: 'lfrtip',
        lengthMenu: [[5, 10, -1], [5, 10, 'All']],
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
            {data: 'status_name',  width: "10%", render: function (data, type, row, meta) {
                return data;
            }},
            {data: 'description', width: "25%", render: function (data, type, row, meta) {                    
                return data;
            }},
            {data: 'created', width: "15%", render: function (data, type, row, meta) {                    
                return data;
            }},
            {data: 'createdby_name', width: "15%", render: function (data, type, row, meta) {                    
                return data;
            }},
            {data: 'id', width: "5%", orderable: false, render: function (data, type, row, meta) {
                    return '<a title="Select" href="#" class="btn btn-default btn-sm"><i class="fa fa-eye"></i></a>';
                }
            },
        ],           
        order: [[1, 'asc']],
    });

    $('#mainTable').on('click', 'a[title^=Select]', function (e) {
        e.preventDefault();
        var elm = $(this).closest("tr");
        var d = t.row(elm).data();
        url = baseUrl + "transaction/transaction/index/"+d.id;
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