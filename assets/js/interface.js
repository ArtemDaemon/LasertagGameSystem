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
                var settingseWrap = ".b-gamesettings";
                var tableWrap = ".b-table__wrap";
                var teamReady = ".js-reload__team-ready";
                var scoreTable = ".js-reload__score-table";
                var roundTime = ".js-reload__round-time";
                var bases = ".js-reload__bases";
                var bomblist = ".js-bomblist";

                data.each(function () {
                    if($(this).hasClass("b-main")) {
                        main = $(this);
                        return;
                    }
                });

                if(main.find(".js-reload__ajax-reloader").length!=0) {
                    location.reload();
                    return;
                }

                $(settingseWrap).html(main.find(settingseWrap).html());
                $(tableWrap).html(main.find(tableWrap).html());
                $(teamReady).html(main.find(teamReady).html());
                $(scoreTable).html(main.find(scoreTable).html());
                $(roundTime).html(main.find(roundTime).html());
                $(bases).html(main.find(bases).html());
                $(bomblist).html(main.find(bomblist).html());
            }
        });
    }

    function changeMission(missionId) {
        var url = '/?missionId='+missionId;
        history.pushState(null,null,url);
        $.ajax({
            url: url,
            success: function(data) {
                data = $(data);

                var main;
                var tableWrap = ".b-table__wrap";
                var teamReady = ".js-reload__team-ready";
                var scoreTable = ".js-reload__score-table";
                var roundTime = ".js-reload__round-time";
                var bases = ".js-reload__bases";

                data.each(function () {
                    if($(this).hasClass("b-main")) {
                        main = $(this);
                        return;
                    }
                });

                if(main.find(".js-reload__ajax-reloader").length!=0) {
                    location.reload();
                }

                $(tableWrap).html(main.find(tableWrap).html());
                $(teamReady).html(main.find(teamReady).html());
                $(scoreTable).html(main.find(scoreTable).html());
                $(roundTime).html(main.find(roundTime).html());
                $(bases).html(main.find(bases).html());
            }
        });
    }

    $(document).on("change", "select[name='missionId']", function () {
        changeMission($(this).find("option:selected").val());
    });

    var reloader = setInterval(reloadPage, 1000);

    $(document).on("click", ".js-reloader_off", function () {
        reloaderOff();
    });

    function reloaderOff() {
        clearInterval(reloader);
    }
});