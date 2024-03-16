import { authenticate } from "../common/javascript/authenticate.js"
import { fetchData, getCookies } from "../common/javascript/helpers.js"

const addBook = (data) => {
    const columnOrder = ["cover", "title", "authors", "genres", "languages", "year published"]
    const dataKeys = ["cover_image_url", "title", "authors", "genres", "languages", "year_published"]

    const row = document.createElement("tr")

    // Iterate through the columns
    for (const columnContent of columnOrder) {
        // Matches data to the column
        const dataKey = dataKeys[columnOrder.indexOf(columnContent)]
        let columnData = data[dataKey]

        const dataElement = document.createElement("td")

        // If the column is cover, then add an image element
        if (columnContent == "cover") {
            const coverElement = document.createElement("img")
            coverElement.src = columnData

            dataElement.appendChild(coverElement)
        }
        // Database stores authors and genres as a string of an array
        // DMZ stores them as an array
        else if (columnContent == "authors" || columnContent == "genres") {
            // If columnData is a string, parse it to get the array
            if (columnData) {
                if (typeof columnData === "string") {
                    columnData = JSON.parse(columnData)
                }

                if (columnData) {
                    // Join the array with and (i.e. A and B )
                    dataElement.textContent = columnData.join(" and ")
                }
            }
        }
        else {
            dataElement.textContent = columnData
        }

        // Add column into the row
        row.appendChild(dataElement)
    }

    const removeFromLibraryCell = document.createElement("td");
    const removeFromLibraryButton = document.createElement("button");
    
    removeFromLibraryButton.textContent = "Remove From Library";
    removeFromLibraryButton.addEventListener("click", async (e) => {

        console.log(userId,data.id )
        fetchData(
            "/search-db/search.php",
            {
                type: "remove_from_library", 
                user_id: userId, 
                book_id: data.id
            })
        .then(() => {
            row.remove()
        })
    })

    row.appendChild(removeFromLibraryButton)
    result.appendChild(row)
}

let userId = null;


const result = document.querySelector("tbody#results")
const sessionId = getCookies("sessionId")

console.log(sessionId)
authenticate({
    type: "get_user",
    sessionId: sessionId
})
    .then(response => {
        console.log(response)

        userId = response.userDetails.id

        const books = []

        response.userLibraries.forEach(entry => {
            books.push(entry.book_id)
        });

        if(books.length > 0){
            fetchData(
                "/search-db/search.php",
                {
                    type: "get_books",
                    book_ids: JSON.stringify(books)
                })
                .then(data => {
                    console.log(data.message.forEach(book => {
                        addBook(book)
                    }))
                })
                .catch(err => {
                    console.log(err)
                })
        }


    })