//Genre Ajax Filtering
jQuery(function($)
{
  ct_course();

  //If input is changed, load posts
      $('#genre-filter input').live('click', function(){
          ct_course(); //Load Posts
      });


//Main ajax function
   function ct_course(course = false)
   {
       var course_value = course;
       var ajax_url = ajax_genre_params.ajax_url; //Get ajax url (added through wp_localize_script)

       $.ajax({
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
               $('#genre-results').html(data);
           },
           error: function()
           {
                               //If an ajax error has occured, do something here...
               $("#genre-results").html('<p>There has been an error</p>');
           }
       });
   }
