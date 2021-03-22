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
}

mashiro_global.ini = new function () {
    this.normalize = function () { // initial functions when page first load (首次加载页面时的初始化函数)
        lazyload();
        post_list_show_animation();
        copy_code_block();
        coverVideoIni();
        scrollBar();
        iconsvg();
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
    if (document.getElementsByTagName('article')[0]?.classList.contains("post-list-thumb")) {
        let options = {
            root: null,
            threshold: [0.4]
        },
            io = new IntersectionObserver(callback, options),
            articles = document.getElementsByClassName('post-list-thumb');

        function callback(entries) {
            entries.forEach((article) => {
                if (!window.IntersectionObserver) {
                    article.target.style.willChange = 'auto';
                    if (article.target.classList.contains("post-list-show") === false) {
                        article.target.classList.add("post-list-show");
                    }
                } else {
                    if (!window.IntersectionObserver) {
                        article.target.style.willChange = 'auto';
                        if (article.target.classList.contains("post-list-show") === false) {
                            article.target.classList.add("post-list-show");
                        }
                    } else {
                        if (article.target.classList.contains("post-list-show")) {
                            article.target.style.willChange = 'auto';
                            io.unobserve(article.target)
                        } else {
                            if (article.isIntersecting) {
                                article.target.classList.add("post-list-show");
                                article.target.style.willChange = 'auto';
                                io.unobserve(article.target)
                            }
                        }
                    }
                }
            })
        }
        for (let a = 0; a < articles.length; a++) {
            io.observe(articles[a]);
        }
    }
}

let ec_click = (e)=>{
    if (!e.target.classList.contains("highlight-wrap")) return;
    e.target.classList.toggle("code-block-fullscreen");
    document.getElementsByTagName("html")[0].classList.toggle('code-block-fullscreen-html-scroll');
}

function code_highlight_style() {
    let pre = document.getElementsByTagName("pre"),
        code = document.querySelectorAll("pre code");
    function gen_top_bar(i) {
        let attributes = {
            'autocomplete': 'off',
            'autocorrect': 'off',
            'autocapitalize': 'off',
            'spellcheck': 'false',
            'contenteditable': 'false',
            'design': 'by Miym'
        },
            ele_name = pre[i]?.children[0]?.className,
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
    document.querySelector(".entry-content")?.removeEventListener("click", ec_click);
    document.querySelector(".entry-content")?.addEventListener("click", ec_click);
}
try {
    code_highlight_style();
} catch{}

let cm_click = (e)=>{
    if (e.target.classList.contains("comment-reply-link")) {
        e.stopPropagation();
        e.preventDefault();
        let data_commentid = e.target.getAttribute("data-commentid");
        addComment.moveForm("comment-" + data_commentid, data_commentid, "respond", e.target.getAttribute("data-postid"));
        //return false;
    }
}
if (Poi.reply_link_version == 'new') {
    document.querySelector(".comments-main")?.addEventListener("click", cm_click);
}

function attach_image() {
    let cached = document.getElementsByClassName("insert-image-tips")[0],
        upload_img = document.getElementById('upload-img-file');
    upload_img?.addEventListener("change", (function () {
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
                cached.innerHTML = '<svg class="picture" aria-hidden="true"><use xlink:href="#svg_loading"/></svg>';
                addComment.createButterbar("上传中...<br>Uploading...");
            });
            xhr.open("POST", Poi.api + 'sakura/v1/image/upload?_wpnonce=' + Poi.nonce, true);
            xhr.send(formData);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 304)) {
                    cached.innerHTML = '<svg class="picture" aria-hidden="true"><use xlink:href="#svg_yes"/></svg>';
                    setTimeout(function () {
                        cached.innerHTML = '<svg class="picture" aria-hidden="true"><use xlink:href="#svg_picture"/></svg>';
                    }, 1000);
                    let res = JSON.parse(xhr.responseText);
                    if (res.status == 200) {
                        let get_the_url = res.proxy;
                        document.getElementById("upload-img-show").insertAdjacentHTML('afterend', '<img class="lazyload upload-image-preview" src="https://cdn.jsdelivr.net/gh/moezx/cdn@3.0.2/img/svg/loader/trans.ajax-spinner-preloader.svg" data-src="' + get_the_url + '" onclick="window.open(\'' + get_the_url + '\')" onerror="imgError(this)" />');
                        lazyload();
                        addComment.createButterbar("图片上传成功~<br>Uploaded successfully~");
                        grin(get_the_url,'Img');
                    } else {
                        addComment.createButterbar("上传失败！<br>Uploaded failed!<br> 文件名/Filename: " + f.name + "<br>code: " + res.status + "<br>" + res.message, 3000);
                    }
                } else if (xhr.readyState == 4) {
                    cached.innerHTML = '<svg class="picture" aria-hidden="true"><use xlink:href="#svg_no"/></svg>';
                    alert("上传失败，请重试.\nUpload failed, please try again.");
                    setTimeout(function () {
                        cached.innerHTML = '<svg class="picture" aria-hidden="true"><use xlink:href="#svg_picture"/></svg>';
                    }, 1000);
                }
            }
        };
    }));
}

