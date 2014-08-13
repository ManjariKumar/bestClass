$(document).ready(function(){
    $('.search_feild').focusin(function(){
	$(this).css('outline-color','#619bfa');
	});
	$('.search_feild').focusout(function(){
	$(this).css('outline-color','#fff');
	});
	//instant search code start
	$('.search_feild').keyup(function(i){
		if ((i.keyCode != 40) && (i.keyCode != 38)){
		var keywords = $(this).val();
		$.post('instant_search.php',{keywords:keywords},function(data){
		if(data==false){
			  $('#instant_search').hide();
			 }else{
			 $value = $('.search_feild').val();
			 if($value==''){
			 $('#instant_search').hide();
			 }else{
				$('#instant_search').show();	
				$('#instant_result').html(data);
				
				$(document).keyup(function(e) {
    			if (e.keyCode == 40) {
				var selected_list_itmem_length = $('#instant_result > li.default').nextAll('li').length;
				if(selected_list_itmem_length==0){
				var selected_keywords = $('#instant_result > li:first').attr('class','default');
				}
				var selected_keywords = $('#instant_result > li.default').removeAttr('class').next().attr('class','default').text();	
				$('.search_feild').val(selected_keywords);	
				}
				});
				
				$("body").not("#instant_search").click(function() {
				$('#instant_search').hide();
				});
				$('#instant_result li').click(function(){
				var selected_keywords = $(this).text();
				$('.search_feild').val(selected_keywords);
				$('#instant_result').html('');
				$('#instant_search').hide();
				});
			}
			}
		
		});
	}
	});
	
	/*funtionlity for learn more btton which will display on hoveing */
	$('.result').mouseover(function(){
	$(this).find('ul li a.moreBtn').css('display','inherit');
	});
	$('.result').mouseout(function(){
	$(this).find('ul li a.moreBtn').css('display','none');
	});
	
	//functionlity for select all categoris in select catagoris page 
	 $('#select_all').click(function () {
     $('.checked_categories').prop('checked', this.checked);
	 });
    $('.checked_categories').change(function () {
	 var check = ($('.checked_categories').filter(":checked").length == $('.checked_categories').length);
	$('#select_all').prop("checked", check);
     });
	 
	 //functionlity for age selecgtion on click then select that item into text box below the images
	 $('.age_selection').click(function(){
	 var value = $(this).attr('title');
	 $('.age_selection').css('font-weight','normal'  ).css('font-size','13px').css('color','#000').css('background','#fff');
	 $(this).css('font-weight','bold').css('font-size','15px').css('color','#fff').css('background','#d44627');
	 $('#selected_age').val(value);
	});
	
	//functionlity for map page
	$('img[usemap]').maphilight();
	$('.c_name').mousemove(function(e){
		 var hovertext = $(this).attr('hovertext');
		 $('#hovered_div').text(hovertext).show();
		 $('#hovered_div').css('top',e.clientY+10).css('left',e.clientX+10);
	 });
	 $('.c_name').mouseout(function(e){
		 $('#hovered_div').hide();
	 });
	$('.c_name').click(function(){
	 var area_name = $(this).attr('alt');
	 $('#selected_age').val(area_name);
	 if(area_name!=""){
	 $('.location_checked').html("");
	 }
	}); 


	$('.check_location').keyup(function(){
	 var location = $(this).val();
	 var zip_code = $(this).val().length;
	 var intRegex = /^\d+$/;
	 var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;
	 if((intRegex.test(location) || floatRegex.test(location)) && zip_code<5) {
	 $('.location_checked').html("not valid!").css("color","#e77776");
	 return false;
	 }else{
	 $.post('instant_search.php',{location:location},function(data){
	 //$('.location_checked').html(data);
	 if(data!=''){
		 $('.location_checked').html("");
	 }else{
		 $('.location_checked').html("not valid!").css("color","#e77776");
		 return false;
	 }
	 });
	 }
	});
});