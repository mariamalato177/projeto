<?php $__env->startSection('header-title', 'List of Movies'); ?>

<?php $__env->startSection('main'); ?>

<header class="bg-white ">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <h2 class="font-semibold text-xl text-gray-800  leading-tight">
            <?php echo $__env->yieldContent('header-title'); ?>
        </h2>
        <br>
        <form action="<?php echo e(route('movies.index')); ?>" method="GET" class="flex flex-wrap items-center gap-4 md:gap-8">
            <label for="search" class="text-black">Search:</label>
            <div class="flex flex-col space-y-2">
                <input type="text" id="search" name="query" value="<?php echo e(request('query')); ?>" placeholder="Movie Title" class="bg-white text-black p-3 rounded shadow-sm min-w-[200px]">
            </div>
            <div class="flex flex-col space-y-2">
                <select name="genre" id="genre" class="bg-white text-black p-3 rounded shadow-sm min-w-[200px]">
                    <option value="">Select Genre</option>
                    <?php $__currentLoopData = $genres; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $genre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($genre['id']); ?>" <?php echo e(request('genre') == $genre['id'] ? 'selected' : ''); ?>>
                        <?php echo e($genre['name']); ?>

                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <button type="submit" class="bg-coral text-white px-6 py-3 rounded shadow-sm hover:bg-orange-500 min-w-[100px]">Search</button>
            </div>
            <div>
                <a href="<?php echo e(route('movies.index')); ?>" class="bg-gray-200 text-black px-6 py-3 rounded shadow-sm hover:bg-gray-300 min-w-[200px]">Reset</a>
            </div>
        </form>
    </div>
