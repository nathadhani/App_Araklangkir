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
                                <label for="username" class="control-label col-lg-4">Username</label>
                                <div class="col-lg-8">
                                    <input type="text" autofocus="" id="username" name="username" placeholder="Username..." data-validation="required" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password" class="control-label col-lg-4">Password</label>
                                <div class="col-lg-8">
                                    <input type="password" id="password" name="password" placeholder="Password..." data-validation="required" class="form-control">
                                </div>
                            </div>                   
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group row">
                                <label for="fullname" class="control-label col-lg-4">Nama Lengkap</label>
                                <div class="col-lg-8">
                                    <input type="text" id="fullname" name="fullname" placeholder="Nama Lengkap..." data-validation="required" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="control-label col-lg-4">Status</label>
                                <div class="col-lg-8">
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
                    <button type="submit" class="btn btn-default">Submit</button>
                    <button type="reset" class="btn btn-default">Reset</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div id="mainTable" class="box-body">
                    <div style="padding: 0 20px 10px 20px" class="row">
                        <button class="btn btn-default toggle-selected" title="Toggle Selected" data-toggle="tooltip" data-placement="bottom"><i class="fa  fa-align-justify"></i></button> 
                        <button class="btn btn-default delete-selected pull-right" title="Delete Rows" data-toggle="tooltip" data-placement="bottom"><i class="fa  fa-trash"></i></button> 
                    </div>
                    <table class="table table-bordered table-condensed table-hover table-striped dataTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Lengkap</th>
                                <th>Username</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <thead id="searchid">
                            <tr>
                                <td><button class="clrs btn btn-info btn-sm btn-line">Clear</button></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>