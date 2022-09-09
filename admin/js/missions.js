$(function(){
	var missions = [];
	var missionpointssetup = [];
	var mission_id = 0;
	var mission_pointsetup_id = 0;
	var point_type = "";
	
    $(document).ready(function(){
		
		//$('tr[id^=basesettings]').css("display", "none");
		$('#divSettingsBase table td').eq(2).css("text-align", "rigth");
		
		
    	AllMissionsLoad();
    	
    	FillSelect("selTypePoint", "getpointtypes", "id", "Name", "");
    	FillSelect("selCommand", "getcommands", "id", "Name", "");
		FillSelect("selSoundsRound", "getsoundsround", "id", "NamePreset", "");
		FillSelect("selSoundsBase", "getsoundsbase", "id", "NamePreset", "");
    	FillSelect("selPoint", "getpoints", "id", "Name", "");
    	FillSelect("selTypeBomb", "getbombtypes", "id", "Name", "");
    	FillSelect("selPreset", "getpresets", "id", "NamePreset", "");
    	FillSelect("selResource", "getresources", "id", "Name", "");
    	FillSelect("selResource2", "getresources", "id", "Name", "");
    	FillSelect("selTypeBase", "getcatalogbasetypes", "id", "Name", "");
    	FillSelect("selBase", "getbases", "id", "Name", "");
    	FillSelect("selPointTypeM1", "getpointtypesm1", "id", "Name", "");
    	FillSelect("selWhoCan", "getwhocan", "Id", "Name", "");
    })
    
	function AllMissionsLoad(){ 
		
		$('#missions table').find('tr:gt(0)').remove();
        $.ajax({async: true, url: "api.php?action=getmissions", success:function(response){
        	missions = $.parseJSON(response);
            for (var i in missions){
                $('#missions table').append('<tr>'
                    +'<td style="display: none;">'+missions[i].id+'</td>'
                    +'<td align="center" >'+ (parseInt(i) + 1)+'</td>'
                    +'<td>'+missions[i].Name+'</td>'
                    +'<td>'+missions[i].PointName+'</td>'
                    +'<td style="display: none;">'+missions[i].TypePoint+'</td>'
                    +'<td align="center">'
                    +'<img id="imgEditMission" src="img/settings.png" title="Настройки сценария" />&nbsp;'
                    +'<img id="imgDeleteMission" src="img/delete.png" title="Удалить сценарий" />&nbsp;'
                    +'</td>'
                    +'</tr>');
            }
        }});
		
	}
	
	function AllMissionPointSetup1(mid){ 
		
		$('#settings_point1').find('tr:gt(0)').remove();
        $.ajax({async: true, url: "api.php?action=getmissionspointsetup&mid=" + mid, success:function(response){
        	missionpointssetup = $.parseJSON(response);
            for (var i in missionpointssetup){
                $('#settings_point1').append('<tr>'
                    +'<td align="center" style="display: none;">'+missionpointssetup[i].id+'</td>'
                    +'<td align="center">' + (parseInt(i) + 1) + '</td>'
                    +'<td align="center">'+missionpointssetup[i].PointName+'</td>'
                    +'<td align="center">'+missionpointssetup[i].TypeWorkName+'</td>'
                    +'<td align="center">'+missionpointssetup[i].NamePreset+'</td>'
                    +'<td align="center">'+(missionpointssetup[i].ResourceTakeName != null ? missionpointssetup[i].ResourceTakeName : "" ) + '</td>'
                    +'<td align="center">'+missionpointssetup[i].NeedTime+'</td>'
                    +'<td align="center">'+missionpointssetup[i].ScoreComplite+'</td>'
                    +'<td align="center" style="display: none;">'+missionpointssetup[i].RedFirstTake+'</td>'
                    +'<td align="center" style="display: none;">'+missionpointssetup[i].RedLastTake+'</td>'
                    +'<td align="center" style="display: none;">'+missionpointssetup[i].BlueFirstTake+'</td>'
                    +'<td align="center" style="display: none;">'+missionpointssetup[i].BlueLastTake+'</td>'
                    +'<td align="center">'+(missionpointssetup[i].ResourceComliteName != null ? missionpointssetup[i].ResourceComliteName : "")+'</td>'
                    +'<td align="center">'+missionpointssetup[i].TimeReset+'</td>'
                    +'<td align="center">'+missionpointssetup[i].Active+'</td>'
                    +'<td align="center">'
                    +'<img id="imgEditMissionPointSetup" src="img/edit.png" title="Редактировать настройки точки" />&nbsp;'
                    +'<img id="imgDeleteMissionPointSetup" src="img/delete.png" title="Удалить настройку точки" />&nbsp;'
                    +'</td>'
                    +'</tr>');
            }
        }});
	}
	
	
	function AllMissionPointSetup2(mid){ 
		
		$('#settings_point2').find('tr:gt(0)').remove();
        $.ajax({async: true, url: "api.php?action=getmissionspointsetup&mid=" + mid, success:function(response){
        	missionpointssetup = $.parseJSON(response);
            for (var i in missionpointssetup){
                $('#settings_point2').append('<tr>'
                    +'<td align="center" style="display: none;">'+missionpointssetup[i].id+'</td>'
                    +'<td align="center">' + (parseInt(i) + 1) + '</td>'
                    +'<td align="center">'+missionpointssetup[i].PointName+'</td>'
                    +'<td align="center">'+missionpointssetup[i].NamePreset+'</td>'
                    +'<td align="center">'+(missionpointssetup[i].ResourceTakeName != null ? missionpointssetup[i].ResourceTakeName : "" ) + '</td>'
                    +'<td align="center">'+missionpointssetup[i].ScoreComplite+'</td>'
                    +'<td align="center">'+missionpointssetup[i].Order+'</td>'
                    +'<td align="center">'+missionpointssetup[i].RedFirstTake+'</td>'
                    +'<td align="center">'+missionpointssetup[i].BlueFirstTake+'</td>'
                    +'<td align="center">'+missionpointssetup[i].Active+'</td>'
                    +'<td align="center">'
                    +'<img id="imgEditMissionPointSetup" src="img/edit.png" title="Редактировать настройки точки" />&nbsp;'
                    +'<img id="imgDeleteMissionPointSetup" src="img/delete.png" title="Удалить настройку точки" />&nbsp;'
                    +'</td>'
                    +'</tr>');
            }
        }});
		
	}
	
	
	function AllMissionPointSetup3(mid){ 
		
		$('#settings_point3').find('tr:gt(0)').remove();
        $.ajax({async: true, url: "api.php?action=getmissionspointsetup&mid=" + mid, success:function(response){
        	missionpointssetup = $.parseJSON(response);
            for (var i in missionpointssetup){
                $('#settings_point3').append('<tr>'
                    +'<td align="center" style="display: none;">'+missionpointssetup[i].id+'</td>'
                    +'<td align="center">' + (parseInt(i) + 1) + '</td>'
                    +'<td align="center">'+missionpointssetup[i].PointName+'</td>'
                    +'<td align="center">'+missionpointssetup[i].NamePreset+'</td>'
                    +'<td align="center">'+(missionpointssetup[i].ResourceTakeName != null ? missionpointssetup[i].ResourceTakeName : "" ) + '</td>'
                    +'<td align="center">'+missionpointssetup[i].ScoreComplite+'</td>'
                    +'<td align="center">'+missionpointssetup[i].Order+'</td>'
                    +'<td align="center">'+missionpointssetup[i].NeedTime+'</td>'
                    +'<td align="center">'+missionpointssetup[i].RedFirstTake+'</td>'
                    +'<td align="center">'+missionpointssetup[i].BlueFirstTake+'</td>'
                    +'<td align="center">'+missionpointssetup[i].Active+'</td>'
                    +'<td align="center">'
                    +'<img id="imgEditMissionPointSetup" src="img/edit.png" title="Редактировать настройки точки" />&nbsp;'
                    +'<img id="imgDeleteMissionPointSetup" src="img/delete.png" title="Удалить настройку точки" />&nbsp;'
                    +'</td>'
                    +'</tr>');
            }
        }});
		
	}
	
	function AllMissionPointSetup4(mid){ 
		
		$('#settings_point4').find('tr:gt(0)').remove();
        $.ajax({async: true, url: "api.php?action=getmissionspointsetup&mid=" + mid, success:function(response){
        	missionpointssetup = $.parseJSON(response);
            for (var i in missionpointssetup){
                $('#settings_point4').append('<tr>'
                    +'<td align="center" style="display: none;">'+missionpointssetup[i].id+'</td>'
                    +'<td align="center">' + (parseInt(i) + 1) + '</td>'
                    +'<td align="center">'+missionpointssetup[i].PointName+'</td>'
                    +'<td align="center">'+missionpointssetup[i].NamePreset+'</td>'
                    +'<td align="center">'+(missionpointssetup[i].ResourceTakeName != null ? missionpointssetup[i].ResourceTakeName : "" ) + '</td>'
                    +'<td align="center">'+missionpointssetup[i].ScoreComplite+'</td>'
                    +'<td align="center">'+missionpointssetup[i].RedFirstTake+'</td>'
                    +'<td align="center">'+missionpointssetup[i].BlueFirstTake+'</td>'
                    +'<td align="center">'+missionpointssetup[i].Active+'</td>'
                    +'<td align="center">'
                    +'<img id="imgEditMissionPointSetup" src="img/edit.png" title="Редактировать настройки точки" />&nbsp;'
                    +'<img id="imgDeleteMissionPointSetup" src="img/delete.png" title="Удалить настройку точки" />&nbsp;'
                    +'</td>'
                    +'</tr>');
            }
        }});
		
	}
	
	function AllMissionPointSetup5(mid){ 
		
		$('#settings_point5').find('tr:gt(0)').remove();
        $.ajax({async: true, url: "api.php?action=getmissionspointsetup&mid=" + mid, success:function(response){
        	missionpointssetup = $.parseJSON(response);
            for (var i in missionpointssetup){
                $('#settings_point5').append('<tr>'
                    +'<td align="center" style="display: none;">'+missionpointssetup[i].id+'</td>'
                    +'<td align="center">' + (parseInt(i) + 1) + '</td>'
                    +'<td align="center">'+missionpointssetup[i].PointName+'</td>'
                    +'<td align="center">'+missionpointssetup[i].NamePreset+'</td>'
                    +'<td align="center">'+(missionpointssetup[i].ResourceTakeName != null ? missionpointssetup[i].ResourceTakeName : "" ) + '</td>'
                    +'<td align="center">'+missionpointssetup[i].WhoseHomeName+'</td>'
                    +'<td align="center">'+missionpointssetup[i].ScoreComplite+'</td>'
                    +'<td align="center">'+missionpointssetup[i].RedFirstTake+'</td>'
                    +'<td align="center">'+missionpointssetup[i].BlueFirstTake+'</td>'
                    +'<td align="center">'+missionpointssetup[i].TimeReset+'</td>'
                    +'<td align="center">'+missionpointssetup[i].Active+'</td>'
                    +'<td align="center">'
                    +'<img id="imgEditMissionPointSetup" src="img/edit.png" title="Редактировать настройки точки" />&nbsp;'
                    +'<img id="imgDeleteMissionPointSetup" src="img/delete.png" title="Удалить настройку точки" />&nbsp;'
                    +'</td>'
                    +'</tr>');
            }
        }});
		
	}
	
	function AllMissionPointSetup6(mid){ 
		
		$('#settings_point6').find('tr:gt(0)').remove();
        $.ajax({async: true, url: "api.php?action=getmissionspointsetup&mid=" + mid, success:function(response){
        	missionpointssetup = $.parseJSON(response);
            for (var i in missionpointssetup){
                $('#settings_point6').append('<tr>'
                    +'<td align="center" style="display: none;">'+missionpointssetup[i].id+'</td>'
                    +'<td align="center">' + (parseInt(i) + 1) + '</td>'
                    +'<td align="center">'+missionpointssetup[i].PointName+'</td>'
                    +'<td align="center">'+missionpointssetup[i].NamePreset+'</td>'
                    +'<td align="center">'+(missionpointssetup[i].ResourceTakeName != null ? missionpointssetup[i].ResourceTakeName : "" ) + '</td>'
                    +'<td align="center">'+missionpointssetup[i].TimeStartWeapon+'</td>'
                    +'<td align="center">'+missionpointssetup[i].NTake+'</td>'
                    +'<td align="center">'+missionpointssetup[i].ScorePlayer+'</td>'
                    +'<td align="center">'+missionpointssetup[i].TimeReset+'</td>'
                    +'<td align="center">'+missionpointssetup[i].TimeStartRad+'</td>'
                    +'<td align="center">'+missionpointssetup[i].IntervalRad+'</td>'
                    +'<td align="center" style="display: none;">'+missionpointssetup[i].DamageRag+'</td>'
                    +'<td align="center">'+missionpointssetup[i].Active+'</td>'
                    +'<td align="center">'
                    +'<img id="imgEditMissionPointSetup" src="img/edit.png" title="Редактировать настройки точки" />&nbsp;'
                    +'<img id="imgDeleteMissionPointSetup" src="img/delete.png" title="Удалить настройку точки" />&nbsp;'
                    +'</td>'
                    +'</tr>');
            }
        }});
		
	}
	
	function AllMissionPointSetup7(mid){ 
		
		$('#settings_point7').find('tr:gt(0)').remove();
        $.ajax({async: true, url: "api.php?action=getmissionspointsetup&mid=" + mid, success:function(response){
        	missionpointssetup = $.parseJSON(response);
            for (var i in missionpointssetup){
                $('#settings_point7').append('<tr>'
                    +'<td align="center" style="display: none;">'+missionpointssetup[i].id+'</td>'
                    +'<td align="center">' + (parseInt(i) + 1) + '</td>'
                    +'<td align="center">'+missionpointssetup[i].PointName+'</td>'
                    +'<td align="center">'+missionpointssetup[i].Bomb+'</td>'
                    +'<td align="center">'+missionpointssetup[i].ScoreExpl+'</td>'
                    +'<td align="center">'+missionpointssetup[i].ScorePlayer+'</td>'
                    +'<td align="center">'+missionpointssetup[i].ScorePlayerDefuse+'</td>'
                    +'<td align="center" style="display: none;">'+missionpointssetup[i].Order+'</td>'
                    +'<td align="center">'+missionpointssetup[i].Active+'</td>'
                    +'<td align="center">'
                    +'<img id="imgEditMissionPointSetup" src="img/edit.png" title="Редактировать настройки точки" />&nbsp;'
                    +'<img id="imgDeleteMissionPointSetup" src="img/delete.png" title="Удалить настройку точки" />&nbsp;'
                    +'</td>'
                    +'</tr>');
            }
        }});
	}
	
	
	
	
	function FillSelect(selId, apiAction, idField, nameField, selectedItem){
        $.ajax({async: true, url: "api.php?action=" + apiAction, success: function(response){
        	
        	var items = $.parseJSON(response);
        	$('select[id="' + selId + '"]').each(function(index) {
	        	
		        var objSel = $(this);
	        	objSel.html("");
	        	
	        	var item = $("<option></option>").attr("value", "0").text("----Выберите значение----");
        		objSel.append(item);
	        	
	        	for (var i in items){
		        	var item = $("<option></option>").attr("value", items[i][idField]).text(items[i][nameField]);
		        	if (items[i][idField] == selectedItem) item.attr("selected", "selected");
		        	
		        	objSel.append(item);
		        }
	    	});
		}});
	}
	
	
	$(document).on("change", "#selTypePoint",function(){
		
		if (mission_id > 0){
	        var pointtype_id = $('#selTypePoint').val();
	        $(".hidden").css("display", "none");
	        $("#settings_point" + pointtype_id).show("slow");
	        $("div[id^=divMissionItemPointParams]").css("display", "none");
	        $("#divMissionItemPointParams" + pointtype_id).css("display", "block");
	        
			eval("AllMissionPointSetup" + pointtype_id + "(" + mission_id + ");");
			
			switch(pointtype_id){
        	
	        	case "1":
	        		FillSelect("selSoundsPoints", "getsoundspoint1", "id", "NamePreset", "");
	        		break;
	        	case "2":
	        		FillSelect("selSoundsPoints", "getsoundpoint2", "id", "NamePreset", "");
	        		break;
	        	case "3":
	        		FillSelect("selSoundsPoints", "getsoundpoint2", "id", "NamePreset", "");
	        		break;
	        	case "4":
	        		FillSelect("selSoundsPoints", "getsoundstakeflag", "id", "NamePreset", "");
	        		break;
	        	case "5":
	        		FillSelect("selSoundsPoints", "getsoundtakehome", "id", "NamePreset", "");
	        		break;
	        	case "6":
	        		FillSelect("selSoundsPoints", "getsoundsurv", "id", "NamePreset", "");
	        		break;
	        	case "7":
	        		FillSelect("selSoundsPoints", "getsoundssetupbomb", "id", "NamePreset", "");
	        		break;
	        	default:
	        		FillSelect("selSoundsPoints", "getsoundssetupbomb", "id", "NamePreset", "");
	        		break;
	        }
		}
    });
    
    $(document).on("change", "#tblSettingsBaseRed #selTypeBase",function(){
		
		if (mission_id > 0){
	        var typebase_id = $('#tblSettingsBaseRed #selTypeBase').val();
	        $('#tblSettingsBaseRed tr[id^=basesettings]').css("display", "none");
	        $('#tblSettingsBaseRed tr[id=basesettings' + typebase_id + ']').css("display", "");
		}
    });
    
    $(document).on("change", "#tblSettingsBaseBlue #selTypeBase",function(){
		
		if (mission_id > 0){
	        var typebase_id = $('#tblSettingsBaseBlue #selTypeBase').val();
	        $('#tblSettingsBaseBlue tr[id^=basesettings]').css("display", "none");
	        $('#tblSettingsBaseBlue tr[id=basesettings' + typebase_id + ']').css("display", "");
		}
    });
    
	$(document).on("click", "#lnkAddMission",function(){
		
		mission_id = -1;
		
		$("#missions tr:not(:first)").css("display", "none");
        $("#divMissionItem span").html("Добавление нового сценария");
        $("#divMissionItem table tr td input").val("");
        $("#divMissionItem").show("fast");
    });
    
    $(document).on("click", "#lnkAddSettingsPoint",function(){
		
		mission_pointsetup_id = -1;
		
		var pointtype_id = $('#selTypePoint').val();
		
		$("#settings_point" + pointtype_id + " tr:not(:first)").css("display", "none");
        $("#divPointItem" + pointtype_id + " span").html("Добавление точки");
        $("#divPointItem" + pointtype_id + " table tr td input").val("");
        //$("#divPointItem" + pointtype_id + " table tr td input").prop("checked", false);
        $("#divPointItem" + pointtype_id + "").show("fast");
        $('#selTypePoint').prop("disabled", true);
    });
    
    
    $(document).on("click", "#lnkHideMissionItem",function(){
        $("#divMissionItem, #divSettingsPoint, #divSettingsBase").hide("fast");
        $(".hidden").hide("fast");
        $("#missions tr").css("display", "");
    });
    
    $(document).on("click", "#lnkHidePointItem",function(){
        var pointtype_id = $('#selTypePoint').val();
		
		$("#divPointItem" + pointtype_id).hide("fast");
		$("#settings_point" + pointtype_id + " tr").css("display", "");
		$('#selTypePoint').prop("disabled", false);
    });
    
    $(document).on("click", "#imgEditMission",function(){
    	
        mission_id = $(this).parent().siblings().eq(0).html();
        var mission = null;
        for (var i = 0; i < missions.length; i++){
        	if (missions[i].id == mission_id){
        		mission = missions[i];
        		break;
        	}
        }
        $("#divMissionItem span").html("Настройки сценария");
        $("#divMissionItem input[type=textbox]").val("");
        $("#divSettingsBase input[type=textbox]").val("");
        
        
        point_type = $(this).parent().siblings().eq(3).text();
        var pointtype_id = $(this).parent().siblings().eq(4).text();
        
        switch(pointtype_id){
        	
        	case "1":
        		FillSelect("selSoundsPoints", "getsoundspoint1", "id", "NamePreset", mission.SoundPointId);
        		break;
        	case "2":
        		FillSelect("selSoundsPoints", "getsoundpoint2", "id", "NamePreset", mission.SoundPointId);
        		break;
        	case "3":
        		FillSelect("selSoundsPoints", "getsoundpoint2", "id", "NamePreset", mission.SoundPointId);
        		break;
        	case "4":
        		FillSelect("selSoundsPoints", "getsoundstakeflag", "id", "NamePreset", mission.SoundPointId);
        		break;
        	case "5":
        		FillSelect("selSoundsPoints", "getsoundtakehome", "id", "NamePreset", mission.SoundPointId);
        		break;
        	case "6":
        		FillSelect("selSoundsPoints", "getsoundsurv", "id", "NamePreset", mission.SoundPointId);
        		break;
        	case "7":
        		FillSelect("selSoundsPoints", "getsoundssetupbomb", "id", "NamePreset", mission.SoundPointId);
        		break;
        	default:
        		FillSelect("selSoundsPoints", "getsoundssetupbomb", "id", "NamePreset", mission.SoundPointId);
        		break;
        }
        
        $( "#divMissionItem table input[data-id], #divMissionItemPointParams" + pointtype_id + " input[data-id], #divSettingsBase input[data-id]" ).each(function( index ) {
        	
			if ($(this).attr("type") == "text")
    			$(this).val(mission[$(this).attr("data-id")])
    		if ($(this).attr("type") == "checkbox")
    			$(this).prop("checked", (mission[$(this).attr("data-id")] == "1" ? true : false));
    		
		});
		
    	$( "#divMissionItem table select[data-id], #divSettingsBase select[data-id]" ).each(function( index ) {
			$(this).val(mission[$(this).attr("data-id")])
		});
        
        /*$( "#divMissionItemPointParams" + pointtype_id + " input[data-id]" ).each(function( index ) {
			
			if ($(this).attr("type") == "text")
    			$(this).val(mission[$(this).attr("data-id")])
    		if ($(this).attr("type") == "checkbox")
    			$(this).prop("checked", (mission[$(this).attr("data-id")] == "1" ? true : false));
		});*/
    	
    	$( "#divMissionItemPointParams" + pointtype_id + " select[data-id]" ).each(function( index ) {
			$(this).val(mission[$(this).attr("data-id")])
		});
        
        
        $(".hidden").css("display", "none");
        $("#tblSettingsBaseRed #selTypeBase").trigger("change");
        $("#tblSettingsBaseBlue #selTypeBase").trigger("change");
        
        $("#divMissionItem, #divSettingsPoint, #divSettingsBase").show("fast");
        $("#divMissionItemPointParams" + pointtype_id).css("display", "block");
        $("#divSettingsPoint").css("display", "flex");
        $("#settings_point" + pointtype_id).show("fast");
        
        eval("AllMissionPointSetup" + pointtype_id + "(" + mission_id + ");");
        
        var tohide = $("#missions tr:gt(0):not()").filter(function() { return $(this).children().first().text() != mission_id });
        tohide.css("display", "none");
    });
    
    
    $(document).on("click", "#imgEditMissionPointSetup",function(){
    	
        mission_pointsetup_id = $(this).parent().siblings().eq(0).html();;
        var missionpointsetup = null;
        for (var i = 0; i < missionpointssetup.length; i++){
        	if (missionpointssetup[i].id == mission_pointsetup_id){
        		missionpointsetup = missionpointssetup[i];
        		break;
        	}
        }
		
		var pointtype_id = $('#selTypePoint').val();
		
        $("#divPointItem" + pointtype_id + " span").html("Редактирование параметров точки");
        $("#divPointItem" + pointtype_id + " table tr td input").val("");
        
        $("#divPointItem" + pointtype_id + "").show("fast");
        $('#selTypePoint').prop("disabled", true);
        
        
        $( "#divPointItem" + pointtype_id + " table input[data-id]" ).each(function( index ) {
			if ($(this).attr("type") == "text")
    			$(this).val(missionpointsetup[$(this).attr("data-id")])
    		if ($(this).attr("type") == "checkbox")
    			$(this).prop("checked", (missionpointsetup[$(this).attr("data-id")] == "1" ? true : false));
		});
    	
    	$( "#divPointItem" + pointtype_id + " table select[data-id]" ).each(function( index ) {
			$(this).val(missionpointsetup[$(this).attr("data-id")])
		});
		
        var tohide = $("#settings_point" + pointtype_id + " tr:gt(0):not()").filter(function() { return $(this).children().first().text() != mission_pointsetup_id });
        tohide.css("display", "none");
    });
    
    $(document).on("click", "#lnkSaveMissionItem",function(){
    	
    	var pointtype_id = $("#selTypePoint").val(); 
    	
    	var typebase_red_id = $('#tblSettingsBaseRed #selTypeBase').val();
    	var typebase_blue_id = $('#tblSettingsBaseBlue #selTypeBase').val();
        //$('tr[id^=basesettings]').css("display", "none");
        //$('tr[id=basesettings' + typebase_id + ']').css("display", "");
	        
    	var paramsString = "";
		
    	$( "#divMissionItem table input[data-id], #divMissionItemPointParams" + pointtype_id + " input[data-id], " + 
    			"#tblSettingsBaseRed #basesettings" + typebase_red_id + " input[data-id], " + 
    			"#tblSettingsBaseBlue #basesettings" + typebase_blue_id + " input[data-id]").each(function( index ) {
			
			if ($(this).attr("type") == "text")
    			paramsString += "&" + $(this).attr("data-id") + "=" + $(this).val();
    		if ($(this).attr("type") == "checkbox")
    			paramsString += "&" + $(this).attr("data-id") + "=" + ($(this).prop("checked") ? "1" : "0");
    		
		});
    	
    	$( "#divMissionItem table select[data-id], #divMissionItemPointParams" + pointtype_id + " select[data-id], #divSettingsBase select[data-id]" ).each(function( index ) {
			paramsString += "&";
    		paramsString += $(this).attr("data-id") + "=" + $(this).val();
		});
		
    	/*if (Name == ""){
    		alert("Заполните поле 'Название'");
    		inputs.eq(0).focus();
    		return;
    	}
    	
    	if (MaxTime == ""){
    		alert("Заполните поле 'Максимальное время раунда'");
    		inputs.eq(1).focus();
    		return;
    	}
    	if (MaxScore == ""){
    		alert("Заполните поле 'Количество очков для победы'");
    		inputs.eq(2).focus();
    		return;
    	}*/
    	
    	var url = "";
    	if (mission_id == -1){
    		url = "api.php?action=addmission" + paramsString;
    	}
    	else
    	{
    		url = "api.php?action=setmission" + paramsString + "&mid=" + mission_id;
    	}
    	
    	$.ajax({async: true, url: url, success:function(){ 
            $('#divMissionItem, #divSettingsPoint, #divSettingsBase').hide("fast");
    		AllMissionsLoad();
        }})
    });
    
    
    $(document).on("click", "#lnkSavePointItem",function(){
    	
    	var pointtype_id = $("#selTypePoint").val(); 
    	var paramsString = "";
    	
    	$( "#divPointItem" + pointtype_id + " table input[data-id]" ).each(function( index ) {
			
			paramsString += "&";
			if ($(this).attr("type") == "text")
    			paramsString += $(this).attr("data-id") + "=" + $(this).val();
    		if ($(this).attr("type") == "checkbox")
    			paramsString += $(this).attr("data-id") + "=" + ($(this).prop("checked") ? "1" : "0");
		});
    	
    	$( "#divPointItem" + pointtype_id + " table select[data-id]" ).each(function( index ) {
			
			paramsString += "&";
    		paramsString += $(this).attr("data-id") + "=" + $(this).val();
		});
    	
    	paramsString += "&MissionId=" + mission_id;
    	var url = "";
    	if (mission_pointsetup_id == -1){
    		url = "api.php?action=addmissionspointsetup" + paramsString;
    	}
    	else
    	{
    		url = "api.php?action=setmissionspointsetup" + paramsString + "&mpsid=" + mission_pointsetup_id;
    	}
    	
    	console.log(url);
    	$.ajax({async: true, url: url, success:function(){ 
            $("#divPointItem" + pointtype_id).hide("fast");
			$("#settings_point" + pointtype_id + " tr").css("display", "");
			$('#selTypePoint').prop("disabled", false);
    		eval("AllMissionPointSetup" + pointtype_id + "(" + mission_id + ");");
        }})
    });
    
    
    $(document).on('click', '#imgDeleteMission',function(){
    	
    	var mission = $(this).parent().siblings().eq(2).text();
    	if (confirm("Удалить сценарий '" + mission + "'?")){
	        
	        var mission_delete_id = $(this).parent().siblings().eq(0).text();
	        var url = "api.php?action=deletemission&mid=" +mission_delete_id;
	        $.ajax({async:true, url: url});
	        
	        $('#missions table tr td').filter(function(){return $(this).text()==mission}).parent().hide('fast');
        }
        
    });
    
    $(document).on('click', '#imgDeleteMissionPointSetup',function(){
    	
    	if (confirm("Удалить настройку?")){
	        
	        var missionsetup_delete_id = $(this).parent().siblings().eq(0).text();
	        var url = "api.php?action=deletemissionspointsetup&mpsid=" + missionsetup_delete_id;
	        $.ajax({async:true, url: url});
	        
	        $(this).parent().parent().hide('fast');
        }
        
    });
    
    
    $(document).on('click', '#copyofleft',function(){
    	var typebase_id = $('#tblSettingsBaseRed #selTypeBase').val();
    	$( "#tblSettingsBaseRed #basesettings" + typebase_id + " input[data-id]").each(function(index){
			
			var dataid = $(this).attr("data-id").replace("Red", "Blue");
			var copytoelem = $("#tblSettingsBaseBlue #basesettings" + typebase_id + " input[data-id='" + dataid + "']");
			copytoelem.val($(this).val());
    		
		});
    });
    
    $(document).on('click', '#copyofright',function(){
    	var typebase_id = $('#tblSettingsBaseBlue #selTypeBase').val();
    	$( "#tblSettingsBaseBlue #basesettings" + typebase_id + " input[data-id]").each(function(index){
			
			var dataid = $(this).attr("data-id").replace("Blue", "Red");
			var copytoelem = $("#tblSettingsBaseRed #basesettings" + typebase_id + " input[data-id='" + dataid + "']");
			copytoelem.val($(this).val());
    		
		});
    });
    
    
})