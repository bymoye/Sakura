/*！
 * Sakura theme application bundle
 * @author bymoye
 * @url https://nmxc.ltd
 * @date 2021/03/20
 */
"use strict";
let addComment;

mashiro_global.variables = new function () {
    this.has_hls = false;
};

(()=>{
    const version_list = { Firefox: 84, Edg: 88, Chrome: 88, Opera: 74, Version: 9 },
        UA = navigator.userAgent;
    let reg;
    if (UA.indexOf('Chrome')!=-1){
        reg = /(Chrome)\/(\d+)/i;
    }else {
        reg = /(Firefox|Chrome|Version|Opera)\/(\d+)/i;
    }
    const version = UA.match(reg);
    if (version && version[2] >= version_list[version[1]] && Poi.pjax) {
        console.log("%c PJAX | BLUR ON! ", "background:#5ad5e8; color:#ffffff;font-size:15px");
        Poi.pjax = true;
    } else {
        console.log("%c PJAX | BLUR OFF ", "background:#f00; color:#ffffff;font-size:15px");
        if (Poi.pjax) console.log("不管怎么说,还是建议使用最新主流浏览器噢!");
        Poi.pjax = false;
    }
})();

mashiro_global.ini = new function () {
    this.normalize = function () { // initial functions when page first load (首次加载页面时的初始化函数)
        lazyload();
        post_list_show_animation();
        copy_code_block();
        coverVideoIni();
        iconsvg();
        scrollBar();
        load_bangumi();
        sm();
    }
    this.pjax = function () { // pjax reload functions (pjax 重载函数)
        pjaxInit();
        post_list_show_animation();
        copy_code_block();
        coverVideoIni();
        iconsvg();
        load_bangumi();
        sm();
    }
}

function setCookie(name, value, days) {
    let expires = "";
    if (days) {
        let date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + mashiro_option.cookie_version_control + "=" + (value || "") + expires + "; path=/";
}

function getCookie(name) {
    let nameEQ = name + mashiro_option.cookie_version_control + "=",
        ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

function removeCookie(name) {
    document.cookie = name + mashiro_option.cookie_version_control + '=; Max-Age=-99999999;';
}

function imgError(ele, type) {
    switch (type) {
        case 1:
            ele.src = 'https://cdn.jsdelivr.net/gh/bymoye/cdn@1.6/sakura/images/Transparent_Akkarin.th.jpg';
            break;
        case 2:
            ele.src = 'https://sdn.geekzu.org/avatar/?s=80&d=mm&r=g';
            break;
        default:
            ele.src = 'https://cdn.jsdelivr.net/gh/bymoye/cdn@1.6/sakura/images/image-404.png';
    }
}

function post_list_show_animation() {
    if (document.querySelector('article') && document.querySelector('article').classList.contains("post-list-thumb")) {
        const options = {
            root: null,
            threshold: [0.4]
        },
            articles = document.getElementsByClassName('post-list-thumb'),
            callback = (entries) => {
                for (let i = 0; i < entries.length; i++) {
                    let article = entries[i];
                    if (!window.IntersectionObserver) {
                        if (article.target.classList.contains("post-list-show") === false) {
                            article.target.classList.add("post-list-show");
                            io.unobserve(article.target);
                        }
                    } else {
                        if (article.target.classList.contains("post-list-show")) {
                            io.unobserve(article.target);
                        } else {
                            if (article.isIntersecting) {
                                article.target.classList.add("post-list-show");
                                io.unobserve(article.target);
                            }
                        }
                    }
                }
            },
            io = new IntersectionObserver(callback, options);
        for (let a = 0; a < articles.length; a++) {
            io.observe(articles[a]);
        }
    }
}

let ec_click = (e)=>{
    if (!e.target.classList.contains("highlight-wrap")) return;
    e.target.classList.toggle("code-block-fullscreen");
    document.documentElement.classList.toggle('code-block-fullscreen-html-scroll');
}

function code_highlight_style() {
    const pre = document.getElementsByTagName("pre"),
        code = document.querySelectorAll("pre code");
    if (!pre.length)return;
    const gen_top_bar=(i)=>{
        const attributes = {
            'autocomplete': 'off',
            'autocorrect': 'off',
            'autocapitalize': 'off',
            'spellcheck': 'false',
            'contenteditable': 'false',
            'design': 'by Miym'
        };
        let ele_name = pre[i].children[0].className,
            lang = ele_name.substr(0, ele_name.indexOf(" ")).replace('language-', ''),
            code_a = code[i];
        if (lang.toLowerCase() == "hljs") lang = code_a.className.replace('hljs', '') ? code_a.className.replace('hljs', '') : "text";
        pre[i].classList.add("highlight-wrap");
        for (let t in attributes) {
            pre[i].setAttribute(t, attributes[t]);
        }
        code_a.setAttribute('data-rel', lang.toUpperCase());
    }
    for (let i = 0; i < code.length; i++) {
        hljs.highlightBlock(code[i]);
    }
    for (let i = 0; i < pre.length; i++) {
        gen_top_bar(i);
    }
    hljs.initLineNumbersOnLoad();
    document.querySelector(".entry-content").removeEventListener("click", ec_click);
    document.querySelector(".entry-content").addEventListener("click", ec_click);
}
try {
    code_highlight_style();
} catch{}


function attach_image() {
    const cached = document.querySelector(".insert-image-tips"),
        upload_img = document.getElementById('upload-img-file');
    if (!upload_img)return;
    upload_img.addEventListener("change", (function () {
        if (this.files.length > 10) {
            addComment.createButterbar("每次上传上限为10张.<br>10 files max per request.");
            return 0;
        }
        for (let i = 0; i < this.files.length; i++) {
            if (this.files[i].size >= 5242880) {
                alert('图片上传大小限制为5 MB.\n5 MB max per file.\n\n「' + this.files[i].name + '」\n\n这张图太大啦~请重新上传噢！\nThis image is too large~Please reupload!');
                return;
            }
        }
        for (let i = 0; i < this.files.length; i++) {
            let f = this.files[i],
                formData = new FormData(),
                xhr = new XMLHttpRequest();
            formData.append('cmt_img_file', f);
            xhr.addEventListener('loadstart', function () {
                cached.innerHTML = '<i class="picture_icon_svg" style="--svg-name: var(--svg_loading);"></i>';
                addComment.createButterbar("上传中...<br>Uploading...");
            });
            xhr.open("POST", Poi.api + 'sakura/v1/image/upload?_wpnonce=' + Poi.nonce, true);
            xhr.send(formData);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 304)) {
                    cached.innerHTML = '<i class="picture_icon_svg" style="--svg-name: var(--svg_yes);"></i>';
                    setTimeout(function () {
                        cached.innerHTML = '<i class="picture_icon_svg" style="--svg-name: var(--svg_picture);"></i>';
                    }, 1000);
                    let res = JSON.parse(xhr.responseText);
                    if (res.status == 200) {
                        let get_the_url = res.proxy;
                        document.getElementById("upload-img-show").insertAdjacentHTML('afterend', '<img class="lazyload upload-image-preview" src="https://cdn.jsdelivr.net/gh/moezx/cdn@3.0.2/img/svg/loader/trans.ajax-spinner-preloader.svg" data-src="' + get_the_url + '" onclick="window.open(\'' + get_the_url + '\')" onerror="imgError(this)"  alt=""/>');
                        lazyload();
                        addComment.createButterbar("图片上传成功~<br>Uploaded successfully~");
                        grin(get_the_url,'Img');
                    } else {
                        addComment.createButterbar("上传失败！<br>Uploaded failed!<br> 文件名/Filename: " + f.name + "<br>code: " + res.status + "<br>" + res.message, 3000);
                    }
                } else if (xhr.readyState == 4) {
                    cached.innerHTML = '<i class="picture_icon_svg" style="--svg-name: var(--svg_no);"></i>';
                    alert("上传失败，请重试.\nUpload failed, please try again.");
                    setTimeout(function () {
                        cached.innerHTML = '<i class="picture_icon_svg" style="--svg-name: var(--svg_picture);"></i>';
                    }, 1000);
                }
            }
        }
    }));
}

