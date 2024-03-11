import { UTILS_PATH } from "/common/javascript/defaults.js";

const headers = {
    'Content-Type': 'application/x-www-form-urlencoded'
};

const fetchData = async (endpoint, data) => {
    try {
        const response = await fetch(UTILS_PATH + endpoint, {
            method: "POST",
            headers: headers,
            body: new URLSearchParams(data)
        });
        return await response.json();
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


const appendResult = (data) => {
    console.log(data)
    const rowOrder = ["id", "cover", "title", "description", "authors", "genres", "languages", "year published"]
    const dataKeys= ["id", "cover_image_url", "title", "description", "authors",  "genres", "languages", "year_published"]
    
    const row = document.createElement("tr")
    
    for (const rowContent of rowOrder) {
        const dataKey = dataKeys[rowOrder.indexOf(rowContent)]
        const rowData = data[dataKey]
        
        const dataElement = document.createElement("td")

        if(rowContent == "cover"){
            const coverElement = document.createElement("img")
            coverElement.src = rowData

            dataElement.appendChild(coverElement)
        }
        else {
            dataElement.textContent = rowData
        }

        row.appendChild(dataElement)
    }

    result.appendChild(row)
}

const form = document.querySelector("form#search");
const result = document.querySelector("tbody#search-results")

form.addEventListener("submit", async (event) => {
    event.preventDefault();

    result.innerHTML = ""

    const data = Object.fromEntries(new FormData(form));
    const dataKeys = Object.keys(data);

    if (dataKeys.length === 1 && dataKeys.includes("title") && data.title.length > 0) {
        try {

            const database = await fetchData(
                "/search-db/search.php",
                { type: "search", ...data }
            );
            
            console.log(database)
            const dbCompare = database.message.map((book) => {
                appendResult(book)
                return {
                    title: book.title,
                    year: JSON.stringify(book.year_published)
                }
            })

            const dmz = await fetchData(
                "/search-dmz/search.php",
                { type: "dmz_search", ...data }
            );

            const booksNotInDb = []

            dmz.message.forEach(book => {

                if (!arrayIncludesObject(dbCompare, {
                    title: book.title,
                    year: book.year_published,
                })) {
                    booksNotInDb.push(book)
                }

            });

            booksNotInDb.forEach(book => {
                appendResult(book)
            })

            const addToDatabase = await fetchData(
                "/search-db/search.php",
                { type: "add", books: JSON.stringify(booksNotInDb) }
            );


            const downloadCovers = await fetchData(
                "/search-frontend/search.php",
                { type: "download_covers", books: JSON.stringify(addToDatabase.message) }
            );

            console.log(downloadCovers)

            const addCoversToDatabase = await fetchData(
                "/search-db/search.php",
                { type: "add_covers", books: JSON.stringify(downloadCovers.message) }
            );

            console.log(addCoversToDatabase)

        } catch (error) {
            console.error("Error:", error);
        }
    }
});
