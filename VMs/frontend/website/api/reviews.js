import { fetchData } from "./_main.js"
import { addLog } from "./log.js"

const exchange= "reviewsExchange"
const queue= "reviewsQueue"

export const getReviewbyBookId = ({bookId}) => {
	return fetchData({
		type: "get_reviews_by_bookid",
		exchange: exchange,
		queue: queue,
		book_id: bookId
	})
    .catch(error => {
        addLog('Error', 'Error getting review where bookId='+bookId+': '+error.message, "frontend/website/api/reviews.js");
        throw error;
    });
}

export const addReview = ({bookId, userId, username, rating, comment})  => {
	addLog('Info', 'Adding review by '+username+' where bookId='+bookId, "frontend/website/api/reviews.js");
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
    .catch(error => {
        addLog('Error', 'Error adding review by '+username+' where bookId='+bookId, "frontend/website/api/reviews.js");
        throw error;
    });
}
