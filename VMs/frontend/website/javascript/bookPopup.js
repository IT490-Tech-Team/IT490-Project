import { addDiscussionComment, getDiscussionByBookId, getDiscussionByCommentId } from "/api/discussion.js"
import { addReview, getReviewbyBookId } from "/api/reviews.js"
import { AddCategoryToUserLibrary, addBookToUserLibrary, removeBookFromUserLibrary } from "/api/search_db.js";

const shareToTwitter = (book) => {
    const message = `Check out "${book.title}" by ${book.authors} on BookQuest!`;
    const url = window.location.href;
    const encodedMessage = encodeURIComponent(message);
    const encodedUrl = encodeURIComponent(url);

    const twitterShareUrl = `https://twitter.com/intent/tweet?text=${encodedMessage}&url=${encodedUrl}`;

    window.open(twitterShareUrl);
}

const shareToPinterest = (book) => {
    const baseUrl = window.location.origin + window.location.pathname;
    const imageUrl = encodeURIComponent(book.cover_image_url);
    const bookUrl = encodeURIComponent(baseUrl);
    const isVideo = 'false'; 
    const description = encodeURIComponent(`Check out "${book.title}" by ${book.authors} on BookQuest!`);

    const pinterestShareUrl = `https://pinterest.com/pin/create/bookmarklet/?media=${imageUrl}&url=${bookUrl}&is_video=${isVideo}&description=${description}`;

    window.open(pinterestShareUrl);
}

const shareToLinkedIn = (book) => {
    const baseUrl = window.location.origin + window.location.pathname;
    const bookUrl = encodeURIComponent(baseUrl);
    const title = encodeURIComponent(`Check out "${book.title}" by ${book.authors} on BookQuest!`);

    const linkedInShareUrl = `https://www.linkedin.com/shareArticle?url=${bookUrl}&title=${title}`;

    window.open(linkedInShareUrl);
}


export const bookPopUp = (book, user, userLibrary) => {
    const container = document.createElement("div")
    container.classList.add("popup")

    container.addEventListener("click", (e) => {
        if(e.target.className == container.className){
            container.remove()
        }
    })
    const imageUrl = book.cover_image_url
    const title = book.title
    let authors = book.authors
    if(typeof authors == "string"){
        authors = JSON.parse(authors)
    }
    if(typeof authors == "object"){
        authors = authors.join(", ")
    }
    const description = book.description
    let genre = book.genres
    if(typeof genre == "string"){
        genre = JSON.parse(genre)
    }
    if(typeof genre == "object"){
        genre = genre.join(", ")
    }
    const language = book.language

    container.innerHTML = `
        <div class="book-content">
            <img src="${imageUrl}" alt="">
            <h1>${title}</h1>
            <h2>Authors</h2>
            <p>${authors}</p>
            <h2>Description</h2>
            <p>${description}</p>
            <h2>Genre</h2>
            <p>${genre}</p>

            <h2>User Library</h2>
            <div id='userLibrary'>
                <div>
                    <input type="text" id="category-input" name="category_name" placeholder="category">
                    <button id="category-submit">Submit</button>
                </div>
            </div>
            <h2>Share this book </h2>
            <button id="share-twitter-button">Share to Twitter</button>
	        <button id="share-pinterest-button">Share to Pinterest</button>
	        <button id="share-linkedin-button">Share to LinkedIn</button>

            <h1>Reviews</h1>
            <div>
                <textarea id="review-input"></textarea>
                <input type="number" id="review-number" max=5 min=1 value=0>
                <button id="review-submit">Send</button>
            </div>
            <div id="reviews-container"></div>
            <h1>Discussion</h1>
            <div>
                <div id="replying-to"></div>
                <textarea id="discussion-input"></textarea>
                <button id="discussion-submit">Send</button>
            </div>
            <div id="discussion-container"></div>
        </div>
    `;

    const shareButton = container.querySelector("#share-twitter-button");
    const sharePinterestButton = container.querySelector("#share-pinterest-button");
    const shareLinkedInButton = container.querySelector("#share-linkedin-button");

    shareButton.addEventListener("click", () => shareToTwitter(book));
    sharePinterestButton.addEventListener("click", () => shareToPinterest(book));
    shareLinkedInButton.addEventListener("click", () => shareToLinkedIn(book));

    const userLibraryContainer = container.querySelector("#userLibrary")
    const submitCategoryButton = container.querySelector("#category-submit")
    const categoryInput = container.querySelector("#category-input")
    
    submitCategoryButton.addEventListener("click", () => {
        AddCategoryToUserLibrary({
            book_id: book.id,
            user_id: user.id,
            categoryName: categoryInput.value
        })
    })
    console.log(submitCategoryButton)

    library(userLibraryContainer, book, user, userLibrary)
    reviews(container, book.id, user.id, user.username)
    discussion(container, book.id, user.id, user.username)

    return container;
}

