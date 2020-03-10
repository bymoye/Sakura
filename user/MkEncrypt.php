<?php  
   
   /******************************************** 
    * 使用方法: 
    *  
    * 1、将本段代码保存为 MkEncrypt.php 
    *  
    * 2、在要加密的页面前面引入这个 php 文件    
    *  require_once('MkEncrypt.php'); 
    *  
    * 3、设置页面访问密码  
    *  MkEncrypt('页面密码'); 
    *  
   ********************************************/  
      
   // 密码 Cookie 加密盐  
   if(!defined('MK_ENCRYPT_SALT'))  
       define('MK_ENCRYPT_SALT', 'Kgs$JC!C');  
      
   /** 
    * 设置访问密码 
    *  
    * @param $password  访问密码 
    * @param $pageid    页面唯一 ID 值，用于区分同一网站的不同加密页面 
    */  
   function MkEncrypt($password, $pageid = 'default') {  
       $pageid     = md5($pageid);  
       $md5pw      = md5(md5($password).MK_ENCRYPT_SALT);  
       $postpwd    = isset($_POST['pagepwd']) ? addslashes(trim($_POST['pagepwd'])) : '';  
       if($postpwd == $password) {         // 提交的密码正确  
           return;  
       }  
   ?>  
           <form action="" method="post" style="text-align: center;">  
               <h2 class="pw-tip">该页面已被加密</h2>  
               <input type="password" name="pagepwd" placeholder="请输入访问密码查看" required><button type="submit">提交</button>  
               <?php if($postpwd): ?>  
               <p id="pw-error">惹密码错了噢</p>  
               <script>setTimeout(function() {document.getElementById("pw-error").style.display = "none"}, 2000);</script>  
               <?php endif; ?>  
           </form>  
   <?php  
       exit();  
   }  