function clean_upload_images() {
    document.getElementById("upload-img-show").innerHTML = '';
}

function add_upload_tips() {
    const form_subit = document.querySelector('.form-submit #submit');
    if (!form_subit) return;
    form_subit.insertAdjacentHTML('afterend', '<div class="insert-image-tips popup"><i class="post_icon_svg" style="--svg-name: var(--svg_picture);--size: 20px;--color:#5d646c;position: absolute;left: 50%;top:50%;transform: translate(-50%,-50%);margin: 0;"></i><span class="insert-img-popuptext" id="uploadTipPopup">上传图片</span></div><input id="upload-img-file" type="file" accept="image/*" multiple="multiple" class="insert-image-button">');
    attach_image();
    const file_subit = document.getElementById('upload-img-file'),
        hover = document.getElementsByClassName('insert-image-tips')[0],
        Tip = document.getElementById('uploadTipPopup');
    if (!file_subit)return;
    file_subit.addEventListener("mouseenter", function () {
        hover.classList.toggle('insert-image-tips-hover');
        Tip.classList.toggle('show');
    });
    file_subit.addEventListener("mouseleave", function () {
        hover.classList.toggle('insert-image-tips-hover');
        Tip.classList.toggle('show');
    });
}

add_upload_tips();

function click_to_view_image() {
    const comment_inline = document.getElementsByClassName('comment_inline_img');
    if (!comment_inline.length) return;
    document.querySelector(".comments-main").addEventListener("click", function (e) {
        if (e.target.classList.contains("comment_inline_img")) {
            let temp_url = e.target.src;
            window.open(temp_url);
        }
    })
}
click_to_view_image();

function original_emoji_click() {
    const emoji = document.getElementsByClassName('emoji-item');
    if (!emoji.length) return;
    document.getElementsByClassName("menhera-container")[0].addEventListener("click", function (e) {
        if (e.target.classList.contains("emoji-item")) {
            grin(e.target.innerText, "custom", "`", "` ");
        }
    })
}
original_emoji_click();

function cmt_showPopup(ele) {
    const popup = ele.querySelector("#thePopup");
    popup.classList.add("show");
    ele.querySelector("input").onblur = ()=>{
        popup.classList.remove("show");
    }
}
function scrollBar() {
    const cached = document.getElementById('bar'),
        f = document.querySelector(".toc-container"),
        sc = document.querySelector(".site-content");
    let blur_rs,
        s = 0, a, b, c, result;
    if (Poi.pjax) {
        let flag = false,
            blur = document.getElementById("svg_blurfilter").children[0];
        const _blur_check = (_scrollTop) => {
            let _blur = blur.getAttribute("stdDeviation");
            return ((_scrollTop > 100 && _blur == "5") || (_scrollTop <= 100 && _blur == "0"))
        },
            _blur = () => {
                let __blur = Number(blur.getAttribute("stdDeviation"));
                if (s > 100 && __blur != 5) {
                    __blur < 5 ? __blur += 0.1 : __blur = 5;
                } else if (s < 100 && __blur != 0) {
                    __blur > 0 ? __blur -= 0.1 : __blur = 0;
                }
                blur.setAttribute("stdDeviation", __blur);
                if (!_blur_check(s)) {
                    requestAnimationFrame(_blur);
                } else {
                    flag = false;
                }
            }
        blur_rs = () => {
            if (!flag && !_blur_check(s)) {
                requestAnimationFrame(_blur);
                flag = true;
            }
        }
        blur_rs();
    }
    window.addEventListener('scroll', () => {
        s = document.documentElement.scrollTop || window.pageYOffset || document.body.scrollTop,
            a = document.documentElement.scrollHeight || document.body.scrollHeight,
            b = window.innerHeight,
            result = parseInt(s / (a - b) * 100);
        cached.style.setProperty("--barwidth", result + "%");
        switch (true) {
            case (result <= 19): c = '#00BCD4'; break;
            case (result <= 39): c = '#50bcb6'; break;
            case (result <= 59): c = '#85c440'; break;
            case (result <= 79): c = '#f2b63c'; break;
            case (result <= 99): c = '#FF0000'; break;
            case (result == 100): c = '#5aaadb'; break;
        }
        cached.style.setProperty("--barcolor", c);
        if (f && sc) {
            f.style.height = sc.getBoundingClientRect(outerHeight)["height"] + "px";
        }
        if (Poi.pjax) blur_rs();
    });
}

function iconsvg() {
    if (!document.getElementById("svg_blurfilter")) {
        const a = "http://www.w3.org/2000/svg",
            svg = document.querySelector("svg"),
            image = document.querySelector("svg image");
        if (Poi.pjax) {
            const filter = document.createElementNS(a, "filter"),
                fe = document.createElementNS(a, "feGaussianBlur");
            filter.id = "svg_blurfilter";
            fe.setAttribute("stdDeviation", "5");
            fe.setAttribute("color-interpolation-filters", "sRGB");
            image.style.filter = "url(#svg_blurfilter)";
            filter.append(fe);
            svg.append(filter);
        }
        // image.setAttribute("filter", "url(#svg_blurfilter)");
        svg.append(image);
    }
    document.querySelector('.openNav').classList.remove('exbit');
    document.querySelector('.site-header').classList.remove('exbit');
}


function no_right_click() {
    const pri = document.getElementById("primary");
    if(!pri)return;
    pri.addEventListener("contextmenu", function (e) {
        if (e.target.nodeName.toLowerCase() == "img") {
            e.preventDefault();
            e.stopPropagation();
        }
    })
}
no_right_click();

function slideToogle(el, duration = 1000, mode = '', callback) {
    const dom = el;
    dom.status = dom.status || getComputedStyle(dom, null)['display'];
    const flag = dom.status != 'none';
    if ((flag == 1 && mode == "show") || (flag == 0 && mode == "hide")) return;
    dom.status = flag ? 'none' : 'block';
    dom.style.transition = 'height ' + duration / 1000 + 's';
    dom.style.overflow = 'hidden';
    clearTimeout(dom.tagTimer);
    dom.tagTimer = dom.tagTimer || null;
    dom.style.display = 'block';
    dom.tagHeight = dom.tagHeight || dom.clientHeight + 'px';
    dom.style.display = '';
    dom.style.height = flag ? dom.tagHeight : "0px";
    setTimeout(() => {
        dom.style.height = flag ? "0px" : dom.tagHeight;
    }, 0)
    dom.tagTimer = setTimeout(() => {
        dom.style.display = flag ? 'none' : 'block';
        dom.style.transition = '';
        dom.style.overflow = '';
        dom.style.height = '';
        dom.status = dom.tagHeight = null;
    }, duration)
    if (callback) callback();
}

function timeSeriesReload(flag) {
    const archives = document.getElementById('archives');
    if (!archives) return;
    const al_li = archives.getElementsByClassName('al_mon');
    if (flag == true) {
        archives.addEventListener("click", function (e) {
            if (e.target.classList.contains("al_mon")) {
                slideToogle(e.target.nextElementSibling, 500);
                e.preventDefault();
            }
        })
        lazyload();
    } else {
        (function () {
            const al_expand_collapse = document.getElementById('al_expand_collapse');
            al_expand_collapse.style.cursor = "s-resize";
            for (let i = 0; i < al_li.length; i++) {
                let a = al_li[i],
                    num = a.nextElementSibling.getElementsByTagName('li').length;
                a.style.cursor = "s-resize";
                a.querySelector('#post-num').textContent = num;
            }
            let al_post_list = archives.getElementsByClassName("al_post_list"),
                al_post_list_f = al_post_list[0];
            for (let i = 0; i < al_post_list.length; i++) {
                slideToogle(al_post_list[i], 500, 'hide', function () {
                    slideToogle(al_post_list_f, 500, 'show');
                })
            }
            archives.addEventListener("click", function (e) {
                if (e.target.classList.contains("al_mon")) {
                    slideToogle(e.target.nextElementSibling, 500);
                    e.preventDefault();
                }
            })
            if (document.body.clientWidth > 860) {
                for (let i = 0; i < al_post_list.length; i++) {
                    let el = al_post_list[i];
                    el.parentNode.addEventListener('mouseover', function () {
                        slideToogle(el, 500, 'show');
                        return false;
                    })
                }
                let al_expand_collapse_click = 0;
                al_expand_collapse.addEventListener('click', function () {
                    if (al_expand_collapse_click == 0) {
                        for (let i = 0; i < al_post_list.length; i++) {
                            let el = al_post_list[i];
                            slideToogle(el, 500, 'show');
                        }
                        al_expand_collapse_click++;
                    } else if (al_expand_collapse_click == 1) {
                        for (let i = 0; i < al_post_list.length; i++) {
                            let el = al_post_list[i];
                            slideToogle(el, 500, 'hide');
                        }
                        al_expand_collapse_click--;
                    }
                });
            }
        })();
    }
}
timeSeriesReload();

