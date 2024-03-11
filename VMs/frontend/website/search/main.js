import { UTILS_PATH } from "/common/javascript/defaults.js";

const headers = {
    'Content-Type': 'application/x-www-form-urlencoded'
};

// Function to fetch from php scripts in /common/utils/ 
const fetchData = async (endpoint, data) => {
    try {
        const response = await fetch(UTILS_PATH + endpoint, {
            method: "POST",
            headers: headers,
            body: new URLSearchParams(data)
        })
        .then(response => response.text())
        .then(response => JSON.parse(response))
        
        return response;
    } catch (error) {
        console.error("Error fetching data:", error);
        return { error: "Error fetching data" };
    }
};

function arrayIncludesObject(arr, obj) {
    // Iterate through the array
    for (let item of arr) {
        // Convert objects to JSON strings for comparison
        const itemString = JSON.stringify(item);
        const objString = JSON.stringify(obj);
        // If the JSON strings match, return true
        if (itemString === objString) {
            return true;
        }
    }
    // If no match found, return false
    return false;
}

// Function that adds the book to the table body as a row
const addBook = (data) => {
    // Arrays to coordinate the column with the data object
    const columnOrder = ["id", "cover", "title", "description", "authors", "genres", "languages", "year published"]
    const dataKeys= ["id", "cover_image_url", "title", "description", "authors",  "genres", "languages", "year_published"]
    
    const row = document.createElement("tr")
    
    // Iterate through the columns
    for (const columnContent of columnOrder) {
        // Matches data to the column
        const dataKey = dataKeys[columnOrder.indexOf(columnContent)]
        let columnData = data[dataKey]
        
        const dataElement = document.createElement("td")

        // If the column is cover, then add an image element
        if(columnContent == "cover"){
            const coverElement = document.createElement("img")
            coverElement.src = columnData

            dataElement.appendChild(coverElement)
        }
        // Database stores authors and genres as a string of an array
        // DMZ stores them as an array
        else if (columnContent == "authors" || columnContent == "genres") {
            // If columnData is a string, parse it to get the array
            if (typeof columnData === "string"){
                columnData =JSON.parse(columnData)
            }

            // Join the array with and (i.e. A and B )
            dataElement.textContent = columnData.join(" and ")
        }
        else {
            dataElement.textContent = columnData
        }

        // Add column into the row
        row.appendChild(dataElement)
    }

    // Add row to the table
    result.appendChild(row)
}

const form = document.querySelector("form#search");
const result = document.querySelector("tbody#search-results")

form.addEventListener("submit", async (event) => {
    event.preventDefault();

    // Empties the result table 
    result.innerHTML = ""

    // Gets the entries from the form (currently only title)
    const data = Object.fromEntries(new FormData(form));
    const dataKeys = Object.keys(data);

    // If the form only has a title search, then do a full DB + DMZ search
    if (dataKeys.length === 1 && dataKeys.includes("title") && data.title.length > 0) {
        try {
            // Searches the database
            const database = await fetchData(
                "/search-db/search.php",
                { type: "search", ...data }
            );
            
            // Adds resulting books to the table
            // and create an a simpler array to compare to DMZ
            const dbCompare = database.message.map((book) => {
                addBook(book)
                return {
                    title: book.title,
                    year: JSON.stringify(book.year_published)
                }
            })

            // Search the DMZ
            const dmz = await fetchData(
                "/search-dmz/search.php",
                { type: "search", ...data }
            );

            // Compares DMZ results and DB results
            const booksNotInDb = []
            dmz.message.forEach(book => {
                // If the book isn't in datbase, add it to the array
                if (!arrayIncludesObject(dbCompare, {
                    title: book.title,
                    year: book.year_published,
                })) {
                    booksNotInDb.push(book)
                }
            });

            booksNotInDb.forEach(book => {
                addBook(book)
            })

            // Adds books that aren't in the database to it
            const addToDatabase = await fetchData(
                "/search-db/search.php",
                { type: "add", books: JSON.stringify(booksNotInDb) }
            );

            // Downloads the books in the frontend
            const downloadCovers = await fetchData(
                "/search-frontend/search.php",
                { type: "download_covers", books: JSON.stringify(addToDatabase.message) }
            );

            // Finally, replaces the default cover with the book cover downloaded
            const addCoversToDatabase = await fetchData(
                "/search-db/search.php",
                { type: "add_covers", books: JSON.stringify(downloadCovers.message) }
            );

        } catch (error) {
            console.error("Error:", error);
        }
    }
});
