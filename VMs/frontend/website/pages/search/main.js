import { getUser } from "/api/authentication.js"
import { addBooksToDatabase, getFilters, searchDatabaseBooks } from "/api/search_db.js"
import { searchExternalBooks } from "/api/search_dmz.js"
import { bookToTableRow } from "/javascript/bookTable.js"
import { SESSION_ID_COOKIE_NAME } from "/javascript/defaults.js"
import { arrayIncludesObject, getCookies } from "/javascript/helpers.js"

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

	if(onlyTitle){
        const externalBooks = await searchExternalBooks({
            ...data
        })

        const booksNotInDb = []

        externalBooks.forEach(book => {
            if (!arrayIncludesObject(dbCompare, {title: book.title, year: book.year_published})){
                booksNotInDb.push(book)
            }
        })

        const booksAdded = await addBooksToDatabase({books: booksNotInDb})

        booksAdded.forEach(book => {
            console.log(book)
            tableBody.appendChild(bookToTableRow(book, userDetails, userLibrary))
        });

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



main()