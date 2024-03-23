import {UTILS_PATH} from "/common/javascript/defaults.js"

export const authenticate = async (object) => {
    return fetch(UTILS_PATH + "/authenticate/main.php", {
        method: "POST",
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams(object)
    })
    .then((response) => response.text())
    .then((response) => JSON.parse(response))
    .then((data) => {
        const returnCode = data.returnCode;

        if(returnCode < 200 || returnCode >= 300){
            throw new Error(data.message);
        }

        return data
    })
}