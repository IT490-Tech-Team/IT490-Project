import { fetchData } from "../common/javascript/helpers.js";
import { authenticate } from "../common/javascript/authenticate.js";
import { SESSION_ID_COOKIE_NAME } from "../common/javascript/defaults.js";
import { getCookies } from "../common/javascript/helpers.js";
import { bookPopUp } from "../common/javascript/bookPopup.js";

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

        if (columnContent == "title"){
            dataElement.addEventListener("click", () => {
                document.querySelector("body").appendChild(bookPopUp(data, userDetails))
            })
        }

        // Add column into the row
        row.appendChild(dataElement)
    }

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
                // Remove the button after adding to library
                addToLibraryCell.innerHTML = "";
            } catch (error) {
                console.error("Error adding book to library:", error);
            }
        });
        addToLibraryCell.appendChild(addToLibraryButton);
        row.appendChild(addToLibraryCell);
    }

    // Add row to the table
    result.appendChild(row)
}

const form = document.querySelector("form#search");
const result = document.querySelector("tbody#search-results")
const genreSelection = document.querySelector("#genre")
const languageSelection = document.querySelector("#language")

// Initialize variables
let loggedIn = false;
let userId = -1;
let userDetails = null;
let libraryBooks = [];

fetchData(
    "/search-db/search.php",
    { type: "get_filters"}
)
.then((data) => {
    data.genres.forEach((genre) => {
        const option = document.createElement("option")
        option.value = genre
        option.textContent = genre
        
        genreSelection.appendChild(option)
    })
    data.languages.forEach(language => {
        const option = document.createElement("option")
        option.value = language
        option.textContent = language
        
        languageSelection.appendChild(option)
    })
})

// Authenticate user and get user's library
authenticate({ type: "get_user", sessionId: getCookies(SESSION_ID_COOKIE_NAME) })
    .then(data => {
        loggedIn = true;
        userId = data.userDetails.id;
        libraryBooks = data.userLibraries.map(entry => entry.book_id);
        userDetails = data.userDetails
    })

form.addEventListener("submit", async (event) => {
    event.preventDefault();

    // Empties the result table 
    result.innerHTML = ""

    // Gets the entries from the form (currently only title)
    const data = Object.fromEntries(new FormData(form));
    const dataKeys = Object.keys(data);

    let onlyTitle = true;

    for (const [key, value] of Object.entries(data)) {
        // if any of the other form values are filled
        if (key != "title" && value != "") {
            onlyTitle = false
        }

        // if title is empty
        if (key == "title" && value == "") {
            onlyTitle = false
        }
    }

    // Searches the database
    const database = await fetchData(
        "/search-db/search.php",
        { type: "search", ...data }
    );


    console.log("good1")
    // Adds resulting books to the table
    // and create an a simpler array to compare to DMZ
    const dbCompare = database.message.map((book) => {
        addBook(book)
        return {
            title: book.title,
            year: JSON.stringify(book.year_published)
        }
    })

    console.log("good2")

    // If the form only has a title search, then do a full DB + DMZ search
    if (onlyTitle) {
        try {
            // Search the DMZ
            const dmz = await fetchData(
                "/search-dmz/search.php",
                { type: "search", ...data }
            );

            console.log("good3")

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

            console.log("good4")
            // Adds books that aren't in the database to it
            const addToDatabase = await fetchData(
                "/search-db/search.php",
                { type: "add", books: JSON.stringify(booksNotInDb) }
            );

            addToDatabase.books.forEach(book => {
                addBook(book)
            })
            console.log("good5")
        } catch (error) {
            console.error("Error:", error);
        }
    }
});
