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

const form = document.querySelector("form#search");

form.addEventListener("submit", async (event) => {
    event.preventDefault();

    const data = Object.fromEntries(new FormData(form));
    const dataKeys = Object.keys(data);

    if (dataKeys.length === 1 && dataKeys.includes("title") && data.title.length > 0) {
        try {

            
            const database = await fetchData(
                "/search-db/search.php",
                { type: "search", ...data }
                );
                
                console.log(database.message)
            const databaseResults = database.message.map((book) => {

                return {
                    title: book.title,
                    year: JSON.stringify(book.year_published)
                }
            })

            console.log(databaseResults)

            const dmz = await fetchData(
                "/search-dmz/search.php",
                { type: "dmz_search", ...data }
            );
            
            const booksNotInDb = []

            dmz.message.forEach(book => {
                
                if (!arrayIncludesObject(databaseResults, {
                    title: book.title,
                    year: book.year_published,
                })){
                    booksNotInDb.push(book)
                }

            });

            console.log(booksNotInDb)

            const addToDatabase = await fetchData(
                "/search-db/search.php",
                { type: "add", books: JSON.stringify(booksNotInDb) }
            );

            console.log(addToDatabase.message)

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
