<?php $__env->startSection('main'); ?>
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-semibold text-gray-800 mb-6">Reports Dashboard</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
        <a href="<?php echo e(route('reports.sales_by_month')); ?>" class="bg-white shadow rounded-lg p-6 hover:bg-gray-100 transition duration-300 ease-in-out">
            <h2 class="text-xl font-medium text-gray-700">Sales by Month</h2>
            <p class="text-gray-500 mt-2">View monthly sales report</p>
        </a>
        <a href="<?php echo e(route('reports.occupancy_rate')); ?>" class="bg-white shadow rounded-lg p-6 hover:bg-gray-100 transition duration-300 ease-in-out">
            <h2 class="text-xl font-medium text-gray-700">Occupancy Rate</h2>
            <p class="text-gray-500 mt-2">View theater occupancy rate</p>
        </a>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-2xl font-medium text-gray-700 mb-4">Export Reports</h2>
        <form action="<?php echo e(route('reports.export')); ?>" method="POST" class="flex items-center space-x-4">
            <?php echo csrf_field(); ?>
            <label for="report_type" class="text-gray-600">Export Type:</label>
            <select name="type" id="report_type" class="bg-gray-100 border border-gray-300 text-gray-700 rounded-lg py-2 px-4">
                <option value="sales_by_month">Sales by Month</option>
                <option value="occupancy_rate">Occupancy Rate</option>
            </select>
            <button type="submit" class="bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-300 ease-in-out">Export</button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/reports/index.blade.php ENDPATH**/ ?>