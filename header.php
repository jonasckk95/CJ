<!DOCTYPE html>

<html>
<head>
    <meta charset="utf-8">
    <title>FarmApp (admin)</title>
    <!-- Responsive Meta Tag -->
    <meta name="viewport" content="width=device-width">

    <!-- jQuery 3 -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Theme style -->
    <script src="dist/js/adminlte.min.js"></script>
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="dist/css/skins/skin-yellow.min.css">

    <!-- sweetalert -->
    <script src="bower_components/sweetalert/sweetalert.js"></script>

    <!-- Ionicons -->
    <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
    <script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="Chart.js-2.8.0/dist/Chart.min.js"></script>

    <!-- Select2 -->
    <link rel="stylesheet" href="bower_components/select2/dist/css/select2.min.css">
    <script src="bower_components/select2/dist/js/select2.full.min.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">

    <!-- Google Font -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    <!-- datepicker  -->
    <link rel="stylesheet" href="bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    <script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>

    <!-- iCheck  -->
    <link rel="stylesheet" href="plugins/iCheck/all.css">
    <script src="plugins/iCheck/icheck.min.js"></script>

</head>

<body class="hold-transition skin-yellow sidebar-mini">
<div class="wrapper">


  <header class="main-header">


    <a href="dashboard.php" class="logo">

      <span class="logo-lg"><b>Farm</b>App</span>
    </a>


    <nav class="navbar navbar-static-top" role="navigation">

      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

          <li class="dropdown user user-menu">

            <a href="#" class="dropdown-toggle" data-toggle="dropdown">

                <span class="hidden-xs"><b><?php echo $_SESSION['username'];?></b></span>
            </a>
            <ul class="dropdown-menu">

              <li class="user-header">
                <p>
                  <?php echo $_SESSION['username'];?><br>
                    <?php echo $_SESSION['role'];?>
                </p>
              </li>

              <li class="user-body">
                <div class="pull-left">
                  <a href="changepassword.php" class="btn btn-default btn-flat">Change password</a>
                </div>
                <div class="pull-right">
                  <a href="logout.php" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>


        </ul>
      </div>
    </nav>
  </header>

  <aside class="main-sidebar">

    <section class="sidebar">

      <ul class="sidebar-menu" data-widget="tree">


        <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>

         <li><a href="category.php"><i class="fa fa-list-alt"></i> <span>Manage Category</span></a></li>

          <li><a href="addproduct.php"><i class="fa fa-product-hunt"></i> <span>Add Product</span></a></li>

           <li><a href="productlist.php"><i class="fa fa-th-list"></i> <span>Manage Product</span></a></li>

            <li><a href="terminal.php"><i class="fa fa-first-order"></i> <span>Sales Terminal</span></a></li>

             <li><a href="orderlist.php"><i class="fa fa-list-ul"></i> <span>Manage Order</span></a></li>

                <li class="treeview">
          <a href="#">
            <i class="fa fa-table"></i> <span>Sales Report</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="tablereport.php"><i class="fa fa-circle-o"></i>Table Report</a></li>
            <li><a href="graphreport.php"><i class="fa fa-circle-o"></i>Grpah Report</a></li>
          </ul>
        </li>

            <li><a href="manageuser.php"><i class="fa fa-registered"></i> <span>Manage Accounts</span></a></li>

      </ul>

    </section>

  </aside>
