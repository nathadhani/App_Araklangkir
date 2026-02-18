<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">            
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">                                
                            <div class="col-lg-12">
                                <label for="product_id" style="display:block">Product</label>
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
                    </div>                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="col-lg-12">
                                <label for="tr_date1" style="display:block">Period</label>
                                <div class="input-group" style="margin-left:-15px;">
                                    <div class="col-lg-6">
                                        <input style="width:100px;" type="text" data-inputmask="'mask': '[99-9999]'" class="form-control dpM" placeholder="Periode" name="period" id="period" value="<?=date('m-Y')?>">
                                    </div>
                                    <div class="col-lg-4" style="margin-left:5px;">
                                        <button id="btn-submit" class="btn btn-default" style="width:50px;margin-left:10px;"><i class="fa fa-play"></i></button>
                                    </div>    
                                </div>        
                            </div>
                        </div>                            
                    </div>
                </div>
                <div class="row" style="height:410px;margin-top:10px;">
                    <table class="table table-bordered table-condensed table-striped table-hover table-responsive" width="100%" id="table-detail">
                        <thead>                            
                            <tr>
                                <th style="vertical-align:middle;text-align:center;">Date</th>                                
                                <th style="vertical-align:middle;text-align:center;">IN</th>
                                <th style="vertical-align:middle;text-align:center;">Out</th>
                                <th style="vertical-align:middle;text-align:center;">Ending</th>                                
                                <th style="vertical-align:middle;text-align:center;">Description</th>
                            </tr>
                        </thead>                            
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>