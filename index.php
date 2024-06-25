<?php include 'config/config.php'; ?>
<!DOCTYPE html>
<html>

<head>
    <title>Tasks App</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body>
    <div class="container p-5">
        <center>
            <div class="d-flex justify-content-between">
                <h2>List of Tasks</h2>
                <button class="btn btn-primary" data-toggle="modal" data-target="#addModal">Add Task</button>
            </div>
        </center>
        <br>
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>No</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
				$limit = 10; // Default Limit
				$request_page = isset($_GET['page'])?(int)$_GET['page'] : 1;
				$page = ($request_page>1) ? ($request_page * $limit) - $limit : 0;	
				
				$all_record = tasks_list();
				$total_all_records = mysqli_num_rows($all_record);
				$total_page = ceil($total_all_records / $limit); // Calculate Total Page 
 
				$tasks_list = tasks_list_paginate($page, $limit);
				$number = $page+1;

                foreach($tasks_list as $tasks):
					?>
                <tr>
                    <td><?= $number++ ?></td>
                    <td><?= $tasks['title'] ?></td>
                    <?php if($tasks['status'] == 'Pending') : ?>
                    <td align="center"><span class="badge badge-danger rounded px-2"><?= $tasks['status'] ?></span></td>
                    <?php elseif($tasks['status'] == 'In Progress') : ?>
                    <td align="center"><span class="badge badge-warning rounded px-2"><?= $tasks['status'] ?></span>
                    </td>
                    <?php elseif($tasks['status'] == 'Completed') : ?>
                    <td align="center"><span class="badge badge-success rounded px-2"><?= $tasks['status'] ?></span>
                    </td>
                    <?php else:?>
                    <td align="center">-
                    </td>
                    <?php endif;?>
                    <td><?= $tasks['description'] ?></td>
                    <td align="center"> <button class="btn btn-sm btn-warning" data-toggle="modal"
                            data-target="#updateStatusModal" data-task-id="<?= $tasks['id'] ?>"
                            data-task-status="<?= $tasks['status'] ?>">Update Status</button></td>
                </tr>
                <?php
				endforeach;
				?>
            </tbody>
        </table>
        <nav>
            <ul class="pagination justify-content-center">
                <?php 
				for($i=1;$i<=$total_page;$i++):
					?>
                <li class="page-item"><a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
                <?php
				endfor;
				?>
            </ul>
        </nav>
        <!-- Modal Create -->
        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="config/config.php" method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addModalLabel">Add Task</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="title">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title" name="title"
                                    placeholder="Title Task" required>
                            </div>
                            <div class="form-group">
                                <label for="status">Status <span class="text-danger">*</span></label>
                                <select class="form-control" id="status" name="status" required>
                                    <option disabled hidden selected>Choose Status</option>
                                    <option value="Pending">Pending</option>
                                    <option value="In Progress">In Progress</option>
                                    <option value="Completed">Completed</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" name="description" id="description" cols="10" rows="3"
                                    placeholder="Description"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Update Status -->
        <div class="modal fade" id="updateStatusModal" tabindex="-1" role="dialog" aria-labelledby="updateStatusModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="config/config.php" method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title" id="updateStatusModalLabel">Update Status Task</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="task_id" name="task_id">
                            <div class="form-group">
                                <label for="status">Status <span class="text-danger">*</span></label>
                                <select class="form-control" id="task_status" name="task_status" required>
                                    <option value="Pending">Pending</option>
                                    <option value="In Progress">In Progress</option>
                                    <option value="Completed">Completed</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
    <script>
        $('#updateStatusModal').on('show.bs.modal', function(event) {

            // Getting Data of Record by Data Button
            let button = $(event.relatedTarget);
            let task_id = button.data('task-id');
            let task_status = button.data('task-status');

            // Update Value in Form
            $('#task_id').val(task_id);
            $('#task_status').val(task_status).change();
        });
    </script>
</body>

</html>
