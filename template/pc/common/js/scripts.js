
document.write("<script src='"+getBasePath()+"/public/assets/js/message/message.min.js'><\/script>");
document.write("<script src='"+getBasePath()+"/public/assets/js/message/message.js'><\/script>");
document.write("<script src='"+getBasePath()+"/public/assets/js/message/messageIE.js'><\/script>");

function getBasePath(){ 
	var obj=window.location; 
	var contextPath=obj.pathname.split("/")[1]; 
	var basePath="/"+contextPath; 
	return basePath; 
} 

/* 公共get */
$(".js-ajax-operation").click(function(){
  var url = $(this).attr('href');
  $.ajax({
      type: "GET",
      url: url,
      success: function(data) {
           if(data.code == 0){
              $.message({message:data.msg,type:'warning',time:'3000'});
            }else{ 
              $.message(data.msg);
              setTimeout(function () {
                  window.location.reload()
                },500);
            }
      }
  }); 
});
/*删除*/
$(".js-ajax-delete").click(function(){
  var url = $(this).attr('href');
 if(confirm("确定要刪除吗？")){
 	$.ajax({
     	type: "GET",
	      url: url,
	      success: function(data) {
	           if(data.code == 0){
	              $.message({message:data.msg,type:'warning',time:'3000'});
	            }else{ 
	              $.message(data.msg);
	              setTimeout(function () {
	                  window.location.reload()
	                },500);
	            }
	      }
	  }); 
 }
  
});