function availableCheck(name) {
        
            var request = $.ajax({
            url: "/ajax/test-ajax/",
            type: "POST",
            data: {site : name}
            });

            request.done(function(msg) {
            $('.'+name).html(msg);
            console.log (msg);
            });

            request.fail(function(textStatus) {
            console.log (textStatus);
            });
        };
function saveSelected() {
        
            var resultString="";
            var index = 1;
            $(".save_dom:checked").each(function(){
                    if(resultString == ""){
                            resultString= $(this).attr('id')+index + "=" + $(this).val();
                            index++;
                    }else {
                            resultString+= "&" + $(this).attr('id')+index + "=" + $(this).val();
                            index++;
                    }
            });
            
            var request = $.ajax({
            
            url: "savedomain.php",
            type: "POST",
            data: resultString
            });

            request.done(function(msg) {
            $(".save_dom:checked").each(function(){
                    $(this).parent().parent().remove();
            });
            
            $('.textForSave').html(msg);
            console.log ($('.'+msg));
            });

            request.fail(function(textStatus) {
            alert( "Request failed: " + textStatus );
            });
        };

function deleteDomains(name) {
        
            var requestNum=$('#delete_text').val();
            
            var request = $.ajax({
            url: "/ajax/test-ajax/",
            type: "POST",
            data: {number : requestNum}
            });
            
            request.done(function(msg) {
            $('.textForDelete').html(msg);
            console.log (msg);
            });

            request.fail(function(textStatus) {
            console.log (textStatus);
            });
        };

function deleteSelected() {
        
            var resultString="";
            var index = 1;
            $(".delete_dom:checked").each(function(){
                    if(resultString == ""){
                            resultString= $(this).attr('id')+index + "=" + $(this).val();
                            index++;
                    }else {
                            resultString+= "&" + $(this).attr('id')+index + "=" + $(this).val();
                            index++;
                    }
            });
            
            var request = $.ajax({
            
            url: "/ajax/delete-selected/",
            type: "POST",
            data: resultString
            });

            request.done(function(msg) {
            $('.textForDelete').html(msg);
            $(".delete_dom:checked").each(function(){
                     $(this).parent().parent().remove();
            });
            console.log (msg);
            });

            request.fail(function(textStatus) {
            console.log (textStatus);
            });
        };