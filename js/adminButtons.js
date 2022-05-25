//a closed page or article cannot be replied to or have any user interaction.
//a deleted group, article, or item cannot be viewed, although a deleted item will have [content deleted]

function administrativePage(moderatedId, groupId, groupName, pageType){
    let destinationURL;
    console.log(pageType);
    if(pageType == 'delete' || pageType == 'restore' || pageType == 'close' || pageType == 'open'){
        destinationURL = "forum-group-action.php?moderated-id=" + moderatedId + "&group-id=" + groupId + "&group-name=" + groupName + "&page-type=" + pageType;
        window.location.href = destinationURL; 
    } else {
        destinationURL = "forums.php";
        window.location.href = destinationURL; 
    }
}

function administrativePageArticle(articleId, moderatedId, groupId, groupName, articleName, pageType){
    let destinationURL;
    console.log(pageType);
    if(pageType == 'delete' || pageType == 'restore' || pageType == 'close' || pageType == 'open'){
        destinationURL = "forum-group-action.php?article-id=" + articleId + "&moderated-id=" + moderatedId + "&group-id=" + groupId + "&group-name=" + groupName + "&article-name=" + articleName + "&page-type=" + pageType;
        window.location.href = destinationURL; 
    } else {
        destinationURL = "forums-articles.php?group-id=" + groupId + "&group-name=" + groupName;
        window.location.href = destinationURL; 
    }
}

function administrativePageItem(itemId, moderatedId, articleId, groupId, groupName, pageType){

}