function Post_Ajax($script_to_run,$data_to_post,$log_to_console = true,$async = true)
{
    return $.ajax({
        type: 'POST',
        url: $script_to_run,
        data: JSON.parse($data_to_post),
        success: function(data){if($log_to_console){console.log(data);}},
        dataType: "html",
        async:$async
      }).responseText;
    //var post_ajax = $.post($script_to_run,JSON.parse($data_to_post),
    //function(data, status){
    //    if($log_to_console)
    //    {
    //      console.log("Data: " + data + "\nStatus: " + status);
    //    }
    //});
    
}

function Show_Element_If_True($el, show = true)
{
    $el.style.display = show ? 'block' : 'none';
}

function Add_Check_Mark()
{
    return '<img src="'+$("#images_location").html()+'/checkmark.jpg" style="width:20px;">';
}

////Delete the user when person clicks the delete button in the context menu
$(document).on("click",'[data-unique_id="delete_user_now"]',function () {
    username = JSON.parse(this.dataset.context);
    username = username.added_context.username;
    Post_Ajax('scripts/Delete_User.php',this.dataset.context);
    $("[data-context]").each(function () {
        if($(this).data("context").username == username)
        {
            $(this).remove();
        }
    });
    
});

////Update the department that the user is assigned too
$(document).on("click",'[data-unique_id^="Change_User_Department"]',function () {
    post = {};
    username = JSON.parse(this.dataset.context);
    post.checked = username.checked;
    post.username = username.added_context.username;
    department = JSON.parse(this.dataset.context);
    post.department = department.department_id;
    Post_Ajax('scripts/Change_Department.php',JSON.stringify(post),false);
    if(post.checked)
    {
        context = $('#modalmt [data-context^=\'{"username":"'+post.username+'"\']').data("context");
        for( var i = 0; i < context.departments.length; i++){ 

            if ( context.departments[i] === post.department) {
              context.departments.splice(i, 1); 
            }
         }
         $('#modalmt [data-context^=\'{"username":"'+post.username+'"\']').attr("data-context", JSON.stringify(context));
    }else
    {
        context = $('#modalmt [data-context^=\'{"username":"'+post.username+'"\']').data("context");
        context.departments.push(post.department); 
        $('#modalmt [data-context^=\'{"username":"'+post.username+'"\']').attr("data-context", JSON.stringify(context));

    }
    
});

//change the config file when user clicks on a table row on the config.php page
$(document).on("click",'[data-context=\'["config_file_row"]\']',function () {
    $key = window.prompt("Key Name",this.children[0].innerText);
    $value = window.prompt("Value Name",this.children[1].innerText);
    if(!$key || !$value || !$key || !$value)
    {
        return false;
    }
    var post = {key:$key,value:$value};
    Post_Ajax('scripts/AddUpdateConfig.php',JSON.stringify(post));
});

//customEvent used for populating the context_menu unique_ids and added contexts
window.addEventListener("customEvent", function(e) {
    //console.log(e.data.added_context.departments);
    var i = 0;
    json = JSON.parse(e.data);
    context = JSON.parse($('[data-unique_id="'+json.unique_id+'"]').attr("data-context"));
    context.checked = false;
    context = JSON.stringify(context);
    $('[data-unique_id="'+json.unique_id+'"]').attr("data-context",context);
    while (i < json.added_context.departments.length) {     
        if(json.added_context.departments[i] == json.department_id)
        {
            context = JSON.parse($('[data-unique_id="'+json.unique_id+'"]').attr("data-context"));
            context.checked = true;
            context = JSON.stringify(context);
            $('[data-unique_id="'+json.unique_id+'"]').attr("data-context",context);
            console.log($('[data-unique_id="'+json.unique_id+'"]').attr("data-context"));
            string = " "+Add_Check_Mark();
            $('[data-unique_id="'+json.unique_id+'"] a').append(string);
        }
        i += 1;
    }
   
});


