$(document).ready(function(){
	 $('.description').click(function(){
	 var id = $(this).attr('title');
	 $.post('./description.php',{id:id},function(data){
		 $('.'+id).val(data);		 
	 });
	 });
});
	