/*视频feature*/
function coverVideo() {
    const video = document.getElementById("coverVideo"),
        btn = document.getElementById("coverVideo-btn");

    if (video.paused) {
        video.play();
        try {
            btn.innerHTML = '<i class="post_icon_svg" style="--svg-name: var(--svg_stop);--size: 14px;"></i>';
        } catch { }
    } else {
        video.pause();
        try {
            btn.innerHTML = '<i class="post_icon_svg" style="--svg-name: var(--svg_play);--size: 14px;"></i>';
        } catch { }
    }
}

function killCoverVideo() {
    const video = document.getElementById("coverVideo"),
        btn = document.getElementById("coverVideo-btn");

    if (video.paused) {
        //console.info('none:killCoverVideo()');
    } else {
        video.pause();
        try {
            btn.innerHTML = '<i class="post_icon_svg" style="--svg-name: var(--svg_play);--size: 14px;"></i>';
        } catch { }
        //console.info('pause:killCoverVideo()');
    }
}

function loadHls() {
    let video = document.getElementById('coverVideo'),
        video_src = document.getElementById("coverVideo").getAttribute("data-src");
    if (Hls.isSupported()) {
        let hls = new Hls();
        hls.loadSource(video_src);
        hls.attachMedia(video);
        hls.on(Hls.Events.MANIFEST_PARSED, function () {
            video.play();
        });
    } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
        video.src = video_src;
        video.addEventListener('loadedmetadata', function () {
            video.play();
        });
    }
}

function loadJS(url, callback) {
    let script = document.createElement("script"),
        fn = callback || function () { };
    script.type = "text/javascript";
    script.onload = function () {
        fn();
    };
    script.src = url;
    document.head.appendChild(script);
}

function coverVideoIni() {
    let video = document.getElementsByTagName('video')[0];
    if (video && video.classList.contains('hls')) {
        if (mashiro_global.variables.has_hls) {
            loadHls();
        } else {
            //不保证可用 需测试
            loadJS("https://cdn.jsdelivr.net/gh/bymoye/sakura@0.0.3/cdn/js/src/16.hls.js", function () {
                loadHls();
                mashiro_global.variables.has_hls = true;
            })
        }
    }
}

function copy_code_block() {
    let ele = document.querySelectorAll("pre code");
    for (let j = 0; j < ele.length; j++) {
        ele[j].setAttribute('id', 'hljs-' + j);
        ele[j].insertAdjacentHTML('afterend', '<a class="copy-code" href="javascript:" data-clipboard-target="#hljs-' + j + '" title="拷贝代码"><i class="post_icon_svg" style="--svg-name: var(--svg_clipboard);--size: 14px;"></i></a>');
    }
    let clipboard = new ClipboardJS('.copy-code');
}

function tableOfContentScroll(flag) {
    if (document.body.clientWidth <= 1200) {
        return;
    } else if (!document.querySelector("div.have-toc") && !document.querySelector("div.has-toc")) {
        let ele = document.querySelector(".toc-container");
        if (ele) ele.remove();
    } else {
        if (flag) {
            let id = 1,
                heading_fix = mashiro_option.entry_content_theme == "sakura" ? (document.querySelector("article.type-post") ? (document.querySelector("div.pattern-attachment-img") ? -75 : 200) : 375) : window.innerHeight / 2;
            let _els = document.querySelectorAll('.entry-content,.links');
                for(let i = 0;i<_els.length;i++){
                    let _el = _els[i].querySelectorAll('h1,h2,h3,h4,h5');
                    for(let j = 0 ;j<_el.length;j++){
                        _el[j].id = "toc-head-" + id;
                        id++;
                    }
                }
            tocbot.init({
                tocSelector: '.toc',
                contentSelector: ['.entry-content', '.links'],
                headingSelector: 'h1, h2, h3, h4, h5',
                headingsOffset: heading_fix - window.innerHeight / 2,
            });
        }
    }
}
tableOfContentScroll(true);
const pjaxInit = function () {
    add_upload_tips();
    no_right_click();
    click_to_view_image();
    original_emoji_click();
    let _p = document.getElementsByTagName("p");
    for(let i=0;i<_p.length;i++){
        _p[i].classList.remove("head-copyright");
    }
    try {
        code_highlight_style();
    } catch {}
    try {
        getqqinfo();
    } catch {}
    lazyload();
    let iconflat = document.getElementsByClassName("iconflat");
    if (iconflat.length != 0) {
        iconflat[0].style.width = '50px';
        iconflat[0].style.height = '50px';
    }
    let openNav = document.getElementsByClassName("openNav");
    if (openNav.length != 0) {
        openNav[0].style.height = '50px';
    }
    smileBoxToggle();
    timeSeriesReload();
    add_copyright();
    tableOfContentScroll(true);
}

const cm_click = (e)=>{
    if (e.target.classList.contains("comment-reply-link")) {
        e.stopPropagation();
        e.preventDefault();
        let data_commentid = e.target.getAttribute("data-commentid");
        addComment.moveForm("comment-" + data_commentid, data_commentid, "respond", e.target.getAttribute("data-postid"));
        //return false;
    }
}

function sm() {

    const sm = document.getElementsByClassName('sm'),
        cm = document.querySelector(".comments-main");
    if (!sm.length) return;
    if (Poi.reply_link_version == 'new' && cm) {
        cm.addEventListener("click",cm_click);
    }
    cm && cm.addEventListener("click",(e)=>{
        let list = e.target.parentNode;
        if(list.classList.contains("sm")){
            let msg = "您真的要设为私密吗？";
            if (confirm(msg) == true) {
                if (list.classList.contains('private_now')) {
                    alert('您之前已设过私密评论');
                    return false;
                } else {
                    list.classList.add('private_now');
                    let idp = list.getAttribute("data-idp"),
                        actionp = list.getAttribute("data-actionp"),
                        rateHolderp = list.getElementsByClassName('has_set_private')[0];
                    let ajax_data = "action=siren_private&p_id=" + idp + "&p_action=" + actionp;
                    let request = new XMLHttpRequest();
                    request.onreadystatechange = function () {
                        if (this.readyState == 4 && this.status == 200) {
                            rateHolderp.innerHTML = request.responseText + ' <i class="post_icon_svg" style="--svg-name: var(--svg_lock);--size: 12px;--color:#7E8892;"></i>';
                        }
                    };
                    request.open('POST', '/wp-admin/admin-ajax.php', true);
                    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    request.send(ajax_data);
                    return false;
                }
            } else {
                alert("已取消");
            }
        }
    })
}

POWERMODE.colorful = true;
POWERMODE.shake = false;
document.body.addEventListener('input', POWERMODE);
function motionSwitch(ele) {
    const motionEles = [".bili", ".menhera", ".tieba"];
    for (let i=0;i<motionEles.length;i++) {
        document.querySelector(motionEles[i] + '-bar').classList.remove('on-hover');
        document.querySelector(motionEles[i] + '-container').style.display = 'none';
    }
    document.querySelector(ele + '-bar').classList.add("on-hover");
    document.querySelector(ele + '-container').style.display = 'block';
}

const ready=function(fn){
    if (typeof fn !== 'function') return;
    if (document.readyState==='complete') {
        return fn();
    }
    document.addEventListener('DOMContentLoaded', fn, false);
};

