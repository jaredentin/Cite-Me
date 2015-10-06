var names = [];

document.addEventListener('DOMContentLoaded', function(){
    chrome.runtime.onMessage.addListener(
        function(request, sender, sendResponse) {   
            if (request.greeting == "get database") {
                $.ajax({
                    crossDomain: true,
                    url: "http://54.208.46.230/php/Account/loginStatus.php",  
                    async: false,
                    type: "POST",  
                    dataType: "html",
                    success: function(data) {
                        if (data === "1"){
                            sendResponse({"populate": pullFromDatabase()});
                        }
                    }
                });
            }
        }
    ); 
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
        }
    });
    return names;
}


//Create Account
function submitCreateAccountForm(){
    if($("#new_uname") !== null && $("#new_pword") !== null && $("#new_first_name") !== null && $("#new_last_name") !== null) {
        if($("#new_uname").val() !== "" && $("#new_pword").val() !== "" && $("#new_first_name").val() !== "" && $("#new_last_name").val() !== "") {   
    
            $username = $("#new_uname").val();
            $password = $("#new_pword").val();
            $firstName = $("#new_first_name").val();
            $lastName = $("#new_last_name").val();

            var $form = $('#createAccountForm');
            var data = {
                username : $username,
                password : $password,
                firstName: $firstName,
                lastName: $lastName
            };

            data = $form.serialize() + ' &' + $.param(data);
            
            
            $.ajax({
                crossDomain: true,
                url: "http://54.208.46.230/php/Account/createAccount.php",  
                async:  false,
                type: "GET",  
                data: data,
                dataType: "html",
                success: function(data) {
                },
                error: function (data) { 
                    console.log(data);
                }  
            });
            
        } else {
            alert("Empty");   
        }
    } else {
        alert("Empty");   
    }
}

//Create group
function submitCreateGroupForm() {
    if(!($("#gname").val() === "")) {
        $groupName = $("#gname").val();
        var $form = $('#create_group_form');
        var data = {
            groupName : $groupName
        };
        data = $form.serialize() + ' &' + $.param(data);
        
        $.ajax({
            crossDomain: true,
            url: "http://54.208.46.230/php/createGroup.php",  
            async: false,
            type: "GET",  
            data: data,
            dataType: "html",
            success: function(data) {
                //$("#createGroupResponse").html(data);
               // window.setTimeout(function(){window.location.reload()}, 1500);
            },
        });
    }
}

//View Your Quotes
function viewQuotes() {
    $.ajax({
        crossDomain: true,
        url: "http://54.208.46.230/php/viewQuotes.php",  
        async: false,
        type: "POST",  
        dataType: "html",
        success: function(data) {
            $("#getAllQuotes").html(data);
           // window.setTimeout(function(){window.location.reload()}, 1500);
        },
    });
}