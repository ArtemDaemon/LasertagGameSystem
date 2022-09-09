$(function(){
	var soundround_id = 0;
	var soundtime_id = 0;
	var soundpoint1_id = 0;
	var soundpoint2_id = 0;
	var soundtakeflag_id = 0;
	var soundtakehome_id = 0;
	var soundsurv_id = 0;
	var soundsetupbomb_id = 0;
	var soundbase_id = 0;
	var soundresoursecome_id = 0;
	
    $(document).ready(function(){
		
    	AllSoundsRoundLoad();
    	AllSoundsTimeLoad();
    	AllSoundsPoint1Load();
    	AllSoundsPoint2Load();
    	AllSoundsTakeFlagLoad();
    	AllSoundsTakeHomeLoad();
    	AllSoundsSurvLoad();
    	AllSoundsSetupBombLoad();
    	AllSoundsBaseLoad();
    	AllSoundsResourseComeLoad();
    	
    	FillSelect("selPoints", "getpoints", "id", "Name", "");
    	FillSelect("selResource", "getresources", "id", "Name", "");
    	FillSelect("selSoundBase", "getsoundsbase", "id", "NamePreset", "");
    	FillSelect("selSoundsRound", "getsoundsround", "id", "NamePreset", "");
    	
    })
    
	function AllSoundsRoundLoad(){ 
		
		$('#soundsround table').find('tr:gt(0)').remove();
        $.ajax({async: true, url: "api.php?action=getsoundsround", success:function(response){
        	var soundsround = $.parseJSON(response);
            for (var i in soundsround){
                $('#soundsround table').append('<tr>'
                    +'<td style="display: none;">'+soundsround[i].id+'</td>'
                	+'<td style="text-align: center;">'+(parseInt(i) + 1)+'</td>'
                    +'<td>'+soundsround[i].NamePreset+'</td>'
                    +'<td style="display: none;">'+soundsround[i].BackSound+'</td>'
                    +'<td style="display: none;">'+soundsround[i].BackSound10+'</td>'
                    +'<td style="display: none;">'+soundsround[i].RedReady+'</td>'
                    +'<td style="display: none;">'+soundsround[i].BlueReady+'</td>'
                    +'<td style="display: none;">'+soundsround[i].RoundStart+'</td>'
                    +'<td style="display: none;">'+soundsround[i].RedWin+'</td>'
                    +'<td style="display: none;">'+soundsround[i].BlueWin+'</td>'
                    +'<td style="display: none;">'+soundsround[i].Draw+'</td>'
                    +'<td style="display: none;">'+soundsround[i].RoundStop+'</td>'
                    +'<td style="display: none;">'+soundsround[i].RoundComplete+'</td>'
                    +'<td align="center">'
                    +'<img id="imgEditSoundRound" src="img/edit.png" title="Редактировать звук раунда" />&nbsp;'
                    +'<img id="imgDeleteSoundRound" src="img/delete.png" title="Удалить звук раунда" />&nbsp;'
                    +'</td>'
                    +'</tr>');
            }
        }});
	}
	
	function AllSoundsTimeLoad(){ 
		
		$('#soundtimes table').find('tr:gt(0)').remove();
        $.ajax({async: true, url: "api.php?action=getsoundtimes", success:function(response){
        	var soundtimes = $.parseJSON(response);
            for (var i in soundtimes){
                $('#soundtimes table').append('<tr>'
                	+'<td style="display: none;">'+soundtimes[i].id+'</td>'
                	+'<td style="text-align: center;">'+(parseInt(i) + 1)+'</td>'
                    +'<td>'+soundtimes[i].NamePreset+'</td>'
                    +'<td>'+soundtimes[i].TimeToEnd+'</td>'
                    +'<td>'+soundtimes[i].File+'</td>'
                    +'<td style="display: none;">'+soundtimes[i].NamePresetId+'</td>'
                    +'<td align="center">'
                    +'<img id="imgEditSoundTime" src="img/edit.png" title="Редактировать звук оповещения" />&nbsp;'
                    +'<img id="imgDeleteSoundTime" src="img/delete.png" title="Удалить звук оповещения" />&nbsp;'
                    +'</td>'
                    +'</tr>');
            }
        }});
	}
	
	function AllSoundsPoint1Load(){ 
		
		$('#soundspoint1 table').find('tr:gt(0)').remove();
        $.ajax({async: true, url: "api.php?action=getsoundspoint1", success:function(response){
        	var soundspoint1 = $.parseJSON(response);
            for (var i in soundspoint1){
                $('#soundspoint1 table').append('<tr>'
                	+'<td style="display: none">'+soundspoint1[i].id+'</td>'
                	+'<td style="text-align: center;">'+(parseInt(i) + 1)+'</td>'
                	+'<td>'+soundspoint1[i].NamePreset+'</td>'
                    +'<td>'+soundspoint1[i].PointName+'</td>'
                    +'<td>'+soundspoint1[i].RedSound+'</td>'
                    +'<td>'+soundspoint1[i].BlueSound+'</td>'
                    +'<td style="display: none">'+soundspoint1[i].PointId+'</td>'
                    +'<td align="center">'
                    +'<img id="imgEditSoundPoint1" src="img/edit.png" title="Редактировать звук точки" />&nbsp;'
                    +'<img id="imgDeleteSoundPoint1" src="img/delete.png" title="Удалить звук точки" />&nbsp;'
                    +'</td>'
                    +'</tr>');
            }
        }});
	}
	
	function AllSoundsPoint2Load(){ 
		
		$('#soundpoint2 table').find('tr:gt(0)').remove();
        $.ajax({async: true, url: "api.php?action=getsoundpoint2", success:function(response){
        	var soundpoint2 = $.parseJSON(response);
            for (var i in soundpoint2){
                $('#soundpoint2 table').append('<tr>'
                	+'<td style="display: none">'+soundpoint2[i].id+'</td>'
                	+'<td style="text-align: center;">'+(parseInt(i) + 1)+'</td>'
                	+'<td>'+soundpoint2[i].NamePreset+'</td>'
                    +'<td>'+soundpoint2[i].RedLead+'</td>'
                    +'<td>'+soundpoint2[i].BlueLead+'</td>'
                    +'<td>'+soundpoint2[i].Period+'</td>'
                    +'<td align="center">'
                    +'<img id="imgEditSoundPoint2" src="img/edit.png" title="Редактировать звук точки" />&nbsp;'
                    +'<img id="imgDeleteSoundPoint2" src="img/delete.png" title="Удалить звук точки" />&nbsp;'
                    +'</td>'
                    +'</tr>');
            }
        }});
	}
	
	function AllSoundsTakeFlagLoad(){
		
		$('#soundtakeflag table').find('tr:gt(0)').remove();
        $.ajax({async: true, url: "api.php?action=getsoundstakeflag", success:function(response){
        	var soundtakeflag = $.parseJSON(response);
            for (var i in soundtakeflag){
                $('#soundtakeflag table').append('<tr>'
                    +'<td style="display: none">'+soundtakeflag[i].id+'</td>'
                	+'<td style="text-align: center;">'+(parseInt(i) + 1)+'</td>'
                    +'<td>'+soundtakeflag[i].NamePreset+'</td>'
                    +'<td style="display: none;">'+soundtakeflag[i].RedTakeFlag+'</td>'
                    +'<td style="display: none;">'+soundtakeflag[i].RedMissFlag+'</td>'
                    +'<td style="display: none;">'+soundtakeflag[i].RedComplete+'</td>'
                    +'<td style="display: none;">'+soundtakeflag[i].RedTakePoint+'</td>'
                    +'<td style="display: none;">'+soundtakeflag[i].BlueTakeFlag+'</td>'
                    +'<td style="display: none;">'+soundtakeflag[i].BlueMissFlag+'</td>'
                    +'<td style="display: none;">'+soundtakeflag[i].BlueComplete+'</td>'
                    +'<td style="display: none;">'+soundtakeflag[i].BlueTakePoint+'</td>'
                    +'<td style="display: none;">'+soundtakeflag[i].RedReadySetup+'</td>'
                    +'<td style="display: none;">'+soundtakeflag[i].BlueReadySetup+'</td>'

                    +'<td align="center">'
                    +'<img id="imgEditSoundTakeFlag" src="img/edit.png" title="Редактировать звук захвата флага" />&nbsp;'
                    +'<img id="imgDeleteSoundTakeFlag" src="img/delete.png" title="Удалить звук захвата флага" />&nbsp;'
                    +'</td>'
                    +'</tr>');
            }
        }});
	}
	
	function AllSoundsTakeHomeLoad(){ 
		
		$('#soundtakehome table').find('tr:gt(0)').remove();
        $.ajax({async: true, url: "api.php?action=getsoundtakehome", success:function(response){
        	var soundtakehome = $.parseJSON(response);
            for (var i in soundtakehome){
                $('#soundtakehome table').append('<tr>'
                	+'<td style="display: none;">'+soundtakehome[i].id+'</td>'
                	+'<td style="text-align: center;">'+(parseInt(i) + 1)+'</td>'
                    +'<td>'+soundtakehome[i].NamePreset+'</td>'
                    +'<td>'+soundtakehome[i].PointName+'</td>'
                    +'<td>'+soundtakehome[i].File+'</td>'
                    +'<td style="display: none">'+soundtakehome[i].PointId+'</td>'
                    +'<td align="center">'
                    +'<img id="imgEditSoundTakeHome" src="img/edit.png" title="Редактировать звук уничтожения домов" />&nbsp;'
                    +'<img id="imgDeleteSoundTakeHome" src="img/delete.png" title="Удалить звук уничтожения домов" />&nbsp;'
                    +'</td>'
                    +'</tr>');
            }
        }});
	}
	
	function AllSoundsSurvLoad(){ 
		
		$('#soundsurv table').find('tr:gt(0)').remove();
        $.ajax({async: true, url: "api.php?action=getsoundsurv", success:function(response){
        	var soundsurv = $.parseJSON(response);
            for (var i in soundsurv){
                $('#soundsurv table').append('<tr>'
                	+'<td style="display: none;">'+soundsurv[i].id+'</td>'
                	+'<td style="text-align: center;">'+(parseInt(i) + 1)+'</td>'
                    +'<td>'+soundsurv[i].NamePreset+'</td>'
                    +'<td>'+soundsurv[i].File+'</td>'
                    +'<td align="center">'
                    +'<img id="imgEditSoundSurv" src="img/edit.png" title="Редактировать звук выживания" />&nbsp;'
                    +'<img id="imgDeleteSoundSurv" src="img/delete.png" title="Удалить звук >выживания" />&nbsp;'
                    +'</td>'
                    +'</tr>');
            }
        }});
	}
	
	
	function AllSoundsSetupBombLoad(){
		
		$('#soundsetupbomb table').find('tr:gt(0)').remove();
        $.ajax({async: true, url: "api.php?action=getsoundssetupbomb", success:function(response){
        	var soundsetupbomb = $.parseJSON(response);
            for (var i in soundsetupbomb){
                $('#soundsetupbomb table').append('<tr>'
                    +'<td style="display: none">'+soundsetupbomb[i].id+'</td>'
                	+'<td style="text-align: center;">'+(parseInt(i) + 1)+'</td>'
                    +'<td>'+soundsetupbomb[i].NamePreset+'</td>'
                    +'<td style="display: none;">'+soundsetupbomb[i].RedTakeBomb+'</td>'
                    +'<td style="display: none;">'+soundsetupbomb[i].RedSetupBomb+'</td>'
                    +'<td style="display: none;">'+soundsetupbomb[i].RedMakeBomb+'</td>'
                    +'<td style="display: none;">'+soundsetupbomb[i].RedLostBomb+'</td>'
                    +'<td style="display: none;">'+soundsetupbomb[i].RedDefuse+'</td>'
                    +'<td style="display: none;">'+soundsetupbomb[i].BlueTakeBomb+'</td>'
                    +'<td style="display: none;">'+soundsetupbomb[i].BlueSetupBomb+'</td>'
                    +'<td style="display: none;">'+soundsetupbomb[i].BlueMakeBomb+'</td>'
                    +'<td style="display: none;">'+soundsetupbomb[i].BlueLostBomb+'</td>'
                    +'<td style="display: none;">'+soundsetupbomb[i].BlueDefuse+'</td>'
                    +'<td style="display: none;">'+soundsetupbomb[i].Explosion+'</td>'
                    +'<td style="display: none;">'+soundsetupbomb[i].Beep+'</td>'
                    +'<td align="center">'
                    +'<img id="imgEditSoundSetupBomb" src="img/edit.png" title="Редактировать звук установки бомбы" />&nbsp;'
                    +'<img id="imgDeleteSoundSetupBomb" src="img/delete.png" title="Удалить звук >установки бомбы" />&nbsp;'
                    +'</td>'
                    +'</tr>');
            }
        }});
	}
	
	function AllSoundsBaseLoad(){ 
		
		$('#soundsbase table').find('tr:gt(0)').remove();
        $.ajax({async: true, url: "api.php?action=getsoundsbase", success:function(response){
        	var soundsbase = $.parseJSON(response);
            for (var i in soundsbase){
                $('#soundsbase table').append('<tr>'
                	+'<td style="display: none">'+soundsbase[i].id+'</td>'
                	+'<td style="text-align: center;">'+(parseInt(i) + 1)+'</td>'
                	+'<td>'+soundsbase[i].NamePreset+'</td>'
                    +'<td>'+soundsbase[i].ReFillReaspawn+'</td>'
                    +'<td>'+soundsbase[i].EmptyRespawn+'</td>'
                    +'<td align="center">'
                    +'<img id="imgEditSoundBase" src="img/edit.png" title="Редактировать звук базы" />&nbsp;'
                    +'<img id="imgDeleteSoundBase" src="img/delete.png" title="Удалить звук базы" />&nbsp;'
                    +'</td>'
                    +'</tr>');
            }
        }});
		
	}
	
	function AllSoundsResourseComeLoad(){ 
		
		$('#soundsresoursecome table').find('tr:gt(0)').remove();
        $.ajax({async: true, url: "api.php?action=getsoundsresoursecome", success:function(response){
        	var soundsresoursecome = $.parseJSON(response);
            for (var i in soundsresoursecome){
                $('#soundsresoursecome table').append('<tr>'
                	+'<td style="display: none">'+soundsresoursecome[i].id+'</td>'
                	+'<td style="text-align: center;">'+(parseInt(i) + 1)+'</td>'
                	+'<td>'+soundsresoursecome[i].ResourceName+'</td>'
                    +'<td>'+soundsresoursecome[i].File+'</td>'
                    +'<td>'+soundsresoursecome[i].SoundBaseName+'</td>'
                    +'<td style="display: none">'+soundsresoursecome[i].ResourseId+'</td>'
                    +'<td style="display: none">'+soundsresoursecome[i].IdSoundBase+'</td>'
                    +'<td align="center">'
                    +'<img id="imgEditSoundResourseCome" src="img/edit.png" title="Редактировать звук прихода ресурсов" />&nbsp;'
                    +'<img id="imgDeleteSoundResourseCome" src="img/delete.png" title="Удалить звук прихода ресурсов" />&nbsp;'
                    +'</td>'
                    +'</tr>');
            }
        }});
		
	}
	
	
    
    function FillPoints(selected, callback){
    	var objSel = $("#selPoints, #selPoints2");
        objSel.html("");
        
        $.ajax({async: true, url: "api.php?action=getpoints", success: function(response){
        	
        	var types = $.parseJSON(response);
            for (var i in types){
	        	var itemType = $("<option></option>").attr("value", types[i].id).text(types[i].Name);
	        	if (types[i].Name == selected) itemType.attr("selected", "selected");
	        	
	        	objSel.append(itemType);
	        }
	        
	        if (callback) callback();
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
		        	if (items[i][nameField] == selectedItem) item.attr("selected", "selected");
		        	
		        	objSel.append(item);
		        }
	    	});
		}});
	}
	
	$(document).on("click", "#lnkAddSoundRound",function(){
		
		soundround_id = -1;
		
		$("#soundsround tr:not(:first)").css("display", "none");
        $("#divSoundRoundItem span").html("Добавление звука раунда");
        $("#divSoundRoundItem table tr td input").val("");
        $("#divSoundRoundItem").show("fast");
    });
    $(document).on("click", "#lnkAddSoundTime",function(){
		
		soundtime_id = -1;
		
		$("#soundtimes tr:not(:first)").css("display", "none");
        $("#divSoundTimeItem span").html("Добавление звука оповещения");
        $("#divSoundTimeItem table tr td input").val("");
        $("#divSoundTimeItem").show("fast");
    });
    
    
    $(document).on("click", "#lnkAddSoundPoint1",function(){
		
		soundpoint1_id = -1;
		
		$("#soundspoint1 tr:not(:first)").css("display", "none");
        $("#divSoundPoint1Item span").html("Добавление звука точки");
        $("#divSoundPoint1Item table tr td input").val("");
        $("#divSoundPoint1Item").show("fast");
    });
    
    $(document).on("click", "#lnkAddSoundPoint2",function(){
		
		soundpoint2_id = -1;
		
		$("#soundpoint2 tr:not(:first)").css("display", "none");
        $("#divSoundPoint2Item span").html("Добавление звука точки");
        $("#divSoundPoint2Item table tr td input").val("");
        $("#divSoundPoint2Item").show("fast");
    });
    
    
    $(document).on("click", "#lnkAddSoundTakeFlag",function(){
		
		soundtakeflag_id = -1;
		
		$("#soundtakeflag tr:not(:first)").css("display", "none");
        $("#divSoundTakeFlagItem span").html("Добавление звука захвата флага");
        $("#divSoundTakeFlagItem table tr td input").val("");
        $("#divSoundTakeFlagItem").show("fast");
    });
    
    $(document).on("click", "#lnkAddSoundTakeHome",function(){
		
		soundtakehome_id = -1;
		
		$("#soundtakehome tr:not(:first)").css("display", "none");
        $("#divSoundTakeHomeItem span").html("Добавление звука уничтожения домов");
        $("#divSoundTakeHomeItem table tr td input").val("");
        $("#divSoundTakeHomeItem").show("fast");
    });
    
    $(document).on("click", "#lnkAddSoundSurv",function(){
		
		soundsurv_id = -1;
		
		$("#soundsurv tr:not(:first)").css("display", "none");
        $("#divSoundSurvItem span").html("Добавление звука выживания");
        $("#divSoundSurvItem table tr td input").val("");
        $("#divSoundSurvItem").show("fast");
    });
    
    $(document).on("click", "#lnkAddSoundSetupBomb",function(){
		
		soundsetupbomb_id = -1;
		
		$("#soundsetupbomb tr:not(:first)").css("display", "none");
        $("#divSoundSetupBombItem span").html("Добавление звука установки бомбы");
        $("#divSoundSetupBombItem table tr td input").val("");
        $("#divSoundSetupBombItem").show("fast");
    });
    
    $(document).on("click", "#lnkAddSoundBase",function(){
		
		soundbase_id = -1;
		
		$("#soundsbase tr:not(:first)").css("display", "none");
        $("#divSoundBaseItem span").html("Добавление звука базы");
        $("#divSoundBaseItem table tr td input").val("");
        $("#divSoundBaseItem").show("fast");
    });
    
    $(document).on("click", "#lnkAddSoundResourseCome",function(){
		
		soundresoursecome_id = -1;
		
		$("#soundsresoursecome tr:not(:first)").css("display", "none");
        $("#divSoundResourseComeItem span").html("Добавление звука прихода ресурсов");
        $("#divSoundResourseComeItem table tr td input").val("");
        $("#divSoundResourseComeItem").show("fast");
    });
    
    
    $(document).on("click", "#lnkHideSoundRoundItem",function(){
        $("#divSoundRoundItem").hide("fast");
        $("#soundsround tr").css("display", "");
    });
    $(document).on("click", "#lnkHideSoundTimeItem",function(){
        $("#divSoundTimeItem").hide("fast");
        $("#soundtimes tr").css("display", "");
    });
    $(document).on("click", "#lnkHideSoundPoint1Item",function(){
        $("#divSoundPoint1Item").hide("fast");
        $("#soundspoint1 tr").css("display", "");
    });
    $(document).on("click", "#lnkHideSoundPoint2Item",function(){
        $("#divSoundPoint2Item").hide("fast");
        $("#soundpoint2 tr").css("display", "");
    });
    $(document).on("click", "#lnkHideSoundTakeFlagItem",function(){
        $("#divSoundTakeFlagItem").hide("fast");
        $("#soundtakeflag tr").css("display", "");
    });
    $(document).on("click", "#lnkHideSoundTakeHomeItem",function(){
        $("#divSoundTakeHomeItem").hide("fast");
        $("#soundtakehome tr").css("display", "");
    });
    $(document).on("click", "#lnkHideSoundSurvItem",function(){
        $("#divSoundSurvItem").hide("fast");
        $("#soundsurv tr").css("display", "");
    });
    $(document).on("click", "#lnkHideSoundSetupBombItem",function(){
        $("#divSoundSetupBombItem").hide("fast");
        $("#soundsetupbomb tr").css("display", "");
    });
    $(document).on("click", "#lnkHideSoundBaseItem",function(){
        $("#divSoundBaseItem").hide("fast");
        $("#soundsbase tr").css("display", "");
    });
    $(document).on("click", "#lnkHideSoundResourseCome",function(){
        $("#divSoundResourseComeItem").hide("fast");
        $("#soundsresoursecome tr").css("display", "");
    });
    
    
    $(document).on("click", "#imgEditSoundRound",function(){
    	
        soundround_id = $(this).parent().siblings().eq(0).html();
        		
        $("#divSoundRoundItem span").html("Редактирование звука раунда");
        var inputs = $("#divSoundRoundItem table tr td input");
        inputs.eq(0).val($(this).parent().siblings().eq(2).text());
        inputs.eq(1).val($(this).parent().siblings().eq(3).text());
        inputs.eq(2).val($(this).parent().siblings().eq(4).text());
        inputs.eq(3).val($(this).parent().siblings().eq(5).text());
        inputs.eq(4).val($(this).parent().siblings().eq(6).text());
        inputs.eq(5).val($(this).parent().siblings().eq(7).text());
        inputs.eq(6).val($(this).parent().siblings().eq(8).text());
        inputs.eq(7).val($(this).parent().siblings().eq(9).text());
        inputs.eq(8).val($(this).parent().siblings().eq(10).text());
        inputs.eq(9).val($(this).parent().siblings().eq(11).text());
        inputs.eq(10).val($(this).parent().siblings().eq(12).text());
        
        
        $("#divSoundRoundItem").show("fast");
        
        var tohide = $("#soundsround tr:gt(0):not()").filter(function() { return $(this).children().eq(0).text() != soundround_id });
        tohide.css("display", "none");
    });
    
    $(document).on("click", "#imgEditSoundTime",function(){
    	
        soundtime_id = $(this).parent().siblings().eq(0).html();
        
        $("#divSoundTimeItem span").html("Редактирование звука оповещения");
        var inputs = $("#divSoundTimeItem table tr td input");
        inputs.eq(0).val($(this).parent().siblings().eq(3).text());
        inputs.eq(1).val($(this).parent().siblings().eq(4).text());
        
        $("#divSoundTimeItem #selSoundsRound").val($(this).parent().siblings().eq(5).text());
        
        $("#divSoundTimeItem").show("fast");
        
        var tohide = $("#soundtimes tr:gt(0):not()").filter(function() { return $(this).children().eq(0).text() != soundtime_id });
        tohide.css("display", "none");
    });
    
   	$(document).on("click", "#imgEditSoundPoint1",function(){
    	
        soundpoint1_id = $(this).parent().siblings().eq(0).html();
        		
        $("#divSoundPoint1Item span").html("Редактирование звука точки");
        var inputs = $("#divSoundPoint1Item table tr td input");
        inputs.eq(0).val($(this).parent().siblings().eq(2).text());
        inputs.eq(1).val($(this).parent().siblings().eq(4).text());
        inputs.eq(2).val($(this).parent().siblings().eq(5).text());
        
        $("#selPoints").val($(this).parent().siblings().eq(6).text());
        
        $("#divSoundPoint1Item").show("fast");
        
        var tohide = $("#soundspoint1 tr:gt(0):not()").filter(function() { return $(this).children().eq(0).text() != soundpoint1_id });
        tohide.css("display", "none");
    });
    
    
    $(document).on("click", "#imgEditSoundPoint2",function(){
    	
        soundpoint2_id = $(this).parent().siblings().eq(0).html();
        		
        $("#divSoundPoint2Item span").html("Редактирование звука точки");
        var inputs = $("#divSoundPoint2Item table tr td input");
        inputs.eq(0).val($(this).parent().siblings().eq(2).text());
        inputs.eq(1).val($(this).parent().siblings().eq(3).text());
        inputs.eq(2).val($(this).parent().siblings().eq(4).text());
        inputs.eq(3).val($(this).parent().siblings().eq(5).text());
        
        $("#divSoundPoint2Item").show("fast");
        
        var tohide = $("#soundpoint2 tr:gt(0):not()").filter(function() { return $(this).children().eq(0).text() != soundpoint2_id });
        tohide.css("display", "none");
    });
    
    $(document).on("click", "#imgEditSoundTakeFlag",function(){
    	
        soundtakeflag_id = $(this).parent().siblings().eq(0).html();
        
        $("#divSoundTakeFlagItem span").html("Редактирование звука захвата флага");
        var inputs = $("#divSoundTakeFlagItem table tr td input");
        inputs.eq(0).val($(this).parent().siblings().eq(2).text());
        inputs.eq(1).val($(this).parent().siblings().eq(3).text());
        inputs.eq(2).val($(this).parent().siblings().eq(4).text());
        inputs.eq(3).val($(this).parent().siblings().eq(5).text());
        inputs.eq(4).val($(this).parent().siblings().eq(6).text());
        inputs.eq(5).val($(this).parent().siblings().eq(7).text());
        inputs.eq(6).val($(this).parent().siblings().eq(8).text());
        inputs.eq(7).val($(this).parent().siblings().eq(9).text());
        inputs.eq(8).val($(this).parent().siblings().eq(10).text());
        inputs.eq(9).val($(this).parent().siblings().eq(11).text());
        inputs.eq(10).val($(this).parent().siblings().eq(12).text());
        
        $("#divSoundTakeFlagItem").show("fast");
        
        var tohide = $("#soundtakeflag tr:gt(0):not()").filter(function() { return $(this).children().eq(0).text() != soundtakeflag_id });
        tohide.css("display", "none");
    });
    
    
    $(document).on("click", "#imgEditSoundTakeHome",function(){
    	
        soundtakehome_id = $(this).parent().siblings().eq(0).html();
        
        $("#divSoundTakeHomeItem span").html("Редактирование звука уничтожения домов");
        var inputs = $("#divSoundTakeHomeItem table tr td input");
        inputs.eq(0).val($(this).parent().siblings().eq(2).text());
        inputs.eq(1).val($(this).parent().siblings().eq(4).text());
        $("#divSoundTakeHomeItem #selPoints").val($(this).parent().siblings().eq(5).text());
        
        $("#divSoundTakeHomeItem").show("fast");
        
        var tohide = $("#soundtakehomes tr:gt(0):not()").filter(function() { return $(this).children().eq(0).text() != soundtakehome_id });
        tohide.css("display", "none");
    });
    
    $(document).on("click", "#imgEditSoundSurv",function(){
    	
        soundsurv_id = $(this).parent().siblings().eq(0).html();
        
        $("#divSoundSurvItem span").html("Редактирование звука выживания");
        var inputs = $("#divSoundSurvItem table tr td input");
        inputs.eq(0).val($(this).parent().siblings().eq(2).text());
        inputs.eq(1).val($(this).parent().siblings().eq(3).text());
        
        $("#divSoundSurvItem").show("fast");
        
        var tohide = $("#soundsurvs tr:gt(0):not()").filter(function() { return $(this).children().eq(0).text() != soundsurv_id });
        tohide.css("display", "none");
    });
    
    $(document).on("click", "#imgEditSoundSetupBomb",function(){
    	
        soundsetupbomb_id = $(this).parent().siblings().eq(0).html();
        
        $("#divSoundSetupBombItem span").html("Редактирование звука установки бомбы");
        var inputs = $("#divSoundSetupBombItem table tr td input");
        inputs.eq(0).val($(this).parent().siblings().eq(2).text());
        inputs.eq(1).val($(this).parent().siblings().eq(3).text());
        inputs.eq(2).val($(this).parent().siblings().eq(4).text());
        inputs.eq(3).val($(this).parent().siblings().eq(5).text());
        inputs.eq(4).val($(this).parent().siblings().eq(6).text());
        inputs.eq(5).val($(this).parent().siblings().eq(7).text());
        inputs.eq(6).val($(this).parent().siblings().eq(8).text());
        inputs.eq(7).val($(this).parent().siblings().eq(9).text());
        inputs.eq(8).val($(this).parent().siblings().eq(10).text());
        inputs.eq(9).val($(this).parent().siblings().eq(11).text());
        inputs.eq(10).val($(this).parent().siblings().eq(12).text());
        inputs.eq(11).val($(this).parent().siblings().eq(13).text());
        inputs.eq(12).val($(this).parent().siblings().eq(14).text());
        
        $("#divSoundSetupBombItem").show("fast");
        
        var tohide = $("#soundsetupbomb tr:gt(0):not()").filter(function() { return $(this).children().eq(0).text() != soundsetupbomb_id });
        tohide.css("display", "none");
    });
    
    
    $(document).on("click", "#imgEditSoundBase",function(){
    	
        soundbase_id = $(this).parent().siblings().eq(0).html();
        		
        $("#divSoundBaseItem span").html("Редактирование звука базы");
        var inputs = $("#divSoundBaseItem table tr td input");
        inputs.eq(0).val($(this).parent().siblings().eq(2).text());
        inputs.eq(1).val($(this).parent().siblings().eq(3).text());
        inputs.eq(2).val($(this).parent().siblings().eq(4).text());
        
        $("#divSoundBaseItem").show("fast");
        
        var tohide = $("#soundsbase tr:gt(0):not()").filter(function() { return $(this).children().eq(1).text() != soundbase_id });
        tohide.css("display", "none");
    });
    
    $(document).on("click", "#imgEditSoundResourseCome",function(){
    	
        soundresoursecome_id = $(this).parent().siblings().eq(0).html();
        		
        $("#divSoundResourseComeItem span").html("Редактирование звука прихода ресурсов");
        $("#divSoundResourseComeItem table tr td input").eq(0).val($(this).parent().siblings().eq(3).text());
        $("#divSoundResourseComeItem table tr td select").eq(0).val($(this).parent().siblings().eq(5).text());
        $("#divSoundResourseComeItem table tr td select").eq(1).val($(this).parent().siblings().eq(6).text());
        
        
        $("#divSoundResourseComeItem").show("fast");
        
        var tohide = $("#soundsresoursecome tr:gt(0):not()").filter(function() { return $(this).children().eq(1).text() != soundbase_id });
        tohide.css("display", "none");
    });
    
    
    $(document).on("click", "#lnkSaveSoundRoundItem",function(){
    	
    	var inputs = $('#divSoundRoundItem table tr td input');
    	var name = inputs.eq(0).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var BackSound = inputs.eq(1).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var BackSound10 = inputs.eq(2).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var RedReady = inputs.eq(3).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var BlueReady = inputs.eq(4).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var RoundStart = inputs.eq(5).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var RedWin = inputs.eq(6).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var BlueWin = inputs.eq(7).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var Draw = inputs.eq(8).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var RoundStop = inputs.eq(9).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var RoundComplete = inputs.eq(10).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	
    	if (name == ""){
    		alert("Заполните поле 'Название'");
    		inputs.eq(0).focus();
    		return;
    	}
    	var url = "";
    	if (soundround_id == -1){
    		url = "api.php?action=addsoundround&name=" + name + "&bs=" + BackSound + "&bs10=" + BackSound10 + "&rr=" + RedReady + "&br=" + BlueReady + "&rstart=" + RoundStart + "&rw=" + RedWin + "&bw=" + BlueWin + "&d=" + Draw + "&rstop=" + RoundStop + "&rc=" + RoundComplete;
    	}
    	else
    	{
    		url = "api.php?action=setsoundround&name=" + name + "&bs=" + BackSound + "&bs10=" + BackSound10 + "&rr=" + RedReady + "&br=" + BlueReady + "&rstart=" + RoundStart + "&rw=" + RedWin + "&bw=" + BlueWin + "&d=" + Draw + "&rstop=" + RoundStop + "&rc=" + RoundComplete + "&srid=" + soundround_id;
    	}
    	
    	$.ajax({async: true, url: url, success:function(){ 
            $('#divSoundRoundItem').hide("fast");
    		AllSoundsRoundLoad();
        }})
    });
    
    
    $(document).on("click", "#lnkSaveSoundTimeItem",function(){
    	
    	var inputs = $('#divSoundTimeItem table tr td input');
    	var name = $("#divSoundTimeItem #selSoundsRound").val();
    	var time = inputs.eq(0).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var file = inputs.eq(1).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	
    	if (name == ""){
    		alert("Заполните поле 'Имя пресета'");
    		inputs.eq(0).focus();
    		return;
    	}
    	
    	var url = "";
    	if (soundtime_id == -1){
    		url = "api.php?action=addsoundtime&name=" + name + "&t=" + time + "&f=" + file;
    	}
    	else
    	{
    		url = "api.php?action=setsoundtime&name=" + name + "&t=" + time + "&f=" + file + "&stid=" + soundtime_id;
    	}
    	
    	$.ajax({async: true, url: url, success:function(){ 
            $('#divSoundTimeItem').hide("fast");
    		AllSoundsTimeLoad();
        }})
    });
    
    $(document).on("click", "#lnkSaveSoundPoint1Item",function(){
    	
    	var inputs = $('#divSoundPoint1Item table tr td input');
    	var NamePreset = inputs.eq(0).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var RedSound = inputs.eq(1).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var BlueSound = inputs.eq(2).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var pointId = $("#selPoints").val();
    	
    	if (NamePreset == ""){
    		alert("Заполните поле 'Имя пресета'");
    		inputs.eq(0).focus();
    		return;
    	}
    	
    	var url = "";
    	if (soundpoint1_id == -1){
    		url = "api.php?action=addsoundpoint1&name=" + NamePreset + "&pid=" + pointId + "&rs=" + RedSound + "&bs=" + BlueSound;
    	}
    	else
    	{
    		url = "api.php?action=setsoundpoint1&name=" + NamePreset + "&pid=" + pointId + "&rs=" + RedSound + "&bs=" + BlueSound + "&spid=" + soundpoint1_id;
    	}
    	
    	$.ajax({async: true, url: url, success:function(){ 
            $('#divSoundPoint1Item').hide("fast");
    		AllSoundsPoint1Load();
        }})
    });
    
    $(document).on("click", "#lnkSaveSoundPoint2Item",function(){
    	
    	var inputs = $('#divSoundPoint2Item table tr td input');
    	var NamePreset = inputs.eq(0).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var RedLead = inputs.eq(1).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var BlueLead = inputs.eq(2).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var Period = inputs.eq(3).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	
    	if (NamePreset == ""){
    		alert("Заполните поле 'Имя пресета'");
    		inputs.eq(0).focus();
    		return;
    	}
    	
    	var url = "";
    	if (soundpoint2_id == -1){
    		url = "api.php?action=addsoundpoint2&name=" + NamePreset + "&rl=" + RedLead + "&bl=" + BlueLead + "&p=" + Period;
    	}
    	else
    	{
    		url = "api.php?action=setsoundpoint2&name=" + NamePreset + "&rl=" + RedLead + "&bl=" + BlueLead + "&p=" + Period + "&spid=" + soundpoint2_id;
    	}
    	
    	$.ajax({async: true, url: url, success:function(){ 
            $('#divSoundPoint2Item').hide("fast");
    		AllSoundsPoint2Load();
        }})
    });
    
    
    $(document).on("click", "#lnkSaveSoundTakeFlagItem",function(){
    	
    	var inputs = $('#divSoundTakeFlagItem table tr td input');
    	var name = inputs.eq(0).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var RedTakeFlag = inputs.eq(1).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var RedMissFlag = inputs.eq(2).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var RedComplete = inputs.eq(3).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var RedTakePoint = inputs.eq(4).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var BlueTakeFlag = inputs.eq(5).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var BlueMissFlag = inputs.eq(6).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var BlueComplete = inputs.eq(7).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var BlueTakePoint = inputs.eq(8).val().replace(/^\s+/, "").replace(/\s+$/, "");
        var RedReadySetup = inputs.eq(9).val().replace(/^\s+/, "").replace(/\s+$/, "");
        var BlueReadySetup = inputs.eq(10).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	
    	if (name == ""){
    		alert("Заполните поле 'Имя пресета'");
    		inputs.eq(0).focus();
    		return;
    	}
    	
    	var url = "";
    	if (soundtakeflag_id == -1){
    		url = "api.php?action=addsoundtakeflag&name=" + name + "&rtf=" + RedTakeFlag + "&rmf=" + RedMissFlag + 
    				"&rc=" + RedComplete + "&rtp=" + RedTakePoint + "&btf=" + BlueTakeFlag + "&bmf=" + BlueMissFlag + "&bc=" + BlueComplete + "&bf=" + BlueReadySetup + "&rf=" + RedReadySetup + "&btp=" + BlueTakePoint;
    	}
    	else
    	{
    		url = "api.php?action=setsoundtakeflag&name=" + name + "&rtf=" + RedTakeFlag + "&rmf=" + RedMissFlag + "&rc=" + RedComplete + "&rtp=" + 
    				RedTakePoint + "&btf=" + BlueTakeFlag + "&bmf=" + BlueMissFlag + "&bc=" + BlueComplete + "&btp=" + BlueTakePoint + "&bf=" + BlueReadySetup + "&rf=" + RedReadySetup + "&stfid=" + soundtakeflag_id;
    	}
    	
    	$.ajax({async: true, url: url, success:function(){ 
            $('#divSoundTakeFlagItem').hide("fast");
    		AllSoundsTakeFlagLoad();
        }})
    });
    
    $(document).on("click", "#lnkSaveSoundTakeHomeItem",function(){
    	
    	var inputs = $('#divSoundTakeHomeItem table tr td input');
    	var name = inputs.eq(0).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var file = inputs.eq(1).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var pointId = $("#divSoundTakeHomeItem #selPoints").val();
    	
    	if (name == ""){
    		alert("Заполните поле 'Имя пресета'");
    		inputs.eq(0).focus();
    		return;
    	}
    	
    	var url = "";
    	if (soundtakehome_id == -1){
    		url = "api.php?action=addsoundtakehome&name=" + name + "&pid=" + pointId + "&f=" + file;
    	}
    	else
    	{
    		url = "api.php?action=setsoundtakehome&name=" + name + "&pid=" + pointId + "&f=" + file + "&sthid=" + soundtakehome_id;
    	}
    	
    	$.ajax({async: true, url: url, success:function(){ 
            $('#divSoundTakeHomeItem').hide("fast");
    		AllSoundsTakeHomeLoad();
        }})
    });
    
    
    $(document).on("click", "#lnkSaveSoundSurvItem",function(){
    	
    	var inputs = $('#divSoundSurvItem table tr td input');
    	var name = inputs.eq(0).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var file = inputs.eq(1).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	
    	if (name == ""){
    		alert("Заполните поле 'Имя пресета'");
    		inputs.eq(0).focus();
    		return;
    	}
    	
    	var url = "";
    	if (soundsurv_id == -1){
    		url = "api.php?action=addsoundsurv&name=" + name + "&f=" + file;
    	}
    	else
    	{
    		url = "api.php?action=setsoundsurv&name=" + name + "&f=" + file + "&ssid=" + soundsurv_id;
    	}
    	
    	$.ajax({async: true, url: url, success:function(){ 
            $('#divSoundSurvItem').hide("fast");
    		AllSoundsSurvLoad();
        }})
    });
    
    
    $(document).on("click", "#lnkSaveSoundSetupBombItem",function(){
    	
    	var inputs = $('#divSoundSetupBombItem table tr td input');
    	var name = inputs.eq(0).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var RedTakeBomb = inputs.eq(1).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var RedSetupBomb = inputs.eq(2).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var RedMakeBomb = inputs.eq(3).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var RedLostBomb = inputs.eq(4).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var RedDefuse = inputs.eq(5).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var BlueTakeBomb = inputs.eq(6).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var BlueSetupBomb = inputs.eq(7).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var BlueMakeBomb = inputs.eq(8).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var BlueLostBomb = inputs.eq(9).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var BlueDefuse = inputs.eq(10).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var Explosion = inputs.eq(11).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var Beep = inputs.eq(12).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	
    	if (name == ""){
    		alert("Заполните поле 'Имя пресета'");
    		inputs.eq(0).focus();
    		return;
    	}
    	
    	var url = "";
    	if (soundsetupbomb_id == -1){
    		url = "api.php?action=addsoundsetupbomb&name=" + name + "&rtb=" + RedTakeBomb + "&rsb=" + RedSetupBomb + "&rmb=" + RedMakeBomb + "&rlb=" + RedLostBomb + 
    				"&rd=" + RedDefuse + "&btb=" + BlueTakeBomb + "&bsb=" + BlueSetupBomb + "&bmb=" + BlueMakeBomb + 
    				"&blb=" + BlueLostBomb + "&bd=" + BlueDefuse + "&e=" + Explosion + "&b=" + Beep;
    	}
    	else
    	{
    		url = "api.php?action=setsoundsetupbomb&name=" + name + "&rtb=" + RedTakeBomb + "&rsb=" + RedSetupBomb + "&rmb=" + RedMakeBomb + "&rlb=" + RedLostBomb + 
    				"&rd=" + RedDefuse + "&btb=" + BlueTakeBomb + "&bsb=" + BlueSetupBomb + "&bmb=" + BlueMakeBomb + 
    				"&blb=" + BlueLostBomb + "&bd=" + BlueDefuse + "&e=" + Explosion + "&b=" + Beep + "&ssbid=" + soundsetupbomb_id;
    	}
    	
    	$.ajax({async: true, url: url, success:function(){ 
            $('#divSoundSetupBombItem').hide("fast");
    		AllSoundsSetupBombLoad();
        }})
    });
    
    $(document).on("click", "#lnkSaveSoundBaseItem",function(){
    	
    	var inputs = $('#divSoundBaseItem table tr td input');
    	var NamePreset = inputs.eq(0).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var ReFillReaspawn = inputs.eq(1).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var EmptyRespawn = inputs.eq(2).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	
    	if (NamePreset == ""){
    		alert("Заполните поле 'Имя пресета'");
    		inputs.eq(0).focus();
    		return;
    	}
    	
    	var url = "";
    	if (soundbase_id == -1){
    		url = "api.php?action=addsoundbase&name=" + NamePreset + "&fr=" + ReFillReaspawn + "&er=" + EmptyRespawn;
    	}
    	else
    	{
    		url = "api.php?action=setsoundbase&name=" + NamePreset + "&fr=" + ReFillReaspawn + "&er=" + EmptyRespawn + "&sbid=" + soundbase_id;
    	}
    	
    	$.ajax({async: true, url: url, success:function(){ 
            $('#divSoundBaseItem').hide("fast");
    		AllSoundsBaseLoad();
        }})
    });
    
    $(document).on("click", "#lnkSaveSoundResourseCome",function(){
    	
    	var ResourseId = $('#divSoundResourseComeItem select').eq(0).val();
    	var File = $('#divSoundResourseComeItem table tr td input').eq(0).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var SoundBaseId = $('#divSoundResourseComeItem select').eq(1).val();
    	
    	if (File == ""){
    		alert("Заполните поле 'Файл'");
    		return;
    	}
    	
    	var url = "";
    	if (soundresoursecome_id == -1){
    		url = "api.php?action=addsoundresoursecome&rid=" + ResourseId + "&f=" + File + "&sbid=" + SoundBaseId;
    	}
    	else
    	{
    		url = "api.php?action=setsoundresoursecome&n&rid=" + ResourseId + "&f=" + File + "&sbid=" + SoundBaseId + "&srcid=" + soundresoursecome_id;
    	}
    	
    	$.ajax({async: true, url: url, success:function(){ 
            $('#divSoundResourseComeItem').hide("fast");
    		AllSoundsResourseComeLoad();
        }})
    });
    
    
    $(document).on('click', '#imgDeleteSoundRound',function(){
    	
    	var preset = $(this).parent().siblings().eq(1).text();
    	if (confirm("Удалить пресет '" + preset + "'?")){
	        var soundround_delete_id = $(this).parent().siblings().eq(0).text();
	        var url = "api.php?action=deletesoundround&srid=" + soundround_delete_id;
	        $.ajax({async:true, url: url});
	        
	        $('#soundsround table tr td').filter(function(){return $(this).text()==preset}).parent().hide('fast');
        }
        
    });
    
    
    $(document).on('click', '#imgDeleteSoundTime',function(){
    	
    	var preset = $(this).parent().siblings().eq(2).text();
    	if (confirm("Удалить пресет '" + preset + "'?")){
	        
	        var soundtime_delete_id = $(this).parent().siblings().eq(0).text();
	        var url = "api.php?action=deletesoundtime&stid=" + soundtime_delete_id;
	        $.ajax({async:true, url: url});
	        
	        $(this).parent().parent().hide('fast');
        }
        
    });
    
    
    $(document).on('click', '#imgDeleteSoundPoint1',function(){
    	
    	var sound_name = $(this).parent().siblings().eq(3).text();
    	if (confirm("Удалить звук '" + sound_name + "'?")){
	        
	        var soundpoint1_delete_id = $(this).parent().siblings().eq(0).text();
	        var url = "api.php?action=deletesoundpoint1&spid=" + soundpoint1_delete_id;
	        $.ajax({async:true, url: url});
	        
	        $(this).parent().parent().hide('fast');
        }
        
    });
    
    $(document).on('click', '#imgDeleteSoundPoint2',function(){
    	
    	var preset = $(this).parent().siblings().eq(2).text();
    	if (confirm("Удалить пресет '" + preset + "'?")){
	        
	        var soundpoint2_delete_id = $(this).parent().siblings().eq(0).text();
	        var url = "api.php?action=deletesoundpoint2&spid=" + soundpoint2_delete_id;
	        $.ajax({async:true, url: url});
	        
	        $('#soundpoint2 table tr td').filter(function(){return $(this).text()==preset}).parent().hide('fast');
        }
        
    });
    
    $(document).on('click', '#imgDeleteSoundTakeFlag',function(){
    	
    	var preset = $(this).parent().siblings().eq(2).text();
    	if (confirm("Удалить пресет '" + preset + "'?")){
	        
	        var soundtakeflag_delete_id = $(this).parent().siblings().eq(0).text();
	        var url = "api.php?action=deletesoundtakeflag&stfid=" + soundtakeflag_delete_id;
	        $.ajax({async:true, url: url});
	        
	        $('#soundtakeflag table tr td').filter(function(){return $(this).text()==preset}).parent().hide('fast');
        }
        
    });
    
    $(document).on('click', '#imgDeleteSoundTakeHome',function(){
    	
    	var preset = $(this).parent().siblings().eq(2).text();
    	if (confirm("Удалить пресет '" + preset + "'?")){
	        
	        var soundtakehome_delete_id = $(this).parent().siblings().eq(0).text();
	        var url = "api.php?action=deletesoundtakehome&sthid=" + soundtakehome_delete_id;
	        $.ajax({async:true, url: url});
	        
	        $('#soundtakehome table tr td').filter(function(){return $(this).text()==preset}).parent().hide('fast');
        }
        
    });
    
    $(document).on('click', '#imgDeleteSoundSurv',function(){
    	
    	var preset = $(this).parent().siblings().eq(2).text();
    	if (confirm("Удалить пресет '" + preset + "'?")){
	        
	        var soundsurv_delete_id = $(this).parent().siblings().eq(0).text();
	        var url = "api.php?action=deletesoundsurv&ssid=" + soundsurv_delete_id;
	        $.ajax({async:true, url: url});
	        
	        $('#soundsurv table tr td').filter(function(){return $(this).text()==preset}).parent().hide('fast');
        }
        
    });
    
    
    $(document).on('click', '#imgDeleteSoundSetupBomb',function(){
    	
    	var preset = $(this).parent().siblings().eq(2).text();
    	if (confirm("Удалить пресет '" + preset + "'?")){
	        
	        var soundsetupbomb_delete_id = $(this).parent().siblings().eq(0).text();
	        var url = "api.php?action=deletesoundsetupbomb&ssbid=" + soundsetupbomb_delete_id;
	        $.ajax({async:true, url: url});
	        
	        $('#soundsetupbomb table tr td').filter(function(){return $(this).text()==preset}).parent().hide('fast');
        }
        
    });
    
    $(document).on('click', '#imgDeleteSoundBase',function(){
    	
    	var preset = $(this).parent().siblings().eq(2).text();
    	if (confirm("Удалить пресет '" + preset + "'?")){
	        
	        var soundbase_delete_id = $(this).parent().siblings().eq(0).text();
	        var url = "api.php?action=deletesoundbase&sbid=" + soundbase_delete_id;
	        $.ajax({async:true, url: url});
	        
	        $('#soundsbase table tr td').filter(function(){return $(this).text()==preset}).parent().hide('fast');
        }
    });
    
    $(document).on('click', '#imgDeleteSoundResourseCome',function(){
    	
    	if (confirm("Удалить звук прихода ресурсов?")){
	        
	        var sound_delete_id = $(this).parent().siblings().eq(0).text();
	        var url = "api.php?action=deletesoundsresoursecome&srcid=" + sound_delete_id;
	        $.ajax({async:true, url: url});
	        
	        $(this).parent().parent().hide('fast');
        }
        
    });
})