function smileBoxToggle() {
    const et = document.getElementById("emotion-toggle");
    et && et.addEventListener('click', function () {
        document.querySelector('.emotion-toggle-off').classList.toggle("emotion-hide");
        document.querySelector('.emotion-toggle-on').classList.toggle("emotion-show");
        document.querySelector('.emotion-box').classList.toggle("emotion-box-show");
    })
}
smileBoxToggle();

function grin(tag, type, before, after) {
    let myField;
    switch (type) {
        case "custom": tag = before + tag + after; break;
        case "Img": tag = '[img]' + tag + '[/img]'; break;
        case "Math": tag = ' {{' + tag + '}} '; break;
        case "tieba": tag = ' ::' + tag + ':: '; break;
        default: tag = ' :' + tag + ': ';
    }
    if (document.getElementById('comment') && document.getElementById('comment').type == 'textarea') {
        myField = document.getElementById('comment');
    } else {
        return false;
    }
    if (document.selection) {
        myField.focus();
        let sel = document.selection.createRange();
        sel.text = tag;
        myField.focus();
    } else if (myField.selectionStart || myField.selectionStart == '0') {
        let startPos = myField.selectionStart,
            endPos = myField.selectionEnd,
            cursorPos = endPos;
        myField.value = myField.value.substring(0, startPos) + tag + myField.value.substring(endPos, myField.value.length);
        cursorPos += tag.length;
        myField.focus();
        myField.selectionStart = cursorPos;
        myField.selectionEnd = cursorPos;
    } else {
        myField.value += tag;
        myField.focus();
    }
}
const copytext = (e) => {
    const setClipboardText = (event) => {
        event.preventDefault();
        const htmlData = "# 商业转载请联系作者获得授权，非商业转载请注明出处。<br>" + "# For commercial use, please contact the author for authorization. For non-commercial use, please indicate the source.<br>" + "# 协议(License)：署名-非商业性使用-相同方式共享 4.0 国际 (CC BY-NC-SA 4.0)<br>" + "# 作者(Author)：" + mashiro_option.author_name + "<br>" + "# 链接(URL)：" + window.location.href + "<br>" + "# 来源(Source)：" + mashiro_option.site_name + "<br><br>" + window.getSelection().toString().replace(/\r\n/g, "<br>"),
            textData = "# 商业转载请联系作者获得授权，非商业转载请注明出处。\n" + "# For commercial use, please contact the author for authorization. For non-commercial use, please indicate the source.\n" + "# 协议(License)：署名-非商业性使用-相同方式共享 4.0 国际 (CC BY-NC-SA 4.0)\n" + "# 作者(Author)：" + mashiro_option.author_name + "\n" + "# 链接(URL)：" + window.location.href + "\n" + "# 来源(Source)：" + mashiro_option.site_name + "\n\n" + window.getSelection().toString().replace(/\r\n/g, "\n");
        if (event.clipboardData) {
            event.clipboardData.setData("text/html", htmlData);
            event.clipboardData.setData("text/plain", textData);
        } else if (window.clipboardData) {
            return window.clipboardData.setData("text", textData);
        }
    }
    if (window.getSelection().toString().length > 30 && mashiro_option.clipboardCopyright) {
        setClipboardText(e);
    }
    addComment.createButterbar("复制成功！<br>Copied to clipboard successfully!", 1000);
}
function add_copyright() {
    document.body.removeEventListener("copy",copytext);
    document.body.addEventListener("copy", copytext);
}
add_copyright();
ready(getqqinfo);

function getqqinfo() {
    let is_get_by_qq = false,
        author = document.getElementById("author"),
        qq = document.getElementById("qq"),
        email = document.getElementById("email"),
        url = document.getElementById("url"),
        qq_check = document.getElementsByClassName("qq-check")[0],
        gravatar_check = document.getElementsByClassName("gravatar-check")[0],
        temp,
        user_avatar_img = document.querySelector("div.comment-user-avatar img");
        if (author == null) return;
    if (!getCookie('user_qq') && !getCookie('user_qq_email') && !getCookie('user_author')) {
        qq.value = author.value = email.value = url.value = "";
        imgError(user_avatar_img,2);
    }
    if (getCookie('user_avatar') && getCookie('user_qq') && getCookie('user_qq_email')) {
        user_avatar_img.setAttribute('src', getCookie('user_avatar'));
        author.value = getCookie('user_author');
        email.value = getCookie('user_qq') + '@qq.com';
        qq.value = getCookie('user_qq');
        if (mashiro_option.qzone_autocomplete) {
            url.value = 'https://user.qzone.qq.com/' + getCookie('user_qq');
        }
        if (qq.value) {
            qq_check.style.display = "block";
            gravatar_check.style.display = "none";
        }
    }
    let emailAddressFlag = email.value;
    author.addEventListener("blur", function () {
        if (temp==author.value){
            return;
        }else{
            temp=author.value;
        }
        let qq = author.value,
            xhr = new XMLHttpRequest();

        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 304)) {
                let res = JSON.parse(xhr.responseText);
                author.value = res[qq][6];
                email.value = qq.trim() + "@qq.com";
                if (mashiro_option.qzone_autocomplete) {
                    url.value = "https://user.qzone.qq.com/" + qq.trim();
                }
                user_avatar_img.setAttribute("src", "https://q2.qlogo.cn/headimg_dl?dst_uin=" + qq + "&spec=100");
                is_get_by_qq = true;
                qq = qq.trim();
                qq_check.style.display = "block";
                gravatar_check.style.display = "none";
                setCookie('user_author', res[qq][6], 30);
                setCookie('user_qq', qq, 30);
                setCookie('is_user_qq', 'yes', 30);
                setCookie('user_qq_email', qq + '@qq.com', 30);
                setCookie('user_email', qq + '@qq.com', 30);
                emailAddressFlag = email.value;
                temp=author.value;
            } else if (xhr.readyState == 4) {
                qq = "";
                qq_check.style.display = "none";
                gravatar_check.style.display = "block";
                imgError(user_avatar_img,2);
                if (email.value && is_get_by_qq==false) {
                    user_avatar_img.setAttribute("src", get_gravatar(email.value, 80));
                    setCookie('user_qq', '', 30);
                    setCookie('user_email', email.value, 30);
                    setCookie('user_avatar', get_gravatar(email.value, 80), 30);
                }
            }
        }
        let ajax_url = mashiro_option.qq_api_url + '?type=getqqnickname&qqnumber=' + qq;
        if (qq == "" || isNaN(qq) || qq.length < 5 || qq.length > 12) {
            qq_check.style.display = "none";
            gravatar_check.style.display = "block";
        } else {
            xhr.open("get", ajax_url, true);
            xhr.send();
        }

        let xhr2 = new XMLHttpRequest();
        xhr2.onreadystatechange = function () {
            if (xhr2.readyState == 4 && (xhr2.status == 200 || xhr2.status == 304)) {
                let res = JSON.parse(xhr2.responseText);
                user_avatar_img.setAttribute("src", res[qq]);
                setCookie('user_avatar', res[qq], 30);
            } else if (xhr2.readyState == 4) {
                if (!qq.value) {
                    qq_check.style.display = "none";
                    gravatar_check.style.display = "block";
                    setCookie("user_qq", "", 30);
                    if (!email.value) {
                        user_avatar_img.setAttribute("src", get_gravatar(email.value, 80));
                        setCookie('user_avatar', get_gravatar(email.value, 80), 30);
                    }
                }
            }
        }
        if (qq == "" || isNaN(qq) || qq.length < 5 || qq.length > 12) {
            qq_check.style.display = "none";
            gravatar_check.style.display = "block";
        } else {
            let ajax_url2 = mashiro_option.qq_avatar_api_url + '?type=getqqavatar&qqnumber=' + qq;
            xhr2.open("get", ajax_url2, true);
            xhr2.send();
        }
    })
    if (getCookie('user_avatar') && getCookie('user_email') && getCookie('is_user_qq') == 'no' && !getCookie('user_qq_email')) {
        user_avatar_img.setAttribute("src", getCookie('user_avatar'));
        email.value = getCookie('user_mail');
        qq.value = '';
        if (!qq.value) {
            qq_check.style.display = "none";
            gravatar_check.style.display = "block";
        }
    }
    email.addEventListener("blur", function () {
        let emailAddress = email.value;
        if (!emailAddress)return;
        if (is_get_by_qq == false || emailAddressFlag != emailAddress) {
            user_avatar_img.setAttribute("src", get_gravatar(emailAddress, 80));
            setCookie('user_avatar', get_gravatar(emailAddress, 80), 30);
            setCookie('user_email', emailAddress, 30);
            setCookie('user_qq_email', '', 30);
            setCookie('is_user_qq', 'no', 30);
            //qq.value = '';
            if (!qq.value) {
                qq_check.style.display = "none";
                gravatar_check.style.display = "block";
            }
        }
    });
    if (getCookie('user_url')) {
        url.value = getCookie("user_url");
    }
    url.addEventListener("blur", function () {
        let URL_Address = url.value;
        url.value = URL_Address;
        setCookie('user_url', URL_Address, 30);
    });
    if (getCookie('user_author')) {
        author.value = getCookie('user_author');
    }
    author.addEventListener("blur", function () {
        let user_name = author.value;
        author.value = user_name;
        setCookie('user_author', user_name, 30);
    });
}

