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
                    <h2 class="card-title text-center mb-4">Deleted Account</h2>

                    <!--alert báo lỗi khi điền thông tin sai-->
                    <div class="alert alert-danger" role="alert" id="alertMessager" style="display: none;">
                        ...
                    </div>

                    <!--alert báo thành công-->
                    <div class="alert alert-success" role="alert" id="alertMessagerSuccess" style="display: none;">
                        ...
                    </div>

                    <p class="text-center mb-5 fw-bolder text-danger">Have you confirmed deleting your account? <br>
                        The account will be permanently deleted from the system.</p>

                    <form action="" method="" id="myForm">
                        <div class="text-center justify-content-between">
                            <button type="reset" class="btn btn-outline-secondary mx-2" id="cancel">Cancel</button>
                            <button type="submit" id="submitButton" class="btn btn-danger fw-bolder mx-2">Deleted</button>
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

            var userid = <?php echo $_SESSION['user_id']; ?>

                $.ajax({
                    url: '../controllers/home_controller.php',
                    method: 'POST',
                    data: {action: 'deletedAccount'},
                    success: function(response) {
                        try {
                            var parsedData = JSON.parse(response)
                            $.each(parsedData, function(index, item) {

                                if (item.field == 'success') {
                                    $('#alertMessagerSuccess').text(item.message);
                                    $('#alertMessagerSuccess').show();
                                    setTimeout(function() {
                                        window.location.href = '../view/signin_view.php';
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

        $('#cancel').click(function(event) {
            event.preventDefault();
            window.location.href = '../view/list_task_todo_view.php';
        });
    });
</script>

<?php include_once '../templates/footer.php'; ?>
