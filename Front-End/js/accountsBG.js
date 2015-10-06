//Sign in
function submitSigninForm(){
    if(!($("#uname").val() === "" && $("#pword").val() === "")) {
        $username = $("#uname").val();
        $password = $("#pword").val();

        var $form = $('#signin_form');
        var data = {
            username : $username,
            password : $password
        };

        data = $form.serialize() + ' &' + $.param(data);

        $.ajax({
            crossDomain: true,
            url: "http://54.208.46.230/php/Account/login.php",  
            async: false,
            type: "GET",  
            data: data,
            dataType: "html",
            success: function(data) {
                if (data == "1") {
                    console.log(data);
                    //window.location.href = "dashboard.html";
                    changePopup("dashboard.html");
                    window.close();
                    
                }
            }, error: function(data){
                console.log(data);
            }
        });
    } else {
        alert("Empty");   
    }
}

//Logout
function logout(){
    $.ajax({
        crossDomain: true,
        url: 'http://54.208.46.230/php/Account/logout.php',
        async: false,
        type: 'POST',
        success: function(msg) {
            changePopup("index.html");
        }
    });
}

//Get name of current user
function getLoggedInUser(){
    $.ajax({
        crossDomain: true,
        url: 'http://54.208.46.230/php/getUser.php',
        async: false,
        type: 'POST',
        success: function(msg) {
            if (msg !== "Not logged in") {
                $(".welcome_msg").html("Welcome, " + msg + "!");
            }
        }
    });
}

//Redirect to new page
function changePopup(address){
    chrome.browserAction.setPopup({
        popup: address
    });
    window.location.href = address;
}