function mail_me() {
    let mail = "mailto:" + mashiro_option.email_name + "@" + mashiro_option.email_domain;
    window.open(mail);
}

function activate_widget() {
    let secondary = document.getElementById("secondary");
    if (!secondary)return;
    if (document.body.clientWidth > 860) {
            let show_hide = document.getElementsByClassName("show-hide");
            if (!show_hide.length)return;
            show_hide[0].addEventListener("click", function () {
                secondary.classList.toggle("active");
            });
    } else {
            secondary.remove();
    }
}
setTimeout(function () {
    activate_widget();
}, 100);

function load_bangumi() {
    let section = document.getElementsByTagName("section"),_flag=false;
    for (let i=0;i<section.length;i++){
        if(section[i].classList.contains("bangumi")){
            _flag = true;
        }
    }
    if(_flag){
            document.addEventListener('click', function (e) {
                let target = e.target;
                if (target === document.querySelector("#bangumi-pagination a")) {
                    let bgpa = document.querySelector("#bangumi-pagination a");
                    bgpa.classList.add("loading");
                    bgpa.textContent = "";
                    let xhr = new XMLHttpRequest();
                    xhr.open('POST', target.href + "&_wpnonce=" + Poi.nonce, true);
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState == 4 && xhr.status == 200) {
                            let html = JSON.parse(xhr.responseText),
                                bfan = document.getElementById("bangumi-pagination"),
                                row = document.getElementsByClassName("row")[0];
                            bfan.remove();
                            row.insertAdjacentHTML('beforeend', html);
                        } else {
                            bgpa.classList.remove("loading");
                            bgpa.innerHTML = '<i class="post_icon_svg" style="--svg-name: var(--svg_exclamation-triangle);--size: 35px;vertical-align: -0.1em;"></i>ERROR ';
                        }
                    };
                    xhr.send();
                }
            });
}
}

mashiro_global.ini.normalize();
loadCSS(mashiro_option.jsdelivr_css_src);
loadCSS(mashiro_option.entry_content_theme_src);

function serialize(form) {
    let formData = new FormData(form),
        getValue = formData.entries(),
        parts = [];
    for (let pair of getValue) {
        parts.push(pair[0] + "=" + encodeURIComponent(pair[1]));
    }
    return parts.join("&");
}

