var bksfn = ({
    revDate: function (mystr, splitt, delimiter) {
        splitt = splitt || "-";
        delimiter = delimiter || "-";
        if (mystr != null) {
            var myarr = mystr.split(splitt);
            myarr = myarr[2] + delimiter + myarr[1] + delimiter + myarr[0];
            return myarr;
        } else {
            return null;
        }
    },
    errMsg: function (err) {
        err = (err == undefined) ? "Error Connection" : "message : " + err;
        alertify.error(err);
        $(':submit').removeAttr('disabled');
    }        
});
    
(function ($) {
    $.fn.sel2dma = function (formatResult, formatSelection, addParm) {
        var theSelect2 = this;
        var select2Config = {};
        if (theSelect2.data('ajax')) {
            select2Config.ajax = {
                'type': 'POST',
                'url': baseUrl + theSelect2.data('url'),
                'dataType': (theSelect2.data('datatype') ? theSelect2.data('datatype') : 'JSON'),
                'delay': (theSelect2.data('delay') ? theSelect2.data('delay') : 250),
                'data': (theSelect2.data('fnData') ? theSelect2.data('fnData') : function (params) {
                    return {
                        q: (params.term != undefined) ? params.term : '',
                        page: params.page,
                        addParm: addParm,
                        limit: theSelect2.data('limit') ? theSelect2.data('limit') : 10
                    }
                }),
                processResult: (theSelect2.data('fnProcessResult') ? theSelect2.data('fnProcessResult') : function (data, page) {
                    return {
                        results: data.items
                    };
                }),
                cache: (theSelect2.data('cache') ? theSelect2.data('cache') : true)
            };
        }
        ;
        if (formatResult != undefined) {
            select2Config.templateResult = formatResult;
            select2Config.templateSelection = formatSelection;
            select2Config.escapeMarkup = function (markup) {
                return markup;
            };
        }
        select2Config.allowClear = (theSelect2.data('allowclear') == "0") ? false : true;
        select2Config.minimumInputLength = theSelect2.data('mininputlength') ? theSelect2.data('mininputlength') : -1;
        var thDefaultValue = theSelect2.data('value');
        var initSelection = function () {

        };
        if (typeof (thDefaultValue) !== 'undefined' && String(thDefaultValue).length > 0) {
            select2Config.initSelection = function (elm, calb) {
                return $.ajax({
                    type: "POST",
                    url: baseUrl + theSelect2.data('url'),
                    dataType: 'json',
                    data: {
                        id: thDefaultValue,
                        action: 'initSelection'
                    },
                    success: function (data) {
                        calb(data);
                    }
                });
            };
        }
        theSelect2.select2(select2Config);
        return theSelect2;
    };
    var theSelectDOMopt = $("select.select2");
    var objSelect2 = [];
    theSelectDOMopt.each(function (key, val) {
        $(theSelectDOMopt[key]).sel2dma();
    });

    $.fn.selectDTBks = function (t, uRLDelete) {
        var selDT = this;
        selDT.on('click', 'tr td', function () {
            if ($(this).parent().find("td").length < 3) {
                $(this).parent().toggleClass('selected');
            } else {
                if (!$(this).is(":last-child")) {
                    $(this).parent().toggleClass('selected');
                }
            }
            return selDT;
        });
        //--- Toogle
        selDT.find("button.toggle-selected").click(function (e) {
            e.preventDefault();
            $('tr', selDT).toggleClass('selected');
        });
        //--- Delete
        selDT.find("button.delete-selected").click(function (e) {
            e.preventDefault();
            var dr = t.rows('.selected').data();
            if (dr.length == 0) {
                alertify.error("Please select row");
                return false;
            }
            alertify.set({buttonFocus: "cancel"});
            // alertify.confirm(dr.length + " rows data will be delete", function (e) {
            alertify.confirm(dr.length + " baris data akan di hapus", function (e) {    
                if (e) {
                    var id = [];
                    $.each(dr, function (i, val) {
                        id[i] = val.id;
                    });
                    $.post(uRLDelete, {id: JSON.stringify(id)}, function (obj) {
                        if (obj.msg == 1) {
                            t.ajax.reload();
                            alertify.success("Delete Data Success");
                        } else {
                            alertify.error("Error : " + obj.msg);
                        }
                    },
                    "json").fail(function () {
                        alertify.error("can't delete!");
                    });
                }
            });
        });
    };

    $.fn.checkIfExist = function(alertElm, controller, field, formElm) {
        const elm = this;
        elm.on('keyup', function (e) {
            e.preventDefault();
            $.ajax({
                url : baseUrl + controller + '/checkIfExist',
                type: 'POST',
                data: {'value' : $(this).val(), 'field' : field},
                datatype: 'json',
                success: function(resp) {
                    if (resp == 1) {
                        $('#'+alertElm).fadeIn('slow', function() {
                            $('#'+formElm).find("button[type=\"submit\"]").attr("disabled",true);
                        });
                    } else {
                        $('#'+alertElm).fadeOut('slow', function() {
                            $('#'+formElm).find("button[type=\"submit\"]").removeAttr('disabled');
                        });
                    }
                },
                error: function(xhr){
                    alertify.error(xhr.responseText);
                }
            });
        });
    };    

    $('input[type="checkbox"].icheck, input[type="radio"].icheck').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass: 'iradio_flat-green'
    });

    $('[data-widget="collapse"]').on('click', function (e) {
        e.preventDefault();
        el = $(this).parent().parent().next();
        $(el).slideToggle();
        if ($('i', this).hasClass('icon-arrow-down')) {
            $('i', this).removeClass('icon-arrow-down').addClass('icon-arrow-up');
        } else {
            $('i', this).removeClass('icon-arrow-up').addClass('icon-arrow-down');
        }
    });

    $.fn.dataTable.ext.errMode = 'none';
    $('#mainTable table').on('error.dt', function (e) {
        console.log(e);
        //window.location.replace('auth/?n=' + window.location.pathname.substr(1));
    });

    // Set Active Menu
    $.defaultPage = '.';
    url = window.location.pathname.replace(/^#/, '');
    if (url != '') {
        // setUpUrl(url.substring(1));        
    } else {
        setUpUrl($.defaultPage);
    }

    function setUpUrl(url) {
        $('li.xn-openable').removeClass('active');
        $('.x-navigation li:has(a[href="' + url + '"])').addClass('active');
        $('.x-navigation a[href="' + url + '"]').parent().addClass('active');

    }

    //---- Datepicker
    $(".dp").datepicker({
        format: "dd-mm-yyyy",
        autoclose: true,
        todayHighlight: true
    });
    $(".dpM").datepicker({
        format: "mm-yyyy",
        autoclose: true,
        minViewMode: 1
    });
    $(".dpY").datepicker({
        format: "yyyy",
        autoclose: true,
        minViewMode: 2
    });    
    $(":input").inputmask();    

})(jQuery);

function formatRupiah(angka) {
    return Number(angka).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); 
}  

function formatRupiahtoNumber(v){
    if(!v){return 0;}
    return Number(v.replace(/[^0-9.]/g, ""));
}

function validasiAngka(evt){
    if(evt.length > 0) {
        evt.value = evt.value.replace(/[^0-9]/g,"");
    }
}