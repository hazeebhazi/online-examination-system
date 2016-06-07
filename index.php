



 <?php

 
      error_reporting(0);
      session_start();
      include_once 'oesdb.php';
      if(isset($_REQUEST['register']))
      {
            header('Location: register.php');
      }
      else if($_REQUEST['stdsubmit'])
      {
          $result=executeQuery("select *,DECODE(stdpassword,'oespass') as std from student where stdname='".htmlspecialchars($_REQUEST['name'],ENT_QUOTES)."' and stdpassword=ENCODE('".htmlspecialchars($_REQUEST['password'],ENT_QUOTES)."','oespass') ");
          if(mysql_num_rows($result)>0)
          {

              $r=mysql_fetch_array($result);
              if(strcmp(htmlspecialchars_decode($r['std'],ENT_QUOTES),(htmlspecialchars($_REQUEST['password'],ENT_QUOTES)))==0&&strcmp(htmlspecialchars_decode($r['status'],ENT_QUOTES),'pending')!=0)
			  
              {
                  $_SESSION['stdname']=htmlspecialchars_decode($r['stdname'],ENT_QUOTES);
                  $_SESSION['stdid']=$r['stdid'];
                  unset($_GLOBALS['message']);
                  header('Location: stdwelcome.php');
              }
			  else
          {
              $_GLOBALS['message']="Waiting for the Approval";
          }

          }
          else
          {
              $_GLOBALS['message']="Check Your User name and Password.";
          }
          closedb();
      }
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
  <head>
    <title>Online Examination System</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link rel="stylesheet" type="text/css" href="oes.css"/>
 <!--<script src="sliderengine/jquery.js"></script>
    <script src="sliderengine/amazingslider.js"></script>
    <link rel="stylesheet" type="text/css" href="sliderengine/amazingslider-0.css">
    <script src="sliderengine/initslider-0.js"></script>-->
    
  </head>
  <body>
  <div id="container">
            
            <div class="header">
                <img style="margin:10px 2px 2px 10px;float:left;" height="80" width="80" src="images/logo.png" alt="OES"/><h3 class="headtext"> &nbsp;Online Examination System </h3><h4 style="color:#ffffff;text-align:center;margin:0 0 5px 5px;"><i>Dear stress,Let's breakup!</i></h4>
    </div>
  <form id="stdloginform" action="index.php" method="post">
      <div class="menubar">
       
       <ul id="menu">
      
                    <?php if(isset($_SESSION['stdname'])){
                          header('Location: stdwelcome.php');}else{  
                        ?>

                      <!--  <li><input type="submit" value="Register" name="register" class="subbtn" title="Register"/></li>-->
           <li><div class="aclass"><a href="register.php" title="Click here  to Register">Register</a></div></li>
                        <?php } ?>
        </ul>

      </div>
      <div class="page">
             
    <table class="logtab"cellpadding="40" cellspacing="10">
              <tr>
                <td colspan="4"> <img src="images/log.png" alt="OES" width="86" height="73" align="absmiddle"  /></td> 
      </tr><tr>
                  <td>User Name</td>
                  <td width="36"><input type="text" tabindex="1" name="name" value="" size="16" placeholder="Your Username."/></td>

              </tr>
              <tr>
                  <td>Password</td>
                  <td><input type="password" tabindex="2" name="password" value="" size="16" placeholder="Your Password."/></td>
              </tr>

              <tr>
                <td colspan="2">
                      <p>
                        <input type="submit" tabindex="3" value="Log In" name="stdsubmit" class="subbtn" />
                      </p>
      
                      <?php

        if($_GLOBALS['message'])
        {
         echo "<div class=\"msg\">".$_GLOBALS['message']."</div>";
        }
      ?>
                </td><td></td>
              </tr>
           </table>


      </div>
    </form>

    <div id="footer">
           <p style="font-size:70%;color:#ffffff;"> Developed By-<b>Google Shouters</b><br/> </p><p>Copyright 2016 <a href="https://www.googleshout.com">Googleshout.com</a></p>   
    </div>
  </div>
  </body>
</html>
