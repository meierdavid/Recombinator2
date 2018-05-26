function chargeAjax (idBalise, lien)
{
	$('.ajaxLoader').toggle();
	$.ajax({
		url: lien,
		cache:false,
		success:function(html){
		$(idBalise).empty();
		$(idBalise).append(html);
		$('.ajaxLoader').toggle();
		},
		error:function(XMLHttpRequest,textStatus, errorThrown){
			$(idBalise).empty();
			$(idBalise).append(errorThrown);
			$(idBalise).append('<br />');
			$(idBalise).append(XMLHttpRequest.responseText);
			$('.ajaxLoader').toggle();
		}
	})
}
