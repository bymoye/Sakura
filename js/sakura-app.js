/*！
 * Sakura theme application bundle
 * @author Mashiro
 * @url https://2heng.xin
 * @date 2019.8.3
 */
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
    }
    this.pjax = function () { // pjax reload functions (pjax 重载函数)
        pjaxInit();
        post_list_show_animation();
        copy_code_block();
        coverVideoIni();
        iconsvg();
        load_bangumi();
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
    let nameEQ = name + mashiro_option.cookie_version_control + "=";
    let ca = document.cookie.split(';');
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
    if (document.getElementsByTagName('article')[0].classList.contains("post-list-thumb")) {
        let options = {
            root: null,
            threshold: [0.4]
        }
        let io = new IntersectionObserver(callback, options);
        let articles = document.getElementsByClassName('post-list-thumb');

        function callback(entries) {
            entries.forEach((article) => {
                if (!window.IntersectionObserver) {
                    article.target.style.willChange = 'auto';
                    if( article.target.classList.contains("post-list-show") === false){
                        article.target.classList.add("post-list-show");
                    }
                } else {
                    if (!window.IntersectionObserver) {
                        article.target.style.willChange = 'auto';
                        if( article.target.classList.contains("post-list-show") === false){
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
        Array.prototype.forEach.call(articles,(article) => {
            io.observe(article)
        })
    }
}


function code_highlight_style() {
    let pre = document.getElementsByTagName("pre");
    let code = document.querySelectorAll("pre code");
    function gen_top_bar(i) {
        let attributes = {
            'autocomplete': 'off',
            'autocorrect': 'off',
            'autocapitalize': 'off',
            'spellcheck': 'false',
            'contenteditable': 'false',
            'design': 'by Miym'
        }
        let ele_name = pre[i].children[0].className;
        let lang = ele_name.substr(0, ele_name.indexOf(" ")).replace('language-', '');
        let code_a = code[i];
        if (lang.toLowerCase() == "hljs") lang = code_a.className.replace('hljs', '') ? code_a.className.replace('hljs', '') : "text";
        pre[i].classList.add("highlight-wrap");
        for (let t in attributes) {
            pre[i].setAttribute(t,attributes[t]);
        }
        code_a.setAttribute('data-rel', lang.toUpperCase());
    }
    code.forEach((block) => {
        hljs.highlightBlock(block);
    })
    for (let i = 0; i < pre.length; i++) {
        gen_top_bar(i);
    }
    hljs.initLineNumbersOnLoad();

    Array.prototype.forEach.call(pre,(button) =>{
        button.addEventListener('click', function (e) {
                if (e.target !== this) return;
                this.classList.toggle("code-block-fullscreen");
                document.getElementsByTagName("html")[0].classList.toggle('code-block-fullscreen-html-scroll');
            });
    })
}
try {
    code_highlight_style();
} catch (e) {}

if (Poi.reply_link_version == 'new') {
    let comment_reply = document.getElementsByClassName('comment-reply-link');
    Array.prototype.forEach.call(comment_reply,(link) =>{
        link.addEventListener('click',function () {
            let data_commentid = this.getAttribute("data-commentid");
            addComment.moveForm("comment-"+data_commentid,data_commentid,"respond",this.getAttribute("data-postid"));
            return false;
        })
    })
}

function attach_image() {
    let cached = document.getElementsByClassName("insert-image-tips")[0];
    let upload_img = document.getElementById('upload-img-file');
    if(upload_img==null)return;
    upload_img.addEventListener("change",(function () {
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
            let f = this.files[i];
            let formData = new FormData();
            formData.append('cmt_img_file', f);
            let xhr = new XMLHttpRequest();
            xhr.addEventListener('loadstart', function(){
                cached.innerHTML = '<svg class="picture" aria-hidden="true"><use xlink:href="#loading"/></svg>';
                addComment.createButterbar("上传中...<br>Uploading...");
            });
            xhr.open("POST",Poi.api + 'sakura/v1/image/upload?_wpnonce=' + Poi.nonce,true);
            xhr.send(formData);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 304)) {
                    cached.innerHTML ='<svg class="picture" aria-hidden="true"><use xlink:href="#yes"/></svg>';
                    setTimeout(function () {
                        cached.innerHTML ='<svg class="picture" aria-hidden="true"><use xlink:href="#picture"/></svg>';
                    }, 1000);
                    let res = JSON.parse(xhr.responseText);
                    if (res.status == 200){
                        let get_the_url = res.proxy;
                        document.getElementById("upload-img-show").insertAdjacentHTML('afterend','<img class="lazyload upload-image-preview" src="https://cdn.jsdelivr.net/gh/moezx/cdn@3.0.2/img/svg/loader/trans.ajax-spinner-preloader.svg" data-src="' + get_the_url + '" onclick="window.open(\'' + get_the_url + '\')" onerror="imgError(this)" />');
                        lazyload();
                        addComment.createButterbar("图片上传成功~<br>Uploaded successfully~");
                        grin(get_the_url, type = 'Img');
                    } else {
                        addComment.createButterbar("上传失败！<br>Uploaded failed!<br> 文件名/Filename: "+f.name+"<br>code: "+res.status+"<br>"+res.message, 3000);
                    }
                }else if(xhr.readyState==4) {
                    cached.innerHTML = '<svg class="picture" aria-hidden="true"><use xlink:href="#no"/></svg>';
                    alert("上传失败，请重试.\nUpload failed, please try again.");
                    setTimeout(function () {
                        cached.innerHTML = '<svg class="picture" aria-hidden="true"><use xlink:href="#picture"/></svg>';
                    }, 1000);
                }
            }
            };
    }));
}

function clean_upload_images() {
    document.getElementById("upload-img-show").innerHTML='';
}

function add_upload_tips() {
    let form_subit = document.querySelector('.form-submit #submit');
    if (form_subit == null) return;
    form_subit.insertAdjacentHTML('afterend','<div class="insert-image-tips popup"><svg class="picture" aria-hidden="true"><use xlink:href="#picture"/></svg><span class="insert-img-popuptext" id="uploadTipPopup">上传图片</span></div><input id="upload-img-file" type="file" accept="image/*" multiple="multiple" class="insert-image-button">');
    attach_image();
    let file_subit = document.getElementById('upload-img-file');
    let hover = document.getElementsByClassName('insert-image-tips')[0];
    let Tip = document.getElementById('uploadTipPopup');
    file_subit.addEventListener("mouseenter",function (){
        hover.classList.toggle('insert-image-tips-hover');
        Tip.classList.toggle('show');
    });
    file_subit.addEventListener("mouseleave",function () {
        hover.classList.toggle('insert-image-tips-hover');
        Tip.classList.toggle('show');
    });
}

add_upload_tips();

function click_to_view_image() {
    let comment_inline = document.getElementsByClassName('comment_inline_img');
    if (comment_inline.length==0) return;
    Array.prototype.forEach.call(comment_inline,(comment_inline_img) => {
        comment_inline_img.addEventListener('click',function(){
            let temp_url = this.src;
            window.open(temp_url);
        })
    })
}
click_to_view_image();

function original_emoji_click() {
    let emoji = document.getElementsByClassName('emoji-item');
    if (emoji.length==0) return;
    Array.prototype.forEach.call(emoji,(emoji_item) => {
        emoji_item.addEventListener('click',function(){
            grin(this.innerText, type = "custom", before = "`", after = "` ");
        })
    })
}
original_emoji_click();

function cmt_showPopup(ele) {
    let popup = ele.children;
    popup.thePopup.classList.add("show");
    ele.lastChild.onblur = function (){
        popup.thePopup.classList.remove("show");
    }
}

function scrollBar() {
    window.addEventListener('scroll',function(){
            let s = document.documentElement.scrollTop||document.body.scrollTop,
                a = document.documentElement.scrollHeight || document.body.scrollHeight ,
                b = window.innerHeight,
                result = parseInt(s / (a - b) * 100),
                cached = document.getElementById('bar');
                cached.style.width = result + "%";
                if (result >= 0 && result <= 19)
                    cached.style.background = '#00BCD4';
                if (result >= 20 && result <= 39)
                    cached.style.background = '#50bcb6';
                if (result >= 40 && result <= 59)
                    cached.style.background = '#85c440';
                if (result >= 60 && result <= 79)
                    cached.style.background = '#f2b63c';
                if (result >= 80 && result <= 99)
                    cached.style.background = '#FF0000';
                if (result == 100)
                    cached.style.background = '#5aaadb';
            let m = document.getElementsByClassName('toc-container');
            if (m.length != 0){
                m[0].style.height = document.getElementsByClassName('site-content')[0].getBoundingClientRect(outerHeight)["height"]+"px";
            }
        });
}

function iconsvg() {
        let iconsvg = document.getElementById('iconsvg');
        iconsvg == null ? document.getElementsByTagName('body')[0].insertAdjacentHTML('beforeend',"<div id='iconsvg' style='display:none;'></div>") : null;
        if (document.getElementById('iconsvg').children.length == 0){
            let xhr = new XMLHttpRequest();
            xhr.open("get","https://cdn.jsdelivr.net/gh/bymoye/sakura@latest/images/icon.svg",true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 304)) {
                    document.getElementById('iconsvg').insertAdjacentHTML('afterbegin',xhr.responseText);
                }
            }
            xhr.send();
        }
        if (document.getElementsByClassName('sitelogo')[0].children.length == 0){
            let xhr = new XMLHttpRequest();
            xhr.open("get","https://cdn.jsdelivr.net/gh/bymoye/sakura@1.0.0/images/nmx.svg",true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 304)) {
                    document.getElementsByClassName('sitelogo')[0].insertAdjacentHTML('afterbegin',xhr.responseText);
                }
            }
            xhr.send();
        }
        document.getElementsByClassName('openNav')[0].classList.add('exhide');
        document.getElementsByClassName('site-header')[0].classList.add('exhide');
}



