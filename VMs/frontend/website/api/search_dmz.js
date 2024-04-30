import { fetchData } from "./_main.js";
import { addLog } from "./log.js"

export const searchExternalBooks = ({title}) => {
	addLog('Info', 'Searching dmz for "'+title+'"', "frontend/website/api/search_dmz.js");
	return fetchData({
		type: "search",
		exchange: "searchDmzExchange",
		queue: "searchDmzQueue",
		title: title
	})
	.then(data => data.books)
    .catch(error => {
        addLog('Error', 'Error searching dmz for "'+title+'": '+error.message, "frontend/website/api/search_dmz.js");
        console.log(error)
        throw error;
    });
}
