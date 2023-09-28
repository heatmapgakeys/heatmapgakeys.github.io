// ==== darkToggler
const darkTogglerCheckbox = document.querySelector("#darkToggler");
const html = document.querySelector("html");

const darkModeToggler = () => {
  darkTogglerCheckbox.checked
    ? html.classList.remove("ud-dark")
    : html.classList.add("ud-dark");
};
if(is_dark_theme=='1') darkModeToggler();

darkTogglerCheckbox.addEventListener("click", darkModeToggler);

// ====== scroll top js
function scrollTo(element, to = 0, duration = 500) {
  const start = element.scrollTop;
  const change = to - start;
  const increment = 20;
  let currentTime = 0;

  const animateScroll = () => {
    currentTime += increment;

    const val = Math.easeInOutQuad(currentTime, start, change, duration);

    element.scrollTop = val;

    if (currentTime < duration) {
      setTimeout(animateScroll, increment);
    }
  };

  animateScroll();
}

Math.easeInOutQuad = function (t, b, c, d) {
  t /= d / 2;
  if (t < 1) return (c / 2) * t * t + b;
  t--;
  return (-c / 2) * (t * (t - 2) - 1) + b;
};

document.querySelector(".back-to-top").onclick = () => {
  scrollTo(document.documentElement);
};


$(document).on('click','.acceptcookies',function(e){
  e.preventDefault();
    $.ajax({
       context: this,
       type:'POST' ,
       url: landing_url_accept_cookie,
       data: {},
       beforeSend: function (xhr) {
             xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
          },
       success:function(response)
       {
         $(this).parent().parent().hide();
       }
    });
});