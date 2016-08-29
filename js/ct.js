

//Main ajax function
   function ct_course_items()
   {
       var ajax_url = ajax_genre_params.ajax_url; //Get ajax url (added through wp_localize_script)

       $.ajax({
           type: 'GET',
           url: ajax_url,
           data: {
               action: 'course_select_filter',
               courses: getSelectedCourse, //Get array of values from previous function
               options: getSelectedOptions //If paged value is being sent through with function call, store here
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
