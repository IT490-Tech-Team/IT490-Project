import { fetchData } from "./_main.js"

const exchange= "reviewsExchange"
const queue= "reviewsQueue"

export const getReviewbyBookId = ({bookId}) => {
	return fetchData({
		type: "get_reviews_by_bookid",
		exchange: exchange,
		queue: queue,
		book_id: bookId
	})
}

export const addReview = ({bookId, userId, username, rating, comment})  => {
	return fetchData({
		type: "add_review",
		exchange: exchange,
		queue: queue,
		book_id: bookId, 
		user_id: userId, 
		username: username,
		rating: rating, 
		comment: comment
	})
}