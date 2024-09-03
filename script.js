document.addEventListener('DOMContentLoaded', function() {
    const places = [
        { id: 1, likes: 0 },
        { id: 2, likes: 0 }
    ];

    places.forEach(place => {
        const likeButton = document.querySelector(`#like-count-${place.id}`).parentElement;
        const likeCountDisplay = document.getElementById(`like-count-${place.id}`);
        const commentForm = document.getElementById(`comment-form-${place.id}`);
        const commentList = document.getElementById(`comment-list-${place.id}`);

        likeButton.addEventListener('click', function() {
            place.likes++;
            likeCountDisplay.textContent = place.likes;
        });

        commentForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const newCommentText = document.getElementById(`new-comment-${place.id}`).value;
            if (newCommentText.trim()) {
                const newComment = document.createElement('p');
                newComment.innerHTML = `<strong>You:</strong> ${newCommentText}`;
                commentList.appendChild(newComment);
                document.getElementById(`new-comment-${place.id}`).value = ''; // Clear the textarea
            }
        });
    });
});
