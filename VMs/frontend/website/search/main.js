import { UTILS_PATH }  from "/common/javascript/defaults.js"

let form = document.querySelector("form#search")

form.addEventListener("submit", (event) => {
    event.preventDefault()
    
    const data = Object.fromEntries(new FormData(form))

    // Search from us
    // Search from api
    // if api has entries we don't have, then add to database

    const dataKeys = Object.keys(data)
    if (dataKeys.length == 1 && dataKeys.indexOf("title") > -1){
        const dmzData = data
        dmzData.type = "dmz_search"

        fetch(UTILS_PATH + "/search/search.php", {
            method: "POST",
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams(data)
        })
        .then((response) => response.text())
        .then((response) => JSON.parse(response))
        .then((response) => {
            console.log(response.message)
            const data = JSON.parse(response.message)
        })
    }
})

