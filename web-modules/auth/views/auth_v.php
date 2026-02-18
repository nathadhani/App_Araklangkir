<!DOCTYPE html>
<html lang="en" class="body-full-height">
    <head>    
        <base href="<?php echo base_url(); ?>"/>
        <!-- META SECTION -->
        <title>App Login</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <link rel="icon" href="assets/img/logo.ico" type="image/x-icon" />
        <!-- END META SECTION -->

        <!-- CSS INCLUDE -->        
        <link id="theme" href="assets/themes/css/webapp.css" rel="stylesheet" type="text/css"/>
        <link href="assets/libs/alertify/css/alertify.core.css" rel="stylesheet" type="text/css"/>
        <link href="assets/libs/alertify/css/alertify.bootstrap.css" rel="stylesheet" type="text/css"/>
        <!-- EOF CSS INCLUDE -->    
    </head>
    <body oncontextmenu="return false">        
        <div class="login-container">
            <div class="layer">
                <div class="login-box animated">
                    <div class="login-body">                    
                        <div class="imgcontainer">
                            <img src="assets/img/login.png" alt="Avatar" class="avatar"><br><br>                  
                            <p style="color: #33414e; font-size: 14px; font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;margin-top: -15px;">
                                Arak Langkir Application
                            </p>
                        </div>                       
                        <form class="form-horizontal" id="frmLogin" autocomplete="off">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <input type="text" id="username" autofocus="" name="username" class="form-control" placeholder="Username"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <input type="password" id="password" name="password"  class="form-control" placeholder="Password"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">                            
                                    <button type="submit" class="btn btn-danger btn-block">Login</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script src="assets/libs/jquery/jquery.min.js" type="text/javascript"></script>
        <script src="assets/libs/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="assets/libs/alertify/js/alertify.min.js" type="text/javascript"></script>
        <script src="assets/js/modules/auth/auth.js" type="text/javascript"></script>        
    </body>
</html>






