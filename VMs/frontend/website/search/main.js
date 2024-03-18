import { bookPopUp } from "../common/javascript/bookPopup.js"
import { arrayIncludesObject, fetchData, getUserDetailsAndLibrary } from "../common/javascript/helpers.js"

const main = async () => {
    const form = document.querySelector("form")
    const tableBody = document.querySelector("tbody")

    // Fetch filters and populate the form select elements for genre and language async
    fetchData(
        "/search-db/search.php",
        {
            type: "get_filters"
        }
    )
        .then(data => {
            const genreSelection = document.querySelector("select#genre")
            const languageSelection = document.querySelector("select#language")

            setSelection(genreSelection, data.genres)
            setSelection(languageSelection, data.languages)
        })

    // awaits for authentication, either success or [null null]
    const [userDetails, userLibrary] = await getUserDetailsAndLibrary()

    form.addEventListener("submit", (event) => { search(event, form, tableBody, userDetails, userLibrary) })
}

// Function to populate a select element with given entries array
const setSelection = (selectElement, entries) => {
    entries.forEach(entry => {
        const option = document.createElement("option")

        option.value = entry
        option.textContent = entry

        selectElement.appendChild(option)
    });
}

// Function to perform search and populate table with results
const search = async (event, form, tableBody, userDetails, userLibrary) => {
    event.preventDefault();

    // Empties the result table 
    tableBody.innerHTML = ""

    // Gets the entries from the form (currently only title)
    const data = Object.fromEntries(new FormData(form));

    const onlyTitle = titleSearchBoolean(data)

    // Searches the database
    const database = await fetchData(
        "/search-db/search.php",
        { type: "search", ...data }
    );

    // Adds resulting books to the table
    database.message.forEach(book => {
        tableBody.appendChild(bookToTableRow(book, userDetails, userLibrary))
    })

    // and create an a simpler array to compare to DMZ
    const dbCompare = database.message.map((book) => {
        return {
            title: book.title,
            year: JSON.stringify(book.year_published)
        }
    })

    // If only title is searched
    if (onlyTitle) {
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

            // Adds books that aren't in the database to it
            const addToDatabase = await fetchData(
                "/search-db/search.php",
                { type: "add", books: JSON.stringify(booksNotInDb) }
            );

            addToDatabase.books.forEach(book => {
                tableBody.appendChild(bookToTableRow(book, userDetails, userLibrary))
            })
    }
}

// If only title is searched
const titleSearchBoolean = (data) => {
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

    return onlyTitle
}

const bookToTableRow = (book, userDetails, userLibrary) => {
    // Arrays to coordinate the column with the data object
    const columnNames = ["cover", "title", "authors", "genres", "languages", "year published"]
    const bookKeys = ["cover_image_url", "title", "authors", "genres", "languages", "year_published"]

    const row = document.createElement("tr")

    // iterates through name and index
    // uses index to get the corresponding book data
    columnNames.forEach((name, index) => {
        const data = book[bookKeys[index]]

        row.appendChild(
            bookDataToTableCell(name, data, book, userDetails)
        )
    })

    // If book no in library, create a "add to library" button
    if (userLibrary && !userLibrary.includes(book.id)){
        row.appendChild(
            bookDataToTableCell("add button", null, book, userDetails)
        )
    }

    return row
}

const bookDataToTableCell = (name, data, bookData, userData) => {

    const cell = document.createElement("td")

    switch (name) {
        case "add button":
            const addToLibraryButton = document.createElement("button");
            addToLibraryButton.textContent = "Add To Library";
            
            addToLibraryButton.addEventListener("click", () => { addToLibrary(cell, bookData.id, userData.id)})

            cell.appendChild(addToLibraryButton);
        break
        case "cover":
            const coverElement = document.createElement("img")
            coverElement.src = data

            cell.appendChild(coverElement)
            break;

        case "genres":
        case "authors":
            if (typeof data === "string") {
                data = JSON.parse(data)
            }
            
            if (data) {
                // Join the array with and (i.e. A and B )
                cell.textContent = data.join(" and ")
            }
            break;

        case "title":
            cell.textContent = data
            cell.addEventListener("click", () => {
                document.querySelector("body").appendChild(
                    bookPopUp(bookData, userData)
                )
            })
            break;

        default:
            cell.textContent = data
            break;
    }
    
    return cell
}

const addToLibrary = async (parentElement, book_id, user_id) => {
    const response = await fetchData(
        "/search-db/search.php",
        {
            type: "add_to_library",
            user_id: user_id, 
            book_id: book_id 
        }
    )
    .then((data) => {
        parentElement.innerHTML = "";
    })
}

main()