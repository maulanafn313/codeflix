@foreach ($movies as $movie)
    <div class="col-md-20 col-6">
        <a href="{{ route('movies.show', $movie->id) }}">
            <div class="mb-4 card">
                <img src="{{ $movie->poster }}" class="card-image-movie-list" alt="...">
                <span class="badge rounded-pill text-bg-dark badge-rating">
                    <img class="star-rating" src="assets/img/star-rating.png" alt="">
                    ({{ $movie->average_rating }})
            </div>
        </a>
    </div>
@endforeach
