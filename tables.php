<?php
require_once('ajax/database.php');
session_start();

// Initialize the database connection
$con = new database();
$html = ''; // Initialize empty variable for user table content

try {
    $connection = $con->opencon();

    // Check for connection error
    if (!$connection) {
        echo json_encode(['error' => 'Database connection failed.']);
        exit;
    }

    // Define the number of records per page
    $recordsPerPage = 10;

    // Get the current page number from the request, default to 1 if not set
    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($currentPage - 1) * $recordsPerPage;

    // Get the total number of records
    $totalQuery = $connection->prepare("SELECT COUNT(*) AS total FROM users");
    $totalQuery->execute();
    $totalRecords = $totalQuery->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalRecords / $recordsPerPage);

    // Fetch users for the current page
    $query = $connection->prepare("SELECT users.user_id, users.user_firstname, users.user_lastname, users.user_birthday, users.user_sex, users.user_email , users.user_name, users.user_profile_picture, CONCAT(user_address.city, ', ', user_address.province) AS address FROM users INNER JOIN user_address ON users.user_id = user_address.user_id LIMIT :offset, :recordsPerPage");
    $query->bindParam(':offset', $offset, PDO::PARAM_INT);
    $query->bindParam(':recordsPerPage', $recordsPerPage, PDO::PARAM_INT);
    $query->execute();
    $users = $query->fetchAll(PDO::FETCH_ASSOC);

    foreach ($users as $user) {
        $html .= '<tr>';
        $html .= '<td>' . $user['user_id'] . '</td>';
        $html .= '<td>' . $user['user_lastname'] . '</td>';
        $html .= '<td>' . $user['user_firstname'] . '</td>';
        $html .= '<td>' . $user['user_sex'] . '</td>';
        $html .= '<td>' . $user['user_email'] . '</td>';
        $html .= '<td>'; // Action column
        $html .= '<form action="update.php" method="post" style="display: inline;">';
        $html .= '<input type="hidden" name="id" value="' . $user['user_id'] . '">';
        $html .= '<button type="submit" class="btn btn-primary btn-sm">Edit</button>';
        $html .= '</form>';
        $html .= '</td>';
        $html .= '</tr>';
    }

    // Output the pagination HTML
    $paginationHtml = '';
    if ($totalPages > 1) {
        $paginationHtml .= '<nav><ul class="pagination justify-content-center">';
        if ($currentPage > 1) {
            $paginationHtml .= '<li class="page-item"><a class="page-link" href="?page=' . ($currentPage - 1) . '">Previous</a></li>';
        }
        for ($i = 1; $i <= $totalPages; $i++) {
            $active = $i == $currentPage ? ' active' : '';
            $paginationHtml .= '<li class="page-item' . $active . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
        }
        if ($currentPage < $totalPages) {
            $paginationHtml .= '<li class="page-item"><a class="page-link" href="?page=' . ($currentPage + 1) . '">Next</a></li>';
        }
        $paginationHtml .= '</ul></nav>';
    }

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Admin Panel - Tables</title>

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <!-- Sidebar -->
        <?php include('includes/navbar_side.php');?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include('includes/navbar.php');?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Tables</h1>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Data Tables</h6>
                        </div>
                        <!-- Search input and Print button -->
                        <div class="container mb-3">
                            <div class="row align-items-center">
                                <div class="col-md-9 col-sm-8 mb-2 mb-sm-0">
                                    <input type="text" id="search" class="form-control" placeholder="Search users...">
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Student ID</th>
                                            <th>Last Name</th>
                                            <th>First Name</th>
                                            <th>Sex</th>
                                            <th>Email</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Student ID</th>
                                            <th>Last Name</th>
                                            <th>First Name</th>
                                            <th>Sex</th>
                                            <th>Email</th>
                                            <th>Actions</th>
                                        </tr>
                                    </tfoot>
                                    <tbody id="userTableBody">
                                        <?php echo $html; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2020</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary <?php echo ($current_page == 'logout.php') ? 'active' : ''; ?>" href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>
    <!-- script na nagpapagana ng live search -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#search').on('keyup', function() {
            var search = $(this).val();
            $.ajax({
                url: 'live_search.php',
                method: 'POST',
                data: {search: search},
                success: function(response) {
                    $('#userTableBody').html(response);
                }
            });
        });
    });

    function printTable() {
        var printContents = document.getElementById('userTable').outerHTML;
        var originalContents = document.body.innerHTML;
        
        document.body.innerHTML = '<html><head><title>Print</title></head><body>' + printContents + '</body></html>';
        window.print();
        document.body.innerHTML = originalContents;
        location.reload(); // Reload to restore the original page content
    }
</script>

</body>

</html>