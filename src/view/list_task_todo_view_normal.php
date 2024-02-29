<?php
include_once '../templates/header.php';
include_once '../controllers/list_task_todo_handler.php';

if (!isset($_SESSION['username'])) {
    // User is not logged in, redirect to login page
    header("Location: ../view/signin_view.php");
    exit;
}

$taskLists = getListTask();
$taskListsClose = getListTaskForStatus(0);
$taskListsDone = getListTaskForStatus(1);
$taskListsInDoing = getListTaskForStatus(2);


?>

<!--Code HTML-->
<div class="container p-5">

    <div class="alert alert-success alert-dismissible fade show" role="alert" id="messageSuccess" style="display: none">
        You add new task successfully, reload list after 3s!. Status task is <strong>In Progress</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <div class="input-group p-2 mb-5">
        <input type="search" class="form-control rounded p-4" placeholder="Add new task in Todo List" aria-label="Search" id="contentTask" aria-describedby="search-addon" />
        <button type="button" class="btn btn-outline-primary" data-mdb-ripple-init id="addTask">
            <i class="fas fa-add"></i>
        </button>
    </div>

    <table class="table align-middle mb-0 bg-white">
        <thead class="bg-light">
        <tr>
            <th scope="col">
                <div class="form-check">
                    <input class="form-check-input fw-bolder" type="checkbox" value="" id="checkBoxAll" />
                </div>
            </th>
            <th>Title</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($taskListsInDoing as $task) { ?>
            <tr>
                <th scope="col">
                    <div class="form-check">
                        <input class="form-check-input fw-bolder" type="checkbox" value="<?php echo $task->getId()?>" id="flexCheckDefault" />
                    </div>
                </th>
                <td>
                    <span>
                        <?php echo $task->getTitle()?>
                    </span>
                </td>
                <td>
                    <?php if ($task->getCompleted() == 0) {?>
                        <span class="badge badge-danger rounded-pill d-inline">Close</span>
                    <?php } elseif ($task->getCompleted() == 1) {?>
                        <span class="badge badge-success rounded-pill d-inline">Done</span>
                    <?php } else {?>
                        <span class="badge badge-primary rounded-pill d-inline">Doing</span>
                    <?php }?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <div class="m-5 text-center">
        <button type="button" class="btn btn-danger" id="btnDeleted" data-mdb-ripple-init>Deleted</button>
        <button type="button" class="btn btn-primary" id="btnSave" data-mdb-ripple-init>Save</button>
    </div>
</div>

<script>
    $(document).ready(function() {

        $('#addTask').click(function() {

            var contentTask = $('#contentTask').val();
            var userid = <?php echo $_SESSION['user_id']; ?>

                $.ajax({
                    type: 'POST',
                    url: '../controllers/list_task_todo_handler.php',
                    data: { action: 'addTask',
                        contentTask: contentTask
                    },
                    success: function(response) {
                        $('#messageSuccess').show();
                        $('#contentTask').val('');
                        console.log(response);
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                    }
                });
        });

        $('#btnDeleted').click(function(){
            var listTasks = [];
            $('#flexCheckDefault:checked').each(function(){
                listTasks.push($(this).val());
            });
            console.log(listTasks);

            $.ajax({
                type: 'POST',
                url: '../controllers/list_task_todo_handler.php',
                data: { action: 'deleteTask',
                    listTasks: listTasks
                },
                success: function(response) {
                    try {

                        var parsedData = JSON.parse(response)
                        $.each(parsedData, function(index, item) {

                            if (item.field == 'success') {
                                $('#messageSuccess').show();
                                $('#messageSuccess').text(item.message);
                                $('#contentTask').val('');
                                console.log(response);
                                setTimeout(function() {
                                    location.reload();
                                }, 3000);
                            } else if (item.field == 'fail') {
                                $('#alertMessager').text(item.message);
                                $('#alertMessager').show();
                            }
                        });

                    } catch (error) {
                        console.error('Error parse JSON:', error.message);
                    }
                }
            });
        });

        $('#btnSave').click(function(){
            var listTasks = [];
            $('#flexCheckDefault:checked').each(function(){
                listTasks.push($(this).val());
            });
            console.log(listTasks);

            $.ajax({
                type: 'POST',
                url: '../controllers/list_task_todo_handler.php',
                data: { action: 'saveStatusTask',
                    listTasks: listTasks
                },
                success: function(response) {
                    try {

                        var parsedData = JSON.parse(response)
                        $.each(parsedData, function(index, item) {

                            if (item.field == 'success') {
                                $('#messageSuccess').show();
                                $('#messageSuccess').text(item.message);
                                $('#contentTask').val('');
                                console.log(response);
                                setTimeout(function() {
                                    location.reload();
                                }, 3000);
                            } else if (item.field == 'fail') {
                                $('#alertMessager').text(item.message);
                                $('#alertMessager').show();
                            }
                        });

                    } catch (error) {
                        console.error('Error parse JSON:', error.message);
                    }
                }
            });
        });

        $('#checkBoxAll').click(function(){
            // Lấy trạng thái checked của checkbox có ID 'flexCheckDefault'
            var isChecked = $(this).prop('checked');

            // Thiết lập trạng thái checked cho tất cả các checkbox có ID 'flexCheckDefault'
            $('input[type="checkbox"][id="flexCheckDefault"]').prop('checked', isChecked);
        });

    });
</script>

<?php include_once '../templates/footer.php'; ?>
