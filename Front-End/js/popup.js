var t = [];

$(document).ready(function(){
    if (document.getElementById("createAccountSubmit")) {
        document.getElementById("createAccountSubmit").addEventListener('click', function(){
            submitCreateAccountForm(); 
        });
    }
    
    if (document.getElementById("signin_button")) {
        document.getElementById("signin_button").addEventListener('click', function(){
            submitSigninForm(); 
        });
    }
    
    if (document.getElementById("create_group_button")) {
        document.getElementById("create_group_button").addEventListener('click', function(){
            submitCreateGroupForm();
        });
    }
    
    if (document.getElementById("logout_button")){
        document.getElementById("logout_button").addEventListener('click', function(){
            logout();
        });
    }
    
    if (document.getElementById("getQuoteRequests")){
        getQuoteRequests();
    }
    
    if (document.getElementById("getGroupRequests")){
        getGroupRequests();
    }
    
    if (document.getElementById("getGroupInvites")){
        getGroupInvites();
    }
    
    if (document.getElementById("getAllQuotes")){
        viewQuotes();
    }
    
    //Send Quote Requests
    if (document.getElementById("select_group_form")){
        quote_populateWithGroups();
        document.getElementById("quotes_group_name_select").addEventListener('change', function(){
            $("#result").html("");
            if (document.getElementById("user_name_select")){
                document.getElementById("user_name_select").disabled = false;
                document.getElementById("quote_input").disabled = true;
                document.getElementById("quote_input").value = '';
                var groupSelected = document.getElementById("quotes_group_name_select").options[document.getElementById("quotes_group_name_select").selectedIndex].text;
                populateWithUsers(groupSelected);
                document.getElementById("user_name_select").addEventListener('change', function(){
                    $("#result").html("");
                    if (document.getElementById("quote_input")){
                        document.getElementById("quote_input").disabled = false;
                        document.getElementById("quote_input").value = '';

                        var userSelected = document.getElementById("user_name_select").options[document.getElementById("user_name_select").selectedIndex].text;
                        
                        document.getElementById("quote_input").addEventListener('change', function(){
                            $("#result").html("");
                            document.getElementById("select_group_form").addEventListener('submit', function(e){
                                if (!(document.getElementById("quote_input").value == '' && document.getElementById("quote_input").value == document.getElementById("quote_input").defaultValue)) {            
                                    
                                    //var quoteSubmitted = false;
                                    submitQuoteRequest(groupSelected, userSelected, document.getElementById("quote_input").value, quoteSubmitted);
                                    e.preventDefault();
                                }
                            });   
                        });
                    }    
                });      
            }
        });
    }
    
    //Send Group Requests to 1 User
    if (document.getElementById("invite_to_group_form")){
        group_populateWithGroups();
        document.getElementById("toUser_groups_group_name_select").addEventListener('change', function(){
            var groupSelected = document.getElementById("toUser_groups_group_name_select").options[document.getElementById("toUser_groups_group_name_select").selectedIndex].text;
            $("#result").html("");
            if (document.getElementById("toUser_group_request_submit")){
                document.getElementById("toUser_group_request_submit").disabled = false;
                document.getElementById("invite_to_group_form").addEventListener('submit', function(event){
                    
                    
                    toUser_submitGroupRequest(groupSelected, event);
              
                 //   $(this).unbind('submit').submit();
                });
            }
        });
    }
    
    //Send Group Requests to all Users in Group
    if (document.getElementById("ask_to_group_form")){
        document.getElementById("ask_to_group_form").addEventListener('submit', function(event){ 
            toGroup_submitGroupRequest(event);
        });
    }
    
    
    
    for(var i=0;i<document.getElementsByClassName("quote_accept").length;i++){
        document.getElementsByClassName("quote_accept")[i].addEventListener('click', function(){
            acceptQuoteRequest(this.getAttribute("name"));
            $("#quoteResponse").html("Quote Accepted");
            window.setTimeout(function(){window.location.reload()}, 1500);
        });
    }
    
    for(var i=0;i<document.getElementsByClassName("quote_deny").length;i++){
        document.getElementsByClassName("quote_deny")[i].addEventListener('click', function(){
            denyQuoteRequest(this.getAttribute("name"));
            $("#quoteResponse").html("Quote Denied");
            window.setTimeout(function(){window.location.reload()}, 1500);
            
        });
    }
    
    for(var i=0;i<document.getElementsByClassName("group_accept").length;i++){
        document.getElementsByClassName("group_accept")[i].addEventListener('click', function(){
            acceptGroupRequest(this.getAttribute("name"));
            $("#groupResponse").html("Group Accepted");
            window.setTimeout(function(){window.location.reload()}, 1500);
            
        });
    }
    
    for(var i=0;i<document.getElementsByClassName("group_deny").length;i++){
        document.getElementsByClassName("group_deny")[i].addEventListener('click', function(){
            denyGroupRequest(this.getAttribute("name"));
            $("#groupResponse").html("Group Denied");
            window.setTimeout(function(){window.location.reload()}, 1500);
        });
    }
    
    for(var i=0;i<document.getElementsByClassName("group_invite_accept").length;i++){
        document.getElementsByClassName("group_invite_accept")[i].addEventListener('click', function(){
            acceptGroupInvite(this.getAttribute("name"));
            $("#getGroupInvites").html("User Accepted");
            window.setTimeout(function(){window.location.reload()}, 1500);
            
        });
    }
    
    for(var i=0;i<document.getElementsByClassName("group_invite_deny").length;i++){
        document.getElementsByClassName("group_invite_deny")[i].addEventListener('click', function(){
            denyGroupInvites(this.getAttribute("name"));
            $("#getGroupInvites").html("User Denied");
            window.setTimeout(function(){window.location.reload()}, 1500);
        });
    }
});

function lookForGroup () {
    for (var i = 0; i < document.getElementsByClassName("group_link").length; i++) {
        x = document.getElementsByClassName("group_link")[i].getAttribute("id");
        y = document.getElementById(x);
        
        if (typeof window.addEventListener === 'function'){
            (function (_y) {
                y.addEventListener('click', function(){
                    selectedGroup = _y.innerHTML; 
                    localStorage.setItem('selectedGroup', selectedGroup);
                });
            })(y);
        }
    }
}

function lookForTag () {
    var tagArr = document.getElementsByTagName('a');
    for (var i = 0, len = tagArr.length; i < len; ++ i) {
        if (tagArr[i].getAttribute("class") == "username_link") {
            t.push(tagArr[i].getAttribute("id"));
        }
    }
    return t;
}

function trackTag(l){
    
    for (var i = 0; i < l.length; i++) {
        e = document.getElementById(String(l[i]));
       
        if (typeof window.addEventListener === 'function'){
            (function (_e) {
                e.addEventListener('click', function(){
                    selectedUser = _e.innerHTML;
                    localStorage.setItem('selectedUser', selectedUser);
                });
            })(e);
        }
    }        
}