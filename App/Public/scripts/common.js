function postCheckAJAX(form, btn_id, service)
{
    if($("#"+btn_id).is("input")) {
        orig_btn_val = $("#"+btn_id).val();
        $("#"+btn_id).val("Processing. Please Wait.");
    } else {
        orig_btn_val = $("#"+btn_id).html();
        $("#"+btn_id).html("Processing. Please Wait.");
    }
    $("#"+btn_id).prop('diabled',true);

    form = "#"+form;

    $("input").removeClass("error_field");
    $("select").removeClass("error_field");
    success_value = false;

    $.ajax({
        type: 'POST',
        url: "/Services/"+service,
        data: $(form).serialize(),
        success: function(data) {
            if(data['JSON_STATUS']==1) {
                $(form).attr("action",data['JSON_REDIRECT']);
                success_value = true;
                //form.submit();
            } else {
                error_message = "";
                if(typeof(data['KEY']) != "undefined" && data['KEY'] !== null) {
                    $.each(data['KEY'], function(index, value) {
                            $("#"+index).addClass("error_field");
                            error_message += value;
                    });
                } else if(typeof(data['MESSAGE']) != "undefined" && data['MESSAGE'] !== null) {
                    error_message = data['MESSAGE'];
                }

                displayErrorMessageBox("#messages",error_message);
                success_value = false;

                //change the button value back
                if($("#"+btn_id).is("input")){
                    $("#"+btn_id).val(orig_btn_val);
                } else {
                    $("#"+btn_id).html(orig_btn_val);
                }
                $("#"+btn_id).prop('diabled',false);
            }
        },
        dataType: "json",
        async:false
    });
    return success_value;
}

function initializeValidation(form,button,service)
{
    $("#"+button).click(function(){
        return postCheckAJAX(form,button,service);
    });
}

function displayErrorMessageBox(selector, message) {
    $(selector).html('<div class="alert"><button class="close" data-dismiss="alert">×</button>'+message+'</div>');
    $(selector+" div.alert").addClass("alert-error");
    $(selector).css("display","block");
}

function displaySuccessMessageBox(selector, message) {
    $(selector).html('<div class="alert alert-success"><button class="close" data-dismiss="alert">×</button>'+message+'</div>');
    $(selector+" div.alert").addClass("alert-success");
    $(selector).css("display","block");
}

$.download = function(url, data, method){
    if(url && data) {
        var form = $('<form>', { action: url, method:(method || 'get')});
        $.each(data, function(key, value) {
            var input = $('<input />', { type: 'hidden', name: key, value: value}).appendTo(form);
        });
        return form.appendTo('body').submit().remove();
    }
    throw new Error('Invalid File');
};

