import { fetchData } from "./_main.js"


export const getReviewbyBookId = ({bookId}) => {
	console.log("p2")
	return fetchData({
		type: "get_reviews_by_bookid",
		exchange: "reviewsExchange",
		queue: "reviewsQueue",
		book_id: bookId
	})
}

export const addReview = ({bookId, userId, username, rating, comment})  => {
	return fetchData({
		type: "add_review",
		exchange: "reviewsExchange",
		queue: "reviewsQueue",
		book_id: bookId, 
		user_id: userId, 
		username: username,
		rating: rating, 
		comment: comment
	})
}