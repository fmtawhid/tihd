<!-- Review Modal -->
<div class="modal fade rating-modal" id="rattingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <button class="custom-close-btn btn btn-primary" data-bs-dismiss="modal">
                    <i class="ph ph-x"></i>
                </button>
                <h5 class="mb-2">{{__('frontend.share_movie_experience')}}</h5>
                <p class="m-0">{{__('frontend.share_your_thoughts')}}</p>

                <div class="mt-5 pt-3">
                    <form class="m-0" id="reviewForm">
                        <ul class="list-inline m-0 p-0 d-flex align-items-center justify-content-center gap-3 rating-list">
                            <li data-value="1" class="star">
                                <span class="text-warning fs-4 icon">
                                    <i class="ph-fill ph-star icon-fill"></i>
                                    <i class="ph ph-star icon-normal"></i>
                                </span>
                            </li>
                            <li data-value="2" class="star">
                                <span class="text-warning fs-4 icon">
                                    <i class="ph-fill ph-star icon-fill"></i>
                                    <i class="ph ph-star icon-normal"></i>
                                </span>
                            </li>
                            <li data-value="3" class="star">
                                <span class="text-warning fs-4 icon">
                                    <i class="ph-fill ph-star icon-fill"></i>
                                    <i class="ph ph-star icon-normal"></i>
                                </span>
                            </li>
                            <li data-value="4" class="star">
                                <span class="text-warning fs-4 icon">
                                    <i class="ph-fill ph-star icon-fill"></i>
                                    <i class="ph ph-star icon-normal"></i>
                                </span>
                            </li>
                            <li data-value="5" class="star">
                                <span class="text-warning fs-4 icon">
                                    <i class="ph-fill ph-star icon-fill"></i>
                                    <i class="ph ph-star icon-normal"></i>
                                </span>
                            </li>
                        </ul>

                        <div class="mt-5">
                            <textarea class="form-control" placeholder="Share your thoughts on your favorite movie" rows="4" id="reviewTextarea"></textarea>
                        </div>

                        <div class="mt-5 pt-2">
                            <button type="submit" class="btn btn-primary" id="submitBtn">{{__('frontend.submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let selectedRating = 0;
let entertainmentId = null;
let reviewId = null; // For storing the review ID when editing
let firstEdit=true;
let ratingStar=0;
let ratingText='';

$(document).ready(function () {
    const ratingModal = $('#rattingModal');

        $('#rattingModal').on('hidden.bs.modal', function () {
            $('.modal-backdrop').remove();
            $('body').css({
                'overflow': '',
                'padding-right': ''
            });
        });

    ratingModal.on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget); // Button that triggered the modal
        entertainmentId = button.data('entertainment-id'); // Get the entertainment ID
        reviewId = button.data('review-id'); // Get the review ID (if editing)

        if(firstEdit==true){
            if (reviewId) {
            const review = button.data('review');
            const rating = button.data('rating');
            selectedRating = rating;
            $('#reviewTextarea').val(review);
            highlightStars(selectedRating); // Highlight existing rating
        } else {
            resetRating(); // Reset the rating on modal open
            $('#reviewTextarea').val(''); // Clear textarea
        }
        }else{
            $('#reviewTextarea').val(ratingText);
            highlightStars(ratingStar);
        }

    });

    $('.star').on('click', function() {
        selectedRating = $(this).data('value'); // Set the selected rating
        highlightStars(selectedRating); // Update star display
    });

    $('#reviewForm').on('submit', function(event) {
        event.preventDefault();
        const textarea = $(this).find('textarea').val().trim();

        if (selectedRating === 0 || textarea === "") {
            alert("Please provide a rating and review text.");
            return;
        } else {
            const url = reviewId
                ? '{{ route('update-rating') }}?is_ajax=1'
                : '{{ route('save-rating') }}?is_ajax=1';
            const method = reviewId ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                method: method,
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: JSON.stringify({
                    entertainment_id: entertainmentId,
                    rating: selectedRating,
                    review: textarea,
                    id: reviewId,
                }),
                success: function(data) {
                    window.successSnackbar(data.message)
                    const reviewdata = data.data;

                    $('#reviweList').removeClass('d-none');

                    $('#review-list').removeClass('d-none');



                    $('#addratingbtn1').addClass('d-none');
                    if ($('#addratingbtn').length) {
                            $('#addratingbtn').addClass('d-none');
                        }

                        $("#rattingModal").modal('hide');
                        $('.modal-backdrop').remove();

                    if (data) {

                        if (reviewId) {
                            firstEdit=false;
                            ratingStar=selectedRating
                            ratingText=textarea
                            const reviewCard = $('#your_review');
                            if (reviewCard.length) {
                                reviewCard.find('p.mb-0.mt-4').text(textarea);
                                const starList = reviewCard.find('ul.list-inline');
                                starList.empty();
                                for (let i = 0; i < selectedRating; i++) {
                                    starList.append('<li class="text-warning"><i class="ph-fill ph-star"></i></li>');
                                }
                            }
                            // const review = button.data('review');
                                $('#reviewTextarea').val(textarea);
                                highlightStars(selectedRating);
                        } else {
                            // Append the new review to the review list
                            // const reviewList = $('.review-list');
                             const newReview = $('#reviewlist'); // Use actual ID from data

                            ratingStar=reviewdata.rating
                            ratingText=reviewdata.review
                            // Create the review card HTML
                            const reviewCard = `
                                 <div id="your_review">
                                   <div class="review-card" >
                                    <div class="mb-3 d-flex align-items-center justify-content-between">
                                        <h5 class="m-0">My Review</h5>
                                        <div class="d-flex align-items-center gap-3">
                                            <button class="btn btn-link p-0 fw-semibold d-flex align-items-center gap-1"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#rattingModal"
                                                    data-review-id="${reviewdata.id}"
                                                    data-entertainment-id="${reviewdata.entertainment_id}"
                                                    data-review="${reviewdata.review}"
                                                    data-rating="${reviewdata.rating}">
                                                <i class="ph ph-pencil-line"></i> <span>Edit</span>
                                            </button>
                                            <button type="button" class="btn btn-link p-0 fw-semibold d-flex align-items-center gap-1"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteratingModal"
                                                    data-id="${reviewdata.id}"
                                                    onclick="setDeleteId(${reviewdata.id})">
                                                <i class="ph ph-trash"></i> <span>Delete</span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="review-detail rounded">
                                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                            <div class="d-flex align-items-center justify-content-center gap-3">
                                                <img src="${reviewdata.profile_image}" alt="user" class="img-fluid user-img rounded-circle">
                                                <div>
                                                    <h6 class="line-count-1 font-size-18">${reviewdata.username}</h6>
                                                    <p class="mb-0 font-size-14-0">${formatDate(reviewdata.created_at)}</p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center gap-1">
                                                ${Array.from({ length: reviewdata.rating }, (_, i) => `<i class="ph-fill ph-star text-warning"></i>`).join('')}
                                            </div>
                                        </div>
                                        <p class="mb-0 mt-4 fw-medium">${reviewdata.review}</p>
                                    </div>
                                </div>
                             </div>
                            `;
                            // newReview.html(reviewCard);
                            newReview.append(reviewCard);
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        }
    });
});

function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    if (isNaN(date.getTime())) return 'Invalid Date';
    const day = date.toLocaleString('en-US', { day: 'numeric' });
    const month = date.toLocaleString('en-US', { month: 'long' });
    const year = date.toLocaleString('en-US', { year: 'numeric' });
    return `${day} ${month} ${year}`;
}
function resetRating() {
    selectedRating = 0;
    $('.star').removeClass('selected');
}

function highlightStars(rating) {
    $('.star').each(function() {
        const starValue = $(this).data('value');
        $(this).toggleClass('selected', starValue <= rating);
    });
}





</script>

<style>
.star.selected .icon-fill {
    display: inline;
}

.star.selected .icon-normal {
    display: none;
}
</style>
