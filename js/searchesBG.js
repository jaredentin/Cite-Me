var $name;
var selectedUser;

$(document).ready(function(){
    //Search for people
    $("#pname").keyup(function(){
        $personName = $("#pname").val();
        var $form = $('#search_for_people_form');
        var data = {
            action: "search",
            personName : $personName
        };
        data = $form.serialize() + ' &' + $.param(data);

        $.ajax({
            crossDomain: true,
            url: "http://54.208.46.230/php/Searches/searchForPeople.php",  
            async: false,
            type: "POST",  
            data: data,
            success: function(data) {
                $(".searchResults").html(data);
                $name = lookForTag();
                trackTag($name);
            },
            error: function(data) {
                console.log(data); 
            }
        });
    });
    
    //Search for Groups
    $("#gname").keyup(function(){
        $groupName = $("#gname").val();
        var $form = $('#search_for_groups_form');
        var data = {
            groupName : $groupName
        };
        data = $form.serialize() + ' &' + $.param(data);

        $.ajax({
            crossDomain: true,
            url: "http://54.208.46.230/php/Searches/searchForGroups.php",  
            async: false,
            type: "POST",  
            data: data,
            dataType: "html",
            success: function(data) {
                $(".searchResults").html(data);
                lookForGroup();
            }
        });
    });
});