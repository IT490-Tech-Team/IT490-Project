import { getUser } from "/api/authentication.js"
import { addBookToUserLibrary, getFilters, searchDatabaseBooks } from "/api/search_db.js"
import { bookPopUp } from "/javascript/bookPopup.js"
import { SESSION_ID_COOKIE_NAME } from "/javascript/defaults.js"
import { getCookies } from "/javascript/helpers.js"

const main = async () => {
	const form = document.querySelector("form")
	const tableBody = document.querySelector("tbody")

	// Fetch filters and populate the form select elements for genre and language async
	getFilters()
	.then(data => {
		const genres = data.genres
		const languages = data.languages

		const genreSelection = document.querySelector("select#genre")
		const languageSelection = document.querySelector("select#language")

		setSelection(genreSelection, genres)
		setSelection(languageSelection, languages)
	})

	// awaits for authentication, either success or [null null]
	const [userDetails, userLibrary] = await getUser({sessionId: getCookies(SESSION_ID_COOKIE_NAME)})
	.then(data => [data.userDetails, data.userLibrary])
	.catch(error => [null, null])

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

const search = async (event, form, tableBody, userDetails, userLibrary) => {
	event.preventDefault();

	tableBody.innerHTML = ""

	const data = Object.fromEntries(new FormData(form))

	const onlyTitle = titleSearchBoolean(data)

	const databaseBooks = await searchDatabaseBooks(data)

    // Adds resulting books to the table
    databaseBooks.forEach(book => {
        tableBody.appendChild(bookToTableRow(book, userDetails, userLibrary))
    })

    // and create an a simpler array to compare to DMZ
    const dbCompare = databaseBooks.map((book) => {
        return {
            title: book.title,
            year: JSON.stringify(book.year_published)
        }
    })

	console.log(dbCompare)
	if(onlyTitle){

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
            cell.addEventListener("click", async () => {
				console.log(bookData)
				const book = bookPopUp(bookData, userData)
                document.querySelector("body").appendChild(book)

            })
            break;

        default:
            cell.textContent = data
            break;
    }
    
    return cell
}

const addToLibrary = async (parentElement, book_id, user_id) => {
    addBookToUserLibrary({book_id, user_id})
    .then((data) => {
        parentElement.innerHTML = "";
    })
}

main()