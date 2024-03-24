import { fetchData } from "./_main.js";

export const addDiscussionComment = ({bookId, userId, username, comment, replyingToId}) => {
	return fetchData({
		type: "add_comment",
		exchange: "discussionExchange",
		queue: "discussionQueue",
		book_id: bookId, 
		user_id: userId, 
		username: username, 
		comment: comment,
		reply_to_id: replyingToId
	})
}

export const getDiscussionByBookId = ({bookId}) => {
	return fetchData({
		type: "get_comment_by_book_id",
		exchange: "discussionExchange",
		queue: "discussionQueue",
		book_id: bookId, 
	})
	.then(data => data.discussions)
}

export const getDiscussionByCommentId = ({commentId}) => {
	return fetchData({
		type: "get_comment_by_comment_id",
		exchange: "discussionExchange",
		queue: "discussionQueue",
		comment_id: commentId
	})
	.then(data => data.discussion)
}