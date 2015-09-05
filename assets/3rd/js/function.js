$(document).ready(function(){
	$(document).ready(function () {
		var username;
        $("#startForm").submit(function (e) {
			username = $("#username").val();
			e.preventDefault();
            $("#spinner").show();
			$.ajax({
				type: "POST",
				url: "?home2",
				contentType: 'json',
				success: function (response) {
					if(response.success){
							$("#change").html('');
						console.log(response.data);
						for (var i=0; i < response.data.length; i++)
						{
							var row = response.data[i];
							if(row.employee == username)
							{
								$('#change').append(row.employee + ', ' + row.age + ', ' + row.company + '<br>');
								break;
							}
						}
					}
				},
			});
        });
    });
});