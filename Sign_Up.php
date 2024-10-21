<!doctype html>
<html lang="en">
    <head>
        <title>Title</title>
        <style>
            .form-control{
                width:100%;
            }
        </style>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />

        <!-- Bootstrap CSS v5.2.1 -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
       

    </head>

    <body>
        <header>
            <!-- place navbar here -->
        </header>
        <main>
            <section class="h-100 h-custom" style="background-color: #8fc4b7;">
                <div class="container py-5 h-100">
                    <div class="row d-flex justify-content-center align-items-center h-100">
                        <div class="col-lg-8 col-xl-6">
                            <div class="card rounded-3">
                                <img src="img/cover.jpg"
                                class="w-100" style="border-top-left-radius: .3rem; border-top-right-radius: .3rem;" alt="cover.jpg">
                                <div class="card-body p-4 p-md-5">
                                    <h3 class="mb-4 pb-2 pb-md-0 mb-md-5 px-md-2">Registration Info</h3>
                                    <form id="reg-form" roll="form" class="px-md-2">
                                        <input type="hidden" id="idx" name="idx" class="form-control">
                                        <div data-mdb-input-init class="form-outline mb-4">
                                            <input type="text" id="fname" name="fname" class="form-control" />
                                            <label for="fname">Full Name</label>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-4">
                                                <div data-mdb-input-init class="form-outline datepicker">
                                                    <input type="date" class="form-control" id="sel_date" name="sel_date" />
                                                    <label for="sel_date" class="form-label">Select a date</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-4">
                                                <select class="form-control" id="gender" name="gender">
                                                    <option value="1" disabled>Gender</option>
                                                    <option value="2">Female</option>
                                                    <option value="3">Male</option>
                                                    <option value="4">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-2">
                                            <select class="form-control" id="sec" name="sec">
                                                <option value="1" disabled>Class</option>
                                                <option value="COM221">COM221</option>
                                                <option value="COM231">COM231</option>
                                                <option value="COM241">COM241</option>
                                            </select>
                                        </div>
                                        
                                        <div class="row mb-4 pb-2 pb-md-0 mb-md-5">
                                            <div class="col-md-6">
                                                <div data-mdb-input-init class="form-outline mb-4">
                                                    <input type="password" id="pword" name="pword" class="form-control" />
                                                    <label for="pword">PASSWORD</label>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" id="save-it"class="btn btn-success btn-lg mb-1">Save</button>
                                        <a class="btn btn-primary btn-lg mb-1" href="login.php" role="button">Back to Login</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <footer>
            <!-- place footer here -->
        </footer>
        <!-- Bootstrap JavaScript Libraries -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
        <script
            src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"
        ></script>
        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
            crossorigin="anonymous"
        ></script>

        <script>
        $(document).ready(function(){
         
                
                $("#reg-form").on("submit", function(e) {
                    e.preventDefault();
			        toastr.info('Saving...');
                    $.ajax({
                        type: "POST",
                        url: "ajax/saveuser.php",
                        data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
                        contentType: false, // The content type used when sending data to the server.
                        cache: false, // To unable request pages to be cached
                        processData: false, // To send DOMDocument or non processed data file it is set to false
                        success: function(data) {
                            if (data == 1 || parseInt(data) == 1) {
                                toastr.success('Adjustment saved successfully!');
                                $(".form-control").val("");
                            } else {
                                    toastr.warning('No changes has been made');
                                }
                            },
                                error: function(data) {
                                    toastr.danger('Please Contact Administrator');
                                }
                            });
                        });
                    });
        </script>
    </body>
</html>
