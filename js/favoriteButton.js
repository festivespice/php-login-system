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

function favoriteGroup(groupId, userId){
    console.log(groupId);
    console.log(userId);
    let y = $(document).scrollTop();
    let destinationURL = "./Includes/forums/forumsFavorite.inc.php?group-id=" + groupId +"&user-id=" + userId + "&scrollY=" + y;
    window.location.href = destinationURL; 
}

applyScroll();