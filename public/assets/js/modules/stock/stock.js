$("#btn-submit").on('click', function (e) {
    e.preventDefault();
    if($('#product_id').val() === null || $('#product_id').val() === ''){
        bksfn.errMsg('Product Belum Dipilih!');    
    } else {
        $('#table-detail tbody').empty();
        $.ajax({
            url: baseUrl + 'stock/stock/getsaldoawal',
            type: 'POST',
            data: {'product_id' : $('#product_id').val(), 'period' : $('#period').val()},
            datatype: 'json',
            success: function(data){
                $saldo = 0;                
                $begin = 0;         
                if (data !== '[]' && data.length > 0){
                    var d = JSON.parse(data)[0];
                    $saldo = Number(d.ending_stock);                
                    $begin = Number(d.ending_stock);
                }
                var rows =`<tr style="vertical-align:middle">
                            <td width="45%" colspan="3" style="text-align:center;">Beginning</td>
                            <td width="15%">
                                ` + ($begin  !== null && $begin !== 0 ? formatRupiah($begin) : '-') + `                       
                            </td>
                            <td width="40%"></td>
                        </tr>`
                $('#table-detail tbody').append(rows);
                $.ajax({
                    url: baseUrl + 'stock/stock/gettrx',
                    type: 'POST',
                    data: {'product_id' : $('#product_id').val(), 'period' : $('#period').val()},
                    dataType: 'json',
                    success: function (data) {
                        if (data !== '[]' && data.length > 0){                            
                            $.each(data, function (i, d) {   
                                $description = d.description;
                                if(Number(d.status) === 2){
                                    $description = d.description + ' / Canceled';
                                }
                                if(Number(d.status) === 1){
                                    $description = d.description + ' / Task';
                                }                                
                                if(Number(d.status) !== 2){                                
                                    $saldo = ($saldo +  Number(d.qty_in)) - Number(d.qty_out);
                                }                                
                                var rows =`<tr style="vertical-align:middle">
                                            <td width="15%">
                                                ` + bksfn.revDate(d.tr_date) + `
                                            </td>
                                            <td width="15%">
                                                ` + (d.qty_in !== null && Number(d.qty_in) > 0 ? formatRupiah(d.qty_in) : '-') + `
                                            </td>
                                            <td width="15%">
                                                ` + (d.qty_out !== null && Number(d.qty_out) > 0 ? formatRupiah(d.qty_out) : '-') + `
                                            </td>                                       
                                            <td width="15%">
                                                ` + ($saldo !== null && Number($saldo) !== 0 ? formatRupiah($saldo) : '-') + `
                                            </td>                                        
                                            <td width="40%">
                                                ` + d.tr_number.slice(-4) + ' - ' + $description +`                       
                                            </td>
                                        </tr>`
                                $('#table-detail tbody').append(rows);
                            });
                        }
                    },
                    error: function(xhr){
                        alertify.error("error");
                    }
                });                            
            },
            error: function(xhr){
                alertify.error("error!");
            }
        });
    }
});