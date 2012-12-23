
    // To copy to clipboard
    $(document).ready(function(){
        $('a#copy-code').zclip({
            path: zeroswfpath,
            copy:function() {return $("#source-code-hidden").text(); },
            afterCopy:function(){}
        });
    });

    // Block type y args is not enabled
    $('[id$=arguments]').change(function(){
        var id=$(this).attr('id').replace('arguments','type');

        if($(this).is(':checked')){
            $('[id='+id+']').attr('disabled', false);
            
        }else{
            $('[id='+id+']').attr('disabled', true);
        }
    });
