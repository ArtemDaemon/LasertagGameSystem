$(function(){
	var base_id = 0;
	var point_id = 0;
	var preset_id = 0;
	var weapon_id = 0;
	var resource_id = 0;
	
    $(document).ready(function(){
		
    	AllBasesLoad();
    	AllPointsLoad();
    	AllPresetsLoad();
    	AllWeaponsLoad();
    	AllResourcesLoad();
    	FillResourceTypes();
    	
    })
    
	function AllBasesLoad(){ 
		
		$('#bases table').find('tr:gt(0)').remove();
        $.ajax({async: true, url: "api.php?action=getbases", success:function(response){
        	var bases = $.parseJSON(response);
            for (var i in bases){
                $('#bases table').append('<tr>'
                	+'<td align="center">'+(parseInt(i) + 1)+'</td>'
                    +'<td align="center">'+bases[i].id+'</td>'
                    +'<td>'+bases[i].Name+'</td>'
                    +'<td>'+bases[i].Address+'</td>'
                    +'<td align="center">'
                    +'<img id="imgEditBase" src="img/edit.png" title="Редактировать базу" />&nbsp;'
                    +'<img id="imgDeleteBase" src="img/delete.png" title="Удалить базу" />&nbsp;'
                    +'</td>'
                    +'</tr>');
            }
        }});
		
	}
	
	function AllPointsLoad(){ 
		
		$('#points table').find('tr:gt(0)').remove();
        $.ajax({async: true, url: "api.php?action=getpoints", success:function(response){
        	var points = $.parseJSON(response);
            for (var i in points){
                $('#points table').append('<tr>'
                	+'<td align="center">'+(parseInt(i) + 1)+'</td>'
                    +'<td align="center">'+points[i].id+'</td>'
                    +'<td>'+points[i].Name+'</td>'
                    +'<td>'+points[i].Address+'</td>'
                    +'<td align="center">'
                    +'<img id="imgEditPoint" src="img/edit.png" title="Редактировать точку" />&nbsp;'
                    +'<img id="imgDeletePoint" src="img/delete.png" title="Удалить точку" />&nbsp;'
                    +'</td>'
                    +'</tr>');
            }
        }});
		
	}
	
	function AllPresetsLoad(){ 
		
		$('#presets table').find('tr:gt(0)').remove();
        $.ajax({async: true, url: "api.php?action=getpresets", success:function(response){
        	var presets = $.parseJSON(response);
            for (var i in presets){
                $('#presets table').append('<tr>'
                	+'<td align="center">'+(parseInt(i) + 1)+'</td>'
                    +'<td align="center">'+presets[i].id+'</td>'
                    +'<td>'+presets[i].NamePreset+'</td>'
                    +'<td>'+presets[i].SumDamage+'</td>'
                    +'<td>'+presets[i].TimeResetSum+'</td>'
                    +'<td>'+presets[i].Armor+'</td>'
                    +'<td align="center">'
                    +'<img id="imgEditPreset" src="img/edit.png" title="Редактировать пресет" />&nbsp;'
                    +'<img id="imgDeletePreset" src="img/delete.png" title="Удалить пресет" />&nbsp;'
                    +'</td>'
                    +'</tr>');
            }
        }});
		
	}
	
	function AllWeaponsLoad(){ 
		
		$('#weapons table').find('tr:gt(0)').remove();
        $.ajax({async: true, url: "api.php?action=getweapons", success:function(response){
        	var weapons = $.parseJSON(response);
            for (var i in weapons){
                $('#weapons table').append('<tr>'
                	+'<td align="center">'+(parseInt(i) + 1)+'</td>'
                    +'<td align="center">'+weapons[i].id+'</td>'
                    +'<td>'+weapons[i].Name+'</td>'
                    +'<td>'+weapons[i].TagerId+'</td>'
                    +'<td align="center">'
                    +'<img id="imgEditWeapon" src="img/edit.png" title="Редактировать оружие" />&nbsp;'
                    +'<img id="imgDeleteWeapon" src="img/delete.png" title="Удалить оружие" />&nbsp;'
                    +'</td>'
                    +'</tr>');
            }
        }});
		
	}
	
	function AllResourcesLoad(){ 
		
		$('#resources table').find('tr:gt(0)').remove();
        $.ajax({async: true, url: "api.php?action=getresources", success:function(response){
        	var resources = $.parseJSON(response);
            for (var i in resources){
                $('#resources table').append('<tr>'
                	+'<td align="center">'+(parseInt(i) + 1)+'</td>'
                    +'<td align="center">'+resources[i].id+'</td>'
                    +'<td>'+resources[i].Name+'</td>'
                    +'<td>'+resources[i].Type+'</td>'
                    +'<td style="display: none;">'+resources[i].TypeId+'</td>'
                    +'<td align="center">'
                    +'<img id="imgEditResource" src="img/edit.png" title="Редактировать ресурс" />&nbsp;'
                    +'<img id="imgDeleteResource" src="img/delete.png" title="Удалить ресурс" />&nbsp;'
                    +'</td>'
                    +'</tr>');
            }
        }});
		
	}
    
    function FillResourceTypes(selectedType){
    	var objSel = $("#selResourceType");
        objSel.html("");
        
        $.ajax({async: true, url: "api.php?action=getresourcetypes", success: function(response){
        	
        	var types = $.parseJSON(response);
            for (var i in types){
	        	var itemType = $("<option></option>").attr("value", types[i].id).text(types[i].Name);
	        	if (types[i].Name == selectedType) itemType.attr("selected", "selected");
	        	
	        	objSel.append(itemType);
	        }
		}});
	}
	
	$(document).on("click", "#lnkAddBase",function(){
		
		base_id = -1;
		
		$("#bases tr:not(:first)").css("display", "none");
        $("#divBaseItem span").html("Добавление базы");
        $("#divBaseItem table tr td input").val("");
        $("#divBaseItem").show("fast");
    });
    $(document).on("click", "#lnkAddPoint",function(){
		
		point_id = -1;
		
		$("#points tr:not(:first)").css("display", "none");
        $("#divPointItem span").html("Добавление точки");
        $("#divPointItem table tr td input").val("");
        $("#divPointItem").show("fast");
    });
    $(document).on("click", "#lnkAddPreset",function(){
		
		preset_id = -1;
		
		$("#presets tr:not(:first)").css("display", "none");
        $("#divPresetItem span").html("Добавление пресета");
        $("#divPresetItem table tr td input").val("");
        $("#divPresetItem table tr td input").eq(1).val("0");
        $("#divPresetItem table tr td input").eq(2).val("0");
        $("#divPresetItem table tr td input").eq(3).val("0");
        
        $("#divPresetItem").show("fast");
    });
    $(document).on("click", "#lnkAddWeapon",function(){
		
		weapon_id = -1;
		
		$("#weapons tr:not(:first)").css("display", "none");
        $("#divWeaponItem span").html("Добавление оружия");
        $("#divWeaponItem table tr td input").val("");
        $("#divWeaponItem").show("fast");
    });
    $(document).on("click", "#lnkAddResource",function(){
		
		resource_id = -1;
		
		$("#resources tr:not(:first)").css("display", "none");
        $("#divResourceItem span").html("Добавление ресурса");
        $("#divResourceItem table tr td input").val("");
        $("#divResourceItem").show("fast");
    });
    
    $(document).on("click", "#lnkHideBaseItem",function(){
        $("#divBaseItem").hide("fast");
        $("#bases tr").css("display", "");
    });
    $(document).on("click", "#lnkHidePointItem",function(){
        $("#divPointItem").hide("fast");
        $("#points tr").css("display", "");
    });
    $(document).on("click", "#lnkHidePresetItem",function(){
        $("#divPresetItem").hide("fast");
        $("#presets tr").css("display", "");
    });
    $(document).on("click", "#lnkHideWeaponItem",function(){
        $("#divWeaponItem").hide("fast");
        $("#weapons tr").css("display", "");
    });
    $(document).on("click", "#lnkHideResourceItem",function(){
        $("#divResourceItem").hide("fast");
        $("#resources tr").css("display", "");
    });
    
    
    $(document).on("click", "#imgEditBase",function(){
    	
        base_id = $(this).parent().siblings().eq(1).html();
        		
        $("#divBaseItem span").html("Редактирование базы");
        var inputs = $("#divBaseItem table tr td input");
        inputs.eq(0).val($(this).parent().siblings().eq(2).text());
        inputs.eq(1).val($(this).parent().siblings().eq(3).text());
        
        $("#divBaseItem").show("fast");
        
        var tohide = $("#bases tr:gt(0):not()").filter(function() { return $(this).children().eq(1).text() != base_id });
        tohide.css("display", "none");
    });
    
    $(document).on("click", "#imgEditPoint",function(){
    	
        point_id = $(this).parent().siblings().eq(1).html();
        
        $("#divPointItem span").html("Редактирование точки");
        var inputs = $("#divPointItem table tr td input");
        inputs.eq(0).val($(this).parent().siblings().eq(2).text());
        inputs.eq(1).val($(this).parent().siblings().eq(3).text());
        
        $("#divPointItem").show("fast");
        
        var tohide = $("#points tr:gt(0):not()").filter(function() { return $(this).children().eq(1).text() != point_id });
        tohide.css("display", "none");
    });
    
    $(document).on("click", "#imgEditPreset",function(){
    	
        preset_id = $(this).parent().siblings().eq(1).html();
        		
        $("#divPresetItem span").html("Редактирование пресета");
        var inputs = $("#divPresetItem table tr td input");
        inputs.eq(0).val($(this).parent().siblings().eq(2).text());
        inputs.eq(1).val($(this).parent().siblings().eq(3).text());
        inputs.eq(2).val($(this).parent().siblings().eq(4).text());
        inputs.eq(3).val($(this).parent().siblings().eq(5).text());
        
        $("#divPresetItem").show("fast");
        
        var tohide = $("#presets tr:gt(0):not()").filter(function() { return $(this).children().eq(1).text() != preset_id });
        tohide.css("display", "none");
    });
    
    $(document).on("click", "#imgEditWeapon",function(){
    	
        weapon_id = $(this).parent().siblings().eq(1).html();
        
        $("#divWeaponItem span").html("Редактирование оружия");
        $('#divWeaponItem table tr td input').eq(0).val($(this).parent().siblings().eq(2).text());
        $('#divWeaponItem table tr td input').eq(1).val($(this).parent().siblings().eq(3).text());
        
        $("#divWeaponItem").show("fast");
        
        var tohide = $("#weapons tr:gt(0):not()").filter(function() { return $(this).children().eq(1).text() != weapon_id });
        tohide.css("display", "none");
    });
    
    $(document).on("click", "#imgEditResource",function(){
    	
        resource_id = $(this).parent().siblings().eq(1).html();
        
        $("#divResourceItem span").html("Редактирование ресурса");
        $('#divResourceItem table tr td input').eq(0).val($(this).parent().siblings().eq(2).text());
        $("#divResourceItem select").val($(this).parent().siblings().eq(4).text());
        
        $("#divResourceItem").show("fast");
        
        var tohide = $("#resources tr:gt(0):not()").filter(function() { return $(this).children().eq(1).text() != resource_id });
        tohide.css("display", "none");
    });
    
    
    $(document).on("click", "#lnkSaveBaseItem",function(){
    	
    	var inputs = $('#divBaseItem table tr td input');
    	var name = inputs.eq(0).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var address = inputs.eq(1).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	
    	if (name == ""){
    		alert("Заполните поле 'Название'");
    		inputs.eq(0).focus();
    		return;
    	}
    	
    	var url = "";
    	if (base_id == -1){
    		url = "api.php?action=addbase&name=" + name + "&address=" + address;
    	}
    	else
    	{
    		url = "api.php?action=setbase&name=" + name + "&address=" + address + "&bid=" + base_id;
    	}
    	
    	$.ajax({async: true, url: url, success:function(){ 
            $('#divBaseItem').hide("fast");
    		AllBasesLoad();
        }})
    });
    
    
    $(document).on("click", "#lnkSavePointItem",function(){
    	
    	var inputs = $('#divPointItem table tr td input');
    	var name = inputs.eq(0).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var address = inputs.eq(1).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	
    	if (name == ""){
    		alert("Заполните поле 'Название'");
    		inputs.eq(0).focus();
    		return;
    	}
    	
    	var url = "";
    	if (point_id == -1){
    		url = "api.php?action=addpoint&name=" + name + "&address=" + address;
    	}
    	else
    	{
    		url = "api.php?action=setpoint&name=" + name + "&address=" + address + "&pid=" + point_id;
    	}
    	
    	$.ajax({async: true, url: url, success:function(){ 
            $('#divPointItem').hide("fast");
    		AllPointsLoad();
        }})
    });
    
    $(document).on("click", "#lnkSavePresetItem",function(){
    	
    	var inputs = $('#divPresetItem table tr td input');
    	var name = inputs.eq(0).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var sum = inputs.eq(1).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var time = inputs.eq(2).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var armor = inputs.eq(3).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	
    	
    	if (name == ""){
    		alert("Заполните поле 'Название'");
    		inputs.eq(0).focus();
    		return;
    	}
    	
    	var url = "";
    	if (preset_id == -1){
    		url = "api.php?action=addpreset&name=" + name + "&sum=" + sum + "&time=" + time + "&armor=" + armor;
    	}
    	else
    	{
    		url = "api.php?action=setpreset&name=" + name + "&sum=" + sum + "&time=" + time + "&armor=" + armor + "&pid=" + preset_id;
    	}
    	
    	$.ajax({async: true, url: url, success:function(){ 
            $('#divPresetItem').hide("fast");
    		AllPresetsLoad();
        }})
    });
    
    
    $(document).on("click", "#lnkSaveWeaponItem",function(){
    	
    	var inputs = $('#divWeaponItem table tr td input');
    	var name = inputs.eq(0).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var tagerId = inputs.eq(1).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	
    	if (name == ""){
    		alert("Заполните поле 'Название'");
    		inputs.eq(0).focus();
    		return;
    	}
    	
    	if (tagerId == "") tagerId = 0;
    	
    	var url = "";
    	if (weapon_id == -1){
    		url = "api.php?action=addweapon&name=" + name + "&tid=" + tagerId;
    	}
    	else
    	{
    		url = "api.php?action=setweapon&name=" + name + "&tid=" + tagerId + "&wid=" + weapon_id;
    	}
    	
    	$.ajax({async: true, url: url, success:function(){ 
            $('#divWeaponItem').hide("fast");
    		AllWeaponsLoad();
        }})
    });
    
    
    $(document).on("click", "#lnkSaveResourceItem",function(){
    	
    	var inputs = $('#divResourceItem table tr td input');
    	var name = inputs.eq(0).val().replace(/^\s+/, "").replace(/\s+$/, "");
    	var typeId = $('#divResourceItem table tr td select').eq(0).val();
    	
    	if (name == ""){
    		alert("Заполните поле 'Название'");
    		inputs.eq(0).focus();
    		return;
    	}
    	
    	var url = "";
    	if (resource_id == -1){
    		url = "api.php?action=addresource&name=" + name + "&tid=" + typeId;
    	}
    	else
    	{
    		url = "api.php?action=setresource&name=" + name  + "&tid=" + typeId + "&rid=" + resource_id;
    	}
    	
    	$.ajax({async: true, url: url, success:function(){ 
            $('#divResourceItem').hide("fast");
    		AllResourcesLoad();
        }})
    });
    
    
    $(document).on('click', '#imgDeleteBase',function(){
    	
    	var base = $(this).parent().siblings().eq(2).text();
    	if (confirm("Удалить базу '" + base + "'?")){
	        
	        var base_delete_id = $(this).parent().siblings().eq(1).text();
	        var url = "api.php?action=deletebase&bid=" + base_delete_id;
	        $.ajax({async:true, url: url});
	        
	        $('#bases table tr td').filter(function(){return $(this).text()==base}).parent().hide('fast');
        }
        
    });
    
    
    $(document).on('click', '#imgDeletePoint',function(){
    	
    	var point = $(this).parent().siblings().eq(2).text();
    	if (confirm("Удалить точку '" + point + "'?")){
	        
	        var point_delete_id = $(this).parent().siblings().eq(1).text();
	        var url = "api.php?action=deletepoint&pid=" + point_delete_id;
	        $.ajax({async:true, url: url});
	        
	        $('#points table tr td').filter(function(){return $(this).text()==point}).parent().hide('fast');
        }
        
    });
    
    $(document).on('click', '#imgDeletePreset',function(){
    	
    	var preset = $(this).parent().siblings().eq(2).text();
    	if (confirm("Удалить пресет '" + preset + "'?")){
	        
	        var preset_delete_id = $(this).parent().siblings().eq(1).text();
	        var url = "api.php?action=deletepreset&pid=" + preset_delete_id;
	        $.ajax({async:true, url: url});
	        
	        $('#presets table tr td').filter(function(){return $(this).text()==preset}).parent().hide('fast');
        }
        
    });
    
    $(document).on('click', '#imgDeleteWeapon',function(){
    	
    	var weapon = $(this).parent().siblings().eq(2).text();
    	if (confirm("Удалить оружие '" + weapon + "'?")){
	        
	        var weapon_delete_id = $(this).parent().siblings().eq(1).text();
	        var url = "api.php?action=deleteweapon&wid=" + weapon_delete_id;
	        $.ajax({async:true, url: url});
	        
	        $('#weapons table tr td').filter(function(){return $(this).text()==weapon}).parent().hide('fast');
        }
        
    });
    
    $(document).on('click', '#imgDeleteResource',function(){
    	
    	var resource = $(this).parent().siblings().eq(2).text();
    	if (confirm("Удалить ресурс '" + resource + "'?")){
	        
	        var resource_delete_id = $(this).parent().siblings().eq(1).text();
	        var url = "api.php?action=deleteresource&rid=" + resource_delete_id;
	        $.ajax({async:true, url: url});
	        
	        $('#resources table tr td').filter(function(){return $(this).text()==resource}).parent().hide('fast');
        }
        
    });
    
})