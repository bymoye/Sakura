const addComment = {
    respondId: '',
    moveForm(commId, parentId, respondId){
        const div = document.getElementById('wp-temp-form-div') || document.createElement('div'),
                comm = document.getElementById(commId),
                respond = document.getElementById(respondId),
                cancel = document.getElementById('cancel-comment-reply-link'),
                comment_parent = document.getElementById('comment_parent');
        this.respondId = respondId;
        if (!div.id){
            div.id = 'wp-temp-form-div';
            div.style.display = 'none';
            respond.parentNode.insertBefore(div, respond);
        }
        if (!comm){
            comment_parent.value = '0'
            div.parentNode.insertBefore(respond, div);
            div.remove()
        }else{
            comm.parentNode.insertBefore(respond, comm.nextSibling);
        }
        const respondDom = document.getElementById("respond");
        window.scrollTo({
            top: respondDom.getBoundingClientRect().top + window.pageYOffset - respondDom.clientTop - 100,
            behavior: "smooth"
        });
        comment_parent.value = parentId;
        cancel.addEventListener('click', function cancelEvent(e){
            e.preventDefault();
            e.stopPropagation()
            let temp = document.getElementById('wp-temp-form-div'),
            respond = document.getElementById(addComment.respondId);
            document.getElementById('comment_parent').value = '0';
            if (temp && respond){
                temp.parentNode.insertBefore(respond, temp);
                temp.remove();
                temp = null;
                respond = null;
            }
            this.style.display = 'none';
            cancel.removeEventListener('click', cancelEvent);
        });
        cancel.style.display = '';
        const comment = document.getElementById('comment');
        comment && comment.focus()
        return false;
    },
    clearButterbar(){
        const butterBar = document.getElementsByClassName("butterBar");
        if (butterBar.length > 0) {
            for(let i=0;i<butterBar.length;i++){
                butterBar[i].remove();
            }
        }
    },

    createButterbar(message, showtime){
        this.clearButterbar();
        const div = document.createElement('div');
        const p = document.createElement('p');
        div.classList.add('butterBar','butterBar--center')
        p.classList.add('butterBar-message')
        p.innerHTML = message;
        div.appendChild(p);
        document.body.appendChild(div);
        setTimeout(() => { addComment.clearButterbar() }, showtime > 0 ? showtime : 6000);
    }
};
export default addComment;