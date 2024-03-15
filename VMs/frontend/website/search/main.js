import { authenticate } from "../common/javascript/authenticate.js";
import { SESSION_ID_COOKIE_NAME } from "../common/javascript/defaults.js";
import { getCookies } from "../common/javascript/helpers.js";
import { UTILS_PATH } from "/common/javascript/defaults.js";

const headers = {
    'Content-Type': 'application/x-www-form-urlencoded'
};

// Function to fetch data from PHP scripts
const fetchData = async (endpoint, data) => {
    try {
        const response = await fetch(UTILS_PATH + endpoint, {
            method: "POST",
            headers: headers,
            body: new URLSearchParams(data)
        });
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return await response.json();
    } catch (error) {
        console.error("Error fetching data:", error);
        return { error: "Error fetching data" };
    }
};

// Function to check if an object is included in an array
function arrayIncludesObject(arr, obj) {
    return arr.some(item => JSON.stringify(item) === JSON.stringify(obj));
}

// Function to add a book to the table body as a row
const addBookToTable = (data) => {
    // Column order and corresponding data keys
    const columnOrder = ["cover", "title", "authors", "genres", "languages", "year published"];
    const dataKeys = ["cover_image_url", "title", "authors", "genres", "languages", "year_published"];

    // Create a table row
    const row = document.createElement("tr");

    // Iterate through the columns
    columnOrder.forEach(columnContent => {
        // Get the data key for the current column
        const dataKey = dataKeys[columnOrder.indexOf(columnContent)];
        let columnData = data[dataKey];

        const dataElement = document.createElement("td");

        // If the column is 'cover', add an image element
        if (columnContent === "cover" && columnData) {
            const coverElement = document.createElement("img");
            coverElement.src = columnData;
            dataElement.appendChild(coverElement);
        } else if (columnContent === "authors" || columnContent === "genres") {
            // Handle authors and genres
            if (columnData && typeof columnData === "string") {
                columnData = JSON.parse(columnData);
                dataElement.textContent = columnData.join(" and ");
            }
        } else {
            dataElement.textContent = columnData;
        }

        // Add the column data to the row
        row.appendChild(dataElement);
    });

    // Add 'Add To Library' button if user is logged in and book is not in the library
    if (loggedIn && !libraryBooks.includes(data.id)) {
        const addToLibraryCell = document.createElement("td");
        const addToLibraryButton = document.createElement("button");
        addToLibraryButton.textContent = "Add To Library";
        addToLibraryButton.setAttribute("data-book-id", data.id);
        addToLibraryButton.addEventListener("click", async (e) => {
            try {
                const response = await fetchData(
                    "/search-db/search.php",
                    { type: "add_to_library", user_id: userId, book_id: data.id }
                );
                console.log(response);
                // Remove the button after adding to library
                addToLibraryCell.innerHTML = "";
            } catch (error) {
                console.error("Error adding book to library:", error);
            }
        });
        addToLibraryCell.appendChild(addToLibraryButton);
        row.appendChild(addToLibraryCell);
    }

    // Add the row to the table
    result.appendChild(row);
};

// Initialize variables
let loggedIn = false;
let userId = -1;
let libraryBooks = [];

// Authenticate user and get user's library
authenticate({ type: "get_user", sessionId: getCookies(SESSION_ID_COOKIE_NAME) })
    .then(data => {
        loggedIn = true;
        userId = data.userDetails.id;
        libraryBooks = data.userLibraries.map(entry => entry.book_id);
        console.log(libraryBooks);
    })
    .catch(error => {
        console.log(error);
    });

// Handle form submission
form.addEventListener("submit", async (event) => {
    event.preventDefault();

    // Clear previous search results
    result.innerHTML = "";

    // Get search data from the form
    const data = Object.fromEntries(new FormData(form));
    const dataKeys = Object.keys(data);

    // Perform search if the form only has a 'title' search
    if (dataKeys.length === 1 && dataKeys.includes("title") && data.title.length > 0) {
        try {
            // Search the database
            const database = await fetchData("/search-db/search.php", { type: "search", ...data });

            // Add resulting books to the table
            database.message.forEach(book => addBookToTable(book));

            // Search the DMZ
            const dmz = await fetchData("/search-dmz/search.php", { type: "search", ...data });

            // Filter out books not in the database
            const booksNotInDb = dmz.message.filter(book => !arrayIncludesObject(database.message, { title: book.title, year_published: book.year_published }));

            // Add books not in the database to the database
            if (booksNotInDb.length > 0) {
                const addToDatabase = await fetchData("/search-db/search.php", { type: "add", books: JSON.stringify(booksNotInDb) });
                addToDatabase.insertedBooks.forEach(book => addBookToTable(book));
            }
        } catch (error) {
            console.error("Error searching:", error);
        }
    }
});
