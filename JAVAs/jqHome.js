function Divs() {
    var divs= $('.special-news > div > div'),
    now = divs.filter(':visible'),
    next = now.next().length ? now.next() : divs.first(),
    speed = 1000;

    setTimeout(function(){
        now.slideUp(speed, function() {
        setTimeout(function() {
            next.slideDown(1000);
        }, 800);
        }
        )
    },2000);
}
$(document).ready(function () {
setInterval(Divs, 2000);
});