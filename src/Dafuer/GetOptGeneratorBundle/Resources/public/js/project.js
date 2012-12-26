
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
            var id_type=$('[id='+object+']').attr('id').replace('arguments','type');
            var id_mandatory=$('[id='+object+']').attr('id').replace('arguments','mandatory');

            if($('[id='+object+']').is(':checked')){
                $('[id='+id_type+']').removeAttr('disabled');
                $('[id='+id_mandatory+']').removeAttr('disabled');
            }else{
                $('[id='+id_type+']').attr('disabled', 'disabled');
                $('[id='+id_mandatory+']').attr('disabled', 'disabled');
            }
    }
