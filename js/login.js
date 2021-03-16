/*
 *	Resize Image
*/
function resizeImage(id) {
	let a = document.getElementById(id);
	a.style.position = "absolute";
	a.style.top = "0px";
	a.style.left = "0px";
	a.style.width = "100%";
	a.style.height = "100%";
	a.style.zIndex = "-1";
	a.style.overflow = "hidden";
	let w = document.documentElement.clientWidth ||document.body.clientWidth,
		h = document.documentElement.clientHeight ||document.body.clientHeight,
		o = document.querySelector("#" + id + " img"),
		iW = parseFloat(getComputedStyle(o, null).width.replace("px", "")),
		iH = parseFloat(getComputedStyle(o, null).height.replace("px", ""));
	o.style.display = "block";
	o.style.opacity = "0";
	if (w > h) {
		if (iW > iH) {
			o.style.width = w + "px";
			o.style.height = Math.round((iH / iW) * w) + "px";
			let newIh = Math.round((iH / iW) * w);
			if (newIh < h) {
				o.style.height = h + "px";
				o.style.width = Math.round((iW / iH) * h) + "px";
			}
		} else {
			o.style.height = h + "px";
			o.style.width = Math.round((iW / iH) * h) + "px";
		}
	} else {
		o.style.height = h + "px";
		o.style.width = Math.round((iW / iH) * h) + "px";
	}
	let newIW = parseFloat(getComputedStyle(o, null).width.replace("px", "")),
		newIH = parseFloat(getComputedStyle(o, null).height.replace("px", ""));
	if (newIW > w) {
		let l = (newIW - w) / 2;
		o.style.marginLeft = -l;
	} else {
		o.style.marginLeft = 0;
	}
	if (newIH > h) {
		let t = (newIH - h) / 2;
		o.style.marginTop = -t;
	} else {
		o.style.marginTop = 0;
	}
	o.style.opacity = "1";
}
function fadeOut(el){
	document.head.insertAdjacentHTML("afterbegin",'<style type="text/css">.show{opacity:1;}.hide{opacity:0;transition: opacity 400ms;}</style>');
	el.classList.add('hide');
	el.classList.remove('show');
	el.addEventListener("transitionend",()=>{el.style.display="none"});
}

let ready=function(fn){
    if (typeof fn !== 'function') return;
    if (document.readyState==='complete') {
        return fn();
    }
    document.addEventListener('DOMContentLoaded', fn, false);
};

function sxyz(){
    let mcanvas = document.getElementById("wzyzm"),
        minput = document.getElementById("yzm"),
        btn = document.getElementById("wp-submit"),
        ctx = mcanvas?.getContext("2d"),
        content = "0123456789zxcvbnmasdfghjklqwertyuiop",
        str = "",
        str1 = "";
    function reset() {
        //画点
        for (var i = 0; i < 25; i++) {
            ctx.beginPath();
            ctx.fillStyle = randomColor(120, 220);
            ctx.arc(getRandom(2, 118), getRandom(2, 38), 1, 0, Math.PI * 2);
            ctx.fill();
        }
        //画线
        for (let i = 0; i < 1; i++) {
            ctx.beginPath();
            ctx.strokeStyle = randomColor(80, 150);
            ctx.lineWidth = getRandom(1, 2);
            ctx.moveTo(getRandom(5, 30), getRandom(5, 35));
            ctx.lineTo(getRandom(85, 115), getRandom(5, 35));
            ctx.stroke();
        }
        //文字
        for (let i = 0; i < 4; i++) {
            var text = content[getRandom(0, content.length - 1)];
            str += text;
            str1 += text.toUpperCase();
            let dushu = getRandom(-30, 30);
            let youzou = getRandom(0, 10);
            ctx.fillStyle = randomColor(80, 150);
            ctx.font = getRandom(20, 40) + "px Microsoft Yahei";
            ctx.textBaseline = "hanging";
 
            ctx.translate(youzou + i * 30, 0);
            ctx.rotate(Math.PI / 180 * dushu);
            ctx.fillText(text, 0, 0);
            ctx.rotate(Math.PI / 180 * -dushu);
            ctx.translate(-(i * 30 + youzou), 0);
        }
    }
 
    reset();
    //判断
    mcanvas.onclick = ()=>{
            ctx.clearRect(0, 0, 120, 40);
            minput.value = "";
            str = "";
            str1 = "";
            reset();
    }
    btn.onclick = function () {
        let mValue = minput.value;
        if (mValue == str || mValue == str1) {
            ctx.clearRect(0, 0, 120, 40);
            minput.value = "";
            str = "";
            str1 = "";
            reset();
        } else {
            alert("是不是有什么打错了你看看?");
            minput.value = "";
			return false;
        }
    };
    //数字取值范围
    function getRandom(min, max) {
        return parseInt(Math.random() * (max - min + 1) + min);
    };
 
    function randomColor(min, max) {
        let r = getRandom(min, max);
        let g = getRandom(min, max);
        let b = getRandom(min, max);
        return "rgb(" + r + "," + g + "," + b + ")"
    };
}