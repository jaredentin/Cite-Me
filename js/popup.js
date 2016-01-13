var t = [];

window.onload = function() {
    localStorage.setItem("indexMessage", "");
}

$(document).ready(function(){
    $(window).keydown(function(event){
        if(event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });
    
    if (document.getElementById("createAccountSubmit")) {
        document.getElementById("createAccountSubmit").addEventListener('click', function(){
            submitCreateAccountForm(); 
        });
    }
    
    if (document.getElementById("signInSubmit")) {
        document.getElementById("signInSubmit").addEventListener('click', function(){
            submitSigninForm(); 
        });
    }
    
    if (document.getElementById("createGroupSubmit")) {
        document.getElementById("createGroupSubmit").addEventListener('click', function(){
            submitCreateGroupForm();
        });
    }
    
    if (document.getElementById("welcomeName")){
        displayWelcomePageData();
    }
    
    if (document.getElementById("logoutButton")){
        document.getElementById("logoutButton").addEventListener('click', function(){
            logout();
        });
    }
    
    if (document.getElementById("changeNameSubmit")){
        document.getElementById("changeNameSubmit").addEventListener('click', function(){
            changeAccountName();
        });
    }
    
    if (document.getElementById("changePasswordSubmit")){
        document.getElementById("changePasswordSubmit").addEventListener('click', function(){
            changeAccountPassword();
        });
    }
    
    if (document.getElementById("deleteAccountButton")){
        document.getElementById("deleteAccountButton").addEventListener('click', function(){
            deleteAccount();
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
                document.getElementById("quote_input").value = '';
               var groupSelected = document.getElementById("quotes_group_name_select").options[document.getElementById("quotes_group_name_select").selectedIndex].text;
                populateWithUsers(groupSelected);   
            }
        });
        
        document.getElementById("select_group_form").addEventListener('submit', function(){
            if (!(document.getElementById("quote_input").value == '' && document.getElementById("quote_input").value == document.getElementById("quote_input").defaultValue)) {              
                submitQuoteRequest();
            } else {
                localStorage.setItem("notificationMsg", "Field(s) empty");
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
        });
    }
    
    for(var i=0;i<document.getElementsByClassName("quote_deny").length;i++){
        document.getElementsByClassName("quote_deny")[i].addEventListener('click', function(){
            denyQuoteRequest(this.getAttribute("name"));
        });
    }
    
    for(var i=0;i<document.getElementsByClassName("group_accept").length;i++){
        document.getElementsByClassName("group_accept")[i].addEventListener('click', function(){
            acceptGroupRequest(this.getAttribute("name"));
        });
    }
    
    for(var i=0;i<document.getElementsByClassName("group_deny").length;i++){
        document.getElementsByClassName("group_deny")[i].addEventListener('click', function(){
            denyGroupRequest(this.getAttribute("name"));
        });
    }
    
    for(var i=0;i<document.getElementsByClassName("group_invite_accept").length;i++){
        document.getElementsByClassName("group_invite_accept")[i].addEventListener('click', function(){
            acceptGroupInvite(this.getAttribute("name"));   
        });
    }
    
    for(var i=0;i<document.getElementsByClassName("group_invite_deny").length;i++){
        document.getElementsByClassName("group_invite_deny")[i].addEventListener('click', function(){
            denyGroupInvite(this.getAttribute("name"));
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