//prevent enter key from doing what bootstrap wants it to do
$(document).keypress(function (e) {
    if (e.which == 13) {
        if($("[id$='Modal']").hasClass('show'))
        {
            if($(document.activeElement).is(":focus") && $(document.activeElement).is("textarea"))
            {
                $(document.activeElement).val($(document.activeElement).val() + "\n");
            }else
            {
                $('[type="submit"]').trigger('click');
            }
        }
        e.preventDefault();
    }
});

    var calcDataTableHeight = function() {
    return $(window).height() * 55 / 100;
  };
    
  $(window).resize(function() {
    table = $('[id$="Table_ID"]').DataTable();
    table.destroy();
    $('[id$="Table_ID"]').DataTable({
        scrollY: calcDataTableHeight()
    });
    $('#Employee_Table_ID').DataTable().destroy();
    $('#Employee_Table_ID').DataTable({
        scrollY: calcDataTableHeight(),
        "columnDefs": [
            {
                "targets": [ 3 ],
                "visible": false
            },
        ]
    })
    $('#Employee_Texts_Table_ID').DataTable().destroy();
    $('#Employee_Texts_Table_ID').DataTable({
        scrollY: calcDataTableHeight(),
        "columnDefs": [
            {
                "targets": [ 2 ],
                "visible": false
            },
        ]
    })
  });

$(document).ready(function () {
    $("#scheduleModal").modal({
        backdrop: 'static',
        keyboard: false,
        display: 'show'
    });

    $('[id$="Table_ID"]').DataTable({
        scrollY: calcDataTableHeight()
    });
    $('#Employee_Table_ID').DataTable().destroy();
    $('#Employee_Table_ID').DataTable({
        scrollY: calcDataTableHeight(),
        "columnDefs": [
            {
                "targets": [ 3 ],
                "visible": false
            },
        ]
    })
    $('#Employee_Texts_Table_ID').DataTable().destroy();
    $('#Employee_Texts_Table_ID').DataTable({
        scrollY: calcDataTableHeight(),
        "columnDefs": [
            {
                "targets": [ 2 ],
                "visible": false
            },
        ]
    })
    $('[data-toggle="tooltip"]').tooltip({
        'delay': { show: 500, hide: 100 },
        'html':true
    });
    $("[type='tel']").on('keyup',function() {
        if($(this).val().startsWith('1'))
        {
            if($(this).val().length == 1 || $(this).val().length == 5 || $(this).val().length == 9)
            {
                $(this).val($(this).val()+"-");
            }    
        }else
        {
            if($(this).val().length == 3 || $(this).val().length == 7)
            {
                $(this).val($(this).val()+"-");
            }    
        }        
    })

    $("#address_checkbox").on('click',function () {
        if($(this).is(':checked'))
        {
            $("#customer_billing_address").val($("#customer_address").val());
            $("#customer_billing_address").attr('readonly',true);
        }else
        {
            $("#customer_billing_address").val("");
            $("#customer_billing_address").attr('readonly',false);
        }

    });
});



$(document).on('click','a[href="#Edit_Customer"]', function(){
    $.each($(this).prop('classList'),function (index, value) {
        if(value.startsWith('customer_id='))
        {
            customer_id = value.substring(12,value.length);
            $("#update_customer_id").val(customer_id);
        }
        if(value.startsWith('customer_name='))
        {
            customer_name = value.substring(14,value.length).split('{').join(' ');
            $("#update_customer_name").val(customer_name);
        }
        if(value.startsWith('customer_phone_number='))
        {
            customer_phone_number = value.substring(22,value.length);
            $("#update_customer_phone_number").val(customer_phone_number);
        }
        if(value.startsWith('customer_phone_number_extension='))
        {
            customer_phone_number_extension = value.substring(32,value.length);
            $("#update_customer_phone_number_extension").val(customer_phone_number_extension);
        }
        if(value.startsWith('customer_address='))
        {
            customer_address = value.substring(17,value.length).split('{').join(' ');
            customer_address = customer_address.split('}').join('\r\n');
            customer_address = customer_address.split('~').join('\r\n');
            $("#update_customer_address").val(customer_address);
        }
        if(value.startsWith('customer_billing_address='))
        {
            customer_billing_address = value.substring(25,value.length).split('{').join(' ');
            customer_billing_address = customer_billing_address.split('}').join('\r\n');
            customer_billing_address = customer_billing_address.split('~').join('\r\n');
            $("#update_customer_billing_address").val(customer_billing_address);
        }        
        if(value.startsWith('customer_web_address='))
        {
            customer_web_address = value.substring(21,value.length).split('{').join(' ');
            $("#update_customer_web_address").val(customer_web_address);
        }
        if(value.startsWith('customer_CCB='))
        {
            customer_CCB = value.substring(13,value.length).split('{').join(' ');
            $("#update_customer_CCB").val(customer_CCB);
        }
        if(value.startsWith('customer_industry='))
        {
            customer_industry = value.substring(18,value.length).split('{').join(' ');
            $("#update_customer_industry").val(customer_industry);
        }
    });
    $("#ChangeCustomerModal").modal('toggle');
    $("#update_customer_modal_title").text("Update "+customer_name);
})

