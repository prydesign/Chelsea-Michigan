	jQuery.noConflict();
	jQuery(document).ready(function($){
		// Sort dem tables
		$('#sortable').tablesorter({
			sortList: [[0,0], [1,0]]
		});
		
		// Change dem listing plans
		$('.listing-plan-change').click(function(){
			fee_id = $(this).prev('select').val();
			listing_id = $(this).attr('rel');
			$.post('/directory/?action=change_listing_plan&id='+listing_id+'&fee_id='+fee_id, function(data) {
				alert('Listing updated');
			});
			return false;
		});
		
		// Change dem metas
		$('.meta_edit').click(function() {
			$(this).next('form').show();
			$(this).prev('span').hide();
			$(this).next('a.cancel').show();
			$(this).hide();
		});
		
		$('.cancel').click(function() {
			$(this).prev('.buyer-name').show();
			$(this).parent('form').hide();	
			$(this).prev('a.meta_edit').show();
			$(this).hide();	
		});
		
		$('.meta_edit_form').submit(function(){
			formDatas = $(this).serialize();
			thisForm = $(this)
			// alert(formDatas);
			$.post('/directory/?action=ajax_edit_meta&'+formDatas, function(data) {
				thisForm.hide();
				new_metaness = thisForm.children('input[name="new_meta"]').val();
				thisForm.parent('td').children('span.name').text(new_metaness);
				thisForm.parent('td').children('span.name').show();
				thisForm.prev('a.meta_edit').show();
			});
		});
	});