function clean_upload_images() {
    document.getElementById("upload-img-show").innerHTML = '';
}

function add_upload_tips() {
    let form_subit = document.querySelector('.form-submit #submit');
    if (form_subit == null) return;
    form_subit.insertAdjacentHTML('afterend', '<div class="insert-image-tips popup"><svg class="picture" aria-hidden="true"><use xlink:href="#svg_picture"/></svg><span class="insert-img-popuptext" id="uploadTipPopup">上传图片</span></div><input id="upload-img-file" type="file" accept="image/*" multiple="multiple" class="insert-image-button">');
    attach_image();
    let file_subit = document.getElementById('upload-img-file'),
        hover = document.getElementsByClassName('insert-image-tips')[0],
        Tip = document.getElementById('uploadTipPopup');
    file_subit?.addEventListener("mouseenter", function () {
        hover?.classList.toggle('insert-image-tips-hover');
        Tip?.classList.toggle('show');
    });
    file_subit?.addEventListener("mouseleave", function () {
        hover?.classList.toggle('insert-image-tips-hover');
        Tip?.classList.toggle('show');
    });
}

add_upload_tips();

function click_to_view_image() {
    let comment_inline = document.getElementsByClassName('comment_inline_img');
    if (comment_inline.length == 0) return;
    document.getElementsByClassName("comments-main")[0]?.addEventListener("click", function (e) {
        if (e.target.classList.contains("comment_inline_img")) {
            let temp_url = e.target.src;
            window.open(temp_url);
        }
    })
}
click_to_view_image();

function original_emoji_click() {
    let emoji = document.getElementsByClassName('emoji-item');
    if (emoji.length == 0) return;
    document.getElementsByClassName("menhera-container")[0]?.addEventListener("click", function (e) {
        if (e.target.classList.contains("emoji-item")) {
            grin(e.target.innerText, "custom", "`", "` ");
        }
    })
}
original_emoji_click();

function cmt_showPopup(ele) {
    let popup = ele.querySelector("#thePopup");
    popup.classList.add("show");
    ele.querySelector("input").onblur = ()=>{
        popup.classList.remove("show");
    }
    // let popup = ele.children;
    // popup.thePopup.classList.add("show");
    // ele.lastChild.onblur = function () {
    //     popup.thePopup.classList.remove("show");
    // }
}
function scrollBar() {
    window.addEventListener('scroll', ()=>{
        let s = document.documentElement.scrollTop || document.body.scrollTop,
        a = document.documentElement.scrollHeight || document.body.scrollHeight,
        b = window.innerHeight, c,
        result = parseInt(s / (a - b) * 100),
        cached = document.getElementById('bar');
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
    let f = document.querySelector(".toc-container");
    if (f != null) {
        f.style.height = document.querySelector(".site-content")?.getBoundingClientRect(outerHeight)["height"] + "px";
    }
    });
}