$(document).on('click','a[href="#Edit_Contractor"]', function(){
    $.each($(this).prop('classList'),function (index, value) {
        if(value.startsWith('contractor_id='))
        {
            contractor_id = value.substring(14,value.length);
            $("#update_contractor_id").val(contractor_id);
        }
        if(value.startsWith('contractor_name='))
        {
            contractor_name = value.substring(16,value.length).split('{').join(' ');
            $("#update_contractor_name").val(contractor_name);
        }
        if(value.startsWith('contractor_first_name='))
        {
            contractor_first_name = value.substring(22,value.length).split("{").join(' ');
            $("#update_contractor_first_name").val(contractor_first_name);
        }
        if(value.startsWith('contractor_last_name='))
        {
            contractor_last_name = value.substring(21,value.length).split("{").join(' ');
            $("#update_contractor_last_name").val(contractor_last_name);
        }
        if(value.startsWith('contractor_phone_number='))
        {
            contractor_phone_number = value.substring(24,value.length);
            $("#update_contractor_phone_number").val(contractor_phone_number);
        }
        if(value.startsWith('contractor_phone_number_extension='))
        {
            contractor_phone_number_extension = value.substring(34,value.length);
            $("#update_contractor_phone_number_extension").val(contractor_phone_number_extension);
        }
        if(value.startsWith('contractor_email='))
        {
            contractor_email = value.substring(17,value.length);
            $("#update_contractor_email").val(contractor_email);
        }
    });
    $("#ChangeContractorModal").modal('toggle');
    $("#update_contractor_modal_title").text("Update "+contractor_name);
})


$(document).on('click','a[href="#Edit_Equipment_Type"]', function(){
    $.each($(this).prop('classList'),function (index, value) {
        if(value.startsWith('equipment_type_id='))
        {
            equipment_type_id = value.substring(18,value.length);
            $("#update_equipment_type_id").val(equipment_type_id);
        }
        if(value.startsWith('equipment_type_name='))
        {
            equipment_type_name = value.substring(20,value.length).split('{').join(' ');
            $("#update_equipment_type_name").val(equipment_type_name);
        }
    });
    $("#ChangeEquipmentTypeModal").modal('toggle');
    $("#update_equipment_type_modal_title").text("Update "+equipment_type_name);
})

$(document).on('click','a[href="#Edit_Equipment_Subtype"]', function(){
    $.each($(this).prop('classList'),function (index, value) {
        if(value.startsWith('equipment_subtype_id='))
        {
            equipment_subtype_id = value.substring(21,value.length);
            $("#update_equipment_subtype_id").val(equipment_subtype_id);
        }
        if(value.startsWith('equipment_subtype_name='))
        {
            equipment_subtype_name = value.substring(23,value.length).split('{').join(' ');
            $("#update_equipment_subtype_name").val(equipment_subtype_name);
        }
        if(value.startsWith('equipment_type_id='))
        {
            equipment_type_id = value.substring(18,value.length).split('{').join(' ');
            $("#update_equipment_type_id option").each(function () {
                if($(this).val() == equipment_type_id)
                {
                    $(this).attr('selected',true);
                }else
                {
                    $(this).attr('selected',false);
                }
            });
        }
    });
    $("#ChangeEquipmentSubtypeModal").modal('toggle');
    $("#update_equipment_subtype_modal_title").text("Update "+equipment_subtype_name);
})

