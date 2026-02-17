<div class="row">
    <div class="col-md-12">
        <form id="mainForm" class="form-horizontal" autocomplete="off">
            <div class="panel panel-default">
                <div class="panel-heading ui-draggable-handle">
                    <h3 class="panel-title"><strong id="ftitle">Add</strong> Data</h3>
                    <ul class="panel-controls">
                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                    </ul>
                </div>
                <div class="panel-body">                                                                        
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group row">
                                <label for="menu" class="control-label col-lg-4">Product Code</label>
                                <div class="col-lg-8">
                                    <input type="text" id="product_code" name="product_code" placeholder="Product Code..." data-validation="required" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-lg-4">Product Name</label>
                                <div class="col-lg-8">
                                    <input type="text" id="product_name" name="product_name" placeholder="Product Name..." class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="link" class="control-label col-lg-4">UOM</label>
                                <div class="col-lg-8">
                                    <input type="text" id="uom" name="uom" placeholder="Uom..." data-validation="required" class="form-control">
                                </div>
                            </div>                     
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group row">
                                <label for="link" class="control-label col-lg-4">Price</label>
                                <div class="col-lg-8">
                                    <input type="text" id="price" name="price" placeholder="Price..." data-validation="required" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-lg-4">Status</label>
                                <div>
                                    <div class="input-group col-lg-2">
                                        <span class="input-group-addon">
                                            <input type="checkbox" name="status" id="status" class="icheckbox_minimal-grey checked" checked/>
                                        </span>
                                        <span class="form-control">Active</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <button type="submit" class="btn btn-default"><i class="fa fa-save"></i></button>
                    <button type="reset" class="btn btn-default"><i class="fa fa-undo"></i></button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <table class="table table-bordered table-condensed table-striped table-hover table-responsive" width="100%" id="mainTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product Code</th>
                                <th>Product Name</th>
                                <th>UOM</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>