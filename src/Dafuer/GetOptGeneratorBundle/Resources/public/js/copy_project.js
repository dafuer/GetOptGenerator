
    $(document).ready(function(){
        $('a#copy-code').zclip({
            path: zeroswfpath,
            copy:function() {return $("#source-code").text(); },
            afterCopy:function(){}
        });
    });
