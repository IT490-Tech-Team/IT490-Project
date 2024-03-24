import { fetchData } from "./_main.js"

export const emailSignUp = ({user_email, email_type, query}) => {
    return fetchData({
        type: "add_update",
        exchange: "emailExchange",
        queue: "emailQueue",
        user_email: user_email,
        email_type: email_type,
        query: query
    })
}
