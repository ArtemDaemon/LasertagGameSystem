function anyIdToggle() {
    var elem = document.getElementById("anyId");
    if(elem.value==0) {
        elem.value=1;
    } else {
        elem.value=0;
    }
}

$(document).ready(function() {
    function reloadPage()
    {
        var reloadUrl = location.href;

        var params = 'isAjax=true';
        if(reloadUrl.indexOf('?') === -1){
            reloadUrl = reloadUrl+'?'+params;
        }
        else{
            reloadUrl = reloadUrl+'&'+params;
        }

        $.ajax({
            url: reloadUrl,
            success: function(data) {
                data = $(data);

                var main;

                data.each(function () {
                    if($(this).hasClass("b-main")) {
                        main = $(this);
                        return;
                    }
                });

                if(main.find(".js-reload__ajax-reloader").length!=0) {
                    location.reload();
                }

                $(".b-main").html(main.html());
            }
        });
    }

    setInterval(reloadPage, 1000);
});