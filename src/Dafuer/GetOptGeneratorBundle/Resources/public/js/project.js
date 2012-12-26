
    // To copy to clipboard
    $(document).ready(function(){
        $('a#copy-code').zclip({
            path: zeroswfpath,
            copy:function() {return $("#source-code-hidden").text(); },
            afterCopy:function(){}
        });
    });

    // Block type y args is not enabled
    function changeArgs(object){  
            var id=$('[id='+object+']').attr('id').replace('arguments','type');

            if($('[id='+object+']').is(':checked')){
                $('[id='+id+']').removeAttr('disabled');

            }else{
                $('[id='+id+']').attr('disabled', 'disabled');
            }
    }
