"use strict";
 $(".blog-short-body").css('cursor','pointer');
 $(document).on('click', '.blog-short-body', function(event) {
   event.preventDefault();
   let that = $(this).attr('block-number');
   var redirect_url = $(".blog-url"+that).attr('href');
   console.log(redirect_url)
   window.location.href = redirect_url;
 });