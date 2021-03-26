function scrollBar() {
    let cached = document.getElementById('bar'),
        f = document.querySelector(".toc-container"),
        blur = document.getElementById("blur_background"),
        i = 0,
        s, a, b, c, result;
    let _blur_check = (_scrollTop) => {
        let _blur = blur.style.backdropFilter;
        return (_scrollTop > 100 && _blur == "blur(5px)") || (_scrollTop <= 100 && (_blur == "blur(0px)" || _blur == ""))
    };
    function _blur() {
        if (_blur_check(s)) return;
        if (s > 100 && blur.style.backdropFilter != "blur(5px)") {
            i <= 5 ? i += 0.01 : i = 5;
            blur.style.backdropFilter = "blur(" + i + "px)";
        }
        if (s < 100 && blur.style.backdropFilter != "blur(0px)") {
            i >= 0 ? i -= 0.01 : i = 0;
            blur.style.backdropFilter = "blur(" + i + "px)";
        }
        if (!_blur_check(s)) {
            requestAnimationFrame(_blur);
        }
    } 
    _blur();
    window.addEventListener('scroll', ()=>{
        s = document.documentElement.scrollTop || window.pageYOffset || document.body.scrollTop,
        a = document.documentElement.scrollHeight || document.body.scrollHeight,
        b = window.innerHeight,
        result = parseInt(s / (a - b) * 100);
    cached.style.width = result + "%";
    switch (true) {
        case (result <= 19): c = '#00BCD4'; break;
        case (result <= 39): c = '#50bcb6'; break;
        case (result <= 59): c = '#85c440'; break;
        case (result <= 79): c = '#f2b63c'; break;
        case (result <= 99): c = '#FF0000'; break;
        case (result == 100): c = '#5aaadb'; break;
    }
    cached.style.background = c;
    if (f != null) {
        f.style.height = document.querySelector(".site-content")?.getBoundingClientRect(outerHeight)["height"] + "px";
    }
    _blur();
    });
}