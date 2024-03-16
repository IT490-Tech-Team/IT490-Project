

export const bookPopUp = (data) => {
    const container = document.createElement("div")
    const content = document.createElement("div")
    
    container.classList.add("popup")
    content.classList.add("book-content")

    container.addEventListener("click", (e) => {
        if(e.target == container){
            container.remove()
        }
    })
    
    console.log(data)

    const authors = data.authors
    const cover_image_url = data.cover_image_url
    const date_added = data.date_added
    const description = data.description
    const genres = data.genres
    const id = data.id
    const languages = data.languages
    const title = data.title
    const year_published = data.year_published

    const imageElement = document.createElement("img")
    imageElement.src = cover_image_url

    const titleElement = document.createElement("h1")
    titleElement.textContent = title

    const descriptionElement = document.createElement("p")
    descriptionElement.textContent = description

    const genresElement = document.createElement("p")
    genresElement.textContent = genres

    const languagesElement = document.createElement("p")
    languagesElement.textContent = languages

    const year_publishedElement = document.createElement("p")
    year_publishedElement.textContent = year_published

    
    const discussionHeading = document.createElement("h1")
    discussionHeading.textContent = "Discussion"
    const reviewHeading = document.createElement("h1")
    reviewHeading.textContent = "Reviews"

    content.appendChild(imageElement)
    content.appendChild(titleElement)
    content.appendChild(descriptionElement)
    content.appendChild(genresElement)
    content.appendChild(languagesElement)
    content.appendChild(year_publishedElement)
    content.appendChild(reviewHeading)
    content.appendChild(discussionHeading)


    container.appendChild(content)

    return container
}

// Todo: Discussion
// * Finish discussion input
// * add service for "discussion" that adds a discussion comment
// * add scripts that interact with service for "discussion"
// * add service functionality to get discussion
// * display entries
// text input for comment section
const discussionInput = () => {
    const container = document.createElement("div")

}

// Todo: Reviews
// * Finish review input
// * add service for "reviews" that adds a review
// * add scripts that interact with service for "reviews"
// * add service functionality to get review
// * display entries
// input for rating
// input for comment
const reviewInput = () => {
    const container = document.createElement("div")
}