function iconsvg() {
    let iconsvg = document.getElementById('iconsvg'),
        sitelogo = document.getElementsByClassName('sitelogo');
    iconsvg == null ? document.body.insertAdjacentHTML('beforeend', "<div id='iconsvg' style='display:none;'></div>") : null;
    if (document.getElementById('iconsvg').children.length == 0) {
        let xhr = new XMLHttpRequest();
       xhr.open("get", "https://cdn.jsdelivr.net/gh/bymoye/sakura/images/icon.svg", true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 304)) {
                document.getElementById('iconsvg').insertAdjacentHTML('afterbegin', xhr.responseText);
            }
        }
        xhr.send();
    }
    if (document.getElementsByClassName('sitelogo')[0].children.length == 0) {
        let xhr = new XMLHttpRequest();
        xhr.open("get", "https://cdn.jsdelivr.net/gh/bymoye/sakura/images/nmx.svg", true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 304)) {
                for (let i = 0; i < sitelogo.length; i++) {
                    if (sitelogo[i].children.length == 0)
                        sitelogo[i].insertAdjacentHTML('afterbegin', xhr.responseText);
                }
            }
        }
        xhr.send();
    }
    document.getElementsByClassName('openNav')[0].classList.add('exhide');
    document.getElementsByClassName('site-header')[0].classList.add('exhide');
}



function no_right_click() {
    document.getElementById("primary")?.addEventListener("contextmenu", function (e) {
        if (e.target.nodeName.toLowerCase() == "img") {
            e.preventDefault();
            e.stopPropagation();
        }
    })
}
no_right_click();

function slideToogle(el, duration = 1000, mode = '', callback) {
    let dom = el;
    dom.status = dom.status || getComputedStyle(dom, null)['display'];
    let flag = dom.status != 'none';
    if ((flag == 1 && mode == "show") || (flag == 0 && mode == "hide")) return;
    dom.status = flag ? 'none' : 'block';
    dom.style.transition = 'height ' + duration / 1000 + 's';
    dom.style.overflow = 'hidden';
    clearTimeout(dom.tagTimer);
    dom.tagTimer = dom.tagTimer || null
    dom.style.display = 'block';
    dom.tagHeight = dom.tagHeight || dom.clientHeight + 'px'
    dom.style.display = '';
    dom.style.height = flag ? dom.tagHeight : "0px"
    setTimeout(() => {
        dom.style.height = flag ? "0px" : dom.tagHeight
    }, 0)
    dom.tagTimer = setTimeout(() => {
        dom.style.display = flag ? 'none' : 'block'
        dom.style.transition = '';
        dom.style.overflow = '';
        dom.style.height = '';
        dom.status = dom.tagHeight = null;
    }, duration)
    if (callback) callback();
}

