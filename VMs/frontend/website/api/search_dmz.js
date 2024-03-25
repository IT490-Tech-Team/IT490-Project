import { fetchData } from "./_main.js";

export const searchExternalBooks = ({title}) => {
	return fetchData({
		type: "search",
		exchange: "searchDmzExchange",
		queue: "searchDmzQueue",
		title: title
	})
	.then(data => data.books)
}