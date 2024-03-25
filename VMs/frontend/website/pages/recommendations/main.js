import { getUser } from "/api/authentication.js"
import { getBooks, searchDatabaseBooks } from "/api/search_db.js"
import { bookPopUp } from "/javascript/bookPopup.js"
import { SESSION_ID_COOKIE_NAME } from "/javascript/defaults.js"
import { getCookies } from "/javascript/helpers.js"

const main = async () => {
	const contentContainer = document.querySelector("div#content")

	const [userDetails, userLibrary] = await getUser({ sessionId: getCookies(SESSION_ID_COOKIE_NAME) })
		.then(data => [data.userDetails, data.userLibrary])
		.catch(error => [null, null])

	if (userLibrary.length > 0) {
		fillRecommendations(contentContainer, userDetails, userLibrary)
	}
	else {
		emptyRecommendations(contentContainer)
	}
}


const emptyRecommendations = (container) => {
	const text = document.createElement("h1")

	text.textContent = "Please add to your library to get recommendations."

	container.appendChild(text)
}


const fillRecommendations = async (container, userDetails, userLibrary) => {
	const text = document.createElement("h1")
	text.textContent = "Here are your recommendations: "
	container.appendChild(text)

	const bookIds = userLibrary.map(library => library.book_id)

	const fetchedBooks = await getBooks({bookIds})

	console.log(fetchedBooks)

	const genres = []
	fetchedBooks.forEach(book => {
		const fetchedGenres = JSON.parse(book.genres)
		fetchedGenres.forEach(genre => {
			if(!genres.includes(genre)){
				genres.push(genre)
			}
		})
	});

	genres.forEach(genre => {
		genreRecommendations(container, genre, userDetails)
	})
}

const genreRecommendations = async (parent, genre, userDetails) => {
	const container = document.createElement("div")
    
    const text = document.createElement("h2")
    text.textContent = `Here's our recommendations based on: ${genre}`
    
    const section = document.createElement("div")
    section.classList.add("section")

	const books = searchDatabaseBooks({genre: genre})
	.then(book => {
		book.forEach((data) => {
            addBookToSection(section, data, userDetails)
        })
    })

    container.appendChild(text)
    container.appendChild(section)

    parent.appendChild(container)
}

const addBookToSection = (parent, data, userDetails) => {
    const book = document.createElement("div")
    book.classList.add("book")

    const imageElement = document.createElement("img")
    imageElement.src = data.cover_image_url

    const titleElement = document.createElement("p")
    titleElement.textContent = data.title ?? ""

    const authorElement = document.createElement("p")
    authorElement.textContent = data.authors ?? ""

    console.log(data.id)
    console.log(userDetails)
    book.addEventListener("click", () => {
        document.querySelector("body").appendChild(bookPopUp(data, userDetails))
    })

    book.appendChild(imageElement)
    book.appendChild(titleElement)
    book.appendChild(authorElement)

    parent.appendChild(book)
}

main()