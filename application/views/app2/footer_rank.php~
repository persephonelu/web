    </div>
    <!-- /#wrapper -->
    <!-- jQuery -->
    <script src="http://cdn.appbk.com/js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="http://cdn.appbk.com/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="http://cdn.appbk.com/js/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="http://cdn.appbk.com/js/sb-admin-2.js"></script>
    
    <!-- 处理导航北京变化 -->
    <script type="text/javascript">
        //一级菜单
        <?php if ( isset($_REQUEST["t"]) ) { ?>
            nav_select = '<?php echo "#" . $_REQUEST["t"] ?>';
            $(nav_select).addClass("btn btn-primary");
        <?php } else { ?>
            nav_select = '#topfreeapplications';
            $(nav_select).addClass("btn btn-primary");
        <?php } ?>

        //二级菜单
        <?php if ( isset($_REQUEST["c"]) ) { ?>
            nav_select = '<?php echo "#" . $_REQUEST["c"] ?>';
            $(nav_select).addClass("btn btn-info");
        <?php } else { ?>
            nav_select = '#应用';
            $(nav_select).addClass("btn btn-info");
        <?php } ?>  
        
        //三级菜单
       <?php if ( isset($_REQUEST["gc"]) ) { ?>
            nav_select = '<?php echo "#" . $_REQUEST["gc"] ?>';
            $(nav_select).addClass("btn btn-success");
        <?php } else { ?>
            nav_select = '#应用';
            $(nav_select).addClass("btn btn-success");
        <?php } ?>   
    </script>

</body>
</html>
