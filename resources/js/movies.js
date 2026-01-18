// Movie-specific JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Add click events to all star ratings
    document.querySelectorAll('.star').forEach(star => {
        star.addEventListener('click', function() {
            const rating = parseInt(this.dataset.rating);
            setRating(rating);
        });
    });

    // Initialize any movie-specific functionality
    console.log('Movies JS loaded');
});
