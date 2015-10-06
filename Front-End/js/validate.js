$(document).ready(function(){
    $("#createAccountForm").validate({
        errorElement: "div",
        rules: {
            username: {
                required: true
            },
            password: {
                required: true
            },
            firstName: {
                required: true
            },
            lastName: {
                required: true
            }
        }
    });
    
    $("#signin_form").validate({
        //errorElement: "div",
        rules: {
            username: {
                required: true
                //username_check: true 
            },
            password: {
                required: true
            }
        }
    });
  
//    $("#create_group_form").validate({
//        errorElement: "div",
//        rules: {
//            groupName: {
//                required: true
//            }
//        }
//    });
   
//    $("#search_for_groups_form").validate({
//        errorElement: "div",
//        rules: {
//            groupName: {
//                required: true
//            }
//        }
//    });
    
//    $("#search_for_people_form").validate({
//        rules: {
//            personName: {
//                required: true
//            }
//        }
//    });
    
    $("#select_group_form").validate({
        rules: {
            quote_input: {
                required: true
            },
            user_name_select: {
                required: true   
            },
            quotes_group_name_select: {
                required: true   
            }
        }
    });
    
//    $("#invite_to_group_form").validate({
//        rules: {
//            quote_input: {
//                required: true
//            }
//        }
//    });
    
    $("form").each(function(){
       $(this).valid(); 
    });
});