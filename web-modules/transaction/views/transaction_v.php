<script type="text/javascript">  
   var id_header = <?php echo $this->uri->segment(4);?>;
</script>

<style>
    #mainTable_filter input {
        width: 250px;
        height: 32px;
        background: #fcfcfc;
        border: 1px solid #aaa;
        border-radius: 5px;
        box-shadow: 0 0 3px #ccc, 0 10px 15px #ffffff inset;
        text-indent: 10px;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <form id="mainForm" class="form-horizontal" autocomplete="off">
            <div class="panel panel-default">
                <div class="panel-heading ui-draggable-handle">
                    <h3 class="panel-title"><strong id="ftitle">New</strong> Transaction</h3>
                    <ul class="panel-controls">
                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                    </ul>
                </div>
                <div class="panel-body">                                                                        
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="description" class="control-label col-lg-4">Trx Number</label>
                                <div class="col-lg-8">
                                    <input type="text" id="tr_number" name="tr_number" placeholder="Trx Number..." class="form-control" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-lg-4">Trx Date</label>
                                <div class="col-lg-8">
                                    <input type="text" id="tr_date" name="tr_date" placeholder="Tanggal..." class="form-control dp" data-date-format="DD MMMM YYYY" value="<?=date('d-m-Y');?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-lg-4">Status</label>
                                <div class="col-lg-8">
                                    <span id="status_name"></span>
                                </div>
                            </div>                      
                        </div>    
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="tr_id" class="control-label col-lg-4">Select Transaction</label>
                                <div class="col-lg-8">
                                    <select id="tr_id" name="tr_id" placeholder="Select" class="form-control">
                                        <option value="" selected="">Select...</option>
                                        <option value="1">IN</option>
                                        <option value="2">OUT</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="description" class="control-label col-lg-4">Description</label>
                                <div class="col-lg-8">
                                    <input type="text" id="description" name="description" placeholder="Description..." data-validation="required" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-lg-4">Total Rp.</label>
                                <div class="col-lg-8">
                                    <span id="total_transaksi" style='text-align:left; color:#FF0000; font-weight:bolder; font-size:24px;'></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
            <div class="panel panel-default">
                <div class="panel-heading ui-draggable-handle">
                    <h3 class="panel-title"><strong>Add</strong> Product</h3>
                    <ul class="panel-controls">
                    </ul>
                </div>
                <div class="panel-body">                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group row">
                                <label for="product_id" class="control-label col-lg-4">Select Product</label>
                                <div class="col-lg-8">
                                    <select id="product_id"
                                            name="product_id"
                                            data-ajax="true"
                                            data-placeholder="-- Select --"
                                            data-url="product/product/getproduct/"
                                            data-value=""
                                            data-limit="100"
                                            placeholder="Product"
                                            class='form-control select2'
                                            require
                                    >
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="qty" class="control-label col-lg-4">Qty</label>
                                <div class="col-lg-8">
                                    <input type="text" onkeypress="validasiAngka(event)" autofocuse="" id="qty" name="qty" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group row">
                                <label for="price" class="control-label col-lg-4">Price</label>
                                <div class="col-lg-8">
                                    <input type="text" onkeypress="validasiAngka(event)" autofocuse="" id="price" name="price" class="form-control"'>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="subtotal" class="control-label col-lg-4">Subtotal</label>
                                <div class="col-lg-8">
                                    <input type="text" autofocuse="" id="subtotal" class="form-control" value="0" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <button id="btn-add-detail" class="btn btn-success" style="width:100px;"><i class="fa fa-plus"></i>&nbsp;Item</button>
                            </div>
                            <div class="row" style="margin-top:10px;">    
                                <button id="btn-reset-detail" class="btn btn-danger" style="width:100px;"><i class="fa fa-undo"></i>&nbsp;Clear</button>
                            </div>            
                        </div>
                    </div>                    
                </div>
                <div class="panel-body">
                    <div class="row" style="margin-top:-10px;">
                        <div class="col-md-12 table-responsive">
                            <table class="table table-bordered table-condensed table-hover" cellspacing="0" width="100%" id="mainTable">
                                <thead>
                                    <tr>
                                        <th style='vertical-align:middle;text-align:center;'>#</th>
                                        <th style='vertical-align:middle;text-align:left;'>Product</th>
                                        <th style='vertical-align:middle;text-align:left;'>Qty</th>
                                        <th style='vertical-align:middle;text-align:left;'>Price</th>
                                        <th style='vertical-align:middle;text-align:left;'>Subtotal</th>
                                        <th style='vertical-align:middle;text-align:left;'>Action</th>
                                    </tr>
                                </thead>
                                <tbody>                                                
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <button id="btn-confirm" class="btn btn-success" style="width:100px;">Confirm</button>
                    <button id="btn-cancel" class="btn btn-danger" style="width:100px;">Cancel</button>
                </div>                
            </div>
        </form>
    </div>
</div>
