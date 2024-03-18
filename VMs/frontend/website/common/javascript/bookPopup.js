import { fetchData } from "./helpers.js";

export const bookPopUp = (bookData, userData) => {
    const container = document.createElement("div")
    const content = document.createElement("div")
    
    container.classList.add("popup")
    content.classList.add("book-content")

    container.addEventListener("click", (e) => {
        if(e.target == container){
            container.remove()
        }
    })
    
    console.log(bookData, userData)

    const authors = bookData.authors
    const cover_image_url = bookData.cover_image_url
    const date_added = bookData.date_added
    const description = bookData.description
    const genres = bookData.genres
    const id = bookData.id
    const languages = bookData.languages
    const title = bookData.title
    const year_published = bookData.year_published

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
    
    const reviewsContainer = document.createElement("div")
    reviewsContainer.id = "reviews"
    const reviewsInput = reviewsInputFunction(bookData.id, userData.id, userData.username, reviewsContainer, content)

    const discussionContainer = document.createElement("div")
    discussionContainer.id = "discussion"
    const discussionInput = discussionInputFunction(bookData.id, userData.id, userData.username, discussionContainer, content)

    fillDiscussion(discussionContainer, content, discussionInput, bookData.id)

    content.childNodes.contains
    content.appendChild(imageElement)
    content.appendChild(titleElement)
    content.appendChild(descriptionElement)
    content.appendChild(genresElement)
    content.appendChild(languagesElement)
    content.appendChild(year_publishedElement)
    content.appendChild(reviewHeading)
    content.appendChild(reviewsInput)
    content.appendChild(discussionHeading)
    content.appendChild(discussionInput)
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
const discussionInputFunction = (book_id, user_id, username, discussionContainer, parent) => {
    console.log(parent)
    const container = document.createElement("div")

    const textInput = document.createElement("textarea")
    const submitButton = document.createElement("button")
    submitButton.textContent = "Submit"
    
    container.appendChild(textInput)
    container.appendChild(submitButton)

    submitButton.addEventListener("click",() => {
        const fetchingData = {
            book_id: book_id,
            user_id: user_id,
            username: username,
            comment: textInput.value,
        }

        if(container.getAttribute("reply-to")){
            fetchingData.type = "reply_comment"
            fetchingData.reply_to_id = JSON.parse(container.getAttribute("reply-to"))
        }
        else {
            fetchingData.type = "add_comment"
        }

        console.log(fetchingData)

        fetchData("/discussion/main.php", fetchingData)
        .then((data) => {
            textInput.value = ""
            fillDiscussion(discussionContainer, parent, container, book_id)
        })
    } )
    return container
}

const fillDiscussion = (discussionContainer, parent, discussionInput, book_id) => {
    console.log(parent)

    fetchData("/discussion/main.php", 
    {
        type: "get_comments",
        id: book_id
    })
    .then((data) => {
        discussionContainer.innerHTML = ""

        console.log(discussionContainer)
        data.discussions.forEach(async discussion => {
            let replied_comment = null
            let repliedElement = null;

            if (discussion.reply_to_id){
                replied_comment = await fetchData("/discussion/main.php", {
                    type: "get_comment_by_id",
                    id: discussion.reply_to_id
                })

                repliedElement = document.createElement("div");
                repliedElement.textContent = `replying to: ${replied_comment.discussion.username}: ${replied_comment.discussion.comment}`
                console.log(replied_comment)
            }
            const commentContainer = document.createElement("div")

            const book_id = discussion.book_id
            const comment = discussion.comment
            const created_at = discussion.created_at
            const user_id = discussion.user_id
            const username = discussion.username
            

            const usernameElement = document.createElement("p")
            usernameElement.textContent = username
            const commentElement = document.createElement("p")
            commentElement.textContent = comment
            const timestampElement = document.createElement("p")
            timestampElement.textContent = created_at
            const replyToButton = document.createElement("button")
            replyToButton.textContent = "Reply To"
            replyToButton.addEventListener("click", () => {
                discussionInput.setAttribute("reply-to", discussion.id)
            })

            commentContainer.appendChild(usernameElement)
            if(repliedElement){
                commentContainer.appendChild(repliedElement)
            }
            commentContainer.appendChild(commentElement)
            commentContainer.appendChild(timestampElement)
            commentContainer.appendChild(replyToButton)

            discussionContainer.appendChild(commentContainer)
        });

        parent.appendChild(discussionContainer)
    })
}

// Todo: Reviews
// * Finish review input
// * add service for "reviews" that adds a review
// * add scripts that interact with service for "reviews"
// * add service functionality to get review
// * display entries
// input for rating
// input for comment
const reviewsInputFunction = (parent) => {
    console.log(parent)
    const container = document.createElement("div")

    const textInput = document.createElement("textarea")
    const submitButton = document.createElement("button")
    submitButton.textContent = "Submit"
    
    container.appendChild(textInput)
    container.appendChild(submitButton)
    return container
}