function no_right_click() {
    $('.post-thumb img').bind('contextmenu', function (e) {
        return false;
    });
}
no_right_click();


function timeSeriesReload(flag) {
    var cached = $('#archives');
    if (flag == true) {
        cached.find('span.al_mon').click(function () {
            $(this).next().slideToggle(400);
            return false;
        });
        lazyload();
    } else {
        (function () {
            $('#al_expand_collapse,#archives span.al_mon').css({
                cursor: "s-resize"
            });
            cached.find('span.al_mon').each(function () {
                var num = $(this).next().children('li').length;
                $(this).children('#post-num').text(num);
            });
            var $al_post_list = cached.find('ul.al_post_list'),
                $al_post_list_f = cached.find('ul.al_post_list:first');
            $al_post_list.hide(1, function () {
                $al_post_list_f.show();
            });
            cached.find('span.al_mon').click(function () {
                $(this).next().slideToggle(400);
                return false;
            });
            if (document.body.clientWidth > 860) {
                cached.find('li.al_li').mouseover(function () {
                    $(this).children('.al_post_list').show(400);
                    return false;
                });
                if (false) {
                    cached.find('li.al_li').mouseout(function () {
                        $(this).children('.al_post_list').hide(400);
                        return false;
                    });
                }
            }
            var al_expand_collapse_click = 0;
            $('#al_expand_collapse').click(function () {
                if (al_expand_collapse_click == 0) {
                    $al_post_list.each(function(index){
                        var $this = $(this),
                        s = setTimeout(function() {
                            $this.show(400);
                        }, 50 * index);
                    });
                    al_expand_collapse_click++;
                } else if (al_expand_collapse_click == 1) {
                    $al_post_list.each(function(index){
                        var $this = $(this),
                        h = setTimeout(function() {
                            $this.hide(400);
                        }, 50 * index);
                    });
                    al_expand_collapse_click--;
                }
            });
        })();
    }
}
timeSeriesReload();

/*视频feature*/
function coverVideo() {
    var video = addComment.I("coverVideo");
    var btn = addComment.I("coverVideo-btn");

    if (video.paused) {
        video.play();
        try {
            btn.innerHTML = '<svg class="stop" aria-hidden="true"><use xlink:href="#stop"></use></svg>';
        } catch (e) {};
        //console.info('play:coverVideo()');
    } else {
        video.pause();
        try {
            btn.innerHTML = '<svg class="play" aria-hidden="true"><use xlink:href="#play"></use></svg>';
        } catch (e) {};
        //console.info('pause:coverVideo()');
    }
}

