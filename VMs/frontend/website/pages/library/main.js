import { getUser } from "/api/authentication.js"
import { getBooks } from "/api/search_db.js"
import { bookToTableRow } from "/javascript/bookTable.js"
import { SESSION_ID_COOKIE_NAME } from "/javascript/defaults.js"
import { getCookies } from "/javascript/helpers.js"

const main = async () => {
	const tableBody = document.querySelector("tbody")

	// awaits for authentication, either success or [null null]
	const [userDetails, userLibrary] = await getUser({ sessionId: getCookies(SESSION_ID_COOKIE_NAME) })
		.then(data => [data.userDetails, data.userLibrary])
		.catch(error => [null, null])

	console.log(userLibrary)
	const userLibraryBookIds = userLibrary.map(book => book.book_id);

	console.log(userLibraryBookIds)

	const books = await getBooks({ bookIds: userLibraryBookIds })

	books.forEach(book => {
		tableBody.appendChild(bookToTableRow(book, userDetails, userLibrary))
	});
}

main()