import { fetchData } from "./_main.js"

const exchange= "emailExchange"
const queue= "emailQueue"

export const emailSignUp = ({user_email, email_type, query}) => {
    return fetchData({
        type: "add_update",
        exchange: exchange,
        queue: queue,
        user_email: user_email,
        email_type: email_type,
        query: query
    })
    .then(data => { return {}});
}
