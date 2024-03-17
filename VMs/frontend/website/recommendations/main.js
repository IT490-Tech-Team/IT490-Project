import { authenticate } from "../common/javascript/authenticate.js"
import { SESSION_ID_COOKIE_NAME } from "../common/javascript/defaults.js"
import { fetchData, getCookies } from "../common/javascript/helpers.js"
import { bookPopUp } from "../common/javascript/bookPopup.js";

const emptyRecommendations = (container) => {
    const text = document.createElement("h1")

    text.textContent = "Please add to your library to get recommendations."

    container.appendChild(text)
}

const recommendations = async (container, data, userDetails) => {
    const text = document.createElement("h1")

    text.textContent = "Here are your recommendations: "

    container.appendChild(text)

    const bookIds = data.map(libraryEntry => {
        return libraryEntry.book_id
    });

    const fetchedBooks = await fetchData(
        "/search-db/search.php",
        {
            type: "get_books",
            book_ids: JSON.stringify(bookIds)
        })

    const genres = []

    fetchedBooks.message.forEach(book => {
        const fetchedGenres = JSON.parse(book.genres)
        fetchedGenres.forEach(genre => {
            if (!(genres.includes(genre))){
                genres.push(genre)
            }
        })
    });

    genres.forEach((genre) => {
        recommendationSection(container, genre, userDetails)
    })
}

const recommendationSection = async (parent, genre, userDetails) => {
    const container = document.createElement("div")
    
    const text = document.createElement("h2")
    text.textContent = `Here's our recommendations based on: ${genre}`
    
    const section = document.createElement("div")
    section.classList.add("section")

    const database = fetchData(
        "/search-db/search.php",
        { type: "search", genre: genre }
    )
    .then(data => {
        console.log(data.message.forEach((data) => {
            addBookToSection(section, data, userDetails)
        }))
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

const contentContainer = document.querySelector("div#content")

authenticate({ type: "get_user", sessionId: getCookies(SESSION_ID_COOKIE_NAME) })
.then((data) => {
    const userLibrary = data.userLibraries
    const userDetails = data

    if(userLibrary.length > 0){
        recommendations(contentContainer, data.userLibraries, userDetails)
    }
    else {
        emptyRecommendations(contentContainer)
    }

})