//Genre Ajax Filtering
jQuery(document).ready((function()
{
  //If input is changed, load posts
      jQuery('#ct-menu-select input').live('click', function(){
          ct_course(jQuery(this).attr("name")); //Load Posts
      });

}));
//Main ajax function
   function ct_course(course)
   {
       var course_value = course;
       var ajax_url = ajax_genre_params.ajax_url; //Get ajax url (added through wp_localize_script)
       jQuery.ajax({
           type: 'GET',
           url: ajax_url,
           data: {
               action: 'course_select_filter',
               course: course_value
           },
           beforeSend: function ()
           {
               //Adding loader image later
           },
           success: function(data)
           {
               //Hide loader here
               jQuery('#ct-menu').html(data);
           },
           error: function()
           {
                               //If an ajax error has occured, do something here...
               jQuery("#ct-menu").html('<p>There has been an error</p>');
           }
       });
   }
