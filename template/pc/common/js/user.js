$("#share-form #submit").click(function(){
      $.ajax({
		    type: "POST",
		    url: "/public/home/user/share_add.html",
		    data:$("#share-form").serialize(),
		    success: function(data) {


		    }
		});
});

