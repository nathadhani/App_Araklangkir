<?php
    $auth = $this->session->userdata('auth');
?>
<?php echo $template['partials']['header']; ?>
<?php echo $template['partials']['sidebar']; ?>

<!-- PAGE CONTENT -->
<div class="page-content">
    <!-- START X-NAVIGATION VERTICAL -->
    <ul class="x-navigation x-navigation-horizontal x-navigation-panel">
         <li class="xn-icon-button">
            <a href="#" class="x-navigation-minimize"><span class="fa fa-dedent"></span></a>
        </li>
        <!-- END TOGGLE NAVIGATION -->

        <!-- SIGN OUT -->
        <li class="xn-icon-button pull-right">
            <a href="auth/logout" title='Sign Out'><span class="fa fa-sign-out"></span></a>                        
        </li> 
        <!-- END SIGN OUT -->        
    </ul>
    <!-- END X-NAVIGATION VERTICAL -->                      

    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href=".">Home</a></li>                    
        <li class=""><?php if (isset($template['title'])) echo $template['title']; ?></li>
        <font color="#000000" class="pull-right"><strong>Today : <?php print date('d F Y'); ?></strong></font>
    </ul>
    <!-- END BREADCRUMB -->            

    <!-- PAGE CONTENT WRAPPER -->
    <div class="page-content-wrap">
        <?php echo $template['body']; ?>
    </div>
    <!-- END PAGE CONTENT WRAPPER -->                
</div>            
<!-- END PAGE CONTENT -->

<?php echo $template['partials']['footer']; ?>