let s = document.getElementById("bgvideo"),
    Siren = {
        MN: function () {
            let icf = document.getElementsByClassName("iconflat")[0];
            icf && icf.addEventListener("click", ()=>{
                document.body.classList.toggle("navOpen");
                document.getElementById("main-container").classList.toggle("open");
                document.getElementById("mo-nav").classList.toggle("open");
                document.getElementsByClassName("openNav")[0].classList.toggle("open");
            });
        },
        MNH: function () {
            if (document.body.classList.contains("navOpen")) {
                document.body.classList.toggle("navOpen");
                document.getElementById("main-container").classList.toggle("open");
                document.getElementById("mo-nav").classList.toggle("open");
                document.getElementsByClassName("openNav")[0].classList.toggle("open");
            }
        },
        splay: function () {
                let video_btn = document.getElementById("video-btn");
                if (!video_btn)return;
                video_btn.classList.add("video-pause");
                video_btn.classList.remove("video-play");
                try{
                video_btn.style.display = "";
                document.querySelector(".video-stu").style.bottom = "-100px";
                document.querySelector(".focusinfo").style.top = "-999px";
                }catch{}
            try {
                for (let i = 0; i < ap.length; i++) {
                    try {
                        ap[i].destroy()
                    } catch {}
                }
            } catch {}
            try {
                hermitInit()
            } catch (e) { }
            s.play();
        },
        spause: function () {
            try {
                let video_btn = document.getElementById("video-btn");
                video_btn.classList.add("video-play");
                video_btn.classList.remove("video-pause");
                document.getElementsByClassName("focusinfo")[0].style.top = "49.3%";
            } catch{}
            s.pause();

        },
        liveplay: function () {
            if (s.oncanplay != undefined && document.querySelector(".haslive") != null) {
                if (document.querySelector(".videolive") != null) {
                    Siren.splay();
                }
            }
        },
        livepause: function () {
            if (s.oncanplay != undefined && document.querySelector(".haslive") != null) {
                Siren.spause();
                let video_stu = document.getElementsByClassName("video-stu")[0];
                video_stu.style.bottom = "0px";
                video_stu.innerHTML = "已暂停 ...";
            }
        },
        addsource: function () {
            let video_stu = document.getElementsByClassName("video-stu")[0];
            video_stu.innerHTML = "正在载入视频 ...";
            video_stu.style.bottom = "0px";
            let t = Poi.movies.name.split(","),
                _t = t[Math.floor(Math.random() * t.length)],
                bgvideo = document.getElementById("bgvideo");
            bgvideo.setAttribute("src", Poi.movies.url + '/' + _t + '.mp4');
            bgvideo.setAttribute("video-name", _t);
        },
        LV: function () {
            let _btn = document.getElementById("video-btn");
            _btn && _btn.addEventListener("click", function () {
                if (this.classList.contains("loadvideo")) {
                    this.classList.add("video-pause");
                    this.classList.remove("loadvideo");
                    Siren.addsource();
                    s.oncanplay = ()=> {
                        Siren.splay();
                        document.getElementById("video-add").style.display = "block";
                        _btn.classList.add("videolive", "haslive");
                    }
                } else {
                    if (this.classList.contains("video-pause")) {
                        Siren.spause();
                        _btn.classList.remove("videolive");
                        document.getElementsByClassName("video-stu")[0].style.bottom = "0px";
                        document.getElementsByClassName("video-stu")[0].innerHTML = "已暂停 ...";
                    } else {
                        Siren.splay();
                        _btn.classList.add("videolive");
                    }
                }
                s.onended = () => {
                    s.setAttribute("src", "");
                    document.getElementById("video-add").style.display = "none";
                    _btn.classList.add("loadvideo");
                    _btn.classList.remove("video-pause", "videolive", "haslive");
                    document.querySelector(".focusinfo").style.top = "49.3%";
                }
            });
            document.getElementById("video-add").addEventListener("click", ()=> {
                Siren.addsource();
            });
        },
        AH: function () {
            if (Poi.windowheight == 'auto' && mashiro_option.windowheight == 'auto') {
                if (document.querySelector(".main-title")) {
                    document.getElementById("centerbg").style.height = "100vh";
                    document.getElementById("bgvideo").style.minHeight = "100vh";
                }
            } else {
                let headertop = document.querySelector(".headertop");
                headertop && headertop.classList.add("headertop-bar");
            }
        },
        PE: function () {
            if (document.querySelector(".headertop")) {
                let headertop = document.querySelector(".headertop"),
                    blank = document.querySelector(".blank");
                if (document.querySelector(".main-title")) {
                     if(blank) blank.style.paddingTop = "0px";
                    headertop.style.display = "";
                    if (Poi.movies.live == 'open') Siren.liveplay();
                } else {
                    if(blank) blank.style.paddingTop = "75px";
                    headertop.style.display = "none";
                    Siren.livepause();
                }
            }
        },
        CE: function () {
            let comments_hidden = document.querySelector(".comments-hidden");
            let comments_main = document.querySelector(".comments-main");
            if (comments_hidden != null) {
                comments_hidden.style.display = "block";
                comments_main.style.display = "none";
                comments_hidden.addEventListener("click", function () {
                    slideToogle(comments_main, 500, 'show');
                    comments_hidden.style.display = "none";
                });
            }
            let archives = document.getElementsByClassName("archives");
            if (archives.length > 0) {
                for (let i =0;i<archives.length;i++){
                    archives[i].style.display = "none";
                }
                archives[0].style.display = "";
                let h3 = document.getElementById("archives-temp").getElementsByTagName("h3");
                for(let i=0;i<h3.length;i++){
                    h3[i].addEventListener("click",(e)=>{
                        e.preventDefault();
                        e.stopPropagation();
                        slideToogle(e.target.nextElementSibling, 300);
                    })
                }
            }
            if (mashiro_option.baguetteBoxON) {
                baguetteBox.run('.entry-content', {
                    captions: function (element) {
                        return element.getElementsByTagName('img')[0].alt;
                    }
                });
            }
            document.querySelector(".js-toggle-search").addEventListener("click", function () {
                document.querySelector(".js-toggle-search").classList.toggle("is-active");
                document.querySelector(".js-search").classList.toggle("is-visible");
                document.documentElement.style.overflowY = "hidden";
                if (mashiro_option.live_search) {
                    let QueryStorage = [];
                    search_a(Poi.api + "sakura/v1/cache_search/json?_wpnonce=" + Poi.nonce);

                    let otxt = document.getElementById("search-input"),
                        list = document.getElementById("PostlistBox"),
                        Record = list.innerHTML,
                        searchFlag = null;
                    otxt.oninput = function () {
                        if (searchFlag == null) {
                            clearTimeout(searchFlag);
                        }
                        searchFlag = setTimeout(function () {
                            query(QueryStorage, otxt.value, Record);
                            div_href();
                        }, 250);
                    };

                    function search_a(val) {
                        if (sessionStorage.getItem('search') != null) {
                            QueryStorage = JSON.parse(sessionStorage.getItem('search'));
                            query(QueryStorage, document.getElementById("search-input").value, Record);
                            div_href();
                        } else {
                            let _xhr = new XMLHttpRequest();
                            _xhr.open("GET", val, true)
                            _xhr.send();
                            _xhr.onreadystatechange = function () {
                                if (_xhr.readyState == 4 && _xhr.status == 200) {
                                    let json = _xhr.responseText;
                                    if (json != "") {
                                        sessionStorage.setItem('search', json);
                                        QueryStorage = JSON.parse(json);
                                        query(QueryStorage, otxt.value, Record);
                                        div_href();
                                    }
                                }
                            }
                        }
                    }
                    if (!Object.values) Object.values = function (obj) {
                        if (obj !== Object(obj))
                            throw new TypeError('Object.values called on a non-object');
                        let val = [],
                            key;
                        for (key in obj) {
                            if (Object.prototype.hasOwnProperty.call(obj, key)) {
                                val.push(obj[key]);
                            }
                        }
                        return val;
                    }

                    function Cx(arr, q) {
                        q = q.replace(q, "^(?=.*?" + q + ").+$").replace(/\s/g, ")(?=.*?");
                        return arr.filter(
                            v => Object.values(v).some(
                                v => new RegExp(q + '').test(v)
                            )
                        );
                    }

                    function div_href() {
                        const ins_selectable = document.getElementsByClassName("ins-selectable");
                        for(let i=0;i<ins_selectable.length;i++){
                            let a = ins_selectable[i];
                            a.addEventListener("click", function () {
                                document.getElementById("Ty").setAttribute("href", this.getAttribute("href"));
                                document.getElementById("Ty").click();
                                document.querySelector(".search_close").click();
                            })
                    }
                }

                    function search_result(keyword, link, fa, title, iconfont, comments, text) {
                        if (keyword) {
                            let s = keyword.trim().split(" "),
                                a = title.indexOf(s[s.length - 1]),
                                b = text.indexOf(s[s.length - 1]);
                            title = a < 60 ? title.slice(0, 80) : title.slice(a - 30, a + 30);
                            title = title.replace(s[s.length - 1], '<mark class="search-keyword"> ' + s[s.length - 1].toUpperCase() + ' </mark>');
                            text = b < 60 ? text.slice(0, 80) : text.slice(b - 30, b + 30);
                            text = text.replace(s[s.length - 1], '<mark class="search-keyword"> ' + s[s.length - 1].toUpperCase() + ' </mark>');
                        }
                        return '<div class="ins-selectable ins-search-item" href="' + link + '"><header><i class="post_icon_svg" style="--svg-name: var(--svg_'+ fa +');--size: 14px;"></i>' + title + '<i class="post_icon_svg" style="--svg-name: var(--svg_' + iconfont + '">'+ comments + '</i>' + '</header><p class="ins-search-preview">' + text + '</p></div>';
                    }

                    function query(B, A, z) {
                        let x, v, s, y = "",
                            w = "",
                            u = "",
                            r = "",
                            p = "",
                            F = "",
                            H = "",
                            G = '<section class="ins-section"><header class="ins-section-header">',
                            D = "</section>",
                            E = "</header>",
                            C = Cx(B, A.trim());
                        for (x = 0; x < Object.keys(C).length; x++) {
                            H = C[x];
                            switch (v = H.type) {
                                case "post":
                                    w = w + search_result(A, H.link, "file", H.title, "message", H.comments, H.text);
                                    break;
                                case "tag":
                                    p = p + search_result("", H.link, "tag", H.title, "none1", "", "");
                                    break;
                                case "category":
                                    r = r + search_result("", H.link, "folder", H.title, "none1", "", "");
                                    break;
                                case "page":
                                    u = u + search_result(A, H.link, "file", H.title, "message", H.comments, H.text);
                                    break;
                                case "comment":
                                    F = F + search_result(A, H.link, "comment", H.title, "none1", "", H.text);
                                    break;
                            }
                        }
                        w && (y = y + G + "文章" + E + w + D), u && (y = y + G + "页面" + E + u + D), r && (y = y + G + "分类" + E + r + D), p && (y = y + G + "标签" + E + p + D), F && (y = y + G + "评论" + E + F + D), s = document.getElementById("PostlistBox"), s.innerHTML = y
                    }
                }
            });
            let searc = document.querySelector(".search_close");
            searc && searc.addEventListener("click", function () {
                let js_search = document.getElementsByClassName("js-search")[0];
                if (js_search.classList.contains("is-visible")) {
                    document.getElementsByClassName("js-toggle-search")[0].classList.toggle("is-active");
                    js_search.classList.toggle("is-visible");
                    document.documentElement.style.overflowY = "unset";
                }
            });
                let show_Nav = document.getElementById("show-nav"),
                    stln = document.querySelector(".site-top .lower nav"),
                    loading = document.getElementById("loading");
                show_Nav && show_Nav.addEventListener("click", function () {
                    if (show_Nav.classList.contains("showNav")) {
                        show_Nav.classList.remove("showNav");
                        show_Nav.classList.add("hideNav");
                        stln && stln.classList.add("navbar");
                    } else {
                        show_Nav.classList.remove("hideNav");
                        show_Nav.classList.add("showNav");
                        stln && stln.classList.remove("navbar");
                    }
                });
                loading && loading.addEventListener("click", function () {
                    loading.classList.add("hide");
                    loading.classList.remove("show");
                });
        },
        NH: function () {
            let t = window.innerHeight / 4,
                i = document.documentElement.scrollTop || window.pageYOffset,
                 _add = (_class) => {
                    document.querySelector(".site-header").classList.add(_class);
                    document.querySelector(".openNav").classList.add(_class);
                },
                _remove = (_class) => {
                    document.querySelector(".site-header").classList.remove(_class);
                    document.querySelector(".openNav").classList.remove(_class);
                },
                mb_to_top = document.getElementById("GoTop");
            window.addEventListener("scroll", function () {
                let s = document.documentElement.scrollTop || window.pageYOffset;
                    if (s > 20){mb_to_top.style.transform = "scale(1)";}
                    if (s < 20){mb_to_top.style.transform = "scale(0)";}
                    if (s == 0) { _remove("exbit");_remove("yya");}
                    if (s > 0) { _add("yya");}
                    if(s > t) {_add("exbit");s <= i && (_remove("exbit")); i=s;}
            });
            mb_to_top.onclick = ()=>{
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                })
            }
        },
        XLS: function () {
            let load_post_timer;
            let intersectionObserver = new IntersectionObserver(entries => {
                if (entries[0].intersectionRatio <= 0) return;
                let pagination = document.querySelector("#pagination a"),
                    page_next = pagination != null ? pagination.getAttribute("href") : undefined,
                    load_key = document.getElementById("add_post_time");
                if (page_next != undefined && load_key) {
                    let load_time = document.getElementById("add_post_time").title;
                    if (load_time != "233") {
                        console.log("%c 自动加载时倒计时 %c", "background:#9a9da2; color:#ffffff; border-radius:4px;", "", "", load_time);
                        load_post_timer = setTimeout(function () {
                            load_post();
                        }, load_time * 1000);
                    }
                }
            });
            intersectionObserver.observe(
                document.querySelector('.footer-device')
            );
            document.body.removeEventListener("click", this.ZV);
            document.body.addEventListener("click", this.ZV);
            document.body.addEventListener("click", function (e) {
                if (e.target == document.querySelector("#pagination a")) {
                    e.preventDefault();
                    e.stopPropagation();
                    clearTimeout(load_post_timer);
                    load_post();
                }
            })
            function load_post() {
                let pagination = document.querySelector("#pagination a");
                if (pagination != null) {
                    pagination.classList.add("loading");
                    pagination.innerHTML = "";
                    let _xhr = new XMLHttpRequest();
                    _xhr.open("GET", pagination.getAttribute("href") + "#main", true);
                    _xhr.responseType = "document";
                    _xhr.onreadystatechange = function () {
                        if (_xhr.readyState == 4 && _xhr.status == 200) {
                            let json = _xhr.response,
                                result = json.querySelectorAll("#main .post"),
                                pga = json.querySelector("#pagination a"),
                                nextHref = pga && pga.getAttribute("href");
                                for(let i=0;i<result.length;i++){
                                    let b = result[i];
                                    document.getElementById("main").insertAdjacentHTML('beforeend', b.outerHTML);
                                }
                            let content = document.querySelector("#content");
                            if(Poi.pjax)_pjax.refresh(content);
                            let dpga = document.querySelector("#pagination a"),
                                addps = document.querySelector("#add_post span");
                            if (dpga){
                                dpga.classList.remove("loading");
                                dpga.innerHTML = "Previous";
                            }
                            if (addps){
                                addps.classList.remove("loading");
                                addps.innerHTML = "";
                            }
                            lazyload();
                            post_list_show_animation();
                            if (nextHref != undefined) {
                                let tempScrollTop = document.documentElement.scrollTop;
                                window.scrollTo({
                                    top: tempScrollTop + 100,
                                    behavior: "smooth"
                                });
                                document.querySelector("#pagination a").setAttribute("href", nextHref);
                            } else {
                                document.getElementById("pagination").innerHTML = "<span>很高兴你翻到这里，但是真的没有了...</span>";
                            }
                        }
                    }
                    _xhr.send();
                }
                return false;
            }
        },
        ZV: function (e) {
            if (e.target.classList.contains("comment-at")) {
                e.preventDefault();
                e.stopPropagation();
                window.scrollTo({
                    top: document.querySelector(e.target.hash).offsetTop - 100,
                    behavior: "smooth"
                });
            }
        },
        XCS: function () {
            let __list = "commentwrap";
            document.body.addEventListener('submit', function (e) {
                if (e.target == document.querySelector("form#commentform.comment-form")) {
                    e.preventDefault();
                    e.stopPropagation();
                    let from_Data = document.querySelector("form#commentform.comment-form");
                    let xhr = new XMLHttpRequest();
                    xhr.open('POST', Poi.ajaxurl, true);
                    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhr.onloadstart = function () {
                        addComment.createButterbar("提交中(Commiting)....");
                    };
                    xhr.onerror = function () {
                        addComment.createButterbar(xhr.responseText);
                    };
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState == 4 && xhr.status == 200) {
                            let data = xhr.responseText;
                            document.getElementById("comment").value = "";
                            let t = addComment,
                                cancel = document.getElementById('cancel-comment-reply-link'),
                                temp = document.getElementById('wp-temp-form-div'),
                                respond = document.getElementById(t.respondId),
                               // post = document.getElementById('comment_post_ID').value,
                                parent = document.getElementById('comment_parent').value;
                            if (parent != '0') {
                                document.getElementById("respond").insertAdjacentHTML('beforebegin', '<ol class="children">' + data + '</ol>');
                            } else if (!document.getElementsByClassName(__list).length) {
                                if (Poi.formpostion == 'bottom') {
                                    document.getElementById("respond").insertAdjacentHTML('beforebegin', '<ol class="' + __list + '">' + data + '</ol>');
                                } else {
                                    document.getElementById("respond").insertAdjacentHTML('afterend', '<ol class="' + __list + '">' + data + '</ol>');
                                }
                            } else {
                                if (Poi.order == 'asc') {
                                    document.getElementsByClassName("commentwrap")[1].insertAdjacentHTML('beforeend', data);
                                } else {
                                    document.getElementsByClassName("commentwrap")[1].insertAdjacentHTML('afterbegin', data);
                                }
                            }
                            t.createButterbar("提交成功(Succeed)");
                            lazyload();
                            code_highlight_style();
                            click_to_view_image();
                            clean_upload_images();
                            cancel.style.display = 'none';
                            cancel.onclick = null;
                            document.getElementById('comment_parent').value = '0';
                            if (temp && respond) {
                                temp.parentNode.insertBefore(respond, temp);
                                temp.remove();
                            }
                        } else {
                            addComment.createButterbar(xhr.responseText);
                        }
                    };
                    xhr.send(serialize(from_Data) + "&action=ajax_comment");
                }
            });
            addComment = {
                moveForm: function (commId, parentId, respondId) {
                    let t = this,
                        div, comm = document.getElementById(commId),
                        respond = document.getElementById(respondId),
                        cancel = document.getElementById('cancel-comment-reply-link'),
                        parent = document.getElementById('comment_parent'),
                        //post = document.getElementById('comment_post_ID'),
                        temp;
                    t.respondId = respondId;
                    if (!document.getElementById('wp-temp-form-div')) {
                        div = document.createElement('div');
                        div.id = 'wp-temp-form-div';
                        div.style.display = 'none';
                        respond.parentNode.insertBefore(div, respond)
                    }
                    !comm ? (temp = document.getElementById('wp-temp-form-div'), document.getElementById('comment_parent').value = '0', temp.parentNode.insertBefore(respond, temp), temp.remove()) : comm.parentNode.insertBefore(respond, comm.nextSibling);
                    const _respond = document.getElementById("respond");
                    window.scrollTo({
                        top: _respond.getBoundingClientRect().top + window.pageYOffset - _respond.clientTop - 100,
                        behavior: "smooth"
                    });
                    parent.value = parentId;
                    cancel.style.display = '';
                    cancel.onclick = function () {
                        let t = addComment,
                            temp = document.getElementById('wp-temp-form-div'),
                            respond = document.getElementById(t.respondId);
                        document.getElementById('comment_parent').value = '0';
                        if (temp && respond) {
                            temp.parentNode.insertBefore(respond, temp);
                            temp.remove();
                        }
                        this.style.display = 'none';
                        this.onclick = null;
                        return false;
                    };
                    try {
                        document.getElementById('comment').focus();
                    } catch { }
                    return false;
                },
                clearButterbar: function (e) {
                    let butterBar = document.getElementsByClassName("butterBar");
                    if (butterBar.length > 0) {
                        for(let i=0;i<butterBar.length;i++){
                            let a = butterBar[i];
                            a.remove();
                        }
                    }
                },
                createButterbar: function (message, showtime) {
                    let t = this;
                    t.clearButterbar();
                    document.body.insertAdjacentHTML('beforeend', '<div class="butterBar butterBar--center"><p class="butterBar-message">' + message + '</p></div>');
                    let butterBar = () => {
                        let _butterBar = document.getElementsByClassName("butterBar");
                        if (_butterBar.length == 0) return;
                        for (let i=0;i<_butterBar.length;i++){
                            let a = _butterBar[i];
                            a.remove();
                        }
                    }
                    if (showtime > 0) {
                        setTimeout(() => { butterBar() }, showtime);
                    } else {
                        setTimeout(() => { butterBar() }, 6000);
                    }
                }
            };
        },
        XCP: function () {
            document.body.addEventListener('click', function (e) {
                if (e.target.parentNode == document.getElementById("comments-navi") && e.target.nodeName.toLowerCase() == "a") {
                    e.preventDefault();
                    e.stopPropagation();
                    let _this = e.target,
                        path = _this.pathname,
                        _xhr = new XMLHttpRequest();
                    _xhr.open("GET", _this.getAttribute('href'), true);
                    _xhr.responseType = "document";
                    _xhr.onloadstart = () => {
                        let comments_navi = document.getElementById("comments-navi"),
                            commentwrap = document.querySelector("ul.commentwrap"),
                            loading_comments = document.getElementById("loading-comments"),
                            comments_list = document.getElementById("comments-list-title");
                        comments_navi.remove();
                        commentwrap.remove();
                        loading_comments.style.display="block";
                        slideToogle(loading_comments, 500, "show");
                        window.scrollTo({
                            top: comments_list.getBoundingClientRect().top + window.pageYOffset - comments_list.clientTop - 65,
                            behavior: "smooth"
                        });
                    }
                    _xhr.onreadystatechange = function () {
                        if (_xhr.readyState == 4 && _xhr.status == 200) {
                            let json = _xhr.response,
                                result = json.querySelector("ul.commentwrap"),
                                nextlink = json.getElementById("comments-navi"),
                                loading_comments = document.getElementById("loading-comments");
                            slideToogle(loading_comments, 200, "hide");
                            document.getElementById("loading-comments").insertAdjacentHTML('afterend', result.outerHTML);
                            document.querySelector("ul.commentwrap").insertAdjacentHTML('afterend', nextlink.outerHTML);
                            lazyload();
                            if (window.gtag) {
                                gtag('config', Poi.google_analytics_id, {
                                    'page_path': path
                                });
                            }
                            code_highlight_style();
                            click_to_view_image();
                            let commentwrap = document.querySelector("ul.commentwrap");
                            window.scrollTo({
                                top: commentwrap && (commentwrap.getBoundingClientRect().top+ window.pageYOffset - commentwrap.clientTop - 200),
                                behavior: "smooth"
                            });
                        }
                    }
                    _xhr.send();
                }
            });
        },
        IA: function () {
            POWERMODE.colorful = true;
            POWERMODE.shake = false;
            document.body.addEventListener('input', POWERMODE)
        },
        FDT: function () {
            const _header = document.querySelector(".pattern-header");
            if (!_header) return;
            let top,
                _center = document.querySelector(".pattern-center"),
                g = 60,
                i = 15,
                e,flag,
                _headertoggle = () => {
                    if (flag)return;
                    flag=true;
                    switch(top){
                        case "0px":g > 60 ? g -= 0.5 : g = 60;
                                   i < 15 ? i += 0.1 : i = 15;
                                   break;
                        case "60%":g < 100 ? g +=0.5 : g = 100;
                                   i > 5 ? i -= 0.1 : i = 5;
                                   break;
                    }
                    _header.style.setProperty("--blur",i+"px");
                    _header.style.setProperty("--saturate",g+"%");
                    if ((top == "0px" && (i!=15 || g!=60)) || (top == "60%" && (i!=5 || g!=100))) {
                        flag=false;
                        e = requestAnimationFrame(_headertoggle);
                    }
                }

            _center && _center.addEventListener("mouseover", function () {
               _header.style.top = "60%";
            });
            _center && _center.addEventListener("mouseleave", function () {
                _header.style.top = "0px";
            });
            _header.ontransitionend = function (e) {
                e.stopPropagation();
                if (e.target === this) {
                    top = _header.style.top;
                        _headertoggle();
                }
            }
            _header.ontransitionrun =()=>{
                flag=false;
                cancelAnimationFrame(e);
            }
        },
    }

