//functions


function changeCurrentIcon() {
    const currentTab = document.URL;
    let currentElement; 

    if(currentTab.includes("profile")){
        currentElement = document.querySelector('#profile');
    }
    if(currentTab.includes("signin")){
        currentElement = document.querySelector('#signin');
    }
    if(currentTab.includes("signup")){
        currentElement = document.querySelector('#signup');
    }
    if(currentTab.includes("forums")){
        currentElement = document.querySelector('#forums');
    }
    if(currentTab.includes("discover")){
        currentElement = document.querySelector('#discover');
    }
    if(currentTab.includes("index")){
        currentElement = document.querySelector('#index');
    }

    if(currentElement != null){
        currentElement.classList.add('active')
    }
}   

//execution space
changeCurrentIcon();