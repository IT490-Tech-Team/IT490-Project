import { fetchData } from "./_main.js";

const exchange = "discussionExchange"
const queue = "discussionQueue"

export const addDiscussionComment = ({bookId, userId, username, comment, replyingToId}) => {
	return fetchData({
		type: "add_comment",
		exchange: exchange,
		queue: queue,
		book_id: bookId, 
		user_id: userId, 
		username: username, 
		comment: comment,
		reply_to_id: replyingToId
	})
	.then(data => { return {}});
}

export const getDiscussionByBookId = ({bookId}) => {
	return fetchData({
		type: "get_comment_by_book_id",
		exchange: exchange,
		queue: queue,
		book_id: bookId, 
	})
	.then(data => data.discussions)
}

export const getDiscussionByCommentId = ({commentId}) => {
	return fetchData({
		type: "get_comment_by_comment_id",
		exchange: exchange,
		queue: queue,
		comment_id: commentId
	})
	.then(data => data.discussion)
}