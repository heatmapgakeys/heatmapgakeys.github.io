"use strict";
$("a.share_button").click(function(e) {
        var width = window.innerWidth * 0.66 ;
        var height = width * window.innerHeight / window.innerWidth ;
        window.open(this.href , 'newwindow', 'width=' + width + ', height=' + height + ', top=' + ((window.innerHeight - height) / 2) + ', left=' + ((window.innerWidth - width) / 2));
        e.preventDefault();
});