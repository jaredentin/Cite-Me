//Quote Requests
function getQuoteRequests(){
    $.ajax({
        crossDomain: true,
        url: "http://54.208.46.230/php/Requests/getQuoteRequests.php",
        async: false,
        type: "POST",
        success: function(data) {
            $("#getQuoteRequests").html(data);
        }
    });
}

function acceptQuoteRequest(buttonID){
    $.ajax({
        crossDomain: true,
        url: "http://54.208.46.230/php/Requests/getQuoteRequests.php",
        async: false,
        type: "GET",
        data: {action: buttonID, button: "accept"},
        success: function(data) {
            //$("#quoteResponse").html("Quote Accepted!");
        }
    });
}

function denyQuoteRequest(buttonID){
    $.ajax({
        crossDomain: true,
        url: "http://54.208.46.230/php/Requests/getQuoteRequests.php",
        async: false,
        type: "GET",
        data: {action: buttonID, button: "deny"},
        success: function(data) {
            //$("#quoteResponse").html("Quote Denied!");
        }
    });
}

//Group Requests
function getGroupRequests(){
    $.ajax({
        crossDomain: true,
        url: "http://54.208.46.230/php/Requests/getGroupRequests.php",
        async: false,
        type: "POST",
        success: function(data) {
            $("#getGroupRequests").html(data);
        }
    });
}

function acceptGroupRequest(buttonID){
    $.ajax({
        crossDomain: true,
        url: "http://54.208.46.230/php/Requests/getGroupRequests.php",
        async: false,
        type: "GET",
        data: {action: buttonID, button: "accept"},
        success: function(data) {
            //$("#groupResponse").html("Group Accepted!");
        }
    });
}

function denyGroupRequest(buttonID){
    $.ajax({
        crossDomain: true,
        url: "http://54.208.46.230/php/Requests/getGroupRequests.php",
        async: false,
        type: "GET",
        data: {action: buttonID, button: "deny"},
        success: function(data) {
            //$("#groupResponse").html("Group Denied!");
        }
    });
}

function getGroupInvites(){
    $.ajax({
        crossDomain: true,
        url: "http://54.208.46.230/php/Requests/getGroupInvites.php",
        async: false,
        type: "POST",
        success: function(data) {
            $("#getGroupInvites").html(data);
        }
    });
}

function acceptGroupInvite(buttonID){
    $.ajax({
        crossDomain: true,
        url: "http://54.208.46.230/php/Requests/getGroupInvites.php",
        async: false,
        type: "GET",
        data: {action: buttonID, button: "accept"},
        success: function(data) {
            $("#getGroupInvites").html(data);
        }
    });
}

function denyGroupInvite(buttonID){
    $.ajax({
        crossDomain: true,
        url: "http://54.208.46.230/php/Requests/getGroupInvites.php",
        async: false,
        type: "GET",
        data: {action: buttonID, button: "deny"},
        success: function(data) {
            $("#getGroupInvites").html(data);
        }
    });
}

//Send Quote Request
var quoteSubmitted = false;
function submitQuoteRequest(group, user, input){
    
  //  event.preventDefault();
    
    if (quoteSubmitted === false) {
        if(!($('#quotes_group_name_select option:selected').text() === "" && $('#user_name_select option:selected').text() === "" && $("#quote_input").val() === "")) {
              
            var group = $('#quotes_group_name_select option:selected').text();
            var user = $('#user_name_select option:selected').text();
            var quote = $("#quote_input").val();
            var $form = $('#select_group_form');

            var parameters = {
                action: "submit form",
                group : group,
                user : user,
                quote: quote
            };

            var data = $form.serialize() +'&' + $.param(parameters);
        
            $.ajax({
                crossDomain: true,
                url: "http://54.208.46.230/php/Requests/sendQuoteRequests.php",  
                async: false,
                type: "GET",  
                data: data,
                dataType: "html",
                success: function(data) {
                    quoteSubmitted = true;
                    $("#result").html(data);
                }
            });
        }
    }
    return quoteSubmitted;
}

//Send Group Request to 1 User
var toUser_groupRequestSent = false;
function toUser_submitGroupRequest(group, event){
  //  event.preventDefault();
    if (toUser_groupRequestSent === false) {
        if(!($('#toUser_groups_group_name_select option:selected').text() === "")) {
            var group = $('#toUser_groups_group_name_select option:selected').text();
            var $form = $('#invite_to_group_form');
            var user = localStorage.getItem('selectedUser');

            var parameters = {
                action: "submit form",
                group : group,
                user: user
            };

            var data = $form.serialize() +'&' + $.param(parameters);

            $.ajax({
                crossDomain: true,
                url: "http://54.208.46.230/php/Searches/searchForPeople.php",
                async: false,
                type: "GET",
                data: data,
                success: function(data) {
                    toUser_groupRequestSent = true;
                    $("#result").html(data);
                }
            });
        }
    }   
}

//Send Group Request to Users in Group
function toGroup_submitGroupRequest(event){
    event.preventDefault();
    var group = localStorage.getItem('selectedGroup');

    $.ajax({
        crossDomain: true,
        url: "http://54.208.46.230/php/Searches/searchForGroups.php",
        async: false,
        type: "GET",
        data: {action: "submit request", group: group},
        success: function(data) {
            $("#result").html(data);   
        }
    });
}

function group_populateWithGroups() {
    $.ajax({
        crossDomain: true,
        url: "http://54.208.46.230/php/Requests/sendGroupRequests.php",
        async: false,
        type: "GET",
        data: {action: "populate groups"},
        success: function(data) {
            $("#toUser_groups_group_name_select").html(data);
        }
    });    
}

function quote_populateWithGroups() {
    $.ajax({
        crossDomain: true,
        url: "http://54.208.46.230/php/Requests/sendQuoteRequests.php",
        async: false,
        type: "GET",
        data: {action: "populate groups"},
        success: function(data) {
            $("#quotes_group_name_select").html(data);
        }
    });    
}

function populateWithUsers(selected) {
    $.ajax({
        crossDomain: true,
        url: "http://54.208.46.230/php/Requests/sendQuoteRequests.php",
        async: false,
        type: "GET",
        data: {action: "populate users", selected: selected},
        success: function(data) {
            $("#user_name_select").html(data);
        }
    });    
}