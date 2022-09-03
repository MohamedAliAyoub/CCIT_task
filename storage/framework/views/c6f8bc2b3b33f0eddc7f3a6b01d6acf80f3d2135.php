<?php $__env->startSection('title','Settings'); ?>

<?php $__env->startSection('content'); ?>

    <div class="card">
        <div class="card-body">
            <div class="form">
                <form action="<?php echo e(route('user.search')); ?>" method="get">
                    <?php echo csrf_field(); ?>
                    <div class="form-group">
                        <div class="">
                            <input type="search" class="form-control mb-3" name="searchword" value="<?php echo e($search_word ?? ''); ?>" placeholder="search with email or name now">
                            <div class="">
                                <button type="submit" class="btn btn-primary mb-3">Search</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered text-center" style="width:100%">
                    <thead class="font-weight-bold">
                    <tr>
                        <th>#</th>
                        <th>name</th>
                        <th>email</th>
                        <th>status</th>
                        <th>action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($key + 1); ?></td>
                            <td>   <?php echo e($user->name); ?>


                            </td>
                            <td>
                                <?php echo e($user->email); ?>

                            </td>


                            <td>
                                <span class="badge bg-<?php echo e($user->is_active == '1' ? 'success' : 'warning text-dark'); ?>"><?php echo e($user->is_active == 1 ? 'true' : 'false'); ?></span>
                            </td>

                            <td>
                                <a class="btn btn-<?php echo e($user->is_active == 0 ? 'warning' : 'success'); ?>" href="<?php echo e(url('admin/users/mange-block/'.$user->id)); ?>" onclick="return  confirm('are you sure?');" ><span><?php echo e($user->is_active == 'active' ? 'De active' : 'active'); ?></span></a>
                                <a class="btn btn-danger" href="<?php echo e(url('admin/users/delete/'.$user->id)); ?>" onclick="return  confirm('are you sure?');" ><span>Delete</span></a>



                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection("script"); ?>
    <script src="<?php echo e(asset('dashboard')); ?>/plugins/datatable/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo e(asset('dashboard')); ?>/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>
    <script>
        $(document).ready(function() {
            table.buttons().container()
                .appendTo('#example2_wrapper .col-md-6:eq(0)');
        });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboards.admins.layouts.admin-dash-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\ccit_task\ccit\resources\views/dashboards/admins/settings.blade.php ENDPATH**/ ?>