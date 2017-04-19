	$(function(){
		$("#username").bind("blur",function(){
			var username = $("#username").val();
			var adminsite = $("#adminsite").val() + "?username="+username;
			$.ajax({
				url: adminsite,
				type: "GET",
				success: function(data) {
					if (data == "noexit") {
						$("#usernamespan").show();	
					} else {
						$("#usernamespan").hide();
						$("#oldptb").val(data);
					}
				} 	
			});		
		});
	});