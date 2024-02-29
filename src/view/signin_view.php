<?php
include_once '../templates/header.php';

if (isset($_SESSION['username'])) {
    // User is not logged in, redirect to login page
    header("Location: ../view/list_task_todo_view.php");
    exit;
}

?>

<!--Code HTML-->
<div class="container">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="card border-white shadow p-3 mb-5 bg-body-tertiary rounded">
                <div class="card-body">
                    <h2 class="card-title text-center">Sign In</h2>

                    <div class="alert alert-danger" role="alert" id="alertMessager" style="display: none;">
                        Please check the form information again!
                    </div>

                    <form action="" method="" id="myForm">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Username<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="username" id="username" required>
                            <span class="text-danger" id="messError_username" style="display: none">...</span>
                        </div>
                        <div class="mb-4">
                            <label for="exampleInputPassword1" class="form-label">Password<span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="password" id="password" required>
                            <span class="text-danger" id="messError_password" style="display: none">...</span>
                        </div>
                        <!--Captcha item-->
                        <div class="row g-1 mb-3 justify-content-between">
                            <div class="col-auto">
                                <input type="text" readonly class="form-control-plaintext fw-bolder text-danger" id="captchaMain" name="captchaMain">
                            </div>
                            <div class="col-auto">
                                <input type="password" class="form-control" name="captcha" id="captcha" placeholder="Captcha" required>
                                <span class="text-danger" id="messError_captcha" style="display: none">...</span>
                            </div>
                            <div class="col-auto">
                                <button type="button" name="refresh" id="refresh" class="btn btn-outline-secondary">Generate</button>
                            </div>
                        </div>
                        <p class="text-end">Don't have an account?
                            <a href="../view/signup_view.php" class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover fw-bolder">Sign Up</a>
                        </p>
                        <div class="text-center justify-bet">
                            <button type="reset" class="btn btn-outline-secondary mx-2">Reset</button>
                            <button type="submit" id="submitButton" class="btn btn-primary fw-bolder mx-2">Sign In</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#myForm').submit(function(event) {
            event.preventDefault();

            $('#alertMessager').hide();
            $('#messError_password').hide();
            $('#messError_username').hide();
            $('#username').removeClass('border border-danger');
            $('#password').removeClass('border border-danger');
            $('#captcha').removeClass('border border-danger');

            var username = $('#username').val();
            var password = $('#password').val();
            var captcha = $('#captcha').val();

            $.ajax({
                type: 'POST',
                url: '../controllers/signin_handler.php',
                data: { action: 'login',
                    username: username,
                    password: password,
                    captcha: captcha},
                success: function(response) {
                    try {

                        $('#username').val(this.data.username);
                        $('#password').val(this.data.password);
                        $('#captcha').val(this.data.captcha);

                        console.log(response);

                        var parsedData = JSON.parse(response)
                        $.each(parsedData, function(index, item) {

                            if (item.field == 'success') {
                                window.location.href = '../view/list_task_todo_view.php';
                            } else if (item.field == 'fail') {
                                $('#alertMessager').text(item.message);
                                $('#alertMessager').show();
                            } else {
                                $('#' + item.field).addClass('border border-danger');
                                $('#messError_' + item.field).text(item.message);
                                $('#messError_' + item.field).show();
                                $('#alertMessager').show();
                            }
                        });
                    } catch (error) {
                        console.error('Error parse JSON:', error.message);
                    }
                },
                error: function() {
                    $('#alertMessager').text('There was an error processing your request!');
                    $('#alertMessager').show();
                }
            });

        });

        $('#refresh').click(function() {
            $.ajax({
                type: 'POST',
                url: '../utils/generate_Captcha.php',
                data: { action: 'generate' },
                success: function(response) {
                    console.log(response);
                    $('#captchaMain').val(response);
                }
            });
        });
    });
</script>

<?php include_once '../templates/footer.php'; ?>
