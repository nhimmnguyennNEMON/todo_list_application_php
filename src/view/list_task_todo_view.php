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
        <input type="search" class="form-control rounded p-4" placeholder="Add new task in Todo List"
               aria-label="Search" id="contentTask" aria-describedby="search-addon"/>
        <button type="button" class="btn btn-outline-primary" data-mdb-ripple-init id="addTask">
            <i class="fas fa-add"></i>
        </button>
    </div>

    <div class="row m-0" id="add_new_view_row">

        <div class="col-md-4 border-right sortable_left" id="list_inprogress">
            <p class="fw-bold text-center text-primary">Doing Tasks</p>
            <ul id="sortable" class="pl-0 menu_list js-sortable-group js-drop-target bg-light p-3">
                <?php foreach ($taskListsInDoing as $task) { ?>
                    <div id="itemTask" data-value="<?php echo $task->getId()?>" class="p-3 alert badge badge-primary rounded-pill d-inline fade show alert_box course d-block" optiondata="Contact">
                        <span>
                            <?php echo $task->getTitle()?>
                        </span>
                    </div>
                <?php } ?>
            </ul>
        </div>

        <div class="col-md-4 border-right sortable_left" id="list_done">
            <p class="fw-bold text-center text-success">Done</p>
            <ul id="sortable2" class="pl-0 menu_list js-sortable-group js-drop-target bg-light p-3">
                <?php foreach ($taskListsDone as $task) { ?>
                    <div id="itemTask" data-value="<?php echo $task->getId()?>" class="p-3 alert badge badge-success rounded-pill d-inline fade show alert_box course d-block" optiondata="Contact">
                        <psan>
                            <?php echo $task->getTitle()?>
                        </psan>
                    </div>
                <?php } ?>
            </ul>
        </div>

        <div class="col-md-4 border-right sortable_left" id="list_close">
            <p class="fw-bold text-center text-danger">Close</p>
            <ul id="sortable3" class="pl-0 menu_list js-sortable-group js-drop-target bg-light p-3">
                <?php foreach ($taskListsClose as $task) { ?>
                    <div id="itemTask" data-value="<?php echo $task->getId()?>" class="p-3 alert badge badge-danger rounded-pill d-inline fade show alert_box course d-block" optiondata="Contact">
                        <span>
                            <?php echo $task->getTitle()?>
                        </span>
                    </div>
                <?php } ?>
            </ul>
        </div>

    </div>
</div>

<script>
    $(document).ready(function() {
        function stopHandler(event, obj) {
            //var elem = obj.item[0];
            //var sampleSortableGroup = $(".js-sortable-group").first().clone().html("");

            var idCount = 1;
            $(".menu_list .alert_box").each(function () {
                $(this).attr("id", "m_" + idCount);
                idCount++;
            });
        }

        $(".js-sortable-parent").sortable().disableSelection();

        var sortableGroup = $(".js-sortable-group")
            .sortable({
                connectWith: ".js-drop-target",

                stop: stopHandler,
            })
            .disableSelection();

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

        $("#sortable").droppable({
            drop: function(event, ui) {
                const movedItemValue = ui.draggable.attr('data-value');
                const statusTask = 2;

                $.ajax({
                    type: 'POST',
                    url: '../controllers/list_task_todo_handler.php',
                    data: { action: 'changeStatus',
                        taskId: movedItemValue,
                        statusTask: statusTask
                    },
                    success: function(response) {
                        location.reload();
                    }
                });
                console.log(`Thẻ có giá trị '${movedItemValue}' đã được di chuyển đến danh sách -- Doing`);
            }
        });

        $("#sortable2").droppable({
            drop: function(event, ui) {
                const movedItemValue = ui.draggable.attr('data-value');
                const statusTask = 1;

                $.ajax({
                    type: 'POST',
                    url: '../controllers/list_task_todo_handler.php',
                    data: { action: 'changeStatus',
                        taskId: movedItemValue,
                        statusTask: statusTask
                    },
                    success: function(response) {
                        location.reload();
                    }
                });
                console.log(`Thẻ có giá trị '${movedItemValue}' đã được di chuyển đến danh sách -- Done`);
            }
        });

        $("#sortable3").droppable({
            drop: function(event, ui) {
                const movedItemValue = ui.draggable.attr('data-value');
                const statusTask = 0;

                $.ajax({
                    type: 'POST',
                    url: '../controllers/list_task_todo_handler.php',
                    data: { action: 'changeStatus',
                        taskId: movedItemValue,
                        statusTask: statusTask
                    },
                    success: function(response) {
                        location.reload();
                    }
                });
                console.log(`Thẻ có giá trị '${movedItemValue}' đã được di chuyển đến danh sách -- Close`);
            }
        });

    });
</script>

<?php include_once '../templates/footer.php'; ?>
