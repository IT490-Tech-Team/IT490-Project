import { authenticate } from "../common/javascript/authenticate.js"
import { getCookies } from "../common/javascript/helpers.js"



const sessionId = getCookies("sessionId")

console.log(sessionId)
authenticate({
    type: "get_user",
    sessionId: sessionId
})
.then(response => {
    console.log(response)
})
console.log()