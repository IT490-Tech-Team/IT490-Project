import { fetchData } from "./_main.js";
import { addLog } from "./log.js"

const exchange = "discussionExchange"
const queue = "discussionQueue"

export const addDiscussionComment = ({bookId, userId, username, comment, replyingToId}) => {
	addLog('Info', 'Adding commnet "'+commnet+'" by '+username, "frontend/website/api/discussion.js");
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
	.then(data => { return {}})
    .catch(error => {
        addLog('Error', 'Error adding commnet "'+commnet+'" by '+username+': '+error.message, "frontend/website/api/discussion.js");
        throw error;
    });
}

export const getDiscussionByBookId = ({bookId}) => {
	return fetchData({
		type: "get_comment_by_book_id",
		exchange: exchange,
		queue: queue,
		book_id: bookId, 
	})
	.then(data => data.discussions)
    .catch(error => {
        addLog('Error', 'Error getting discussion by book id where bookId='+bookId+': '+error.message, "frontend/website/api/discussion.js");
        throw error;
    });
}

export const getDiscussionByCommentId = ({commentId}) => {
	return fetchData({
		type: "get_comment_by_comment_id",
		exchange: exchange,
		queue: queue,
		comment_id: commentId
	})
	.then(data => data.discussion)
    .catch(error => {
        addLog('Error', 'Error getting discussion by comment id where commentId='+commentId+': '+error.message, "frontend/website/api/discussion.js");
        throw error;
    });
}