function killCoverVideo() {
    var video = addComment.I("coverVideo");
    var btn = addComment.I("coverVideo-btn");

    if (video.paused) {
        //console.info('none:killCoverVideo()');
    } else {
        video.pause();
        try {
            btn.innerHTML = '<svg class="play" aria-hidden="true"><use xlink:href="#play"></use></svg>';
        } catch (e) {};
        //console.info('pause:killCoverVideo()');
    }
}

function loadHls(){
    var video = addComment.I('coverVideo');
    var video_src = $('#coverVideo').attr('data-src');
    if (Hls.isSupported()) {
        var hls = new Hls();
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

function coverVideoIni() {
    if ($('video').hasClass('hls')) {
        if (mashiro_global.variables.has_hls){
            loadHls();
        }else{
            $.getScript("https://cdn.jsdelivr.net/gh/bymoye/sakura@0.0.3/cdn/js/src/16.hls.js",function(){
                loadHls();
                mashiro_global.variables.has_hls = true;
            });
        }
        //console.info('ini:coverVideoIni()');
    }
}

function copy_code_block() {
    $('pre code').each(function (i, block) {
        $(block).attr({
            id: 'hljs-' + i
        });
        $(this).after('<a class="copy-code" href="javascript:" data-clipboard-target="#hljs-' + i + '" title="拷贝代码"><svg class="clipboard" aria-hidden="true"><use xlink:href="#clipboard"></use></svg></a>');
    });
    var clipboard = new ClipboardJS('.copy-code');
}

function tableOfContentScroll(flag) {
    if (document.body.clientWidth <= 1200) {
        return;
    } else if ($("div").hasClass("have-toc") == false && $("div").hasClass("has-toc") == false) {
        $(".toc-container").remove();
    } else {
        if (flag) {
            var id = 1,
                heading_fix = mashiro_option.entry_content_theme == "sakura" ? $("article").hasClass("type-post") ? $("div").hasClass("pattern-attachment-img") ? -75 : 200 : 375 : window.innerHeight / 2;
            $(".entry-content , .links").children("h1,h2,h3,h4,h5").each(function () {
                var hyphenated = "toc-head-" + id;
                this.id = hyphenated;
                id++;
            });
            tocbot.init({
                tocSelector: '.toc',
                contentSelector: ['.entry-content', '.links'],
                headingSelector: 'h1, h2, h3, h4, h5',
                headingsOffset: heading_fix - window.innerHeight / 2,
            });
        }
    }
}
tableOfContentScroll(flag = true);
var pjaxInit = function () {
    add_upload_tips();
    no_right_click();
    click_to_view_image();
    original_emoji_click();
    $("p").remove(".head-copyright");
    try {
        code_highlight_style();
    } catch (e) {};
    try {
        getqqinfo();
    } catch (e) {};
    lazyload();
    $('.iconflat').css('width', '50px').css('height', '50px');
    $('.openNav').css('height', '50px');
    smileBoxToggle();
    timeSeriesReload();
    add_copyright();
    tableOfContentScroll(flag = true);
}
$(document).on("click", ".sm", function () {
    var msg = "您真的要设为私密吗？";
    if (confirm(msg) == true) {
        $(this).commentPrivate();
    } else {
        alert("已取消");
    }
});
$.fn.commentPrivate = function () {
    if ($(this).hasClass('private_now')) {
        alert('您之前已设过私密评论');
        return false;
    } else {
        $(this).addClass('private_now');
        var idp = $(this).data('idp'),
            actionp = $(this).data('actionp'),
            rateHolderp = $(this).children('.has_set_private');
        var ajax_data = {
            action: "siren_private",
            p_id: idp,
            p_action: actionp
        };
        $.post("/wp-admin/admin-ajax.php", ajax_data, function (data) {
            $(rateHolderp).html(data);
        });
        return false;
    }
};

POWERMODE.colorful = true;
POWERMODE.shake = false;
document.body.addEventListener('input', POWERMODE);

function motionSwitch(ele) {
    var motionEles = [".bili", ".menhera", ".tieba"];
    for (var i in motionEles) {
        $(motionEles[i] + '-bar').removeClass("on-hover");
        $(motionEles[i] + '-container').css("display", "none");
    }
    $(ele + '-bar').addClass("on-hover");
    $(ele + '-container').css("display", "block");
}
$('.comt-addsmilies').click(function () {
    $('.comt-smilies').toggle();
})
$('.comt-smilies a').click(function () {
    $(this).parent().hide();
})

function smileBoxToggle() {
    $(document).ready(function () {
        $("#emotion-toggle").click(function () {
            $(".emotion-toggle-off").toggle(0);
            $(".emotion-toggle-on").toggle(0);
            $(".emotion-box").toggle(160);
        });
    });
}
smileBoxToggle();

function grin(tag, type, before, after) {
    var myField;
    if (type == "custom") {
        tag = before + tag + after;
    } else if (type == "Img") {
        tag = '[img]' + tag + '[/img]';
    } else if (type == "Math") {
        tag = ' {{' + tag + '}} ';
    } else if (type == "tieba") {
        tag = ' ::' + tag + ':: ';
    } else {
        tag = ' :' + tag + ': ';
    }
    if (addComment.I('comment') && addComment.I('comment').type == 'textarea') {
        myField = addComment.I('comment');
    } else {
        return false;
    }
    if (document.selection) {
        myField.focus();
        sel = document.selection.createRange();
        sel.text = tag;
        myField.focus();
    } else if (myField.selectionStart || myField.selectionStart == '0') {
        var startPos = myField.selectionStart;
        var endPos = myField.selectionEnd;
        var cursorPos = endPos;
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

function add_copyright() {
    document.body.addEventListener("copy", function (e) {
        if (window.getSelection().toString().length > 30 && mashiro_option.clipboardCopyright) {
            setClipboardText(e);
        }
        addComment.createButterbar("复制成功！<br>Copied to clipboard successfully!", 1000);
    });

    function setClipboardText(event) {
        event.preventDefault();
        var htmlData = "# 商业转载请联系作者获得授权，非商业转载请注明出处。<br>" + "# For commercial use, please contact the author for authorization. For non-commercial use, please indicate the source.<br>" + "# 协议(License)：署名-非商业性使用-相同方式共享 4.0 国际 (CC BY-NC-SA 4.0)<br>" + "# 作者(Author)：" + mashiro_option.author_name + "<br>" + "# 链接(URL)：" + window.location.href + "<br>" + "# 来源(Source)：" + mashiro_option.site_name + "<br><br>" + window.getSelection().toString().replace(/\r\n/g, "<br>");;
        var textData = "# 商业转载请联系作者获得授权，非商业转载请注明出处。\n" + "# For commercial use, please contact the author for authorization. For non-commercial use, please indicate the source.\n" + "# 协议(License)：署名-非商业性使用-相同方式共享 4.0 国际 (CC BY-NC-SA 4.0)\n" + "# 作者(Author)：" + mashiro_option.author_name + "\n" + "# 链接(URL)：" + window.location.href + "\n" + "# 来源(Source)：" + mashiro_option.site_name + "\n\n" + window.getSelection().toString().replace(/\r\n/g, "\n");
        if (event.clipboardData) {
            event.clipboardData.setData("text/html", htmlData);
            event.clipboardData.setData("text/plain", textData);
        } else if (window.clipboardData) {
            return window.clipboardData.setData("text", textData);
        }
    }
}
add_copyright();
$(function () {
    getqqinfo();
});


function getqqinfo() {
    var is_get_by_qq = false,
        cached = $('input');
    if (!getCookie('user_qq') && !getCookie('user_qq_email') && !getCookie('user_author')) {
        cached.filter('#qq,#author,#email,#url').val('');
    }
    if (getCookie('user_avatar') && getCookie('user_qq') && getCookie('user_qq_email')) {
        $('div.comment-user-avatar img').attr('src', getCookie('user_avatar'));
        cached.filter('#author').val(getCookie('user_author'));
        cached.filter('#email').val(getCookie('user_qq') + '@qq.com');
        cached.filter('#qq').val(getCookie('user_qq'));
        if (mashiro_option.qzone_autocomplete) {
            cached.filter('#url').val('https://user.qzone.qq.com/' + getCookie('user_qq'));
        }
        if (cached.filter('#qq').val()) {
            $('.qq-check').css('display', 'block');
            $('.gravatar-check').css('display', 'none');
        }
    }
    var emailAddressFlag = cached.filter('#email').val();
    cached.filter('#author').on('blur', function () {
        var qq = cached.filter('#author').val();
        $.ajax({
            type: 'get',
            url: mashiro_option.qq_api_url + '?type=getqqnickname&qqnumber=' + qq,
            dataType: 'jsonp',
            jsonp: 'callback',
            jsonpCallback: 'portraitCallBack',
            success: function (data) {
                cached.filter('#author').val(data[qq][6]);
                cached.filter('#email').val($.trim(qq) + '@qq.com');
                if (mashiro_option.qzone_autocomplete) {
                    cached.filter('#url').val('https://user.qzone.qq.com/' + $.trim(qq));
                }
                $('div.comment-user-avatar img').attr('src', 'https://q2.qlogo.cn/headimg_dl?dst_uin=' + qq + '&spec=100');
                is_get_by_qq = true;
                cached.filter('#qq').val($.trim(qq));
                if (cached.filter('#qq').val()) {
                    $('.qq-check').css('display', 'block');
                    $('.gravatar-check').css('display', 'none');
                }
                setCookie('user_author', data[qq][6], 30);
                setCookie('user_qq', qq, 30);
                setCookie('is_user_qq', 'yes', 30);
                setCookie('user_qq_email', qq + '@qq.com', 30);
                setCookie('user_email', qq + '@qq.com', 30);
                emailAddressFlag = cached.filter('#email').val();
            },
            error: function () {
                cached.filter('#qq').val('');
                $('.qq-check').css('display', 'none');
                $('.gravatar-check').css('display', 'block');
                $('div.comment-user-avatar img').attr('src', get_gravatar(cached.filter('#email').val(), 80));
                setCookie('user_qq', '', 30);
                setCookie('user_email', cached.filter('#email').val(), 30);
                setCookie('user_avatar', get_gravatar(cached.filter('#email').val(), 80), 30);
            }
        });
        $.ajax({
            type: 'get',
            url: mashiro_option.qq_avatar_api_url + '?type=getqqavatar&qqnumber=' + qq,
            dataType: 'jsonp',
            jsonp: 'callback',
            jsonpCallback: 'qqavatarCallBack',
            success: function (data) {
                $('div.comment-user-avatar img').attr('src', data[qq]);
                setCookie('user_avatar', data[qq], 30);
            },
            error: function () {
                cached.filter('#qq,#email,#url').val('');
                if (!cached.filter('#qq').val()) {
                    $('.qq-check').css('display', 'none');
                    $('.gravatar-check').css('display', 'block');
                    setCookie('user_qq', '', 30);
                    $('div.comment-user-avatar img').attr('src', get_gravatar(cached.filter('#email').val(), 80));
                    setCookie('user_avatar', get_gravatar(cached.filter('#email').val(), 80), 30);
                }
            }
        });
    });
    if (getCookie('user_avatar') && getCookie('user_email') && getCookie('is_user_qq') == 'no' && !getCookie('user_qq_email')) {
        $('div.comment-user-avatar img').attr('src', getCookie('user_avatar'));
        cached.filter('#email').val(getCookie('user_email'));
        cached.filter('#qq').val('');
        if (!cached.filter('#qq').val()) {
            $('.qq-check').css('display', 'none');
            $('.gravatar-check').css('display', 'block');
        }
    }
    cached.filter('#email').on('blur', function () {
        var emailAddress = cached.filter('#email').val();
        //if(emailAddress=='')return;
        if (is_get_by_qq == false || emailAddressFlag != emailAddress) {
            $('div.comment-user-avatar img').attr('src', get_gravatar(emailAddress, 80));
            setCookie('user_avatar', get_gravatar(emailAddress, 80), 30);
            setCookie('user_email', emailAddress, 30);
            setCookie('user_qq_email', '', 30);
            setCookie('is_user_qq', 'no', 30);
            cached.filter('#qq').val('');
            if (!cached.filter('#qq').val()) {
                $('.qq-check').css('display', 'none');
                $('.gravatar-check').css('display', 'block');
            }
        }
    });
    if (getCookie('user_url')) {
        cached.filter('#url').val(getCookie('user_url'));
    }
    cached.filter('#url').on('blur', function () {
        var URL_Address = cached.filter('#url').val();
        cached.filter('#url').val(URL_Address);
        setCookie('user_url', URL_Address, 30);
    });
    if (getCookie('user_author')) {
        cached.filter('#author').val(getCookie('user_author'));
    }
    cached.filter('#author').on('blur', function () {
        var user_name = cached.filter('#author').val();
        cached.filter('#author').val(user_name);
        setCookie('user_author', user_name, 30);
    });
}

function mail_me() {
    var mail = "mailto:" + mashiro_option.email_name + "@" + mashiro_option.email_domain;
    window.open(mail);
}

function activate_widget(){
    if (document.body.clientWidth > 860) {
        $('.show-hide').on('click', function() {
            $("#secondary").toggleClass("active")
        });
    }else{
        $("#secondary").remove();
    }
}
setTimeout(function () {
    activate_widget();
}, 100);

function load_bangumi() {
    if ($("section").hasClass("bangumi")) {
        $('body').on('click', '#bangumi-pagination a', function () {
            $("#bangumi-pagination a").addClass("loading").text("");
            var xhr = new XMLHttpRequest();
            xhr.open('POST', this.href + "&_wpnonce=" + Poi.nonce, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var html = JSON.parse(xhr.responseText);
                    $("#bangumi-pagination").remove();
                    $(".row").append(html);
                }else{
                    $("#bangumi-pagination a").removeClass("loading").html('<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ERROR ');
                }
            };
            xhr.send();
            return false;
        });
    }
}

mashiro_global.ini.normalize();
loadCSS(mashiro_option.jsdelivr_css_src);
loadCSS(mashiro_option.entry_content_theme_src);
//loadCSS("https://at.alicdn.com/t/font_679578_qyt5qzzavdo39pb9.css");

var home = location.href,
    s = $('#bgvideo')[0],
    Siren = {
        MN: function () {
            $('.iconflat').on('click', function () {
                $('body').toggleClass('navOpen');
                $('#main-container,#mo-nav,.openNav').toggleClass('open');
            });
        },
        MNH: function () {
            if ($('body').hasClass('navOpen')) {
                $('body').toggleClass('navOpen');
                $('#main-container,#mo-nav,.openNav').toggleClass('open');
            }
        },
        splay: function () {
            $('#video-btn').addClass('video-pause').removeClass('video-play').show();
            $('.video-stu').css({
                "bottom": "-100px"
            });
            $('.focusinfo').css({
                "top": "-999px"
            });
            try {
                for (var i = 0; i < ap.length; i++) {
                    try {
                        ap[i].destroy()
                    } catch (e) {}
                }
            } catch (e) {}
            try {
                hermitInit()
            } catch (e) {}
            s.play();
        },
        spause: function () {
            $('#video-btn').addClass('video-play').removeClass('video-pause');
            $('.focusinfo').css({
                "top": "49.3%"
            });
            s.pause();
        },
        liveplay: function () {
            if (s.oncanplay != undefined && $('.haslive').length > 0) {
                if ($('.videolive').length > 0) {
                    Siren.splay();
                }
            }
        },
        livepause: function () {
            if (s.oncanplay != undefined && $('.haslive').length > 0) {
                Siren.spause();
                $('.video-stu').css({
                    "bottom": "0px"
                }).html('已暂停 ...');
            }
        },
        addsource: function () {
            $('.video-stu').html('正在载入视频 ...').css({
                "bottom": "0px"
            });
            var t = Poi.movies.name.split(","),
                _t = t[Math.floor(Math.random() * t.length)];
            $('#bgvideo').attr('src', Poi.movies.url + '/' + _t + '.mp4');
            $('#bgvideo').attr('video-name', _t);
        },
        LV: function () {
            var _btn = $('#video-btn');
            _btn.on('click', function () {
                if ($(this).hasClass('loadvideo')) {
                    $(this).addClass('video-pause').removeClass('loadvideo').hide();
                    Siren.addsource();
                    s.oncanplay = function () {
                        Siren.splay();
                        $('#video-add').show();
                        _btn.addClass('videolive').addClass('haslive');
                    }
                } else {
                    if ($(this).hasClass('video-pause')) {
                        Siren.spause();
                        _btn.removeClass('videolive');
                        $('.video-stu').css({
                            "bottom": "0px"
                        }).html('已暂停 ...');
                    } else {
                        Siren.splay();
                        _btn.addClass('videolive');
                    }
                }
                s.onended = function () {
                    $('#bgvideo').attr('src', '');
                    $('#video-add').hide();
                    _btn.addClass('loadvideo').removeClass('video-pause').removeClass('videolive').removeClass('haslive');
                    $('.focusinfo').css({
                        "top": "49.3%"
                    });
                }
            });
            $('#video-add').on('click', function () {
                Siren.addsource();
            });
        },
        AH: function () {
            if (Poi.windowheight == 'auto' && mashiro_option.windowheight == 'auto') {
                if ($('h1.main-title').length > 0) {
                    var _height = $(window).height() + "px";
                    $('#centerbg').css({
                        'height': '100vh'
                    });
                    $('#bgvideo').css({
                        'min-height': '100vh'
                    });
                    window.resizeFlag = null;
                    $(window).resize(function () {
                        //直接resize性能爆炸，改成延时
                        if (resizeFlag = null) {
                            clearTimeout(resizeFlag);
                        }
                        resizeFlag = setTimeout(function () {
                            Siren.AH();
                        }, 1000);
                    })
                }
            } else {
                $('.headertop').addClass('headertop-bar');
            }
        },
        PE: function () {
            if ($('.headertop').length > 0) {
                if ($('h1.main-title').length > 0) {
                    $('.blank').css({
                        "padding-top": "0px"
                    });
                    $('.headertop').css({
                        "height": "auto"
                    }).show();
                    if (Poi.movies.live == 'open') Siren.liveplay();
                } else {
                    $('.blank').css({
                        "padding-top": "75px"
                    });
                    $('.headertop').css({
                        "height": "0px"
                    }).hide();
                    Siren.livepause();
                }
            }
        },
        CE: function () {
            $('.comments-hidden').show();
            $('.comments-main').hide();
            $('.comments-hidden').click(function () {
                $('.comments-main').slideDown(500);
                $('.comments-hidden').hide();
            });
            $('.archives').hide();
            $('.archives:first').show();
            $('#archives-temp h3').click(function () {
                $(this).next().slideToggle('fast');
                return false;
            });
            if (mashiro_option.baguetteBoxON) {
                baguetteBox.run('.entry-content', {
                    captions: function (element) {
                        return element.getElementsByTagName('img')[0].alt;
                    },
                    ignoreClass: 'fancybox',
                });
            }
            $('.js-toggle-search').on('click', function () {
                $('.js-toggle-search').toggleClass('is-active');
                $('.js-search').toggleClass('is-visible');
                $('html').css('overflow-y', 'hidden');
                if (mashiro_option.live_search) {
                    var QueryStorage = [];
                    search_a(Poi.api + "sakura/v1/cache_search/json?_wpnonce=" + Poi.nonce);

                    var otxt = addComment.I("search-input"),
                        list = addComment.I("PostlistBox"),
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
                            query(QueryStorage, $("#search-input").val(), Record);
                            div_href();
                        } else {
                            var _xhr = new XMLHttpRequest();
                            _xhr.open("GET", val, true)
                            _xhr.send();
                            _xhr.onreadystatechange = function () {
                                if (_xhr.readyState == 4 && _xhr.status == 200) {
                                    json = _xhr.responseText;
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
                        var val = [],
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
                        i = arr.filter(
                            v => Object.values(v).some(
                                v => new RegExp(q + '').test(v)
                            )
                        );
                        return i;
                    }

                    function div_href() {
                        $(".ins-selectable").each(function () {
                            $(this).click(function () {
                                $("#Ty").attr('href', $(this).attr('href'));
                                $("#Ty").click();
                                $(".search_close").click();
                            });
                        });
                    }

                    function search_result(keyword, link, fa, title, iconfont, comments, text) {
                        if (keyword) {
                            var s = keyword.trim().split(" "),
                                a = title.indexOf(s[s.length - 1]),
                                b = text.indexOf(s[s.length - 1]);
                            title = a < 60 ? title.slice(0, 80) : title.slice(a - 30, a + 30);
                            title = title.replace(s[s.length - 1], '<mark class="search-keyword"> ' + s[s.length - 1].toUpperCase() + ' </mark>');
                            text = b < 60 ? text.slice(0, 80) : text.slice(b - 30, b + 30);
                            text = text.replace(s[s.length - 1], '<mark class="search-keyword"> ' + s[s.length - 1].toUpperCase() + ' </mark>');
                        }
                        return '<div class="ins-selectable ins-search-item" href="' + link + '"><header><svg class="searchresult" aria-hidden="true"><use xlink:href="#'+ fa +'"></use></svg>' + title + '<svg class="searchresult" aria-hidden="true"><use xlink:href="#'+ iconfont +'"></use> ' + comments + '</svg>' + '</header><p class="ins-search-preview">' + text + '</p></div>';
                    }

                    function query(B, A, z) {
                        var x, v, s, y = "",
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
                                    break
                            }
                        }
                        w && (y = y + G + "文章" + E + w + D), u && (y = y + G + "页面" + E + u + D), r && (y = y + G + "分类" + E + r + D), p && (y = y + G + "标签" + E + p + D), F && (y = y + G + "评论" + E + F + D), s = addComment.I("PostlistBox"), s.innerHTML = y
                    }
                }
            });
            $('.search_close').on('click', function () {
                if ($('.js-search').hasClass('is-visible')) {
                    $('.js-toggle-search').toggleClass('is-active');
                    $('.js-search').toggleClass('is-visible');
                    $('html').css('overflow-y', 'unset');
                }
            });
            $('#show-nav').on('click', function () {
                if ($('#show-nav').hasClass('showNav')) {
                    $('#show-nav').removeClass('showNav').addClass('hideNav');
                    $('.site-top .lower nav').addClass('navbar');
                } else {
                    $('#show-nav').removeClass('hideNav').addClass('showNav');
                    $('.site-top .lower nav').removeClass('navbar');
                }
            });
            $("#loading").click(function () {
                $("#loading").fadeOut(500);
            });
        },
        NH: function() {
            var h1 = 0,
            b1 = 0,
            t = window.innerHeight / 4,
            i = document.documentElement.scrollTop || window.pageYOffset,
           // $bodyblur = $("<style></style>"),
            style = document.createElement("style");
            style.type = "text/css";
            style.innerHTML = "body::before{backdrop-filter:blur(5px)}";
            800 > document.body.clientWidth && (window.document.head.appendChild(style));
            $(window).scroll(function() {
                var s = document.documentElement.scrollTop || window.pageYOffset,
                cWidth = document.body.clientWidth,
                cached = document.getElementsByClassName("site-header")[0],
                n = document.getElementsByClassName("openNav")[0],
                b = document.getElementsByTagName("body")[0];
                s == h1 && (cached.classList.remove('yya'),n.classList.remove('yya')),
                s > h1 && (cached.classList.add('yya'),n.classList.add('yya')),
                s > t && (cached.classList.add('exbit'),n.classList.add('exbit'),
                800 < cWidth && b1 == 0 && (window.document.head.appendChild(style),b1 = 1),
                s > i ? (cached.classList.remove('exhide'),n.classList.remove('exhide')) : (cached.classList.add('exhide'),n.classList.add('exhide')),
                i=s),
                800 < cWidth && s < t && b1 == 1 && (window.document.head.removeChild(style),b1 = 0);
            })
        },
        XLS: function () {
            $body = (window.opera) ? (document.compatMode == "CSS1Compat" ? $('html') : $('body')) : $('html,body');
            var load_post_timer;
            var intersectionObserver = new IntersectionObserver(function (entries) {
                if (entries[0].intersectionRatio <= 0) return;
                var page_next = $('#pagination a').attr("href");
                var load_key = addComment.I("add_post_time");
                if (page_next != undefined && load_key) {
                    var load_time = addComment.I("add_post_time").title;
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
            $('body').on('click', '#pagination a', function () {
                clearTimeout(load_post_timer);
                load_post();
                return false;
            });

            function load_post() {
                $('#pagination a').addClass("loading").text("");
                $.ajax({
                    type: "POST",
                    url: $('#pagination a').attr("href") + "#main",
                    success: function (data) {
                        result = $(data).find("#main .post");
                        nextHref = $(data).find("#pagination a").attr("href");
                        $("#main").append(result.fadeIn(500));
                        $("#pagination a").removeClass("loading").text("Previous");
                        $('#add_post span').removeClass("loading").text("");
                        lazyload();
                        post_list_show_animation();
                        if (nextHref != undefined) {
                            $("#pagination a").attr("href", nextHref);
                            //加载完成上滑
                            var tempScrollTop = $(window).scrollTop();
                            $(window).scrollTop(tempScrollTop);
                            $body.animate({
                                scrollTop: tempScrollTop + 100
                            }, 666)
                        } else {
                            $("#pagination").html("<span>很高兴你翻到这里，但是真的没有了...</span>"); 
                        }
                    }
                });
                return false;
            }
        },
        XCS: function () {
            var __cancel = jQuery('#cancel-comment-reply-link'),
                __cancel_text = __cancel.text(),
                __list = 'commentwrap';
            jQuery(document).on("submit", "#commentform", function () {
                jQuery.ajax({
                    url: Poi.ajaxurl,
                    data: jQuery(this).serialize() + "&action=ajax_comment",
                    type: jQuery(this).attr('method'),
                    beforeSend: addComment.createButterbar("提交中(Commiting)...."),
                    error: function (request) {
                        var t = addComment;
                        t.createButterbar(request.responseText);
                    },
                    success: function (data) {
                        jQuery('textarea').each(function () {
                            this.value = ''
                        });
                        var t = addComment,
                            cancel = t.I('cancel-comment-reply-link'),
                            temp = t.I('wp-temp-form-div'),
                            respond = t.I(t.respondId),
                            post = t.I('comment_post_ID').value,
                            parent = t.I('comment_parent').value;
                        if (parent != '0') {
                            jQuery('#respond').before('<ol class="children">' + data + '</ol>');
                        } else if (!jQuery('.' + __list).length) {
                            if (Poi.formpostion == 'bottom') {
                                jQuery('#respond').before('<ol class="' + __list + '">' + data + '</ol>');
                            } else {
                                jQuery('#respond').after('<ol class="' + __list + '">' + data + '</ol>');
                            }
                        } else {
                            if (Poi.order == 'asc') {
                                jQuery('.' + __list).append(data);
                            } else {
                                jQuery('.' + __list).prepend(data);
                            }
                        }
                        t.createButterbar("提交成功(Succeed)");
                        lazyload();
                        code_highlight_style();
                        click_to_view_image();
                        clean_upload_images();
                        cancel.style.display = 'none';
                        cancel.onclick = null;
                        t.I('comment_parent').value = '0';
                        if (temp && respond) {
                            temp.parentNode.insertBefore(respond, temp);
                            temp.parentNode.removeChild(temp)
                        }
                    }
                });
                return false;
            });
            addComment = {
                moveForm: function (commId, parentId, respondId) {
                    var t = this,
                        div, comm = t.I(commId),
                        respond = t.I(respondId),
                        cancel = t.I('cancel-comment-reply-link'),
                        parent = t.I('comment_parent'),
                        post = t.I('comment_post_ID');
                    __cancel.text(__cancel_text);
                    t.respondId = respondId;
                    if (!t.I('wp-temp-form-div')) {
                        div = document.createElement('div');
                        div.id = 'wp-temp-form-div';
                        div.style.display = 'none';
                        respond.parentNode.insertBefore(div, respond)
                    }!comm ? (temp = t.I('wp-temp-form-div'), t.I('comment_parent').value = '0', temp.parentNode.insertBefore(respond, temp), temp.parentNode.removeChild(temp)) : comm.parentNode.insertBefore(respond, comm.nextSibling);
                    jQuery("body").animate({
                        scrollTop: jQuery('#respond').offset().top - 180
                    }, 400);
                    parent.value = parentId;
                    cancel.style.display = '';
                    cancel.onclick = function () {
                        var t = addComment,
                            temp = t.I('wp-temp-form-div'),
                            respond = t.I(t.respondId);
                        t.I('comment_parent').value = '0';
                        if (temp && respond) {
                            temp.parentNode.insertBefore(respond, temp);
                            temp.parentNode.removeChild(temp);
                        }
                        this.style.display = 'none';
                        this.onclick = null;
                        return false;
                    };
                    try {
                        t.I('comment').focus();
                    } catch (e) {}
                    return false;
                },
                I: function (e) {
                    return document.getElementById(e);
                },
                clearButterbar: function (e) {
                    if (jQuery(".butterBar").length > 0) {
                        jQuery(".butterBar").remove();
                    }
                },
                createButterbar: function (message, showtime) {
                    var t = this;
                    t.clearButterbar();
                    jQuery("body").append('<div class="butterBar butterBar--center"><p class="butterBar-message">' + message + '</p></div>');
                    if (showtime > 0) {
                        setTimeout("jQuery('.butterBar').remove()", showtime);
                    } else {
                        setTimeout("jQuery('.butterBar').remove()", 6000);
                    }
                }
            };
        },
        XCP: function () {
            $body = (window.opera) ? (document.compatMode == "CSS1Compat" ? $('html') : $('body')) : $('html,body');
            $('body').on('click', '#comments-navi a', function (e) {
                e.preventDefault();
                var path = $(this)[0].pathname;
                $.ajax({
                    type: "GET",
                    url: $(this).attr('href'),
                    beforeSend: function () {
                        $('#comments-navi').remove();
                        $('ul.commentwrap').remove();
                        $('#loading-comments').slideDown();
                        $body.animate({
                            scrollTop: $('#comments-list-title').offset().top - 65
                        }, 800);
                    },
                    dataType: "html",
                    success: function (out) {
                        result = $(out).find('ul.commentwrap');
                        nextlink = $(out).find('#comments-navi');
                        $('#loading-comments').slideUp('fast');
                        $('#loading-comments').after(result.fadeIn(500));
                        $('ul.commentwrap').after(nextlink);
                        lazyload();
                        if (window.gtag) {
                            gtag('config', Poi.google_analytics_id, {
                                'page_path': path
                            });
                        }
                        code_highlight_style();
                        click_to_view_image();
                    }
                });
            });
        },
        IA: function () {
            POWERMODE.colorful = true;
            POWERMODE.shake = false;
            document.body.addEventListener('input', POWERMODE)
        },
        FDT: function () {
            header = $(".pattern-header");
            var g=0.4;
            function sleep(ms) {
                return new Promise(resolve => setTimeout(resolve, ms));
            };
            async function headerdown() {
                if($(".pattern-header").length>0){
                    var reg = /blur\((.*?)px\) saturate\((.*?)\)/g;
                    header = $(".pattern-header");
                    var str = header.css("backdrop-filter");
                    var blur = str.replace(reg,'$1');
                    for (var i = blur ; i >= 5; i--) {
                        if(header.css("top")=="180px"){
                            g>=1?g=1:g+=0.05;
                            header.css( "backdrop-filter", "blur(" + i + "px) saturate("+g+")");
                        }else{
                            return;
                        }
                        await sleep(20); 
                    }
                }
            };
            async function headerup() {
                if($(".pattern-header").length>0){
                    var reg = /blur\((.*?)px\) saturate\((.*?)\)/g;
                    header = $(".pattern-header");
                    var str = header.css("backdrop-filter");
                    var blur = str.replace(reg,'$1');
                    for (var i = blur ; i <= 15; i++) {
                        if(header.css("top")=="0px"){
                            g<=0.5?g=0.5:g-=0.05;
                            header.css( "backdrop-filter", "blur(" + i + "px) saturate("+g+")" );
                        }else{
                            return;
                        }
                        await sleep(20);
                    } 
                }
            };
            $(".pattern-center").hover(function(){
                    header.stop(true, false).animate({top:"60%"},500,function(){headerdown();}).animate({top:"60%"},500,function(){headerdown();});
                },function(){
                    header.stop(true, false).animate({top:"0"},500,function(){headerup();}).animate({top:"0"},500,function(){headerup();});
                });
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
            mb_to_top.onclick = function() {
                topFunction(10);
            }
        }
    }

    function topFunction(a) {
        let d = document.body.scrollTop ? document.body : document.documentElement;
        let c = d.scrollTop;
        let b = function() {
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
$(function () {
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
    if (Poi.pjax) {
        $(document).pjax('a[target!=_top]', '#page', {
            fragment: '#page',
            timeout: 8000,
        }).on('pjax:beforeSend', () => { //离开页面停止播放
            $('.normal-cover-video').each(function () {
                this.pause();
                this.src = '';
                this.load = '';
            });
        }).on('pjax:send', function () {
            $("#bar").css("width", "0%");
            if (mashiro_option.NProgressON) NProgress.start();
            Siren.MNH();
        }).on('pjax:complete', function () {
            Siren.AH();
            Siren.FDT();
            Siren.PE();
            Siren.CE();
            if (mashiro_option.NProgressON) NProgress.done();
            mashiro_global.ini.pjax();
            $("#loading").fadeOut(500);
            if (Poi.codelamp == 'open') {
                self.Prism.highlightAll(event)
            };
            if ($('.ds-thread').length > 0) {
                if (typeof DUOSHUO !== 'undefined') {
                    DUOSHUO.EmbedThread('.ds-thread');
                } else {
                    $.getScript("//static.duoshuo.com/embed.js");
                }
            }
        }).on('pjax:end', function() {
            if (window.gtag){
                gtag('config', Poi.google_analytics_id, {
                    'page_path': window.location.pathname
                });
            }
        }).on('submit', '.search-form,.s-search', function (event) {
            event.preventDefault();
            $.pjax.submit(event, '#page', {
                fragment: '#page',
                timeout: 8000,
            });
            if ($('.js-search.is-visible').length > 0) {
                $('.js-toggle-search').toggleClass('is-active');
                $('.js-search').toggleClass('is-visible');
                $('html').css('overflow-y', 'unset');
            }
        });
        window.addEventListener('popstate', function (e) {
            Siren.AH();
            Siren.FDT();
            Siren.PE();
            Siren.CE();
            timeSeriesReload(true);
            post_list_show_animation();
        }, false);
    }
    $.fn.postLike = function () {
        if ($(this).hasClass('done')) {
            return false;
        } else {
            $(this).addClass('done');
            var id = $(this).data("id"),
                action = $(this).data('action'),
                rateHolder = $(this).children('.count');
            var ajax_data = {
                action: "specs_zan",
                um_id: id,
                um_action: action
            };
            $.post(Poi.ajaxurl, ajax_data, function (data) {
                $(rateHolder).html(data);
            });
            return false;
        }
    };
    $(document).on("click", ".specsZan", function () {
        $(this).postLike();
    });
    console.log("%c 迷与迷 %c", "background:#2196F3; color:#ffffff", "", "https://nmxc.ltd");
    console.log("%c Theme %c", "background:#2196F3; color:#ffffff", "", "https://github.com/bymoye/Sakura");
    console.log("%c 原作者 %c", "background:#2196F3; color:#ffffff", "", "https://github.com/mashirozx/Sakura/");
});

var isWebkit = navigator.userAgent.toLowerCase().indexOf('webkit') > -1,
    isOpera = navigator.userAgent.toLowerCase().indexOf('opera') > -1,
    isIe = navigator.userAgent.toLowerCase().indexOf('msie') > -1;
if ((isWebkit || isOpera || isIe) && document.getElementById && window.addEventListener) {
    window.addEventListener('hashchange', function () {
        var id = location.hash.substring(1),
            element;
        if (!(/^[A-z0-9_-]+$/.test(id))) {
            return;
        }
        element = addComment.I(id);
        if (element) {
            if (!(/^(?:a|select|input|button|textarea)$/i.test(element.tagName))) {
                element.tabIndex = -1;
            }
            element.focus();
        }
    }, false);
}

function social_share_limit(){
if ($(".top-social").length > 0 || $(".top-social_v2").length > 0){
    $(".top-social").length > 0 ? a = $(".top-social li") : a = $(".top-social_v2 li");
    for (var i=a.length-1;i>=11;i--){
        a[i].remove();
    }
    if(document.body.clientWidth<=860){
        for (var i=a.length-1;i>=10;i--){
            a[i].remove();
        }
    }
    if(document.body.clientWidth<=425){
        for (var i=a.length-1;i>=5;i--){
            a[i].remove();
        }
    }
}
}
social_share_limit();