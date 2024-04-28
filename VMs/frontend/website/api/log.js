import { fetchData } from "./_main.js";

const exchange = "logExchange"
const queue = "logQueue"

export const addLog = ({message_type, message, source}) => {
	return fetchData({
		type: "add_log",
		exchange: exchange,
		queue: queue,
		message: message, 
		message_type: message_type, 
		source: source,
	})
	.then(data => { return {}})
	.catch(error => {
		console.log("error logging: ");
		console.log(error); 
		return error;
	});
}