const library = (parent, book, user, userLibrary) => {
    console.log(book, user, userLibrary)
    const userLibraryBookIds = userLibrary.map(library => library.book_id)
    const button = document.createElement("button")

    if (userLibrary && !userLibraryBookIds.includes(book.id)){
        button.textContent = "Add To Library"
        button.addEventListener("click", () => {
            addToLibrary(book.id, user.id)
            .then(() => {
                location.reload();
            })

        })
    }
    else {
        button.textContent = "Remove From Library"
        button.addEventListener("click", () => {
            removeFromLibrary(book.id, user.id)
            .then(() => {
                location.reload();
            })
        })
    }

    parent.appendChild(button)

}

const reviews = (parent, bookId, userId, username) => {
	const reviewsContainer = parent.querySelector("#reviews-container")
	const reviewsSubmitButton = parent.querySelector("#review-submit")
	const reviewsInputText = parent.querySelector("#review-input")
	const reviewsNumber = parent.querySelector("#review-number")

	fillReviews(reviewsContainer, bookId)

	reviewsSubmitButton.addEventListener("click", () => {
		const rating = reviewsNumber.value
		const comment = reviewsInputText.value

		addReview({bookId, userId, username, rating, comment})
		.then(data => {
			fillReviews(reviewsContainer, bookId)
		})
	})
}

const fillReviews = (reviewsContainer, bookId) => {
	reviewsContainer.innerHTML = ""
	getReviewbyBookId({bookId})
	.then(data => {
		data.reviews.forEach(review => {

			const reviewElement = document.createElement("div")
			reviewElement.innerHTML = 
			`
			<h3>${review.username}</h3>
			<p>review: ${review.comment}</p>
			<p>rating: ${review.rating}</p>
			`

			reviewsContainer.appendChild(
				reviewElement
			)
		});
	})
	.catch(error => {
		console.log(error)
	})
}

const discussion = (parent, bookId, userId, username) => {
	const discussionSubmitButton = parent.querySelector("#discussion-submit")
	const discussionContainer = parent.querySelector("#discussion-container")
	const discussionInputText = parent.querySelector("#discussion-input")
	const replyingToTextElement = parent.querySelector("#replying-to")

	fillDiscussion(discussionContainer, replyingToTextElement, discussionSubmitButton, bookId)

	discussionSubmitButton.addEventListener("click", () => {
		const comment = discussionInputText.value
		const replyingToId = discussionSubmitButton.getAttribute("replying-to") ?? null
		addDiscussionComment({bookId, userId, username, comment, replyingToId})
		.then(data => {
			fillDiscussion(discussionContainer, replyingToTextElement, discussionSubmitButton, bookId)
		})
	})
}

const fillDiscussion = (discussionContainer, replyingToTextElement, discussionSubmitButton, bookId) => {
	discussionContainer.innerHTML = ""

	getDiscussionByBookId({bookId})
	.then(data => {
		data.forEach(async (comment) => {
			let repliedComment = null
			if(comment.reply_to_id){
				repliedComment = await getDiscussionByCommentId({commentId: comment.reply_to_id})
			}
			const commentElement = document.createElement("div")
			commentElement.innerHTML = 
			`
			<h2>${comment.username}</h2>
			<p>${repliedComment ? `replying to ${repliedComment.username}: ${repliedComment.comment}` : ""}</p>
			<p>${comment.comment}</p>
			<button>Reply To</button>
			`

			const replyToButton = commentElement.querySelector("button")
			replyToButton.addEventListener("click", () => {
				replyingToTextElement.innerHTML = 
				`
				<h4>replying to</h4>
				<p>${comment.username}: ${comment.comment}</p>
				`

				discussionSubmitButton.setAttribute("replying-to", comment.id)
			})

			discussionContainer.appendChild(commentElement)
		})
	})
}

const addToLibrary = async (book_id, user_id) => {
    addBookToUserLibrary({book_id, user_id})
}

const removeFromLibrary = async (book_id, user_id) => {
    removeBookFromUserLibrary({book_id, user_id})
}