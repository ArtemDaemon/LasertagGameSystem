$(function(){
	var game_id = 0;
	var game_player_id = 0;
	var playerlist_id = 0;
	var result_id = 0;
	var results = [];
	
    $(document).ready(function(){
		
    	AllGamesLoad();
    	FillMissions();
    	FillCommands("");
    	FillPlayers();
    	
    })
    
	function AllGamesLoad(){ 
		$('#games table').find('tr:gt(0)').remove();
        $.ajax({async: true, url: "api.php?action=getgames", success:function(response){
        	var games = $.parseJSON(response);
            for (var i in games){
                $('#games table').append('<tr>'
                    +'<td style="display: none;">'+games[i].id+'</td>'
                    +'<td style="text-align: center;">'+games[i].N+'</td>'
                    +'<td><a href="http://192.168.0.108/?command=gameCheck&id='+games[i].id+'">'+games[i].Name+'</a></td>'
                    +'<td></td>'
                    +'<td style="text-align: center;">'
                    +'<img id="imgGamePlayers" src="img/players.png" alt="Список игроков" />&nbsp;'
                    +'<img id="imgGameResult" src="img/stat.png" alt="Результаты и статистика игры" />&nbsp;'
                    +'<img id="imgEditGame" src="img/edit.png" alt="Редактировать игру" />&nbsp;'
                    +'<img id="imgDeleteGame" src="img/delete.png" alt="Удалить игру" />&nbsp;'
                    +'</td>'
                    +'</tr>');
            }
        }});
		
	}
	
	function AllGamePlayersLoad(gameId){ 
		
		$('#players table').find('tr:gt(0)').remove();
        $.ajax({async: true, url: "api.php?action=getgameplayers&gid=" + gameId, success:function(response){
        	var players = $.parseJSON(response);
            for (var i in players){
                $('#players table').append('<tr>'
                    +'<td style="display: none">'+players[i].id+'</td>'
                    +'<td style="text-align: center;" >'+players[i].TagerId+'</td>'
                    +'<td>'+players[i].PlayerName+'</td>'
                    +'<td>'+players[i].TeamColor+'</td>'
                    +'<td style="display: none">'+players[i].Color+'</td>'
                    +'<td style="display: none">'+players[i].Name+'</td>'
                    +'<td style="text-align: center;">'
                    +'<img id="imgEditPlayer" src="img/edit.png" alt="Редактировать игрока" />&nbsp;'
                    +'<img id="imgDeletePlayer" src="img/delete.png" alt="Удалить игрока из списка" />&nbsp;'
                    +'</td>'
                    +'</tr>');
            }
        }});
	}
	
	function AllGameResultLoad(gameId, callback){ 
		
		$('#results table').find('tr:gt(0)').remove();
        $.ajax({async: true, url: "api.php?action=getgameresults&gid=" + gameId, success:function(response){
        	results = $.parseJSON(response);
            var score = [0, 0];
            for (var i in results){
            	var colspanHtml = "";
            	if (i == 0){
            		colspanHtml = '<td id="tdScore" style="font-size: 32px; text-align: center;" rowspan="' + results.length + '"></td>';
            		
            	}
            	var result = "";
            	if (results[i].WhoWin == "1" && results[i].Draw != "1"){
            		result = "Победа Красных";
            		score[0]++;
            	}
            	if (results[i].WhoWin == "2" && results[i].Draw != "1"){
            		result = "Победа Синих";
            		score[1]++;
            	}
            	
            	if (results[i].Draw == "1")
            		result = "Ничья";
            	
            	result += " (" + results[i].ScoreRed + " / " + results[i].ScoreBlue + ")";
                $('#results table').append('<tr>'
                    +'<td style="display: none">'+results[i].id+'</td>'
                    +'<td style="text-align: center;">' + (parseInt(i) + 1) + '</td>'
                    +'<td>'+ results[i].Name + '</td>'
                    +'<td>'+result+'</td>'
                    +colspanHtml
                    +'<td style="text-align: center;">'
                    +'<img id="imgEditResult" src="img/edit.png" alt="Редактировать результаты раунда" />&nbsp;'
                    +'<img id="imgDeleteResult" src="img/delete.png" alt="Удалить результаты раунда" />&nbsp;'
                    +'</td>'
                    +'</tr>');
            }
            
            $("#tdScore").text(score[0] + " : " + score[1]);
            
            if (callback) callback();
        }});
	}
	
	function AllGameStatsLoad(gameId){ 
		$('#stats table').find('tr:gt(0)').remove();
        $.ajax({async: true, url: "api.php?action=getgamestats&gid=" + gameId, success:function(response){
        	var stats = $.parseJSON(response);
            for (var i in stats){
                $('#stats table').append('<tr>'
                    +'<td style="text-align: center;">' + (parseInt(i) + 1) + '</td>'
                    +'<td style="text-align: center;">'+stats[i].id+'</td>'
                    +'<td>'+ stats[i].PlayerName + '</td>'
                    +'<td>'+ stats[i].WeaponName + '</td>'
                    +'<td>'+ stats[i].ColorName + '</td>'
                    +'<td style="text-align: center;">'+ stats[i].Score + '</td>'
                    +'<td style="text-align: center;">'+ stats[i].NTakePoint + '</td>'
                    +'<td style="text-align: center;">'+ stats[i].NFirstTake + '</td>'
                    +'<td style="text-align: center;">'+ stats[i].NLastTake + '</td>'
                    +'<td style="text-align: center;">'+ stats[i].NSetupBomb + '</td>'
                    +'<td style="text-align: center;">'+ stats[i].NTakePointFlag + '</td>'
                    +'</tr>');
            }
        }});
	}
	
	function FillMissions(selectedMission){
    	var objSel = $("#selMission");
        objSel.html("");
        
        $.ajax({async: true, url: "api.php?action=getmissions", success: function(response){
        	
        	var missions = $.parseJSON(response);
            for (var i in missions){
	        	var item = $("<option></option>").attr("value", missions[i].id).text(missions[i].Name);
	        	if (missions[i].Name == selectedMission) item.attr("selected", "selected");
	        	
	        	objSel.append(item);
	        }
		}});
	}
	
	function FillCommands(selectedCommand){
    	var objSel = $("#selCommand, #selCommandResult");
        objSel.html("");
        
        $.ajax({async: true, url: "api.php?action=getcommands", success: function(response){
        	
        	var commands = $.parseJSON(response);
            for (var i in commands){
	        	var item = $("<option></option>").attr("value", commands[i].id).text(commands[i].Name);
	        	if (commands[i].Name == selectedCommand) item.attr("selected", "selected");
	        	
	        	objSel.append(item);
	        }
		}});
		
		
	}
	
	function FillPlayers(selectedPlayer){
    	var objSel = $("#selPlayers");
        objSel.html("<option value=''></option>");
        
        $.ajax({async: true, url: "api.php?action=getplayerlist", success: function(response){
        	
        	var commands = $.parseJSON(response);
            for (var i in commands){
	        	var item = $("<option></option>").attr("value", commands[i].id).text(commands[i].PlayerName);
	        	if (commands[i].Name == selectedPlayer) item.attr("selected", "selected");
	        	
	        	objSel.append(item);
	        }
	        objSel.val("");
		}});
	}
	
	
	$(document).on("change", "#selPlayers",function(){
		
		$('#divPlayerItem input').eq(1).val($("#selPlayers option:selected").text());
		
    });
    
	$(document).on("click", "#lnkAddGame",function(){
		
		game_id = -1;
		
		$("#games tr:not(:first)").css("display", "none");
        $("#divGameItem span").html("Добавление новой игры");
        $("#divGameItem table tr td input").val("");
        
        $("#divGameItem").show("fast");
    });
    $(document).on("click", "#lnkAddPlayer",function(){
		
		playerlist_id = -1;
		
		$("#players tr:not(:first)").css("display", "none");
        $("#divPlayerItem span").html("Добавление игрока");
        $("#divPlayerItem table tr td input").val("");
        
        $("#divPlayerItem #selPlayers").val("");
        
        $("#divPlayerItem").show("fast");
    });
    
    
	$(document).on("click", "#lnkAddResult",function(){
		
		result_id = -1;
		
		$("#results tr:not(:first)").css("display", "none");
        $("#divResultItem span").html("Добавление нового раунда");
        $("#divResultItem table tr td input").val("");
        
        $("#stats").hide("fast");
        $("#divResultItem").show("fast");
        
    });
    
    $(document).on("click", "#lnkHideGameItem",function(){
        $("#divGameItem").hide("fast");
        $("#games tr").css("display", "");
    });
    $(document).on("click", "#lnkHideGamePlayers",function(){
        $("#players, #divPlayerItem").hide("fast");
        $("#games tr").css("display", "");
    });
    $(document).on("click", "#lnkHidePlayerItem",function(){
        $("#divPlayerItem").hide("fast");
        $("#players tr").css("display", "");
    });
    
    $(document).on("click", "#lnkHideResults",function(){
        $("#results").hide("fast");
        $("#divResultItem").hide("fast");
        $("#stats").hide("fast");
        $("#games tr").css("display", "");
    });
    
    $(document).on("click", "#lnkHideResultItem",function(){
        $("#divResultItem").hide("fast");
        $("#results tr").css("display", "");
        $("#stats").show("fast");
    });
    
    
    
    $(document).on("click", "#imgGamePlayers",function(){
    	
        game_id = $(this).parent().siblings().eq(0).html();
        $("#stats, #results, #divGameItem, #divPlayerItem").hide("fast");
        
        AllGamePlayersLoad(game_id);
        $("#players").show("fast");
        
        var tohide = $("#games tr:gt(0):not()").filter(function() { return $(this).children().first().text() != game_id });
        tohide.css("display", "none");
    });
    
    
    $(document).on("click", "#imgGameResult",function(){
    	
        game_id = $(this).parent().siblings().eq(0).html();
        
        $("#players, #divPlayerItem, #divGameItem, #divResultItem").hide("fast");
        
        var tohide = $("#games tr:gt(0):not()").filter(function() { return $(this).children().first().text() != game_id });
	    tohide.css("display", "none");
        AllGameResultLoad(game_id, function(){
        	
        	$("#results").show("fast");
        	AllGameStatsLoad(game_id);
        	$("#stats").show("fast");
        });
        
    });
    
    
    $(document).on("click", "#imgGameStats",function(){
    	
        game_id = $(this).parent().siblings().eq(0).html();
        
        $("#players").hide("fast");
        $("#divPlayerItem").hide("fast");
        
        AllGameStatsLoad(game_id);
        $("#stats").show("fast");
    });
    
    $(document).on("click", "#imgEditGame",function(){
    	
        game_id = $(this).parent().siblings().eq(0).html();
        
        $("#players, #stats, #results").hide("fast");
        
        $("#divGameItem span").html("Редактирование игры");
        var inputs = $("#divGameItem table tr td input");
        inputs.eq(0).val($(this).parent().siblings().eq(1).text());
        inputs.eq(1).val($(this).parent().siblings().eq(2).text());
        
        $("#divGameItem").show("fast");
        
        var tohide = $("#games tr:gt(0):not()").filter(function() { return $(this).children().first().text() != game_id });
        tohide.css("display", "none");
    });
    
    $(document).on("click", "#imgEditPlayer",function(){
    	
        playerlist_id = $(this).parent().siblings().eq(0).html();
        game_player_id = $(this).parent().siblings().eq(5).html();
        
        $("#divPlayerItem span").html("Редактирование игрока");
        var inputs = $("#divPlayerItem table tr td input");
        inputs.eq(0).val($(this).parent().siblings().eq(1).text());
        inputs.eq(1).val($(this).parent().siblings().eq(2).text());
        $("#divPlayerItem table tr td select").val($(this).parent().siblings().eq(4).text());
        
        $("#divPlayerItem").show("fast");
        
        var tohide = $("#players tr:gt(0):not()").filter(function() { return $(this).children().first().text() != playerlist_id });
        tohide.css("display", "none");
    });
    
    
    $(document).on("click", "#imgEditResult",function(){
    	
        result_id = $(this).parent().siblings().eq(0).html();
        $("#results tr:not(:first)").css("display", "none");
        $("#divResultItem span").html("Редактирование результатов раунда");
        
        var result = null;
        for (var i = 0; i < results.length; i++){
        	if (results[i].id == result_id){
        		result = results[i];
        		break;
        	}
        }
        
        var inputs = $("#divResultItem input");
        inputs.eq(0).val(result.ScoreRed);
        inputs.eq(1).val(result.ScoreBlue);
        inputs.eq(2).prop("checked", result.Draw == "1" ? true : false);
        
        $("#selMission").val(result.MissionId);
        $("#selCommandResult").val(result.WhoWin);
        
        $("#stats").hide("fast");
        $("#divResultItem").show("fast");
        
        var tohide = $("#results tr:gt(0):not()").filter(function() { return $(this).children().first().text() != result_id });
        tohide.css("display", "none");
    });
    
    
    $(document).on("click", "#lnkSaveGameItem",function(){
    	
    	var inputs = $('#divGameItem table tr td input');
    	
    	var number = inputs.eq(0).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var name = inputs.eq(1).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	
    	
    	if (number == ""){
    		alert("Заполните поле 'Номер'");
    		inputs.eq(0).focus();
    		return;
    	}
    	
    	if (name == ""){
    		alert("Заполните поле 'Название'");
    		inputs.eq(1).focus();
    		return;
    	}
    	
    	var url = "";
    	if (game_id == -1){
    		url = "api.php?action=addgame&name=" + name + "&num=" + number;
    	}
    	else
    	{
    		url = "api.php?action=setgame&name=" + name + "&num=" + number + "&gid=" + game_id;
    	}
    	
    	$.ajax({async: true, url: url, success:function(){ 
            $('#divGameItem').hide("fast");
    		AllGamesLoad();
        }})
    });
    
    
    $(document).on("click", "#lnkSavePlayerItem",function(){
    	
    	var inputs = $('#divPlayerItem table tr td input');
    	var tagerId = inputs.eq(0).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var name = inputs.eq(1).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var command = $('#divPlayerItem table tr td select').eq(1).val();
    	
    	if (name == ""){
    		alert("Заполните поле 'Название'");
    		inputs.eq(0).focus();
    		return;
    	}
    	
    	if (playerlist_id == -1){
	    	$.ajax({async: true, url: "api.php?action=getplayerbyname&name=" + name + "&add=1", success:function(response){ 
	            var player = JSON.parse(response);
	            
	            var url = "api.php?action=addgameplayer&gid=" + game_id + "&pid=" + player.id + "&tid=" + tagerId + "&cid=" + command;
		    	$.ajax({async: true, url: url, success:function(){ 
		            $('#divPlayerItem').hide("fast");
		    		AllGamePlayersLoad(game_id);
		        }});
	        }});
        }
        else{
        	$.ajax({async: true, url: "api.php?action=getplayerbyname&name=" + name + "&add=1", success:function(response){ 
	            
	            var player = JSON.parse(response);
	            if (player.id != game_player_id) {
	            	var url = "api.php?action=deletegameplayer&gid=" + game_id + "&pid=" + game_player_id;
	        		$.ajax({async:true, url: url});
	        		
	        		var url = "api.php?action=addgameplayer&gid=" + game_id + "&pid=" + player.id + "&tid=" + tagerId + "&cid=" + command;
			    	$.ajax({async: true, url: url, success:function(){ 
			            $('#divPlayerItem').hide("fast");
			    		AllGamePlayersLoad(game_id);
			        }});
	            }
	            else{
		            var url = "api.php?action=setgameplayer&gid=" + game_id + "&pid=" + player.id + "&tid=" + tagerId + "&cid=" + command + "&plid=" + playerlist_id;
			    	$.ajax({async: true, url: url, success:function(){ 
			            $('#divPlayerItem').hide("fast");
			    		AllGamePlayersLoad(game_id);
			        }});
			    }
	        }});
        }
    });
    
    
    $(document).on("click", "#lnkSaveResultItem",function(){
    	
    	var inputs = $('#divResultItem table tr td input');
    	
    	var missionId = $('#selMission').val();
    	var scoreRed = inputs.eq(0).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var scoreBlue = inputs.eq(1).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var whoWin = $('#selCommandResult').val();
    	var draw = inputs.eq(2).prop("checked") ? "1" : "0";
    	
    	if (scoreRed == ""){
    		alert("Заполните поле 'Очки красные'");
    		inputs.eq(0).focus();
    		return;
    	}
    	
    	if (scoreBlue == ""){
    		alert("Заполните поле 'Очки синие'");
    		inputs.eq(1).focus();
    		return;
    	}
    	
    	var url = "";
    	if (result_id == -1){
    		url = "api.php?action=addgameresult&gid=" + game_id + "&mid=" + missionId + "&sr=" + scoreRed + "&sb=" + scoreBlue + "&w=" + whoWin + "&d=" + draw;
    	}
    	else
    	{
    		url = "api.php?action=setgameresult&gid=" + game_id + "&mid=" + missionId + "&sr=" + scoreRed + "&sb=" + scoreBlue + "&w=" + whoWin + "&d=" + draw + "&grid=" + result_id;
    	}
    	
    	$.ajax({async: true, url: url, success:function(){ 
            $('#divResultItem').hide("fast");
    		AllGameResultLoad(game_id, function(){
        	
	        	$("#results, #stats").show("fast");
	        });
        }})
    });
    
    $(document).on('click', '#imgDeleteGame',function(){
    	
    	var game = $(this).parent().siblings().eq(2).text();
    	if (confirm("Удалить игру '" + game + "'?")){
	        
	        var game_delete_id = $(this).parent().siblings().eq(0).text();
	        var url = "api.php?action=deletegame&gid=" +game_delete_id;
	        $.ajax({async:true, url: url});
	        
	        $('#games table tr td').filter(function(){return $(this).text()==game}).parent().hide('fast');
        }
        
    });
    
    $(document).on('click', '#imgDeletePlayer',function(){
    	
    	var player = $(this).parent().siblings().eq(2).text();
    	if (confirm("Удалить игрока '" + player + "' из команды?")){
	        
	        var player_delete_id = $(this).parent().siblings().eq(5).text();
	        
	        var url = "api.php?action=deletegameplayer&gid=" + game_id + "&pid=" + player_delete_id;
	        $.ajax({async:true, url: url});
	        
	        $('#players table tr td').filter(function(){return $(this).text()==player}).parent().hide('fast');
        }
        
    });
    
    
    $(document).on('click', '#imgDeleteResult',function(){
    	
    	if (confirm("Удалить результат раунда?")){
	        
	        var result_delete_id = $(this).parent().siblings().eq(0).text();
	        
	        var url = "api.php?action=deletegameresult&grid=" + result_delete_id;
	        $.ajax({async:true, url: url});
	        
	        $(this).parent().parent().hide('fast');
        }
        
    });
    
    
    
})