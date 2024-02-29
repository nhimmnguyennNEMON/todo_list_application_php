<?php
include_once '../templates/header.php';

if (!isset($_SESSION['username'])) {
    // User is not logged in, redirect to login page
    header("Location: ../view/signin_view.php");
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
                    <h2 class="card-title text-center">Change Password</h2>

                    <!--alert báo lỗi khi điền thông tin sai-->
                    <div class="alert alert-danger" role="alert" id="alertMessager" style="display: none;">
                        The information in the form is incorrect!
                    </div>

                    <!--alert báo thành công-->
                    <div class="alert alert-success" role="alert" id="alertMessagerSuccess" style="display: none;">
                        Please check the form information again!
                    </div>

                    <form action="" method="" id="myForm">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Old Password<span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="oPass" id="password" required maxlength="25" minlength="6"
                                   value="<?php echo  $_SESSION['old_password'] ?? ''?>">
                            <span class="text-danger" id="messError_password" style="display: none">...</span>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">New Password<span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="nPass" id="newPassword" required maxlength="25" minlength="6"
                                   value="<?php echo  $_SESSION['new_Password'] ?? ''?>">
                            <span class="text-danger" id="messError_newPassword" style="display: none">The new password must not be the same as the old password!</span>
                        </div>
                        <div class="mb-4">
                            <label for="exampleInputPassword1" class="form-label">Confirm Password<span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" required maxlength="25" minlength="6"
                                   value="<?php echo  $_SESSION['confirm_Password'] ?? ''?>">
                            <span class="text-danger" id="messError_confirmPassword" style="display: none">Password confirmation does not match!</span>
                        </div>
                        <!--Captcha item-->
                        <div class="row g-1 mb-3 justify-content-between">
                            <div class="col-auto">
                                <input type="text" readonly class="form-control-plaintext fw-bolder text-danger" id="captchaMain" name="captcha">
                            </div>
                            <div class="col-auto">
                                <input type="password" class="form-control" name="userInput" id="captcha" placeholder="Captcha" required maxlength="6">
                                <span class="text-danger" id="messError_captcha" style="display: none"></span>
                            </div>
                            <div class="col-auto">
                                <button type="button" name="refresh" id="refresh" class="btn btn-outline-secondary">Generate</button>
                            </div>
                        </div>
                        <div class="text-center justify-content-between">
                            <button type="reset" class="btn btn-outline-secondary mx-2">Reset</button>
                            <button type="submit" id="submitButton" class="btn btn-primary fw-bolder mx-2">Change</button>
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

        // Check xem verify form không có alert lỗi thì mới cho submit
        $('#myForm').submit(function(event) {
            event.preventDefault();

            const password = $('#password').val();
            const newPassword = $('#newPassword').val();
            const confirmPassword = $('#confirmPassword').val();
            const captcha = $('#captcha').val();
            var userid = <?php echo $_SESSION['user_id']; ?>

            $.ajax({
                type: 'POST',
                url: '../controllers/change_password_handler.php',
                data: {
                    password: password,
                    newPassword: newPassword,
                    confirmPassword: confirmPassword,
                    captcha: captcha,
                    userid: userid
                },

                success: function(response) {

                    <?php
                    unset($_SESSION['old_password']);
                    unset($_SESSION['new_Password']);
                    unset($_SESSION['confirm_Password']);
                    ?>

                    try {

                        var parsedData = JSON.parse(response)
                        $.each(parsedData, function(index, item) {

                            if (item.field == 'success') {
                                $('#alertMessagerSuccess').text(item.message);
                                $('#alertMessagerSuccess').show();
                                setTimeout(function() {
                                    window.location.href = '../view/list_task_todo_view.php';
                                }, 3000);
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