$(document).on('click', 'a[href="#Edit_Equipment"]', function(){
    console.log('edit');
    $.each($(this).prop('classList'),function (index, value) {
        if(value.startsWith('equipment_id='))
        {
            equipment_id = value.substring(13,value.length);
            $("#update_equipment_id").val(equipment_id);
        }
        if(value.startsWith('equipment_name='))
        {
            equipment_name = value.substring(15,value.length).split('{').join(' ');
            $("#update_equipment_name").val(equipment_name);
        }
        if(value.startsWith('equipment_subtype_id='))
        {
            equipment_subtype_id = value.substring(21,value.length).split('{').join(' ');
            $("#update_equipment_subtype_id option").each(function () {
                if($(this).val() == equipment_subtype_id)
                {
                    $(this).attr('selected',true);
                }else
                {
                    $(this).attr('selected',false);
                }
            });
        }
        if(value.startsWith('person_who_owns_equipment='))
        {
            equipment_subtype_id = value.substring(26,value.length);
            if(equipment_subtype_id != "false")
            {
                $("#update_employees_dropdown_id option").each(function () {
                    if($(this).val() == equipment_subtype_id)
                    {
                        $(this).attr('selected',true);
                    }else
                    {
                        $(this).attr('selected',false);
                    }
                });
            }
        }

    });
    $("#ChangeEquipmentModal").modal('toggle');
    $("#update_equipment_modal_title").text("Update "+equipment_name);
})

$(document).on('click','a[href="#Edit_Employee"]', function(){
    $.each($(this).prop('classList'),function (index, value) {
        if(value.startsWith('employee_id='))
        {
            employee_id = value.substring(12,value.length);
            $("#update_employee_id").val(employee_id);
        }
        if(value.startsWith('first_name='))
        {
            first_name = value.substring(11,value.length).split('{').join(' ');
            $("#update_employee_first_name").val(first_name);
        }
        if(value.startsWith('last_name='))
        {
            last_name = value.substring(10,value.length).split("{").join(' ');
            $("#update_employee_last_name").val(last_name);
        }
        if(value.startsWith('phone_number='))
        {
            phone_number = value.substring(13,value.length);
            $("#update_employee_phone_number").val(phone_number);
        }
        if(value.startsWith('email_address='))
        {
            email_address = value.substring(14,value.length);
            $("#update_employee_email_address").val(email_address);
        }
    });
    $added_rows = Post_Ajax('ajax_return_scripts/Employee_Skills_Table_Rows.php','{"employee_id":"'+employee_id+'"}',false,false);
    $("#EmployeeHasSkills").html($added_rows);
    $("#ChangeEmployeeModal").modal('toggle');
    $("#update_employee_modal_title").text("Update "+first_name+" "+last_name);
});

$(document).on('click','a[href="#Edit_Employee_Skill"]', function(){
    $.each($(this).prop('classList'),function (index, value) {
        if(value.startsWith('skill_id='))
        {
            employee_skill_id = value.substring(9,value.length);
            $("#update_employee_skill_id").val(employee_skill_id);
        }
        if(value.startsWith('name='))
        {
            employee_skill_name = value.substring(5,value.length).split('{').join(' ');
            $("#update_employee_skill_name").val(employee_skill_name);
        }
    });
    $("#ChangeEmployeeSkillModal").modal('toggle');
    $("#update_employee_skill_modal_title").text("Update "+employee_skill_name);
})

$(document).on('click','.add_icon', function(){
    $added_rows = Post_Ajax('ajax_return_scripts/Employee_Skills_Dropdown.php','{"employee_id":";alkdjs"}',false,false);
    console.log($added_rows);
    $(this).parents('thead').next('tbody').append("<tr><td>"+$added_rows+"</td></tr>");
})

$(document).on('click','.minus_icon', function(){
    $(this).parents('tr').remove();
})

$(document).on('click','.schedule_icon', function(){
    var icons = ['<img class = "schedule_icon" src = "../images/send_sms.png" height = "35px;" width = "35px;">', '<img class = "schedule_icon" src = "../images/waiting_for_reply.png" height = "20px;" width = "35px;">', '<img class = "schedule_icon" src = "../images/available.png" height = "35px;" width = "35px;">','<img class = "schedule_icon" src = "../images/unavailable.png" height = "35px;" width = "35px;">'];
    var alength = icons.length;
    for (i = 0; i < alength; i++) {
        if(icons[i].includes($(this).attr('src')))
        {
            if(i == alength - 1){i=-1;}
            json = $(this).parents('tr').data('context');
            json.new_status = i + 1;
            json.date = $(this).parent('td').data('context');
            Post_Ajax('scripts/Process_Schedule_Change_Temp.php',JSON.stringify(json),true);
            $(this).replaceWith(icons[i+1]);
        }     
    }
})


$(document).on("click",function (e) {
//    console.log(e.originalEvent.clientX);
//    console.log(e.originalEvent.clientY);
});