if (Poi.pjax) {
    var _pjax = new Pjax({
        selectors: ["#page", "title", ".footer-device"],
        elements: [
            "a:not([target='_top']):not(.comment-reply-link):not(#pagination a):not(#comments-navi a):not(.user-menu-option a):not(.header-user-avatar a):not(.emoji-item)",
            ".search-form",
            ".s-search",
        ],
        timeout: 8000,
        history: true,
        cacheBust: false
    });
    document.addEventListener("pjax:send", () => {
        // $(document).on('pjax:beforeSend', () => { //离开页面停止播放
        let normal = document.getElementsByClassName("normal-cover-video");
        if (normal.length > 0) {
            for (let a=0; a < normal.length; a++) {
                normal[a].pause();
                normal[a].src = '';
                normal[a].load = '';
            }
        }
        document.getElementById("bar").style.setProperty("--barwidth","0%");
        if (mashiro_option.NProgressON) NProgress.start();
        Siren.MNH();
    });
    document.addEventListener("pjax:complete", function () {
        Siren.AH();
        Siren.FDT();
        Siren.PE();
        Siren.CE();
        //Siren.XLS();
        if (mashiro_option.NProgressON) NProgress.done();
        mashiro_global.ini.pjax();
        let loading = document.getElementById("loading");
        loading && loading.classList.add("hide");
        loading && loading.classList.remove("show");
        if (Poi.codelamp == 'open') {
            self.Prism.highlightAll(event)
        }
        if (document.querySelector(".js-search.is-visible")) {
            document.getElementsByClassName("js-toggle-search")[0].classList.toggle("is-active");
            document.getElementsByClassName("js-search")[0].classList.toggle("is-visible");
            document.documentElement.style.overflowY = "unset";
        }
    });
    document.addEventListener("pjax:success", function () {
        if (window.gtag) {
            gtag('config', Poi.google_analytics_id, {
                'page_path': window.location.pathname
            });
        }
    });
    window.addEventListener('popstate', function (e) {
        Siren.AH();
        Siren.FDT();
        Siren.PE();
        Siren.CE();
        sm();
        timeSeriesReload(true);
        post_list_show_animation();
    }, false);
}
ready(()=>{
    Siren.AH();
    Siren.PE();
    Siren.NH();
    Siren.XLS();
    Siren.XCS();
    Siren.XCP();
    Siren.CE();
    Siren.MN();
    Siren.FDT();
    Siren.IA();
    Siren.LV();
    console.log("%c 迷与迷 %c", "background:#2196F3; color:#ffffff", "", "https://nmxc.ltd");
    console.log("%c Theme %c", "background:#2196F3; color:#ffffff", "", "https://github.com/bymoye/Sakura");
    console.log("%c 原作者 %c", "background:#2196F3; color:#ffffff", "", "https://github.com/mashirozx/Sakura/");
});

{
    let isWebkit = navigator.userAgent.toLowerCase().indexOf('webkit') > -1,
        isOpera = navigator.userAgent.toLowerCase().indexOf('opera') > -1,
        isIe = navigator.userAgent.toLowerCase().indexOf('msie') > -1;
    if ((isWebkit || isOpera || isIe) && document.getElementById && window.addEventListener) {
        window.addEventListener('hashchange', function () {
            let id = location.hash.substring(1),
                element;
            if (!(/^[A-z0-9_-]+$/.test(id))) {
                return;
            }
            element = document.getElementById(id);
            if (element) {
                if (!(/^(?:a|select|input|button|textarea)$/i.test(element.tagName))) {
                    element.tabIndex = -1;
                }
                element.focus();
            }
        }, false);
    }
}
