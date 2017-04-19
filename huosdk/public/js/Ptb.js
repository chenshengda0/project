	$(function(){
		$("#username").bind("blur",function(){
			var username = $("#username").val();
			
			var takeclass=null;
			var obj=document.getElementsByName("takeclass")
			for (var i=0;i<obj.length;i++){ //遍历Radio
				if(obj[i].checked){
					takeclass=obj[i].value;
				}
			} 
			
			var adminsite = $("#adminsite").val() + "?username="+username+"&takeclass="+takeclass;
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