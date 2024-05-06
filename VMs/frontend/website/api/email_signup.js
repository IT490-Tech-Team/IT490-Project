import { fetchData } from "./_main.js"
import { addLog } from "./log.js"

const exchange= "emailExchange"
const queue= "emailQueue"

export const emailSignUp = ({user_email, email_type, query}) => {
	
	addLog('Info', 'Signing up "'+user_email+'" for recurring emails', "frontend/website/api/email_signup.js");
    
    return fetchData({
        type: "add_update",
        exchange: exchange,
        queue: queue,
        user_email: user_email,
        email_type: email_type,
        query: query
    })
    .then(data => { return {}})
    .catch(error => {
        addLog('Error', 'Error signing up "'+user_email+'" for recurring emails', "frontend/website/api/discussion.js");
        throw error;
    });
}
