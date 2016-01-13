var names = [];
document.addEventListener('DOMContentLoaded', function(){
    displayWelcomePageData();
    chrome.runtime.onMessage.addListener(
        function(request, sender, sendResponse) {
            if (request.greeting == "get database") {
                $.ajax({
                    crossDomain: true,
                    url: "http://54.208.46.230/php/Account/loginStatus.php",  
                    async: false,
                    type: "POST",  
                    success: function(data) {
                        if (data === "1"){
                            sendResponse({"populate": pullFromDatabase()});
                        }
                    },
                    error: function(data){
                        console.log(data); 
                    }
                });
            }
        }
    ); 
    
    if (parent.document.getElementById("notificationMsg")){
        var msg = localStorage.getItem("notificationMsg");    
        parent.document.getElementById("notificationMsg").innerHTML = msg;   
        
        setTimeout(function(){
            parent.document.getElementById("notificationMsg").innerHTML = "";
            localStorage.setItem("notificationMsg", "");
        }, 1500);
    } else {
        localStorage.setItem("notificationMsg", "");
    }
    
    if (document.getElementById("indexMessage")){
        var msg = localStorage.getItem("indexMessage");    
        document.getElementById("indexMessage").innerHTML = msg;
        
        setTimeout(function(){
            document.getElementById("indexMessage").innerHTML = "";
            localStorage.setItem("indexMessage", "");
        }, 1500);
    } else {
        localStorage.setItem("indexMessage", "");
    }
    
    if (parent.document.getElementById("createAccountMsg")){
        var msg = localStorage.getItem("createAccountMsg");    
        parent.document.getElementById("createAccountMsg").innerHTML = msg;
        
        setTimeout(function(){
            parent.document.getElementById("createAccountMsg").innerHTML = "";
            localStorage.setItem("createAccountMsg", "");
        }, 1500); 
    } else {
        localStorage.setItem("createAccountMsg", "");
    }
});

function pullFromDatabase(){
    $.ajax({
        crossDomain: true,
        url: "http://54.208.46.230/php/pullFromDatabase.php",  
        async: false,
        type: "POST",  
        dataType: "json",
        success: function(data) {
            var firstName;
            var lastName;
            var quote;
            for (var i = 0; i < data.length; i++) {
                var users = [];
                firstName = data[i][0];
                lastName = data[i][1];
                quote = data[i][2];
                
                users.push(firstName, lastName, quote);
                names.push(users);    
            }
        }, error: function(data) {
            console.log(data);
        }
    });
    return names;
}

function displayWelcomePageData(){
        $.ajax({
        crossDomain: true,
        url: "http://54.208.46.230/php/welcome.php",  
        async: false,
        type: "POST",  
        dataType: "json",
        success: function(data) {
            var firstName = data[0];
            var numGroupInvites = data[1];
            var numGroupRequests = data[2];
            var numQuoteRequests = data[3];
            totalNotifications = numGroupInvites + numGroupRequests + numQuoteRequests;
        
            chrome.browserAction.setBadgeText({
                text: String(totalNotifications)
            });
         
            chrome.browserAction.setBadgeBackgroundColor({
                color: "#000000"
            });
            $("#welcomeName").html("Welcome, " + firstName);
            
            $("#welcomeInfo1").html("Group Requests: " + numGroupRequests);
            $("#welcomeInfo2").html("Group Invites: " + numGroupInvites);
            $("#welcomeInfo3").html("Quote Requests: " + numQuoteRequests);
        }, error: function(data) {
            console.log(data);
        }
    });
}


//Create Account
function submitCreateAccountForm(){
    if ($("#password").val() === $("#confirmPassword").val()){
        var msg = ajaxFormSubmission($('#createAccountForm'));
        localStorage.setItem("createAccountMsg", msg);
        if (msg === "Account created") {
            window.location.href = "../index.html";
            localStorage.setItem("indexMessage", "Account created");
        } else {
            location.reload(); 
        }
    } else {
        localStorage.setItem("createAccountMsg", "Passwords don't match");
        location.reload();
    }
}

function ajaxFormSubmission(submittedForm){
    var ajaxResponse;
    if (submittedForm.serializeArray().length > 0){
        $.ajax({
            crossDomain: true,
            url: submittedForm.attr("action"),  
            type: "POST",
            async: false,
            data: submittedForm.serializeArray(),
            success: function(data) {
                console.log(data);
                ajaxResponse = data;
            },
            error: function (data) {
                console.log(data);
                ajaxResponse = data;
            }
        });
    } else {
        ajaxResponse = "Empty";
        console.log("Empty");
    }
    return ajaxResponse;
}

//Create group
function submitCreateGroupForm() {
    var returnMessage = ajaxFormSubmission($('#createGroupForm'));
    localStorage.setItem("notificationMsg", returnMessage);
    if (returnMessage === "Group Created"){ 
        window.location.href = "../welcome.html";
    }
        location.reload();   
}

//View Your Quotes
function viewQuotes() {
    $.ajax({
        crossDomain: true,
        url: "http://54.208.46.230/php/viewQuotes.php",  
        async: false,
        type: "POST",  
        success: function(data) {
            $("#getAllQuotes").html(data);
        },
        error: function(data){
            console.log(data);
        }
    });
}