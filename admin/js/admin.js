//Zufallspasswort generieren
function randString(){
	var dataSet = $("#Password_add").attr("data-character-set").split(",");  
	var possible = "";
if($.inArray("a-z", dataSet) >= 0)
	{
		possible += "abcdefghijklmnopqrstuvwxyz";
	}
	
if($.inArray("A-Z", dataSet) >= 0)
	{
		possible += "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	}

if($.inArray("0-9", dataSet) >= 0)
	{
		possible += "0123456789";
	}
	
if($.inArray("#", dataSet) >= 0)
	{
		possible += "!?%&*$#@";
	}

var text = "";
for(var i=0; i < $("#Password_add").attr("data-size"); i++) 
	{
		text += possible.charAt(Math.floor(Math.random() * possible.length));
	}
return text;
}

//Benutzer löschen	
function delete_user(user_id){
	$.confirm({
		boxWidth: "50%",
		useBootstrap: false,	
		title: "Benutzer löschen?",
		content: "Der Benutzer und alle von ihm erstellten Links, Themen und Zuordnungen werden gelöscht!",
		type: "red",
		buttons: 
			{   
				ok: {
						text: "ok!",
						btnClass: "btn-primary",
						keys: ["enter"],
						action: function(){
							$.ajax({
								url: "inc/delete.php?delete_user=1",
								type: "POST",
								data: {"user_id":user_id} ,
								success: function(data)
									{
										console.log(data);
										$("#results").hide("slow");
										$("#user_list").load(" #user_list > *");
										$("#user_list").show("slow");
									},
								});  
							}
					},
				cancel:	
					{
						text: "abbrechen!",
						action: function(){}
					}
			}
		});	
}

//Reihenfolge der Kategorien setzen
 function sendOrderToServer() {
	$("#cat_list").sortable();
	var sortable_data = $("#cat_list").sortable("serialize"); 
	$.ajax({
		url: "inc/update.php?set_cat_order=1",
		type: "POST",
		data: sortable_data,
		success: function(data)
			{
				console.log(data);
				$("#cat_list").sortable("disable");
			},
		}); 
}


//Vorlage löschen	
function delete_template(template_id){
	$.confirm({
		boxWidth: "50%",
		useBootstrap: false,	
		title: "Vorlage löschen?",
		content: "Die Vorlage wird gelöscht!",
		type: "red",
		buttons: 
			{   
				ok: 
					{
						text: "ok!",
						btnClass: "btn-primary",
						keys: ["enter"],
						action: function(){
							$.ajax({
								url: "inc/delete.php?delete_template=1",
								type: "POST",
								data: {"template_id":template_id} ,
								success: function(data)
									{
										console.log(data);
										$("#edit").hide("slow");
										$("#template_list").load(" #template_list > *");
										if($("#template_list").is(":hidden"))
											{
												$("#template_list").show("slow");
											}
									},
								});  
							}
					},
				cancel:	
					{
						text: "abbrechen!",
						action: function(){}
					}
			}
		});	
}

//Podcast löschen	
function delete_podcast(podcast_id, session_podcast){
	$.confirm({
		title: "Podcast löschen?",
		boxWidth: "50%",
		useBootstrap: false,	
		content: "Der Podcast, alle enthaltenen Episoden, Links, Themen und Zuordnungen werden gelöscht!",
		type: "red",
		buttons: 
			{   
				ok: 
					{
						text: "ok!",
						btnClass: "btn-primary",
						keys: ["enter"],
						action: function(){
						$.ajax({
							url: "inc/delete.php?delete_podcast=1",
							type: "POST",
							data: {"podcast_id":podcast_id} ,
							success: function(data)
								{
									console.log(data);
									if(podcast_id !== session_podcast)
										{
											$("#podcast_list").load(" #podcast_list > *");
											$("#podcast_menu").load(" #podcast_menu > *");
										}
									else{
											location.reload();
										}
								},
							});  
						}
					},
				cancel:	
					{
						text: "abbrechen!",
						action: function(){}
					}
			}
		});	
}

//Benutzer bearbeiten
function edituser(user_id){
	var pk = user_id;
	var table = "users";
	jQuery.ajax({
		url: "inc/select.php?edit_user=1",
		data: {	"pk":pk,
				"table":table
			},
		type: "POST",
		success:function(data)
			{
				$("#user_list").hide("slow");
				$("#edit").html(data).show("slow");
			},
		error:function ()
			{
			}
		});										
}

//Vorlage bearbeiten
function edit_template(template_id,podcast_id){
	var pk = template_id;
	var podcast_id = podcast_id;
	var table = "episode_templates";
	jQuery.ajax({
		url: "inc/select.php?edit_template=1",
		data: {	"pk":pk,
				"table":table,
				"podcast_id":podcast_id
			},
		type: "POST",
		success:function(data)
			{
				$("#template_list").hide("slow");
				$("#edit").html(data).show("slow");
			},
		error:function ()
			{
			}
		});										
}

//Episode bearbeiten
function edit_episode(episode_id){
	var pk = episode_id;
	var table = "episoden";
	jQuery.ajax({
		url: "inc/select.php?edit_episode=1",
		data: {"pk":pk,
				"table":table
			},
		type: "POST",
		success:function(data)
			{
				$("#episode_list").hide("slow");
				$("#edit").html(data).show("slow");
				},
		error:function ()
			{
			}
		});										
}

//Episode löschen	
function delete_episode(episode_id){
	$.confirm({
		title: "Episode löschen?",
		boxWidth: "50%",
		useBootstrap: false,	
		content: "Die Episode und alle enthaltenen Beiträge und Themen werden gelöscht!",
		type: "red",
		buttons: 
			{   
				ok:
					{
						text: "ok!",
						btnClass: "btn-primary",
						keys: ["enter"],
						action: function(){
							$.ajax({
								url: "inc/delete.php?delete_episode=1",
								type: "POST",
								data: {"episode_id":episode_id} ,
								success: function(data)
									{
										console.log(data);
										$("#episode_list").load(" #episode_list > *");
										$("#edit").hide("slow");
										if($("#episode_list").is(":hidden"))
											{
												$("#episode_list").show("slow");
											}

									},
								});  
						}
					},
				cancel:	
					{
						text: "abbrechen!",
						action: function(){}
					}
			}
		});	
}

//Kategorien löschen
function delete_category(cat_id){
	$.confirm({
		title: "Kategorie löschen?",
		boxWidth: "50%",
		useBootstrap: false,	
		content: "Die Kategorie und alle Links und Themen dieser Kategorie werden gelöscht!",
		type: "red",
		buttons: 
			{   
				ok: 
					{
						text: "ok!",
						btnClass: "btn-primary",
						keys: ["enter"],
						action: function(){
							jQuery.ajax({
								url: "inc/delete.php?del_category=1",
								data: {"cat_id":cat_id},
								type: "POST",
								success:function(data)
									{
										console.log(data);
										$("#category-"+cat_id).remove();
									},
								error:function ()
									{
								}
							});
						}
					},
				cancel:	
					{
						text: "abbrechen!",
						action: function(){}
					}
			}
		});	
}

//Kategorien der Episode bearbeiten
function edit_episode_cat(cat_id){
	var episode = $("#cat"+cat_id).attr("episode");
	if($("#cat"+cat_id).prop("checked")) 
		{
			$.ajax({
				url: "inc/check.php?add=1",
				type: "POST",
				data: {	"id":cat_id,
						"episode":episode,
						"id_podcast":"ID_EPISODE",
						"table":"episode_categories",
						"column":"ID_CATEGORY",
					},																
				success: function(data)
					{ 
						console.log(data)
					}
				});
		}
	else
		{
			$.ajax({
				url: "inc/check.php?remove=1",
				type: "POST",
				data: {	"id":cat_id,
						"episode":episode,
						"id_podcast":"ID_EPISODE",
						"table":"episode_categories",
						"column":"ID_CATEGORY",
					},
				success: function(data)
					{ 
						console.log(data)
					}
				});
		}
}

//Benutzer der Episode bearbeiten
function edit_episode_user(user_id){
	var episode = $("#user"+user_id).attr("episode");
	if($("#user"+user_id).prop("checked")) 
		{
			$.ajax({
				url: "inc/check.php?add=1",
				type: "POST",
				data: {	"id":user_id,
						"episode":episode,
						"id_podcast":"ID_EPISODE",
						"table":"episode_users",
						"column":"ID_USER",
					},
				success: function(data)
					{
						console.log(data)
					}
				});
		}
	else
		{
			$.ajax({
				url: "inc/check.php?remove=1",
				type: "POST",
				data: {	"id":user_id,
						"episode":episode,
						"id_podcast":"ID_EPISODE",
						"table":"episode_users",
						"column":"ID_USER",
					},
				success: function(data)
					{ 
						console.log(data)
					}
				});
		}
}

//Benutzer der Episode bearbeiten
function edit_podcast_user(user_id){
	var podcast = $("#user"+user_id).attr('podcast');
	if($("#user"+user_id).prop("checked")) 
		{
			$.ajax({
				url: "inc/check.php?add=1",
				type: "POST",
				data: {	"id":user_id,
						"episode":podcast,
						"id_podcast":"ID_PODCAST",
						"table":"podcast_users",
						"column":"ID_USER",
					},
				success: function(data)
					{	
						console.log(data)
					}
				});
		}
	else
		{
			$.ajax({
				url: "inc/check.php?remove=1",
				type: "POST",
				data: {	"id":user_id,
						"episode":podcast,
						"id_podcast":"ID_PODCAST",
						"table":"podcast_users",
						"column":"ID_USER",
					},
				success: function(data)
					{
						console.log(data)
					}
				});
		}

}

$(document).ready(function(){
	
/* $('#new_episode').on('hidden.bs.modal', function () {
  $('#nummer_add_neu').val('');
}); */

	//Reihenfolge der Kategorien ändern (Pfeile)
    $(".movedownlink").on("click", function() {
		$(this).parents(".sectionsid").insertAfter($(this).parents(".sectionsid").next());
		sendOrderToServer()
    });
	
    $(".moveuplink").on("click", function() {
		$(this).parents(".sectionsid").insertBefore($(this).parents(".sectionsid").prev());
		sendOrderToServer();   
    });

	//Change-Modal aufrufen
	$(".change").on("click", function(){
		$("#change").modal("show");
		$.ajax({
			url: "inc/select.php?change=1",
			type: "POST",
			data: {},
			success: function(data)
				{
					console.log(data),
					$("#change_content").html(data);
				},
			});
	});	

	//Version prüfen
	$(".check_version").on("click", function(){
		$.ajax({
			url: "inc/select.php?check_version=1",
			type: "POST",
			data: {},
			success: function(data)
				{
					console.log(data),
					$("#version_check").html(data);
				},
			});
	});	

	//Benutzer suchen
	$("#UserSearch").on('input', function(){
		if($("#UserSearch").val() != '')
			{
				$("#user_list").hide("slow");
				$("#results").show("slow");
			}
		else
			{
				$("#user_list").show("slow");
			}

	var UserSearch = $(this).val();
	var value = $(this).attr('check');
	jQuery.ajax({
		url: "inc/search.php?search_user=1",
		data: {	"UserSearch":UserSearch,
				"value":value,
			},
		type: "POST",
		success:function(data)
			{
				console.log(data);
				$("#results").html(data);
			},
		error:function ()
			{
			}
		});

	});	

	//Podcast update
	$("#update_podcast").on("click", function(){
		var podcast_desc = $("#Beschreibung").val();
		var podcast_short = $("#short_edit").val();
		var color = $("#color_edit").val();
		var Podcast = $(this).attr("podcast");
		if(podcast_short == "")
			{
				$.gritter.add({
					title: "Bitte alle Felder ausfüllen!",
					text: "Bitte gib einen Kurzbezeichner ein!",
					image: "../images/delete.png",
					time: "1000"
				});		
				return;
			}
		jQuery.ajax({
			url: "inc/update.php?podcast_update=1",
			data: {	"podcast_desc":podcast_desc,
					"podcast_short":podcast_short,
					"Podcast":Podcast,
					"color":color
				},
			type: "POST",
			success:function(data)
				{
					console.log(data);
					$.gritter.add({
						title: "OK!",
						text: "Änderungen gespeichert",
						image: "../images/confirm.png",
						time: "1000"
					});
				},
			error:function ()
				{
				}
			});
		$("#pc").load(" #pc > *");
		$("#podcast_list").load(" #podcast_list > *");
		$("#podcast-name-top").load(" #podcast-name-top > *");
	});	

	//Editable
	$.fn.editable.defaults.mode = 'inline';
	$.fn.editableform.buttons = '<button type=\"submit\" class=\"btn btn-success editable-submit\"><i class=\"fas fa-fw fa-check\"></i></button>' + '<button type=\"button\" class=\"btn btn-danger editable-cancel\"><i class=\"fas fa-fw fa-ban\"></i></button>' ;
	$('.update').editable({
		params: function(params) 
			{ 
				var data = {};
				data['pk'] = params.pk;
				data['name'] = params.name;
				data['value'] = params.value;
				data['table'] = $(this).attr('table');
				return data;
			},
		type: 'POST',
		emptytext: 'Nichts hinterlegt',
		display: function(value) {
			if($(this).attr('beschr')=='URL')
				{
					$(this).text($(this).attr('beschr'));
				}
			else
				{
					$(this).text(value);
				}
			} ,					
		success: function(data)
			{
				console.log(data);
			}
	});


	//Kategorien updaten
	$('.cat_up').change(function(){
		var pk = $(this).attr("id_cat")
		var row = $(this).attr("row")
		var table = $(this).attr("table")
		var value = $(this).val()
		
		if($(this).attr("row") == "EXPORT_CAT")
		{
			$("#export_cat_options_"+pk).toggle("slow");
		}
		
		if($(this).attr('type') == 'checkbox')
			{
				if($(this).is(':checked'))
					{
						var value = '1';
					}
				else
					{
						var value = '0';
					}
			}

		$.ajax({
			url: 'inc/update.php?up_cat=1',
			type: 'POST',
			data: {row:row, pk:pk, value:value, table:table },
			success: function(data)
				{
					console.log(data);
				},
			});
	});	

	//Zufallspasswort generieren
	$("#renew").on("click", function(){
		$("#Password_add").each(function(){
			$(this).val(randString($(this)));
		});
	});
	$("#Password_add").on("click", function (){
		$(this).select();
	});

	//Kategorie hinzufügen

	$("#cat_name_new").on("change keyup", function(){
		if($(this).val() == "")
			{
				$("#cat_add_send").attr("disabled", true);	
			}
		else		
			{
				$("#cat_add_send").removeAttr("disabled");	
			}
	});

	$("#cat_coll_new").on("change click", function(){
		if($(this).is(":checked"))
			{
				$("#cat_visible_new").prop("checked", true);	
				$("#cat_visible_new").attr("disabled", true);	
			}
		else		
			{
				$("#cat_visible_new").removeAttr("disabled");	
				$("#cat_visible_new").prop("checked", false);	
			}
	});

	$("#cat_add_send").click(function(){
		var cat_name_new = $("#cat_name_new").val();
		var podcast = $(this).attr("podcast");
		if($("#cat_visible_new").is(":checked"))
			{
				var cat_visible_new = "1";
			}
		else
			{
				var cat_visible_new = "0";
			}

		if($("#cat_topics_new").is(":checked"))
			{
				var cat_topics_new = "1";
			}
		else
			{
				var cat_topics_new = "0";
			}

		if($("#cat_coll_new").is(":checked"))
			{
				var cat_coll_new = "1";
			}
		else
			{
				var cat_coll_new = "0";
			}

		var cat_entries_new = $("#cat_entries_new").val();
		if(cat_name_new == "")
			{
				$.gritter.add({
					title: "Kein Titel!",
					text: "Bitte geben Sie einen Titel ein!",
					image: "../images/delete.png",
					time: "1000"
				});		
				return;
			}
		jQuery.ajax({
			url: "inc/insert.php?add_cat=1",
			data: {	"cat_name":cat_name_new,
				"cat_visible":cat_visible_new,
				"cat_topics":cat_topics_new,
				"cat_coll":cat_coll_new,
				"cat_entries":cat_entries_new,									
				"podcast":podcast										
				},
			type: "POST",
			success:function(data)
				{
					$("#cat_name").val("");
					console.log(data);
					location.reload();
				},
			error:function ()
				{
				}
			});

	});		

	// Neue Episode anlegen
	$("#add_new_episode").click(function(){
		var podcast = $("#nummer_add_neu").attr("podcast");

		if($("#nummer_add_neu").val() == "")
			{
				$.gritter.add({
				title: "Unvollständige Angaben",
				text: "Bitte gib eine Episoden-Nummer ein!",
				image: "../images/delete.png",
				time: "1000"
				});		
				return;
			}
		else
			{
				if($("#template_check").prop("checked")) 
					{
						var users = $("#template_select option:selected").attr("users");
						var cats = $("#template_select option:selected").attr("cats");

					}
				else
					{
						var users = "";
						var cats = "";			
					}

				$.ajax({
					url: "inc/insert.php?add_episode=1",
					type: "POST",
					data: {	"podcast":podcast,
							"date":$("#date_new").val(),
							"title_new":$("#title_new_episode").val(),
							"nummer":$("#nummer_add_neu").val(),
							"users":users,
							"cats":cats
						},
					success: function(data)
						{ 
							console.log(data);
							$.gritter.add({
								title: "OK!",
								text: "Episode angelegt",
								image: "../images/confirm.png",
								time: "1000"
							});
							$("#episode_list").load(" #episode_list > *");
							$('#nummer_add_neu').val('');

						}
					});
			}
	});



	// Neue Vorlage anlegen
	$("#title_template_new, #podcast_template_new").on("change keyup", function(){
			if( ($("#title_template_new").val() == "") || ($("#podcast_template_new option:selected").val() == "not") )
				{
					$("#add_new_template").attr("disabled", true);	
				}
			else		
				{
					$("#add_new_template").removeAttr("disabled");	
				}
	});

	$("#add_new_template").click(function(){
		$.ajax({
			url: "inc/insert.php?add_template=1",
			type: "POST",
			data: {	"title":$("#title_template_new").val(),
					"podcast":$("#podcast_template_new option:selected").val(),
				},
			success: function(data)
				{ 
					console.log(data);
					$.gritter.add({
						title: "OK!",
						text: "Vorlage angelegt",
						image: "../images/confirm.png",
						time: "1000"
					});
					$("#template_list").load(" #template_list > *");
				}
			})
	});

	//Neuen User anlegen
	$("#User_add, #Password_add").on("change keyup", function(){
		var User_add = $("#User_add").val();
		var Password_add = $("#Password_add").val();
		if((User_add == "") && (Password_add == ""))
			{
				$("#add_user").attr("disabled", true);	
			}
		else		
			{
				$("#add_user").removeAttr("disabled");	
			}
		});

	$("#add_user").on("click", function(){
		var User_add = $("#User_add").val();
		var User_add_mail = $("#User_add_mail").val();
		var Password_add = $("#Password_add").val();
		jQuery.ajax({
			url: "inc/insert.php?add_user=1",
			data: {
			"User_add":User_add,
			"User_add_mail":User_add_mail,
			"Password_add":Password_add,
			},
			type: "POST",
			success:function(data)
				{
					$("#User_add").val("");
					$("#User_add_mail").val("");
					$("#Password_add").val("");
					console.log(data);
					$.gritter.add({
						title: "OK!",
						text: "User \"" + User_add + "\" hinzugefügt",
						image: "../images/confirm.png",
						time: "1000"
					});
					$("#user_list").load(" #user_list > *");

				},
			error:function ()
				{
				}
			});
	});	

	// Neuen Podcast anlegen
	$("#short").on("change keyup", function(){
		if($(this).val() == "")
			{
				$("#add_new_podcast").attr("disabled", true);	
			}
		else		
			{
				$("#add_new_podcast").removeAttr("disabled");	
			}
	});

	$("#add_new_podcast").click(function(){
		if($("#short").val() == "")
			{
				$.gritter.add({
					title: "Unvollständige Angaben",
					text: "Bitte gib einen Kurzbezeichner ein!",
					image: "../images/delete.png",
					time: "1000"
				});		
				return;
			}
		else
			{
				$.ajax({
				url: "inc/insert.php?add_podcast=1",
				type: "POST",
				data: {	"descr":$("#descr").val(),
						"short":$("#short").val()
					},
				success: function(data)
					{ 
						location.reload();
					}
				});
			}
	});

	//Benutzernamen prüfen
	$("#User_add").on("change input keyup blur", function(){
		$.ajax({
			url: "inc/check.php?check_new_user=1",
			type: "POST",
			data: {"username":$("#User_add").val()},
			success: function(data)
				{ 
					console.log(data);
					$("#user-availability-status-new").html(data);
				}
			});
	});

	//Neuen Podcast-Kurzbezeichner prüfen
	$("#short").on("change input keyup blur", function(){
		$.ajax({
			url: "inc/check.php?check_podcast_short=1",
			type: "POST",
			data: {"short":$("#short").val()},
			success: function(data)
				{ 
					console.log(data);
					$("#podcast-availability-status-new").html(data);
				}
			});
	});

	//Podcast-Kurzbezeichner prüfen
	$("#short_edit").on('change input keyup blur', function(){
		$.ajax({
			url: "inc/check.php?check_edit_podcast_short=1",
			type: "POST",
			data: {	"short_edit":$("#short_edit").val(),
					"short_cur":$("#short_edit").attr('short_cur')
				},
			success: function(data)
				{ 
					console.log(data);
					$("#podcast_edit-availability-status-new").html(data);
				}
			});
	});

	//Nummer der neuen Episode prüfen
	$("#nummer_add_neu").on('change input keyup blur', function(){
		$.ajax({
			url: "inc/check.php?check_new_episode=1",
			type: "POST",
			data: {	"podcast":$("#nummer_add_neu").attr('podcast'),
					"nummer":$("#nummer_add_neu").val()
				},
			success: function(data)
				{ 
					console.log(data);
					$("#number-availability-status-new").html(data);
				}
			});
	});

});
