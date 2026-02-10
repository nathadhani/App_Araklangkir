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
        <div class="panel panel-default">
            <div class="panel-heading ui-draggable-handle">                                
                <div class="panel-title-box">
                    <h3>List Transaction</h3>
                </div>
                <ul class="panel-controls">                    
                </ul>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <div class="col-lg-12">
                                <label for="tr_date1" style="display:block">Period</label>
                                <div class="input-group" style="margin-left:-15px;">
                                    <div class="col-lg-6">
                                        <input type="text" id="tr_date" name="tr_date" placeholder="Tanggal..." class="form-control dp" data-date-format="DD MMMM YYYY" style="width:120px;" value="<?=date('d-m-Y');?>">                                        
                                    </div>
                                    <div class="col-lg-2" style="margin-left:-15px;">
                                        <button id="btn-submit" class="btn btn-success" style="width:120px;margin-left:10px;">Submit</button>
                                    </div>    
                                </div>        
                            </div>
                        </div>                            
                    </div>
                </div>                            
                
                <div class="row table-responsive" style="margin-top:10px;">
                    <table class="table table-bordered table-condensed table-hover table-striped dataTable" width="100%" id="mainTable">
                        <thead>
                            <tr>
                                <th style="vertical-align:middle;text-align:center;">#</th>    
                                <th style="vertical-align:middle;text-align:center;">Trx</th>
                                <th style="vertical-align:middle;text-align:center;">Number</th>
                                <th style="vertical-align:middle;text-align:center;">Rupiah</th>
                                <th style="vertical-align:middle;text-align:center;">Status</th>
                                <th style="vertical-align:middle;text-align:center;">Created</th>
                                <th style="vertical-align:middle;text-align:center;">Created by</th>
                                <th style="vertical-align:middle;text-align:center;">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>        
                </div>                
            </div>
        </div>
    </div>
</div>