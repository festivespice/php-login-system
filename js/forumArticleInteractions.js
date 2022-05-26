function userLikeDislike(groupId, groupName, articleId, userId, userOpinion){
    let = destinationURL = "forum-articles.php?group-id=" + groupId + "&group-name=" + groupName;
    if(userId != "" && userId != null){ // if the user isn't logged in
        if(userOpinion == 'like' || userOpinion == 'dislike' || userOpinion == 'liked' || userOpinion == 'disliked'){
            let scrollY = $(document).scrollTop();
            destinationURL = "./includes/forums/forum-articles-interaction.inc.php?group-id=" + groupId + "&group-name=" + groupName + "&article-id=" + articleId + "&user-opinion=" + userOpinion + "&scroll-y=" + scrollY;
        } 
    } else { //if the user hasn't logged in, make them aware. 
        let = destinationURL = "forum-articles.php?error=not-logged-in&group-id=" + groupId + "&group-name=" + groupName;
    }
    window.location.href = destinationURL; 
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
