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

const form = document.querySelector("form#search");

form.addEventListener("submit", async (event) => {
    event.preventDefault();

    const data = Object.fromEntries(new FormData(form));
    const dataKeys = Object.keys(data);

    if (dataKeys.length === 1 && dataKeys.includes("title") && data.title.length > 0) {
        try {
            const dmz = await fetchData(
                "/search-dmz/search.php",
                { type: "dmz_search", ...data }
            );

            console.log(dmz)

            const addToDatabase = await fetchData(
                "/search-db/search.php",
                { type: "add", books: JSON.stringify(dmz) }
            );

            console.log(addToDatabase)

            const downloadCovers = await fetchData(
                "/search-frontend/search.php",
                { type: "download_covers", books: JSON.stringify(addToDatabase) }
            );

            console.log(downloadCovers)

            const addCoversToDatabase = await fetchData(
                "/search-db/search.php",
                { type: "add_covers", books: JSON.stringify(downloadCovers) }
            );

            console.log(addCoversToDatabase)

        } catch (error) {
            console.error("Error:", error);
        }
    }
});