function timeSeriesReload(flag) {
    let archives = document.getElementById('archives');
    if (archives == null) return;
    let al_li = archives.getElementsByClassName('al_mon');
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
            let al_expand_collapse = document.getElementById('al_expand_collapse');
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
            archives?.addEventListener("click", function (e) {
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
                if (false) {
                    for (let i = 0; i < al_post_list.length; i++) {
                        let el = al_post_list[i];
                        el.parentNode.addEventListener('mouseover', function () {
                            slideToogle(el, 500, 'hide');
                            return false;
                        })
                    }
                }
                let al_expand_collapse_click = 0;
                al_expand_collapse.addEventListener('click', function () {
                    if (al_expand_collapse_click == 0) {
                        for (let i = 0; i < al_post_list.length; i++) {
                            let el = al_post_list[i];
                            slideToogle(el, 500, 'show');
                        };
                        al_expand_collapse_click++;
                    } else if (al_expand_collapse_click == 1) {
                        for (let i = 0; i < al_post_list.length; i++) {
                            let el = al_post_list[i];
                            slideToogle(el, 500, 'hide');
                        };
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
    let video = document.getElementById("coverVideo"),
        btn = document.getElementById("coverVideo-btn");

    if (video.paused) {
        video.play();
        try {
            btn.innerHTML = '<svg class="stop" aria-hidden="true"><use xlink:href="#svg_stop"></use></svg>';
        } catch { };
    } else {
        video.pause();
        try {
            btn.innerHTML = '<svg class="play" aria-hidden="true"><use xlink:href="#svg_play"></use></svg>';
        } catch { };
    }
}

function killCoverVideo() {
    let video = document.getElementById("coverVideo"),
        btn = document.getElementById("coverVideo-btn");

    if (video.paused) {
        //console.info('none:killCoverVideo()');
    } else {
        video.pause();
        try {
            btn.innerHTML = '<svg class="play" aria-hidden="true"><use xlink:href="#svg_play"></use></svg>';
        } catch { };
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
    if (document.getElementsByTagName('video')[0]?.classList.contains('hls')) {
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
        ele[j].insertAdjacentHTML('afterend', '<a class="copy-code" href="javascript:" data-clipboard-target="#hljs-' + j + '" title="拷贝代码"><svg class="clipboard" aria-hidden="true"><use xlink:href="#svg_clipboard"></use></svg></a>');
    };
    let clipboard = new ClipboardJS('.copy-code');
}

function tableOfContentScroll(flag) {
    if (document.body.clientWidth <= 1200) {
        return;
    } else if (document.querySelector("div.have-toc") == null && document.querySelector("div.has-toc") == null) {
        let ele = document.getElementsByClassName("toc-container")[0];
        if (ele != null) ele.parentNode.removeChild(ele);
    } else {
        if (flag) {
            let id = 1,
                heading_fix = mashiro_option.entry_content_theme == "sakura" ? (document.querySelector("article.type-post") != null ? (document.querySelector("div.pattern-attachment-img") != null ? -75 : 200) : 375) : window.innerHeight / 2;
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
    } catch {};
    try {
        getqqinfo();
    } catch {};
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

function sm() {
    let sm = document.getElementsByClassName('sm');
    if (sm.length == 0) return;
    document.querySelector(".comments-main")?.addEventListener("click",(e)=>{
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
                            rateHolderp.innerHTML = request.responseText + ' <svg class="lock" aria-hidden="true"><use xlink:href="#svg_lock"></use></svg>';
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
    let motionEles = [".bili", ".menhera", ".tieba"];
    for (let i=0;i<montionEles.length;i++) {
        document.querySelector(motionEles[i] + '-bar').classList.remove('on-hover');
        document.querySelector(motionEles[i] + '-container').style.display = 'none';
    }
    document.querySelector(ele + '-bar').classList.add("on-hover");
    document.querySelector(ele + '-container').style.display = 'block';
}
let ready=function(fn){
    if (typeof fn !== 'function') return;
    if (document.readyState==='complete') {
        return fn();
    }
    document.addEventListener('DOMContentLoaded', fn, false);
};
function smileBoxToggle() {
    document.getElementById("emotion-toggle")?.addEventListener('click', function () {
        let emotion_toggle_off = document.querySelector('.emotion-toggle-off'),
            emotion_toggle_on = document.querySelector('.emotion-toggle-on'),
            emotion_box = document.querySelector('.emotion-box');
        emotion_toggle_off.classList.toggle("emotion-hide");
        emotion_toggle_on.classList.toggle("emotion-show");
        emotion_box.classList.toggle("emotion-box-show");
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
let copytext = (e)=>{
    if (window.getSelection().toString().length > 30 && mashiro_option.clipboardCopyright) {
        setClipboardText(e);
    }
    addComment.createButterbar("复制成功！<br>Copied to clipboard successfully!", 1000);
    function setClipboardText(event) {
        event.preventDefault();
        let htmlData = "# 商业转载请联系作者获得授权，非商业转载请注明出处。<br>" + "# For commercial use, please contact the author for authorization. For non-commercial use, please indicate the source.<br>" + "# 协议(License)：署名-非商业性使用-相同方式共享 4.0 国际 (CC BY-NC-SA 4.0)<br>" + "# 作者(Author)：" + mashiro_option.author_name + "<br>" + "# 链接(URL)：" + window.location.href + "<br>" + "# 来源(Source)：" + mashiro_option.site_name + "<br><br>" + window.getSelection().toString().replace(/\r\n/g, "<br>"),
            textData = "# 商业转载请联系作者获得授权，非商业转载请注明出处。\n" + "# For commercial use, please contact the author for authorization. For non-commercial use, please indicate the source.\n" + "# 协议(License)：署名-非商业性使用-相同方式共享 4.0 国际 (CC BY-NC-SA 4.0)\n" + "# 作者(Author)：" + mashiro_option.author_name + "\n" + "# 链接(URL)：" + window.location.href + "\n" + "# 来源(Source)：" + mashiro_option.site_name + "\n\n" + window.getSelection().toString().replace(/\r\n/g, "\n");
        if (event.clipboardData) {
            event.clipboardData.setData("text/html", htmlData);
            event.clipboardData.setData("text/plain", textData);
        } else if (window.clipboardData) {
            return window.clipboardData.setData("text", textData);
        }
    }
}
function add_copyright() {
    document.body.removeEventListener("copy",copytext);
    document.body.addEventListener("copy", copytext);
}
add_copyright();
ready(()=>{
    getqqinfo();
});


function getqqinfo() {
    let is_get_by_qq = false,
        author = document.getElementById("author"),
        qq = document.getElementById("qq"),
        email = document.getElementById("email"),
        url = document.getElementById("url"),
        qq_check = document.getElementsByClassName("qq-check")[0],
        gravatar_check = document.getElementsByClassName("gravatar-check")[0],
        user_avatar_img = document.querySelector("div.comment-user-avatar img");
        if (author == null) return;
    if (!getCookie('user_qq') && !getCookie('user_qq_email') && !getCookie('user_author')) {
        qq.value = author.value = email.value = url.value = "";
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
                qq.value = qq.trim();
                qq_check.style.display = "block";
                gravatar_check.style.display = "none";
                setCookie('user_author', res[qq][6], 30);
                setCookie('user_qq', qq, 30);
                setCookie('is_user_qq', 'yes', 30);
                setCookie('user_qq_email', qq + '@qq.com', 30);
                setCookie('user_email', qq + '@qq.com', 30);
                emailAddressFlag = email.value;
            } else if (xhr.readyState == 4) {
                qq.value = "";
                qq_check.style.display = "none";
                gravatar_check.style.display = "block";
                if (!email.value) {
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
        if (is_get_by_qq == false || emailAddressFlag != emailAddress) {
            user_avatar_img.setAttribute("src", get_gravatar(emailAddress, 80));
            setCookie('user_avatar', get_gravatar(emailAddress, 80), 30);
            setCookie('user_email', emailAddress, 30);
            setCookie('user_qq_email', '', 30);
            setCookie('is_user_qq', 'no', 30);
            qq.value = '';
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
    if (document.body.clientWidth > 860) {
            let show_hide = document.getElementsByClassName("show-hide");
            show_hide[0]?.addEventListener("click", function () {
                secondary?.classList.toggle("active");
            });
    } else {
            secondary?.parentNode.removeChild(secondary);
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
                            bfan.parentNode.removeChild(bfan);
                            row.insertAdjacentHTML('beforeend', html);
                        } else {
                            bgpa.classList.remove("loading");
                            bgpa.innerHTML = "<svg class='fire' aria-hidden='true'><use xlink:href='#exclamation-triangle'></use></svg> ERROR ";
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
            document.getElementsByClassName("iconflat")[0]?.addEventListener("click", function () {
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
                video_btn?.classList.add("video-pause");
                video_btn?.classList.remove("video-play");
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
            _btn.addEventListener("click", function () {
                if (this.classList.contains("loadvideo")) {
                    this.classList.add("video-pause");
                    this.classList.remove("loadvideo");
                    Siren.addsource();
                    s.oncanplay = function () {
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
                s.onended = function () {
                    document.getElementById("bgvideo")?.setAttribute("src", "");
                    document.getElementById("video-add").style.display = "none";
                    _btn?.classList.add("loadvideo");
                    _btn?.classList.remove("video-pause", "videolive", "haslive");
                    document.querySelector(".focusinfo").style.top = "49.3%";
                }
            });
            document.getElementById("video-add").addEventListener("click", function () {
                Siren.addsource();
            });
        },
        AH: function () {
            if (Poi.windowheight == 'auto' && mashiro_option.windowheight == 'auto') {
                if (document.querySelector(".main-title") != null) {
                    let _height = document.documentElement.clientHeight + "px";
                    document.getElementById("centerbg").style.height = "100vh";
                    document.getElementById("bgvideo").style.minHeight = "100vh";
                }
            } else {
                document.querySelector(".headertop")?.classList.add("headertop-bar");
            }
        },
        PE: function () {
            if (document.querySelector(".headertop") != null) {
                let headertop = document.querySelector(".headertop"),
                    blank = document.querySelector(".blank");
                if (document.querySelector(".main-title") != null) {
                    try {
                        blank.style.paddingTop = "0px";
                    } catch (e) { }
                    headertop.style.height = "auto";
                    headertop.style.display = "";
                    if (Poi.movies.live == 'open') Siren.liveplay();
                } else {
                    try {
                        blank.style.paddingTop = "75px";
                    } catch (e) {}
                    headertop.style.height = "0px";
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
                document.getElementsByTagName("html")[0].style.overflowY = "hidden";
                if (mashiro_option.live_search) {
                    let QueryStorage = [];
                    search_a(Poi.api + "sakura/v1/cache_search/json?_wpnonce=" + Poi.nonce);

                    let otxt = document.getElementById("search-input"),
                        list = document.getElementById("PostlistBox"),
                        Record = list.innerHTML,
                        searchFlag = null;
                    otxt.oninput = function () {
                        if (searchFlag = null) {
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
                        let i = arr.filter(
                            v => Object.values(v).some(
                                v => new RegExp(q + '').test(v)
                            )
                        );
                        return i;
                    }

                    function div_href() {
                        let ins_selectable = document.getElementsByClassName("ins-selectable");
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
                        return '<div class="ins-selectable ins-search-item" href="' + link + '"><header><svg class="searchresult" aria-hidden="true"><use xlink:href="#svg_' + fa + '"></use></svg>' + title + '<svg class="searchresult" aria-hidden="true"><use xlink:href="#svg_' + iconfont + '"></use> ' + comments + '</svg>' + '</header><p class="ins-search-preview">' + text + '</p></div>';
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
            document.querySelector(".search_close")?.addEventListener("click", function () {
                let js_search = document.getElementsByClassName("js-search")[0];
                if (js_search.classList.contains("is-visible")) {
                    document.getElementsByClassName("js-toggle-search")[0].classList.toggle("is-active");
                    js_search.classList.toggle("is-visible");
                    document.getElementsByTagName("html")[0].style.overflowY = "unset";
                }
            });
            try {
                let show_Nav = document.getElementById("show-nav");
                show_Nav.addEventListener("click", function () {
                    if (show_Nav.classList.contains("showNav")) {
                        show_Nav.classList.remove("showNav");
                        show_Nav.classList.add("hideNav");
                        document.querySelector(".site-top .lower nav")?.classList.add("navbar");
                    } else {
                        show_Nav.classList.remove("hideNav");
                        show_Nav.classList.add("showNav");
                        document.querySelector(".site-top .lower nav")?.classList.remove("navbar");
                    }
                });
                document.getElementById("loading").addEventListener("click", function () {
                    let loading = document.getElementById("loading");
                    loading.classList.add("hide");
                    loading.classList.remove("show");
                });
            } catch (e) { }
        },
        NH: function () {
            let h1 = 0,
                b1 = 0,
                t = window.innerHeight / 4,
                i = document.documentElement.scrollTop || window.pageYOffset,
                style = document.createElement("style");
            style.innerHTML = "body::before{backdrop-filter:blur(5px)}";
            800 > document.body.clientWidth && (window.document.head.appendChild(style));
            window.addEventListener("scroll", function () {
                let s = document.documentElement.scrollTop || window.pageYOffset,
                    cWidth = document.body.clientWidth,
                    cached = document.getElementsByClassName("site-header")[0],
                    n = document.getElementsByClassName("openNav")[0];
                s == 0 && (cached.classList.remove('exbit'), n.classList.remove('exbit'));
                s == h1 && (cached.classList.remove('yya'), n.classList.remove('yya')),
                    s > h1 && (cached.classList.add('yya'), n.classList.add('yya')),
                    s > t && (cached.classList.add('exbit'), n.classList.add('exbit'),
                        800 < cWidth && b1 == 0 && (window.document.head.appendChild(style), b1 = 1),
                        s > i ? (cached.classList.remove('exhide'), n.classList.remove('exhide')) : (cached.classList.add('exhide'), n.classList.add('exhide')),
                        i = s),
                    800 < cWidth && s < t && b1 == 1 && (window.document.head.removeChild(style), b1 = 0);
            })
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
                                nextHref = json.querySelector("#pagination a")?.getAttribute("href");
                                for(let i=0;i<result.length;i++){
                                    let b = result[i];
                                    document.getElementById("main").insertAdjacentHTML('beforeend', b.outerHTML);
                                }
                            let test = document.querySelector("#content");
                            _pjax.refresh(test);
                            document.querySelector("#pagination a")?.classList.remove("loading");
                            document.querySelector("#pagination a").innerHTML = "Previous";
                            document.querySelector("#add_post span").classList.remove("loading");
                            document.querySelector("#add_post span").innerHTML = "";
                            lazyload();
                            post_list_show_animation();
                            if (nextHref != undefined) {
                                let respond = document.getElementById("respond");
                                let tempScrollTop = document.documentElement.scrollTop;
                                window.scrollTo({ top: tempScrollTop });
                                window.scrollTo({
                                    top: tempScollTop + 100,
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
            let __cancel = document.getElementById("cancel-comment-reply-link"),
                __cancel_text = __cancel?.textContent,
                __list = "commentwrap";
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
                                post = document.getElementById('comment_post_ID').value,
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
                                temp.parentNode.removeChild(temp)
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
                        post = document.getElementById('comment_post_ID'),
                        temp;
                    __cancel.textContent = __cancel_text;
                    t.respondId = respondId;
                    if (!document.getElementById('wp-temp-form-div')) {
                        div = document.createElement('div');
                        div.id = 'wp-temp-form-div';
                        div.style.display = 'none';
                        respond.parentNode.insertBefore(div, respond)
                    }
                    !comm ? (temp = document.getElementById('wp-temp-form-div'), document.getElementById('comment_parent').value = '0', temp.parentNode.insertBefore(respond, temp), temp.parentNode.removeChild(temp)) : comm.parentNode.insertBefore(respond, comm.nextSibling);
                    let _respond = document.getElementById("respond");
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
                            temp.parentNode.removeChild(temp);
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
                            a.parentNode.removeChild(a);
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
                            a.parentNode.removeChild(a);
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
                        comments_navi.parentNode.removeChild(comments_navi);
                        commentwrap.parentNode.removeChild(commentwrap);
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
                                top: commentwrap?.getBoundingClientRect().top+ window.pageYOffset - commentwrap?.clientTop - 200,
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
            let _header = document.getElementsByClassName("pattern-header");
            if (_header.length == 0) return;
            let _center = document.getElementsByClassName("pattern-center"),
                str, _blur,
                g = 0.4,
                reg = /blur\((.*?)px\) saturate\((.*?)\)/g,
                i = 0,
                last_time = Date.now(),
                _headertoggle = () => {
                    let nowTime = Date.now(),
                        top = _header[0]?.style.top,
                        e = requestAnimationFrame(_headertoggle);
                    if (nowTime - last_time > 20) {
                        last_time = nowTime;
                        if (top == "0px") {
                            g <= 0.5 ? g = 0.5 : g -= 0.05;
                            _header[0].style.backdropFilter = "blur(" + i++ + "px) saturate(" + g + ")";
                        } else if (top == "60%") {
                            g >= 1 ? g = 1 : g += 0.05;
                            _header[0].style.backdropFilter = "blur(" + i-- + "px) saturate(" + g + ")";
                        }
                    }
                    if (i >= 16 || i <= 4) {
                        cancelAnimationFrame(e);
                    }
                }

            _center[0]?.addEventListener("mouseover", function () {
                _header[0].style.top = "60%";
            });
            _center[0]?.addEventListener("mouseleave", function () {
                _header[0].style.top = "0px";
            });
            _header[0].ontransitionend = function (e) {
                e.stopPropagation();
                if (e.target === this) {
                    let top = _header[0].style.top;
                    str = getComputedStyle(_header[0])?.backdropFilter;
                    _blur = str?.replace(reg, '$1');
                    if (top == "0px" || top == "60%") {
                        i = _blur;
                        _headertoggle();
                    }
                }
            }
        },
        GT: function () {
            let mb_to_top = document.getElementById("GoTop");
            window.addEventListener("scroll", function () {
                let scrollTop = document.documentElement.scrollTop || window.pageYOffset || document.body.scrollTop;
                if (scrollTop > 20) {
                    mb_to_top.style.transform = "scale(1)";
                } else {
                    mb_to_top.style.transform = "scale(0)";
                }
            }
            );
            mb_to_top.onclick = function () {
                topFunction(10);
            }
        }
    }

function topFunction(a) {
    let d = document.body.scrollTop ? document.body : document.documentElement,
        c = d.scrollTop,
        b = function () {
            c = c + (0 - c) / (a || 5);
            if (c < 1) {
                d.scrollTop = 0;
                return
            }
            d.scrollTop = c;
            requestAnimationFrame(b)
        };
    b()
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
        document.getElementById("bar").style.width = "0%";
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
        loading?.classList.add("hide");
        loading?.classList.remove("show");
        if (Poi.codelamp == 'open') {
            self.Prism.highlightAll(event)
        };
        if (document.querySelector(".js-search.is-visible")?.length > 0) {
            document.getElementsByClassName("js-toggle-search")[0]?.classList.toggle("is-active");
            document.getElementsByClassName("js-search")[0]?.classList.toggle("is-visible");
            document.getElementsByTagName("html")[0].style.overflowY = "unset";
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
};
ready(()=>{
    Siren.AH();
    Siren.PE();
    Siren.NH();
    Siren.GT();
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

function social_share_limit() {
    let top_social = document.getElementsByClassName("top-social"),
        top_social_v2 = document.getElementsByClassName("top-social_v2");
    if (top_social.length > 0 || top_social_v2.length > 0) {
        let a = top_social.length > 0 ? top_social[0].getElementsByTagName("li") : top_social_v2[0].getElementsByTagName("li");
        for (let i = a.length - 1; i >= 11; i--) {
            a[i].parentNode.removeChild(a[i]);
        }
        if (document.body.clientWidth <= 860) {
            for (let i = a.length - 1; i >= 10; i--) {
                a[i].parentNode.removeChild(a[i]);
            }
        }
        if (document.body.clientWidth <= 425) {
            for (let i = a.length - 1; i >= 5; i--) {
                a[i].parentNode.removeChild(a[i]);
            }
        }
    }
}
social_share_limit();