</header>
<div class="flex justify-center">
        <div class="my-4 p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg text-gray-900 w-full max-w-[90%] mx-auto">

    <?php if(session('error')): ?>
    <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
    <?php endif; ?>
    <div class="px-[50px]">
        <?php if(!empty($movies)): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-16 gap-y-12 mt-12">
            <?php $__currentLoopData = $movies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $movie): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-3">
                <div class="card mb-4">
                    <img
                        style="height: 354.99px"
                        class="rounded-lg shadow-md ease-in-out duration-300 hover:opacity-50 cursor-pointer"
                        src="<?php echo e($movie['poster_path'] ? 'https://image.tmdb.org/t/p/w500' . $movie['poster_path'] : asset('storage/posters/_no_poster_1.png')); ?>"
                        alt="<?php echo e($movie['title']); ?>"
                        data-movie="<?php echo e(json_encode($movie)); ?>"
                        onclick="openModal(event)">

                    <div class="text-center">
                        <h5 class="card-title mt-2"><?php echo e($movie['title']); ?></h5>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        </div>

        <div class="flex flex-col items-center mt-6">
            <div class="text-gray-700 mb-4">
                <?php if($movies->total() > 0): ?>
                Showing <?php echo e($movies->firstItem()); ?>–<?php echo e($movies->lastItem()); ?> of <?php echo e($movies->total()); ?> results
                <?php endif; ?>
            </div>

            <div class="flex justify-center items-center space-x-1 relative">
                
                <?php if($movies->onFirstPage()): ?>
                <span class="px-4 py-2 bg-white text-gray-400 border border-gray-300 rounded-l cursor-not-allowed">&laquo;</span>
                <?php else: ?>
                <a href="<?php echo e($movies->previousPageUrl()); ?>" class="px-4 py-2 bg-white text-gray-800 border border-gray-300 rounded-l hover:bg-gray-100">&laquo;</a>
                <?php endif; ?>

                
                <?php
                $currentPage = $movies->currentPage();
                $lastPage = $movies->lastPage();
                $start = max(1, $currentPage - 4); // Para dispositivos grandes, começa 4 páginas antes
                $end = min($lastPage, $currentPage + 4); // Vai até 4 páginas depois
                ?>

                
                <div class="hidden lg:flex">
                    <?php if($start > 1): ?>
                    <a href="<?php echo e($movies->url(1)); ?>" class="px-4 py-2 bg-white text-gray-800 border border-gray-300 hover:bg-gray-100">1</a>
                    <?php if($start > 2): ?>
                    <span class="px-4 py-2 bg-white text-gray-400">...</span>
                    <?php endif; ?>
                    <?php endif; ?>

                    <?php for($page = $start; $page <= $end; $page++): ?>
                        <?php if($page==$currentPage): ?>
                        <span class="px-4 py-2 bg-gray-800 text-white font-bold border border-gray-300"><?php echo e($page); ?></span>
                        <?php else: ?>
                        <a href="<?php echo e($movies->url($page)); ?>" class="px-4 py-2 bg-white text-gray-800 border border-gray-300 hover:bg-gray-100"><?php echo e($page); ?></a>
                        <?php endif; ?>
                        <?php endfor; ?>

                        <?php if($end < $lastPage): ?>
                            <?php if($end < $lastPage - 1): ?>
                            <span class="px-4 py-2 bg-white text-gray-400">...</span>
                            <?php endif; ?>
                            <a href="<?php echo e($movies->url($lastPage)); ?>" class="px-4 py-2 bg-white text-gray-800 border border-gray-300 hover:bg-gray-100"><?php echo e($lastPage); ?></a>
                            <?php endif; ?>
                </div>

                
                <div class="flex lg:hidden">
                    <a href="<?php echo e($movies->url($currentPage - 1)); ?>" class="px-4 py-2 bg-white text-gray-800 border border-gray-300 hover:bg-gray-100">...</a>
                    <span class="px-4 py-2 bg-gray-800 text-white font-bold border border-gray-300"><?php echo e($currentPage); ?></span>
                    <a href="<?php echo e($movies->url($currentPage + 1)); ?>" class="px-4 py-2 bg-white text-gray-800 border border-gray-300 hover:bg-gray-100">...</a>
                </div>

                
                <?php if($movies->hasMorePages()): ?>
                <a href="<?php echo e($movies->nextPageUrl()); ?>" class="px-4 py-2 bg-white text-gray-800 border border-gray-300 rounded-r hover:bg-gray-100">&raquo;</a>
                <?php else: ?>
                <span class="px-4 py-2 bg-white text-gray-400 border border-gray-300 rounded-r cursor-not-allowed">&raquo;</span>
                <?php endif; ?>
            </div>

            <?php else: ?>
            <div class="alert alert-warning">No movies found.</div>
            <?php endif; ?>
        </div>
    </div>

        <!--Pop up of the details -->
        <div id="modal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75 hidden transition-opacity duration-300">
            <div class="bg-gray-900 text-gray-100 rounded-lg shadow-lg max-w-4xl w-full p-6 relative flex flex-col md:flex-row items-start">
                <button id="close-modal" class="absolute top-3 right-3 text-gray-500 hover:text-gray-300 text-4xl p-2">&times;</button>

                <div class="w-full md:w-1/3">
                    <img id="modal-poster" class="rounded-lg shadow-md" src="" alt="Movie Poster">
                </div>

                <div class="flex-1 ml-0 md:ml-6 mt-4 md:mt-0">
                    <h2 id="modal-title" class="text-2xl font-semibold text-white mb-2"></h2>
                    <p id="modal-overview" class="text-gray-300 text-sm mb-4"></p>

                    <div class="mt-4 text-sm">
                        <p><span class="font-semibold">Genre:</span> <span id="modal-genre" class="text-gray-400"></span></p>
                        <p><span class="font-semibold">Release Year:</span> <span id="modal-year" class="text-gray-400"></span></p>
                        <p><span class="font-semibold">Rating:</span> <span id="modal-rating" class="text-gray-400">N/A</span></p>
                    </div>

                    <div class="mt-4">
                        <h3 class="text-lg font-semibold text-white">Review:</h3>
                        <p id="modal-review" class="text-gray-300 text-sm italic"></p>
                    </div>

                    <div class="flex justify-start">
                        <a class="text-blue-500 font-bold cursor-pointer" id="modal-more-reviews" href="">View More Reviews</a>
                    </div>
                    <div class="mt-4">
                        <a id="modal-trailer" href="" target="_blank" class="text-blue-500 hover:text-blue-300">Watch Trailer</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .modal {
            display: none;
        }

        .modal.open {
            display: flex;
        }
    </style>

    <script>
        const reviewCache = {};

        async function openModal(event) {

            event.preventDefault();

            const movie = JSON.parse(event.target.getAttribute('data-movie'));

            document.getElementById('modal-poster').src = movie.poster_path ? `https://image.tmdb.org/t/p/w500${movie.poster_path}` : '/storage/posters/_no_poster_1.png';
            document.getElementById('modal-title').textContent = movie.title;
            document.getElementById('modal-overview').textContent = movie.overview || "No synopsis available.";
            document.getElementById('modal-genre').textContent = movie.genre_names || "Unknown genre";
            document.getElementById('modal-year').textContent = movie.release_date ? new Date(movie.release_date).getFullYear() : "N/A";
            document.getElementById('modal-rating').textContent = movie.vote_average ? `${movie.vote_average.toFixed(1)}/10` : "N/A";

            const trailerLink = document.getElementById('modal-trailer');
            trailerLink.classList.add('hidden');

            const moreReviewsLink = document.getElementById('modal-more-reviews');
            moreReviewsLink.href = `/movies/${movie.id}`;

            try {
                const response = await fetch(`https://api.themoviedb.org/3/movie/${movie.id}/videos?api_key=00ba7a7ea04d04cfb14ee146d36ec4e6`);
                const data = await response.json();
                const trailer = data.results.find(video => video.type === 'Trailer' && video.site === 'YouTube');

                if (trailer) {
                    trailerLink.href = `https://www.youtube.com/watch?v=${trailer.key}`;
                    trailerLink.classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error fetching trailer:', error);
            }

            if (reviewCache[movie.id]) {
                document.getElementById('modal-review').textContent = reviewCache[movie.id];
            } else {
                try {
                    const reviewResponse = await fetch(`https://api.themoviedb.org/3/movie/${movie.id}/reviews?api_key=00ba7a7ea04d04cfb14ee146d36ec4e6&language=en-US&page=1`);
                    const reviewData = await reviewResponse.json();
                    const shortReview = reviewData.results
                        .filter(review => review.content.length <= 300)
                        .sort((a, b) => a.content.length - b.content.length)[0];

                    const reviewText = shortReview ? shortReview.content : "No short reviews available.";
                    reviewCache[movie.id] = reviewText;
                    document.getElementById('modal-review').textContent = reviewText;
                } catch (error) {
                    console.error('Error fetching review:', error);
                    document.getElementById('modal-review').textContent = "Unable to fetch review.";
                }
            }

            const modal = document.getElementById('modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        document.getElementById('close-modal').addEventListener('click', function() {
            const modal = document.getElementById('modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });
    </script>

    <?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/movies/index.blade.php ENDPATH**/ ?>