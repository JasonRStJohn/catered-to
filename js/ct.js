//Tab Like Functionality To Filter Tables
jQuery(document).ready(function()
{
 jQuery('#ct-menu-select input.ct-course').live('click', function(){
          jQuery('div.ct-shown').removeAttr('class').attr('class','ct-hidden');
		  var shown = jQuery(this).attr('name');
		  jQuery('div#'+shown).removeAttr('class').attr('class','ct-shown');
 }); 
 jQuery('#ct-menu-select input.ct-option').live('click', function(){
		  var checkCheck = jQuery('#ct-menu-select input.ct-option:checked').length;
		  if(checkCheck == 0){
			  jQuery('div#ct-menu tr.ct-list-item').removeClass('ct-hidden');
		  } else {
			  jQuery('div#ct-menu tr.ct-list-item').addClass('ct-hidden');
			  var ctOption = jQuery(this).attr('name');
              var arr = [];
			  jQuery('#ct-menu-select input.ct-option:checked').each(function() {
					arr.push(jQuery(this).attr('id'));
              });
			  jQuery("tr."+arr.join(".")).removeClass('ct-hidden');
		  }
 });
 jQuery('ul.ct-menu-nav li').hover(function(){ jQuery(this).addClass('ct-highlighted')},function(){jQuery(this).removeClass('ct-highlighted');});
 
 jQuery('#ct-cat').change(
	function (){
		catslug=jQuery(this).val();
		jQuery.ajax({
			type:'POST',
			url:ajaxurl,
			data:{
				'action':'ct_ajax_cathandle',
				'catslug':catslug
				},
			success: function(data){
				jQuery('#ct-sub-cat').html(data);
				jQuery('#ct-item').html('<option disabled selected value> -- select an option -- </option>')
			}
		});
	});
 jQuery('#ct-sub-cat').change(
	function (){
		catslug=jQuery(this).val();
		jQuery.ajax({
			type:'POST',
			url:ajaxurl,
			data:{
				'action':'ct_ajax_subcathandle',
				'catslug':catslug
				},
			success: function(data){
				jQuery('#ct-item').html(data);
		}
	});
 }); 
 jQuery('#ct-item').change(
	function(){
		price=jQuery("#ct-item option:selected").attr('value');
		jQuery('#ct-price').text(price); 
	});
	function ct_item_add(){
		//e.preventDefault();
			var cat = (jQuery('#ct-cat option:selected').attr('value'));
			var subcat = (jQuery('#ct-sub-cat option:selected').text());
			var item = (jQuery('#ct-item option:selected').attr('id'));
			var item = item.replace( /^\D+/g, '');
			var item_name = jQuery('#ct-item option:selected').text();
			var qty = (jQuery('#ct-quantity').val());
			var price = (jQuery('#ct-price').text());
			var cost = price * qty;
			var event = (jQuery('button#add').attr('value'));
			var notes = (jQuery('#ct-item-notes').val());
		jQuery.ajax({
			type:'POST',
			url:ajaxurl,
			dataType:'json',
			data:{
				'action':'ct_quote_item_alter',
				'ct_action_type':'add',
				'event_id':event,
				'item_id':item,
				'item_cat': cat,
				'item_subcat':subcat,
				'item_qty':qty,
				'item_notes':notes
			}
		});
 }
 jQuery('#add').click( function() {ct_item_add();});
 jQuery('#ct-item-notes').change( function() {ct_item_add();});

  jQuery('#schedule_add').click(
	function(e){
		//e.preventDefault();
			var event = (jQuery('button#schedule_add').attr("value"));
			var item = (jQuery('input#ct_sched_item').val());
			var time = (jQuery('input#ct_sched_time').val());
		jQuery.ajax({
			type:'POST',
			url:ajaxurl,
			dataType:'json',
			data:{
				'action':'ct_schedule_item_alter',
				'ct_action_type':'add',
				'event_id':event,
				'sched_item':item,
				'sched_time': time
			}
		});
 });
 
 jQuery('#service_add').click(
	function(e){
			var cat = "service";
			var subcat = "none";
			var item = (jQuery('#ct_event_service option:selected').attr('id'));
			var item = item.replace(/^\D+/g,'');
			var qty = (jQuery('#ct_service_hours').val());
			var event = (jQuery('button#service_add').attr('value'));
		jQuery.ajax({
			type:'POST',
			url:ajaxurl,
			dataType:'json',
			data:{
				'action':'ct_quote_item_alter',
				'ct_action_type':'add',
				'event_id':event,
				'item_id':item,
				'item_cat': cat,
				'item_subcat':subcat,
				'item_qty':qty,
				'item_notes':'NA',
			}
		});
 });
 
 jQuery('#ct-quote button.ct-remove').live('click', function(){
		var ctid = jQuery(this).attr('id');
		jQuery.ajax({
			type:'POST',
			url:ajaxurl,
			data:{
				'action':'ct_quote_item_alter',
				'ct_action_type':'delete',
				'ct_id':ctid
			}
			});
 });
  jQuery('#ct-schedule-display button.ct-sched-remove').live('click', function(){
		var ctid = jQuery(this).attr('id');
		jQuery.ajax({
			type:'POST',
			url:ajaxurl,
			data:{
				'action':'ct_schedule_item_alter',
				'ct_action_type':'delete',
				'ct_id':ctid
			}
			});
 });
 jQuery('.ct-quant-changer').live( 'change',
	function(event){
		var price = jQuery(this).parent().attr('value');
		var cost = (jQuery(this).val())*price;
		var ctid = jQuery(this).attr('id');
		var ctqty = jQuery(this).val();
		jQuery.ajax({
			type:'POST',
			url:ajaxurl,
			data:{
				'action':'ct_quote_item_alter',
				'ct_action_type':'change',
				'ct_id':ctid,
				'ct_qty':ctqty
			}
			});
		jQuery(this).next().text(cost);
		}
 );
 jQuery('#officePDF').click(function(e){
	 e.preventDefault();
	 quotePDF('office');
 });
  jQuery('#kitchenPDF').click(function(e){
	 e.preventDefault();
	 quotePDF('kitchen');
 });
  jQuery('#customerPDF').click(function(e){
	 e.preventDefault();
	 quotePDF('customer');
 });
 jQuery('#ct_event_date').datepicker();
 jQuery('#ct_event_time_s, #ct_event_time_e, #ct_sched_time').timepicker({});
 });
 
 function quotePDF(ct_type) {
	
	var ct_name_date = jQuery('#title').val()+" - "+jQuery('#ct_event_date').val();
	var ct_footer = jQuery('textarea#ct_event_terms_data').val();
	var ct_type_time = jQuery('#ct_event_type').val()+' | '+jQuery('#ct_event_time_s').val()+' - '+jQuery('#ct_event_time_e').val();
	var ct_venue_guests = '@ '+jQuery('#ct_event_venue').val()+' | # of Guests: '+jQuery('#ct_event_guests').val();
	var ct_contact_name = jQuery('#ct_event_contact').val();
	var ct_contact_mail = jQuery('#ct_event_email').val();
	var ct_contact_phoneh = jQuery('#ct_event_phone_home').val();
	var ct_contact_phonem = jQuery('#ct_event_phone_mob').val();
	var ct_office_notes = jQuery('#ct_office_notes_data').val();
	var ct_kitchen_notes = jQuery('#ct_kitchen_notes_data').val();
	var ct_customer_notes = jQuery('#ct_customer_notes_data').val();
	var ct_equipment_list = jQuery('#ct_equipment_list_data').val();
	var ct_event_status = jQuery('#ct_event_status').val();
	var ct_json_menu_array_kitchen = [];
	for(i = 0; i < ct_json_menu_array.length; i++){
		ct_json_menu_array_kitchen[i] = ct_json_menu_array[i].slice(0,4);	
	}
	var ct_status = {};
	var ct_color;
	if(ct_event_status=='Lead') { ct_color = 'blue'};
	if(ct_event_status=='Booked') { ct_color = 'green'};
	if(ct_event_status=='Cancelled/Not Booked') { ct_color = 'red'};
	if(ct_event_status=='Completed') { ct_color = 'yellow'};		
	var ct_menu = [];
	var ct_info = [];
    console.log(ct_json_serv_array)
		if (ct_json_serv_array.length>0) {
			var ct_serv = {
					table:{
						body: ct_json_serv_array,
						widths: [ 15,100, 100, 25,50],
						},
					layout: 'noBorders',
					fontSize:12,
				};
		} else {
			ct_serv = '';
		}
		if (typeof ct_json_sched_array !== 'undefined') {
			var ct_sched = [
					{
					text:"Schedule",
					margin:[0,20],
					decoration: 'underline',
					fontSize:14,
					pageBreak:'before',
				},
				{
					table: {
						body: ct_json_sched_array,
						widths: [ 25,70, 20,150,25],
						},
					layout: 'noBorders',
					fontSize:12,
				},
			]
		} else {
			ct_sched = '';
		}
	switch(ct_type) { 
		case 'office': 
			ct_menu = [
				{
					text:"Menu",
					margin:[0,20],
					decoration: 'underline',
					fontSize:14,
				},
				{
					table: {
						body: ct_json_menu_array,
						widths: [ 15,100, 100,25,50],
						},
					layout: 'noBorders',
					fontSize:12,
				},
				ct_serv,
				{
					 canvas: [
						{
							type: 'line',
							x1: 0,
							y1: 5,
							x2: 325,
							y2: 5,
							lineWidth: 0.5
						}
					]
				},
				{
					table:{
						body: ct_json_totals_array,
						widths: [ 15,100, 100, 25,50],
						},
					layout: 'noBorders',
					fontSize:12,						
					},
				ct_sched,
				];
				ct_info = [
					{
						text: ct_event_status,
						background: ct_color,
						margin:[0,10,0,10],
					},
					{
						
						text:"Contact Details",
						decoration: 'underline',
						fontSize:14,
					},
					{
						text:ct_contact_name,
						fontSize:12,
						margin:[0,10,0,0],
					},
					{
						text:ct_contact_mail,
						fontSize:12,
					},
					{
						text:ct_contact_phoneh,fontSize:12,
						fontSize:12,
					},
					{
						text:ct_contact_phonem,
						fontSize:12,
					},
					{
						text:"Office Notes",
						fontSize:14,
						margin:[0,20,0,0],
						decoration: 'underline',
					},
					{
						text:ct_office_notes,
						fontSize:10,
					},
					{
						text:"Kitchen Notes",
						fontSize:14,
						margin:[0,20,0,0],
						decoration: 'underline',
					},
					{
						text:ct_kitchen_notes,
						fontSize:10,
					},
					{
						text:"Customer Notes",
						fontSize:14,
						margin:[0,20,0,0],
						decoration: 'underline',
					},
					{
						text:ct_customer_notes,
						fontSize:10,
					},
					{
						text:"Equipment List",
						fontSize:14,
						margin:[0,20,0,0],
						decoration: 'underline',
					},
					{
						text:ct_equipment_list,
						fontSize:10,
					},
					];
					break;
		case 'kitchen':
			 ct_menu = [
				{
					text:"Menu",
					margin:[20,30],
					decoration: 'underline',
					fontSize:14,
				},
				{
					table: {
						body: ct_json_menu_array_kitchen,
						widths: [ 25,115, 115,35],
						},
					layout: 'noBorders',
					fontSize:12,
				},
			];
			 ct_info = [
				{
						
						text:"Contact Details",
						decoration: 'underline',
						fontSize:14,
					},
					{
						text:ct_contact_name,
						fontSize:12,
						margin:[0,10,0,0],
					},
					{
						text:ct_contact_mail,
						fontSize:12,
					},
					{
						text:ct_contact_phoneh,fontSize:12,
						fontSize:12,
					},
					{
						text:ct_contact_phonem,
						fontSize:12,
					},
					{
						text:"Kitchen Notes",
						fontSize:14,
						margin:[0,20,0,0],
						decoration: 'underline',
					},
					{
						text:ct_kitchen_notes,
						fontSize:10,
					},
					{
						text:"Equipment List",
						fontSize:14,
						margin:[0,20,0,0],
						decoration: 'underline',
					},
					{
						text:ct_equipment_list,
						fontSize:10,
					},
				];
				break;
		case 'customer':
			ct_menu = [
				{
					text:"Menu",
					margin:[0,20],
					decoration: 'underline',
					fontSize:14,
				},
				{
					table: {
						body: ct_json_menu_array,
						widths: [ 15,100, 100,25,50],
						},
					layout: 'noBorders',
					fontSize:12,
				},
				ct_serv,
				{
					 canvas: [
						{
							type: 'line',
							x1: 0,
							y1: 5,
							x2: 325,
							y2: 5,
							lineWidth: 0.5
						}
					]
				},
				{
					table:{
						body: ct_json_totals_array,
						widths: [ 15,100, 100, 25,50],
						},
					layout: 'noBorders',
					fontSize:12,						
					},
				ct_sched,
				];
			ct_info = [
					{
						text:"Contact Details",
						decoration: 'underline',
						fontSize:14,
					},
					{
						text:ct_contact_name,
						fontSize:12,
						margin:[0,10,0,0],
					},
					{
						text:ct_contact_mail,
						fontSize:12,
					},
					{
						text:ct_contact_phoneh,fontSize:12,
						fontSize:12,
					},
					{
						text:ct_contact_phonem,
						fontSize:12,
					},
					{
						text:"Notes",
						fontSize:14,
						margin:[0,20,0,0],
						decoration: 'underline',
					},
					{
						text:ct_customer_notes,
						fontSize:10,
					},
			]
	}
	var ct_event_details = [
		{
			text: ct_name_date,
			bold:true,
			fontSize:14,
		},
		{
			text:ct_type_time,
			fontSize:10,
		},
		{
			text:ct_venue_guests,
			fontSize:10,
		}
	];
	var ct_pdf = {
		  defaultStyle: {
			font: 'PT-Sans',
		},
		pageSize: "LETTER",
		pageMargins :[25,50,25,180],
		footer: {
			text:ct_footer,
			font: 'Roboto',
			fontSize:6,
			margin: [25,25],
		},
		content:[
		{
			columns: [
				{
				image:'logo',
				width:150,
				},
				{
					stack: ct_event_details,
					margin: 5,
					width:'*',
					alignment: 'right',
				},
			]
		},
		{	
			columns: [
				{	width:175,
					margin: [10,20],
					stack: ct_info,
				},
				ct_menu,			
			]
		},
		],
		images: {
			logo:"image:'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAABoCAYAAABLw827AAAACXBIWXMAAA7EAAAOxAGVKw4bAAAgAElEQVR4nO1dO3ajQLOu/54bwgLQAtACmFwol5yLyVFuKRfOhXORG+VGuVE+LAAWAAto5f8NfItpmmpeenv6O8dnxrKAph9fV1XX4z///e9//wsKCgoKT4D/uXcDFBQUFLpCEZaCgsLTQBGWgoLC00ARloKCwtNAEZaCgsLTQBGWgoLC00ARloKCwtPgf+/dAAUFhfsgz3PY7/eQJAkwxsCyLPA8797NasR/lOOogsK/hziOYblcgq7rlc///PlzpxZ1g1IJFRT+QcRxXCMrTdPu1JrueFiVkDEGSZJAnueQZVnlb5ZlgWmaMB6P79Q6hX8Bq9WqMvds24bVanXHFl0O4poCABiNRndoST88HGElSQJBEECapnA6ncjvHA4HAPjeEVarFczn81s2UeEfwGq1guPxWPnM932YzWY/YqPM87z2mW3bt29ITzyMSpimKSyXS1gul5AkiZSseJxOJ1iv1+A4DjDGbtBKhX8BjLFyU+Sh63qnefnoSNMUiqKofMYYg8VicacWdcdDEFYYhvDy8gJJkvS+Vtd1yLLs4U83FG6PNE0hiiKI4xjSNO18XRRFNfsOAIBhGGCa5iWbeBccDofa+y0WC/KdHw13Vwl934cgCCqdxRgDwzBgPB7DZDKp/C3LMojjuLZDHA4HWK1WT6GHK1wXeZ7Dy8tL5TPGGGy3207mA1EVRMzn86dY1E1gjEEURZXP0LTyDLgrYQVBUCMrAIDNZtM4OVzXhSiK4P39vfxM13VIkkQRloJUnRM3OQp42ENhNpud3bZ7Y7/fV9RaxhhsNpunIeK7EVYcxyRZfXx8tBo1dV0Hx3FgNBrBer2+ZjMVnhCiBIGYTCat18ZxTH7+E06l8zwH3/cra242mz3VodXdbFi+79c+60JWPGzbBsMwAOB7p3iGUw6F64JygwH4Vnu6zC0ZYT27dMUYg/V6XSEr0zSfzvZ7F8KK47g2qRaLxaAdbLVagWVZEIbh04i1CtdDkiTkPOgiRTDGSPsVY+yppBAKcRxXVF3TNGG32z3dmrmLSrjf72tG9qE7mG3bSrJSKHGOhCSzXT3LCVoTbNsG0zShKAqYTCbged5TvtNdCEucGJZlPb19QOH+kElIeOLcBpnt69mlK4Bvu28URcAYe0qiQtycsNI0rXXaM3egwuNAJiF1lcCp6w3DAMuyzmnWQ+FSa40xBvv9HuI4Lu3HrutefS3fRcISX4oiMQWFvpCpg10ISxZd8ROkq0sjSRJYLpeVz/b7fXkKeU3chbBEcjqdThAEwdM4r3VBHMflAhqNRjCbzf5JH7EkSSAMQyiKAnRdB9M0W8c5TVMYjUa9NzBKQtI0rZOERJHdObbVayLP87vNpTzPwXEccmxuEbZ0c8Iaj8dgGEbt5fb7PRiGAY7j3LpJjWCMQRAEYJomjEYjME2zdSFFUVQ7Qg6CAL6+vmrXJkkCURRBlmWg6zpMJpPGPkBPZbTVmKYJi8XiYhMYg2J1Xa+11fM8yLIMDMMoSbjJNpSmaW1yx3FMtpcKejdNE7bbbad3o+LjAKCzOkeRnW3btWdHUQSHw6ESPOy67kUkMcYYZFlWGQPe/sYYK2Nt8W/z+Rxc1z372V1BhfVg225B7neRsCzLIo2j7+/vcDweYbvdXl09RI9mTGGDi8QwjPLkEScIumBggPVut2tUM6hBZYxVJMs8z+Ht7a22UJIkgcPhAGEY1u4bhmHFux+/7/s+fH19DTq4QALEvuA3ElwQi8UCgiAoT3exP3zfhzRNpWNF9QOSHQ/P80jv9CzLYLlcSo3h2GYAuTqo63pJAIwxUnJL07TmDiEuwDzPYb1ek24T6/UaiqIYTBxxHFc2IR6MsVIq5Tc2AICiKMDzPLAs6+52Npwr18ZdMo7meQ7T6VQ60TVNA8dxrnKcjMbCMAwbRVjDMCqLk4fruo2T07bt2r0nk0mp36dpCi8vL9J3Y4xBGIblJGSMged50hg3bK9sYcuesd/vIQiCztdQoKRGhOM4tf7j+wFJgOpjvp3UBsEYg1+/fvWaH4wxcF23ppJSG4GmaWUQNBWbSKFvts4kScDzvE4hQzLw7bwFoiiCt7e32uevr6830Y7u4jg6Go1gtVpJU8KgTctxnF6LsA1hGMJ0OoUgCFr17aIoyIXUJvrK8nghwbWRFUBVKgAAWC6XjWSF7e2aYieOY/j169fZZCUGpvNA9Ub8DHdhJIEmsgL47gvqvWQSVdu9KFB9a9t2+WzRwCwDlWNKBs/zYLlcnkVWACC1J10LMs3iVocTdwvNcV0XFotF4yIrigLW6zXM5/NBExTBGIPVakXuDH3RZjxv8gPCRcpPMJkoj88Qs17KoGlap4nr+37NvkbdyzCMxpS52KcyUOOl63qpaneRWPA5VEoXvJdlWdJ24nvgj6ZptZxPsmBn/F4fCagrcSyXS9jv99K/a5oGk8kEZrNZY/zjPXJYyVxHzt38uuKu2Ro8zwPbthsDmDHKfr1ew2w26x37xNuhZBPKsiyYzWblwtjv96RNBQBaxV4q7xLuPnzSf03TYLfbwXg8Jm04pmmC7/sVO9Dr6yuMx2Nyx+9iw6BS+Yj3WCwWYFlWKV3IVNE24qYIazKZ1CQWtM+Ypgnz+bwmneq6Tj6Hj3CQ2cC6qEpYMYb/Hm4wVP/P53OynU3SJg/eaE4BN3L+75TKCnAfD3yZ20IQBGBZ1tWjTu6eD8u2bfj6+upkV8JdqQ9p4ckWBU3TwPf92mJfLBbkAmgzblKe1qhCrlaryk4dhmG5ECk7T5IklRAmtBHIJNI2g7ssOwZiu93WJluTutdG3FRGy/l8XrFZdQkRQVtiEyhy7HKaC0DHHi4Wi0p/Mcbg8/MTxuOxtP+7LNQgCMjiD9he2YmozBh/y9NBgO85K9v4dV0Hz/PO0oS64CEyjuq6Xua4cl1XOil0XYfD4dA5M2kQBFJJaTKZQBRFJAFR4m2XCYK7NQ/LsiDLssqk+/j4KCdmmqY1wsKFjZjNZiVBUDY9xlir6iBTAzVNg8/PT3LBpWlKLpY24s7zvDZGuq6XNfDwHnyqkyiKSNWrTeU5152B6k/LsirSMJIVwPcYi8/TNK2VsKjULvzz+A2MR5IkJAlQLhfXBJ5qN20CRVFc1OZM4SEIC4HE9fX11ZiKtos3bZ7nUolCXCzidRTJdQnRkIn62F7GWKnWIahn8RKkYRiV3ynbR1ssZlN/yRYKgJy420hE1g9IVpqmwXa7rbWRcgVpIwLqWV1TDcnIDskds5TyfSsG7gN8S7dt0pxssWPWBBlk/XJr6Yrf8LBfMLUTQtf1q9uyHoqwEKPRCHa7nZQgkiRpzdEtW6RDJggAdJoglOSXZVm5KEzTrKlS1I6EajEe6fPfpdTbpsWZpqnUwMtLetR1sgOEthOhNglYTGsiC4vpIkUM3VzwudRY43jNZrNK3zLGyHnXhcCpPsGFLwOVhgng9vGNQRBU2r9YLKRZUoqi6JU/vy8ekrAAvtl6t9vVWBz/1ua7Q03ktgmCTpsiNE1rXaTUMb4I8dkyNQjvJ+aopyTGNjcLmZQ5mUwapTLZiWoXX5smwqLynlFOstjGJsj6vKsDbZOriKZpNVtpHMcksbaRh+z92moQyGy1t5Su0jStzCHDMMrTYcuySPNNl1PtoXhYwkIMyYgok5LaTrZk16FPThPajI0UQcgMsPhMfmJSthP8nuydZOlWAJr7VUz2xt+vTZrI81xKwpqm1RZbUxuHSnJdQkSacrcD0HOBGmM8UW16DvV+lIsFD4y/pHDLgGzeloobPr4vFb4FUD9wuSQenrAsyyKlrCbIFluT3xCGp1DXdfF1kRn3ESJB5HkuXahYGEBsH4WmxdmUzK7pBFBmoO9C3LLdFV0kxOub3quLSwL1nK7ZGWSYTCY1qUlGPG3kKHu/JofPJgN3V/eJS4D3QaPsefj5LfHwhAVAl9CWDZrMkNokXTVNkK7FB5r0dipPkIzgKFWQMSbNJtC0OGXXNKkUMsmrK3HLFqjMR0emunchnXPdGShQqqDsWQDt7gyyTampL2Ubxi1TNcdxXLF9LhaL2rNlcaR9BYw+eArColhc1imyidg00E2Oq10miIwkAb7bSRFE08IWv9+kDjYtTkrawawTFMIwlBJpV0OvrB8o6VZmg8IA9CYwxshndV3QMulM5hcm88trI0dq7jaRapPfYJd+uQQwxhPbKCtWIVtr1yw2ezXCYoz1iq1qug81gLJFJ1swssXmeV6jetA1F7hsR6TISkZAlCoIQB+lAzQbX2ULuunk9f39vdGm1mVxUmMl89uSkXZXlY4igy6lvJraKXs2JUF3mRuyTYNCGIalVKNpWi3kqGshjXNO6cSwKcMwyIMqmYrcpJFgdtJzcFFPd3SOE4+pNU0rc0mZpgmWZXVO0NbX0NnkdCoCJwjmFhqNRrXKIl2c82RuAxhuQj2Xguu65GBTE/CSR9t5npfhMkiaYoB4FxKR9YOMWJuKPjSBMUYaxbuW8pJJ1DIbJxXQLotxHAp+w2CMwcfHRy0Eq8vzPM+D0+nU6LojAxXoLVtnMh89WR/GcQzL5bKShWQILkZYaZrCdDolF/jpdIIsyyq7DZIY7r4yEqI6pmkX7XpCIU6Q7XZb891qmyBooJZJS9SkQZcLarFRC5vP1dWnbTLpViR0nqwA/jqhYoZQRNvmIovna/KKH1I/ENtL9fnpdIIkSaTPw/GiiLKpndT3ZTGObddRn4uJDmezGRRFURuDJvCpeoYk0kvTFJbLZW2uZVkG0+kUvr6+yveNoojcnDAYXYSswvsQXEwlXC6XvUIFkMT2+32ZkcFxHPB9H+I4Lj3VxUkt84nCQZdNEl6qiaKoskixo6n4NxmSJIH5fN5Y+ECmBlG+VKvVSnpETLVD9jlmSP39+zfZruPxWPZvkiTgOE7lvbfbLWlMlRmdsd+pCTzEI/t0OkkPCzA9UNOmJFPxoyiC6XQqHa8hvk2+70vHANPHUCiKokzIh2OF/Y1RGGJANsC3BCtK29gvXVL1UEjTFFarFby8vEjjeNGDPUmSxqwnvOqIiSFt265EepxrkL9IAj8qx9Pr6ytYlgWn06mMl8P0r+fmfhYzCsiyNYpAqYQfWMuyYLfbAWMMptNp7ZrJZALz+bxybZfnyTz15/M5GYsmIwRZuwD+GmEtyyr9n6IoGty/n5+fMBqNpNkBUJ3HSXc8Hhvtf21JBTFzAQU8vjdNE7IsgziOS4mDd5aVjQPfTpnNsGs7kdhlsZg4F0ejUZm9dMgY4Fzs8kyUQkV11TAM2O12nYSHpv4fAj7FEWOs1gebzebsU86LEJY4wfmskiLQGI/hCrJEeUPAGCu9b9tUQzw659U2KkPmEPATjwdF7KiONg0kitR9gFJbl+s0TavEFA7J5qlpWm2CtmWhbMs8SwHdKzzPK4/5+5CDZVm1RbpYLFoLY/i+35jDSobNZtMasE/NRYBvQmlyLhbvgemX+uTl4tuF11+ayEzThM1mc5HaozcnLAp5nkOWZWVBhiG7E6ZVdl23dSHgYhbVAKwI0mY7QNHWdd2aJJLnuTQLhMzO05RmGBGGYadMqQDfi3Kz2ZQ7vizDadMkT9MU3t7eWgncNM1S2uH7gjEGf/78aX2vLmmSAf5KFWLwOBrgMWyGV6Xw/ygFOY5TnmLx5MzbZ5qA+fNRSxDVNvydz4WPf6fIB+cR5oWjEARBWQBVZsvEfG59CSGOY/B9H2zbrhUGQT8s6sABn2nbdil48N/DlED4nUvGPV6EsCiCoPIrtQF1ZGpgMMEeqpbYKTgBxU4Rizzg5LAsqzWGC0NTsiwrJyGeCvEDwX+XMVY5RJD1036/L+0epmn2ThOCk4Nf4NgPsqo++FzeFYCapLI2F0VRM+KPRqNKQQkkHoC/iQD7vBeGyuDmhe+EBNC2GFFyx7aiioaHO3yf5HkOcRz3Oq2mnoWklec5jMdj8lk8MKAc29c34R01BrcA/9x7l6q7WBGK1WpVsydMJpNWckAvbpnYjIbboTUL0eem65G3goLC4+JihNVkT+CNtbj7oAEe7ViyXelW1TgUFBQeHxct84WOZ005q7veB087lFSkoKCAuEpdQvQg75tmAu1FSqpSUFCgcNVCqmiQFqsrA0DlhAWN4bIshgoKCgoAN678nOd55XgWbVq3yu+joKDw3LhLqXoFBQWFIXiKfFgKCgoKAA9QSFVBQeHngDF2ds6rJjORUgkVFBqAHvh4cIQn3xj9gN7+TSmSzgVmdcBnrlarsw+nmoLqu1wrC2e6RByi67rS7BlKwlJQIMAno2xybMYQKQyMnkwm4DjORePnDodDmR8Ln3XP0/QmUr5mPncAZcNSUKgAJZjpdArH4xFOp1Mvqel4PMJyuYTlcnmxFOFUwr9bV6t5FCgJS0Hh/xHHcRnALSMpDHAGkGeDBfgOUp9Op7DZbM5ygqbUK8ysekkpi3+vNvQhYj5jRVc0SaeKsBQUQJ5zDIPm+aSRPFACiuO4Vhla1/UyY0ifdEs8ZDm4MJvnpTAejwflgW9D36wdbVCEpfDPIwgCsqjFYrEga0ry0HW9jNBgjMF+v68Qn67rpc1pSLZNWQWcSybYA7h9QdShUDYshYtDVgb+ERcFJrHjSUnTNPj4+JDm2ZdB13VwXRc+Pz/LlNpoExtCVk2pnVEt/NdwM8LCVDLUpF2tVuA4Dvz69Qt+/fpFis/L5ZK8FpOxDUUcx+B5Hvi+D6vVSjoJui42mX4fBAF4ngfz+RyCIBi0eFHtENtF5SMXq+H0aQumCsIxGY/Hvfo4CAKYz+eVHGZRFMF4PK70T9O4Y2I8mYF5uVxWrm3KyS4D9hFPSqZplm0dCsyLb5pmp/TLMogFS0TbzjnznlJtnwGDVELMnIhpidsgFl4Qk9FnWQZFUcBmsykzMfJAG8F+v6/5Z2RZBsvlcnDxyDzP4XA4lAn0xfTGuAMXRVFW7KEmYBAEEIYhnE4n0DQNfN+v3Ad9eWazWelX0yU1Mg/f9yHLsooPzH6/L5/F2wp4u4po68CspbZtQxRFEIZhZXHwOfFlY9IEPvsqgiqLJRt33kcIg+Q/Pj5KEsHNT9O0Qe1DiCXhMaXRpXypZPUnu0LcPLfbbcV3Ko7j3mSIG7Q4FlRNQhmwdsJQIj4HvQgrTVM4HA6VGmNdCAuzhk4mE3h/f4f1el1bYJgHmwKmlKVIiSqH1Ad4LVV+C4s/mqYJr6+vUBQFBEEAhmFU3nu1WsHhcADbtmEymcDhcADHcSrEYpom5HleKZ4QBEHvQUebCF6HucfEtmOaYeo0yTAMSNO00hZ+M8Dd1rKss6qc8CQiK4dFjTt6S2+3WzBNE97e3mC5XNbGaDweD25fFEW1vG2XJKtzgWmw+XLxSMxIZFmWQZqmvaRBTD4g5tDvq2Jesxx9E2oq4Xw+LycH/+M4Dvz+/VtaNr0J+P3xeAzb7RZ0Xa+Is23iKHbkNXT2JEkqmVB5+L4PpmlCGIbgOA6sViuYzWYVoypKm67rwm63A8dxYLfbgWEY0hMeXddhPp/3VmP4Qpb4bOwTirAAaBWVJ7ihbemCtnnSVFAD2zgej2Gz2ZCLTCzl3gdiYU8s2vEoOB6PlfZhoQ++iLCu653K2/0k1AgLyeTt7a3yg4Uf+EkyZMJgFRN+8p1Op06TBZP+XxKyCr6oFtm2XbMjnE6nkgjwPfhqu7jQ+Hc0TbOiFmPh1j7vw5dHx+q76FksEpPYPvGd+eeKbUMiOVfa4O8puxfV97qul1I1f634jkPbJxbxaJLu7wVxA0FpVVR9h2w0566ha3uzN6GmEoZhCHEcl4Ub0CYD8D0B9/t9+fuQclzYWeJEbSK/oihgMpmUFUd4EXg0Gp01ALJr8XNxcGSLRPxcrNNXFEXlXqj29K3Jh7nxMTki1pEriqLSp0VRwGw2I8uKUe2l+uHciclf3+SISbWNH1fZGA0lrMPhULl2SNXnawILp/AJLnHOj8fjsnoUwN/aCF2lw/l8XpIzb1tGTeJcXFtKJU8JbdsG13XLOn9Ykh1LVOGLDZGwoiiCoihqO0XT5MMBoYp1Apy3Y+BuToEx1qm8FEB9oMR2ylSzPsBnua5bEhGqThQZou2Kkr7EZ1NtGUoI2M4upNhW/xEAypNMfB/ZZtIVomnh0bLcirY6sX3876J5ZSgexXbXhlajO54eoMT18fEBo9EIRqNRWUK8bcCxU9HewxdX5CcxGtUpksBdN03TXmTXFaLxUhbwKvrF4OlhGyjDeN8FhySIu6zM8ImSG3/qR32H/z//DuKY9K3dh+3EUuuogsraJBv3w+EAh8OhPA3Da/H+p9MJ0jSVqvUyiOrgoy1W3i6FxW55TCaTih31eDyeXQPhWdwaWv2wPM+DLMtq/imLxQIYY51Koeu6XhZA3Ww2lRAA7KjD4QDT6RReXl4qEgGKxzipKUe6cyv0FEUB0+m0svNqmiYdRD7uqmvkOlUl+BzsdjuYz+fSk1OAv346Yp/xtjAKeP16vYbpdDpoMWiaBq7rwmKxgNlsVi46kbBk465pWum3F4ZhRW3Da33f790+sb8umVXhEhCdbqkisuPxuDK3ZP6NPxGNhJUkScn24pHvfD4vdemup3ej0ahm3OT9fdBRkN8tcaFj1LzMuDwU6BslSm4y25ioThmGQaqpYrvwCB+B79QVYltGoxHYtl3eQ7RfYRvQnYKHmIFAprp9fn5Cnue9Dbt5npcuEfhD+f0wxqTjjqAqaeO1YRj2dhweYne9JUTykWkv4ufXOOW9BPpWzmqDlLAwpAD9YShJAqWstgmT5zkYhkHuBLiI2/INvb6+wmKxIMnxHOMwShvnSmltoEhhSLu7HFYgQXmeB7PZrHVDoYz/5/i3iQcAAHIbmewZeNhDtR2vG2JDPccV4hbg3YZ4u50I8fN/xb1BSljr9RpOp1Nj6a35fA6z2QwWi0XjQ7IsK78jHrPj0b5s4vJ+RpQ0cy1RmDf0i+DbWhQFqV6J7yS6MAzd6cX2UDYh3rdsNBrVFr3YZ6K9DiWwIYSFZCmSsfjMJtcJvIfrupBlGRk6JLu2DY8uYfEqK5a/o2BZVmUu/Cs5skjCwjASwzBgu91KL9Z1HXzfbzV4YqgG/l/8W5eTotFoVOry/D2oE8c+kCVoQ0OxKNLyx80AtK0L8yTxRMb3Af7ex1tYtkjR6C+SI38kTpG8OGZiW6ln9YF4/zYCo/4mmzPnqBlNtsl7Qwx2Ho/HjWPAm1eKovgngqFJwkJDeltqjT4wDKMMaKUgW7yi/5LodHqtyTcajcAwDNJmxrd1PB5DURSVnRHbx39PdNYcqnJ1OXRAFRzbh8+TtYWytw0NvZD5l8kkG+o56CBsmmZ5P/EZfRLO8RBJ4JEWeRzHpHe7DJZlVTaXR7VjXRI1wkrTFOI4vpj3L6pWKHGIKmEb4YgEgQG7PHT9O85wiFiMzpgULMuq2AZwB+QnEtq/+Ikfx3GZ+A3BG5UZ+86bNIQUKElFPH08nU6VZ6OjKYKXEjH4W2zL0I1KJrFSpNgGdKegVNpzNlL+XSmV814Q53Wbu5Bt27XTwp+Omh8WTuZLhSrwJ3tINqvVCjzPq0w63/eBse9AXN5BsCiKSlsw1tHzPAD4nnzv7+9l3J5hGJ13mjZPanzWcrkETdNgv9/DYrGokIGu6zCbzcrwJTw1pbI3YsDx4XA4O7CYeg+Av5OWX5ToaIrP0zStbMt+vyfbgn/HbApdpRm0q1CnmpQ9kBp3VHFxs9rv91AURSVQnDFWBm+7rluOSZqmZXC4jNQsyyo3TpRMzvVjGgL0SwT4u9EgcL61Af0hAf6qhfdw1UDpkNc0GGOlEMH74jX9C/A9d2Xz7eoZR3VdLw2EruvWJjJ/bC02klp8lmWVqoKu6+A4ztmTTdY5tm2XoUpJkkAYhuSu53kemKYJx+MRDMMgSyBhRgdd12Gz2dRiFLu0kZLIbNuuSC+UOmpZViVEx3VdOB6PMBqNYLfb1d4J4xzx/32AxCRKgpZlVaQ8HEe8hrqHruuw3W5r+dH4kBLRR6koCojjuNGcMZvNKmQQBMFdCCtJEqkf4+l06uTjyAMdtO9BWJ7n1dR+Xdfh/f29M2EB/PVOkIVL1eoSRlEEb29vjbXBFBSeHbwPG0BzLbxrAdfaJaFpWme/tEvGEjqOQwbaD0HTWKgUyQr/JFCdRARBMDgJ5FBcw3YmHgDdCreyA9ZUQhTR/gUDnsK/C4xn5aWC379/w+fn58UyDrS5h1iWBa7rVmxZ+HkftY6/Xtd1OBwOZ6V4HgLXdUsbGs8ds9msd3/2KvNl2zaYpll6pT9aYKiCwqWAKYf5Of7y8nIR0mKMlXaxMAzJdTQej2E8HpcHSHjd6+tr7yyifOrkewgbeGgTBEHl+fP5/KI2NWl6GYDHjU9SULgE8NBBVGccxzkrZUuSJKV9qCgKaQEVgG+y4Q9EqGDnNui6XjlkwZz9PxEkYaFD2n6/V6qhwo+Gbduw2WxqYVPr9Rocx+m18PM8h9VqBcvlsmLQz7JM6qLQNdi5DWLG25+6bkm3BowfTJIElsslvL6+3uXYV0HhFsC5/f7+Xvk8yzL4/fs3WJYFk8mkLJzCH8FjBZ/j8SgliaZTL7Fa0VDCsm0b3t7eynv1zZF1DaM5+mH1vabJD6vm1sAjDMNyENFA+Gj5gxQULoU4jmG9Xl/sfowx0s+NB+9e0cclgQLvWsAYgz9//rTGIqJbQx+H6yYEQUD6j3X1xcrzvMz1RqHRrcFxHPj4+Cg7Er2+f6q4qfBvw7Zt+Pr6qlSmGQLGGEwmE/jz508jWSVJUlEdzxUGxNTJj2SDRuJs+7ftsKPVD2s8HkMURbDZbMr8REhcURQ9TByWgsIlgBlIdrtdb+LSNA0mkwl8fX2B7/utJ+yiNHVubnmxvT9RsGhUCUUwxsryUihKappWxkxQYwkAAAEESURBVKJZlqXcIBR+FNAnMUmSMm0Qn4Mes0ag71Sf+Y8EhVJW30rgsnvyFbJlLhUAUGaCxYwYl5DIsNr4UKA7iEza7EVYPDC2jhdrkbzw59bOawoKCj8bgwkLgTtQFEW1NK387oMZMPHHMIyr1DDL87yS01zWZgBQJ58KCk+GswmLBy8+Y6WbppS0GJF/CeLK87yWoqPt+3/+/Hmo8uQKCgrNuChhUcCy4EgmfAl1tAlcCijRidIchb7xWgoKCvfH1QmrDVQK3KFQ0pKCws/G3QlLQUFBoStUPiwFBYWngSIsBQWFp4EiLAUFhaeBIiwFBYWngSIsBQWFp4EiLAUFhafB/wHn/KBMdwOyRAAAAABJRU5ErkJggg==',"
		}
	};
	pdfMake.fonts={        
		Roboto: {
                normal: 'Roboto-Regular.ttf',
                bold: 'Roboto-Medium.ttf',
                italics: 'Roboto-Italic.ttf',
                bolditalics: 'Roboto-MediumItalic.ttf'
        },
		'PT-Sans': {
				normal: 'PT-Sans-Regular.ttf',
				bold: 'PT-Sans-Bold.ttf',
				italics: 'PT-Sans-Italic.ttf',
				bolditalics: 'PT-Sans-Bold.ttf',
		},
	};
	pdfMake.createPdf(ct_pdf ).download(jQuery('#title').val()+'-'+ct_type+'.pdf');
 }
 