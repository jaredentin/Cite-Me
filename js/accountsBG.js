function submitSigninForm(){
    var response = ajaxFormSubmission($('#signInForm'));
    if (response == "Logged in") {
        
        changePopup("dashboard.html", function(){
            chrome.browserAction.setIcon({
                path: "../img/icon_in.png"
            });
        });
        
        localStorage.setItem("notificationMsg", response);
    } else {
        localStorage.setItem("indexMessage", response);
        location.reload();
    }
}

function logout(){
    $.ajax({
        crossDomain: true,
        url: 'http://54.208.46.230/php/Account/logout.php',
        async: false,
        type: 'POST',
        success: function(msg) {
            localStorage.setItem("indexMessage", msg);
            changePopup("index.html", function(){
                chrome.browserAction.setIcon({
                    path: "../img/icon_out.png"
                });
                chrome.browserAction.setBadgeText({
                    text: ""
                });
                chrome.browserAction.setBadgeBackgroundColor({
                    color: "#000000"
                });
            });
        }
    });
}

function changeAccountPassword(){
    if ($("#newPassword").val() === $("#newPasswordConfirm").val()){
        var returnMessage = ajaxFormSubmission($("#changePasswordForm"));
        localStorage.setItem("notificationMsg", returnMessage);
        location.reload();
    } else {
        localStorage.setItem("notificationMsg", "Passwords don't match");
        location.reload();
    } 
}

function changeAccountName(){
    if ($("#firstName").val() !== "" &&  $("#lastName").val() !== ""){
        returnMessage = ajaxFormSubmission($('#changeNameForm'));
        if (returnMessage === "Name updated"){
            localStorage.setItem("notificationMsg", returnMessage);
            location.reload();
        }
    } else {
        localStorage.setItem("notificationMsg", "Field(s) empty");
        location.reload();
    }
}

function deleteAccount(){
    $.ajax({
        crossDomain: true,
        url: 'http://54.208.46.230/php/Account/deleteAccount.php',
        async: false,
        type: 'POST',
        success: function(data) {
            localStorage.setItem("indexMessage", data);
            parent.changePopup("index.html", function(){
                chrome.browserAction.setIcon({
                    path: "../img/icon_out.png"
                });
            });
        },
        error: function(data){
            console.log(data);   
        }
    });
}

//Redirect to new page
function changePopup(address, callback){
    chrome.browserAction.setPopup({
        popup: address
    });
    
    callback();
    window.location.replace(address);
}