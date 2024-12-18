<?php $__env->startSection('header-title', 'Movie Details'); ?>

<?php $__env->startSection('main'); ?>
<div style="padding-left: 50px; padding-right: 50px;">
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8 max-w-2xl mx-auto">
        <div class="flex items-center justify-center mb-4">
            <img src="<?php echo e($movie['poster_path'] ? 'https://image.tmdb.org/t/p/w500'.$movie['poster_path'] : asset('storage/posters/_no_poster_1.png')); ?>"
                 alt="<?php echo e($movie['title']); ?>" 
                 class="rounded-lg shadow-md w-48 md:w-64" 
                 style="border: 4px solid #e2e8f0;">
        </div>
        <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-800 mb-2"><?php echo e($movie['title']); ?></h2>
            <p class="text-gray-500 italic mb-4"><?php echo e($movie['overview'] ?? 'No synopsis available.'); ?></p>
            <p class="text-gray-600 mb-2">
                <strong>Genre:</strong> <?php echo e($movie['genre_names'] ?? 'Unknown genre'); ?>

            </p>
            <p class="text-gray-600 mb-2">
                <strong>Rating:</strong> <?php echo e($movie['vote_average'] ? number_format($movie['vote_average'], 1) . '/10' : 'N/A'); ?>

            </p>
            <p class="text-gray-600">
                <strong>Release Year:</strong> <?php echo e($movie['release_date'] ? \Carbon\Carbon::parse($movie['release_date'])->year : 'N/A'); ?>

            </p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6 max-w-3xl mx-auto">
        <h3 class="text-2xl font-semibold text-gray-800 mb-4 text-center">Reviews</h3>
        
        <?php $__empty_1 = true; $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="mb-6 p-4 border border-gray-200 rounded-lg shadow-sm bg-gray-50">
                <p class="italic text-gray-700 mb-2"><?php echo e($review['content']); ?></p>
                <p class="text-sm text-gray-500 text-right">- <?php echo e($review['author']); ?></p>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="text-center text-gray-600">No reviews available for this movie.</p>
        <?php endif; ?>

        <?php if($reviews->isNotEmpty() || !$reviews->onFirstPage()): ?>
            <div class="mt-6">
                <div class="flex justify-center">
                    <div class="flex items-center">
                        <?php if($reviews->onFirstPage()): ?>
                            <button class="px-4 py-2 bg-gray-200 text-gray-600 rounded-md cursor-not-allowed" disabled>
                                &laquo; Previous
                            </button>
                        <?php else: ?>
                            <a href="<?php echo e($reviews->previousPageUrl()); ?>" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-400 transition">
                                &laquo; Previous
                            </a>
                        <?php endif; ?>

                        <span class="mx-2 text-sm text-gray-600">
                            Page <?php echo e($reviews->currentPage()); ?> of <?php echo e($reviews->lastPage()); ?>

                        </span>

                        <?php if($reviews->isNotEmpty() && $reviews->hasMorePages()): ?>
                            <a href="<?php echo e($reviews->nextPageUrl()); ?>" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-400 transition">
                                Next &raquo;
                            </a>
                        <?php else: ?>
                            <button class="px-4 py-2 bg-gray-200 text-gray-600 rounded-md cursor-not-allowed" disabled>
                                Next &raquo;
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/movies/show.blade.php ENDPATH**/ ?>