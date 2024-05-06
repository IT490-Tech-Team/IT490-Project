import { getUser } from "/api/authentication.js"
import { getBooks } from "/api/search_db.js"
import { bookToTableRow } from "/javascript/bookTable.js"
import { SESSION_ID_COOKIE_NAME } from "/javascript/defaults.js"
import { getCookies } from "/javascript/helpers.js"

const main = async () => {
	const body = document.querySelector("body")
	

	// awaits for authentication, either success or [null null]
	const [userDetails, userLibrary] = await getUser({ sessionId: getCookies(SESSION_ID_COOKIE_NAME) })
		.then(data => [data.userDetails, data.userLibrary])
		.catch(error => [null, null])

	const userLibraryBookIds = userLibrary.map(book => book.book_id);

	const books = await getBooks({ bookIds: userLibraryBookIds })

	// Extract categories
	const uniqueCategories = [...new Set(userLibrary.map(item => item.category))];
	
	uniqueCategories.forEach((category) => {
        const userLibraryByCategory = userLibrary.filter(book => book.category == category);
		const bookIds = userLibraryByCategory.map(library_entry => library_entry.book_id)

		const categoryBooks = books.filter(book => bookIds.includes(book.id))
		console.log(categoryBooks)
        createTable(body, category, categoryBooks, userDetails, userLibrary);
	})

}

const createTable = (parent, category, books, userDetails, userLibrary) => {
	const header = document.createElement("h1")
	header.textContent = category ?? "Uncategorized"

	const table = document.createElement("table")
	table.innerHTML = `
		<thead>
		<tr>
			<td id="cover">cover</td>
			<td id="title">title</td>
			<td id="authors">authors</td>
		</tr>
	</thead>
	<tbody id="results"></tbody>
	`

	const tableBody = table.querySelector("tbody")

	books.forEach(book => {
		tableBody.appendChild(bookToTableRow(book, userDetails, userLibrary))
	});

	parent.appendChild(header)
	parent.appendChild(table)
	console.log(category, books)
}

main()