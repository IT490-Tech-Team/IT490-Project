import { UTILS_PATH }  from "/common/javascript/defaults.js"

let form = document.querySelector("form#search")

const fetchDMZ = () => {

}
const fetchDatabase = () => {

}
const fetchFrontend = () => {

}

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

        console.log("DMZ: ", data)
        fetch(UTILS_PATH + "/search-dmz/search.php", {
            method: "POST",
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams(data)
        })
        .then((response) => {
            return response.text()
        })
        .then((response) => JSON.parse(response))
        .then((response) => {
            console.log(response.message)
            const data = {
                type: "add",
                books: JSON.stringify(response.message)
            }

            console.log(response.message)
            // Display Books
            // Send to Database
            fetch(UTILS_PATH + "/search-db/search.php", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams(data)
            })
            .then((response) => response.text())
            .then((response) => JSON.parse(response))
            .then((response) => {

                const data = {
                    type: "download_covers",
                    books: JSON.stringify(response.message)
                }
                fetch(UTILS_PATH + "/search-frontend/search.php", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams(data)
                })
                .then((response) => response.text())
                  .then((response) => JSON.parse(response))
                .then(response => {
                    const data = {
                        type: "add_covers",
                        books: JSON.stringify(response.message)
                    }

                    fetch(UTILS_PATH + "/search-db/search.php", {
                        method: "POST",
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams(data)
                    })
                    .then(response => {
                        console.log(response)
                    })

                })
            })
        })
    }
})

