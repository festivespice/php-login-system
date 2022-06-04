function userLikeDislikeArticle(groupId, groupName, articleId, articleName, userId, userOpinion, source){ //a name is not needed
    let = destinationURL = "forum-articles.php?group-id=" + groupId + "&group-name=" + groupName;
    console.log("what");
    if(userId != "" && userId != 0){ // if the user isn't logged in
        console.log("no!!!");
        if(userOpinion == 'like' || userOpinion == 'dislike' || userOpinion == 'liked' || userOpinion == 'disliked'){
            let scrollY = $(document).scrollTop();
            destinationURL = "./includes/forums/forum-articles-interaction.inc.php?group-id=" + groupId + "&group-name=" + groupName + "&article-id=" + articleId + "&article-name=" + articleName + "&user-opinion=" + userOpinion + "&source=" + source + "&scroll-y=" + scrollY;
        } 
    } else { //if the user hasn't logged in, make them aware. 
        let = destinationURL = "forum-articles.php?error=not-logged-in&group-id=" + groupId + "&group-name=" + groupName;
    }
    window.location.href = destinationURL; 
}


function userLikeDislikeItem(groupId, groupName, articleId, articleName, itemId, userId, userOpinion){ //a name is needed for the url of an article.
    let = destinationURL = "forum-articles.php?group-id=" + groupId + "&group-name=" + groupName;
    if(userId != "" && userId != 0){ // if the user isn't logged in
        if(userOpinion == 'like' || userOpinion == 'dislike' || userOpinion == 'liked' || userOpinion == 'disliked'){
            let scrollY = $(document).scrollTop();
            destinationURL = "./includes/forums/forum-items-interaction.inc.php?group-id=" + groupId + "&group-name=" + groupName + "&article-id=" + articleId + "&item-id=" + itemId + "&user-opinion=" + userOpinion + "&scroll-y=" + scrollY;
        } 
    } else { //if the user hasn't logged in, make them aware. 
        let = destinationURL = "forum-articles.php?error=not-logged-in&group-id=" + groupId + "&group-name=" + groupName;
    }
    window.location.href = destinationURL; 
}

function openHiddenForm(objectId, objectType){
    if(objectType == "article"){
        htmlForm = document.getElementById("article"+objectId);
        htmlForm.style.display="flex";
    }
}

function getJsonFromUrl(url) 
{
    if(!url) url = location.search;
    var query = url.substr(1);
    var result = {};
    query.split("&").forEach(function(part) {
        var item = part.split("=");
        result[item[0]] = decodeURIComponent(item[1]);
    });
    return result;
}


function applyScroll(){
    const queryString =  window.location.search;
    const parameters = getJsonFromUrl(queryString);
    if(parameters.scrollY){
        $(document).scrollTop(parameters.scrollY);
    }
}

applyScroll();
