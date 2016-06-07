
<?php


error_reporting(0);
session_start();
include_once '../oesdb.php';

if (!isset($_SESSION['admname'])) {
    $_GLOBALS['message'] = "Session Timeout.Click here to <a href=\"index.php\">Re-LogIn</a>";
} else if (isset($_REQUEST['logout'])) {
  
    unset($_SESSION['admname']);
    header('Location: index.php');
} else if (isset($_REQUEST['dashboard'])) {
  

    header('Location: admwelcome.php');
} else if (isset($_REQUEST['delete'])) {
    
    unset($_REQUEST['delete']);
    $hasvar = false;
    foreach ($_REQUEST as $variable) {

        if (is_numeric($variable)) { 
            $hasvar = true;

            if (!@executeQuery("delete from testconductor where tcid=$variable")){
               if (mysql_errno () == 1451) 
                    $_GLOBALS['message'] = "Too Prevent accidental deletions, system will not allow propagated deletions.<br/><b>Help:</b> If you still want to delete this user, then first manually delete all the records that are associated with this user.";
                else
                    $_GLOBALS['message'] = mysql_errno();
            }
            // $_GLOBALS['message']=$_GLOBALS['message'].$variable;
        }
    }
    if (!isset($_GLOBALS['message']) && $hasvar == true)
        $_GLOBALS['message'] = "Selected Test Conductor/s are successfully Deleted";
    else if (!$hasvar) {
        $_GLOBALS['message'] = "First Select the Test Conductors to be Deleted.";
    }
} else if (isset($_REQUEST['savem'])) {
   
    if(empty ($_REQUEST['password'])||empty ($_REQUEST['email'])||empty ($_REQUEST['city'])||empty ($_REQUEST['address'])||empty ($_REQUEST['pin']))
    {
         $_GLOBALS['message']="All Fields Are Required.";
    }
	/*else if((strcmp(htmlspecialchars_decode($r['stdname'],ENT_QUOTES),(htmlspecialchars($_REQUEST['cname'],ENT_QUOTES)))!=0) && (mysql_num_rows($result)>0))
    {
        $_GLOBALS['message']="Username Already Exists.";
    }*/
	
	//else if(mysql_num_rows($result)!1
	else if(strlen($_REQUEST['contactno'])!=10)
	{
		 $_GLOBALS['message']="Invalid Mobile No.";
		}
		
		else if(strlen($_REQUEST['pin'])!=6)
	   {
		 $_GLOBALS['message']="Invalid Pincode.";
		}
		 else {
        $query = "update testconductor set tcname='" . htmlspecialchars($_REQUEST['cname'],ENT_QUOTES) . "', tcpassword=ENCODE('" . htmlspecialchars($_REQUEST['password'],ENT_QUOTES) . "','oespass'),emailid='" . htmlspecialchars($_REQUEST['email'],ENT_QUOTES) . "',contactno='" . htmlspecialchars($_REQUEST['contactno'],ENT_QUOTES) . "',address='" .htmlspecialchars($_REQUEST['address'],ENT_QUOTES) . "',city='" . htmlspecialchars($_REQUEST['city'],ENT_QUOTES) . "',pincode='" . htmlspecialchars($_REQUEST['pin'],ENT_QUOTES) . "' where tcid='" . $_REQUEST['tc'] . "';";
        if (!@executeQuery($query))
            $_GLOBALS['message'] = mysql_error();
        else
            $_GLOBALS['message'] = "Test Conductor Information is Successfully Updated.";
    }
    closedb();
}
else if (isset($_REQUEST['savea'])) {
    
    $result = executeQuery("select max(tcid) as tc from testconductor");
    $r = mysql_fetch_array($result);
    if (is_null($r['tc']))
        $newstd = 1;
    else
        $newstd=$r['tc'] + 1;

    $result = executeQuery("select tcname as tc from testconductor where tcname='" . htmlspecialchars($_REQUEST['cname'],ENT_QUOTES) . "';");
 $res=executeQuery("select emailid from student where emailid='".htmlspecialchars($_REQUEST['email'],ENT_QUOTES)."';");


     if(empty($_REQUEST['cname'])||empty ($_REQUEST['password'])||empty ($_REQUEST['email']))
    {
         $_GLOBALS['message']="All Fields Are Required.";
    }else if(mysql_num_rows($result)>0)
    {
        $_GLOBALS['message']="Username Already Exists.";
    }
	else if(strlen($_REQUEST['contactno'])!=10)
	{
		 $_GLOBALS['message']="Invalid Mobile No.";
		}
		else if(mysql_num_rows($res)>0)
		{
			$_GLOBALS['message']="Email already used.";
		}
		else if(strlen($_REQUEST['pin'])!=6)
	{
		 $_GLOBALS['message']="Invalid Pincode.";
		}else if (mysql_num_rows($result) > 0) {
        $_GLOBALS['message'] = "Sorry User Already Exists.";
    } else {
        $query = "insert into testconductor values($newstd,'" . htmlspecialchars($_REQUEST['cname'],ENT_QUOTES) . "',ENCODE('" . htmlspecialchars($_REQUEST['password'],ENT_QUOTES) . "','oespass'),'" . htmlspecialchars($_REQUEST['email'],ENT_QUOTES) . "','" . htmlspecialchars($_REQUEST['contactno'],ENT_QUOTES) . "','" . htmlspecialchars($_REQUEST['address'],ENT_QUOTES) . "','" . htmlspecialchars($_REQUEST['city'],ENT_QUOTES) . "','" . htmlspecialchars($_REQUEST['pin'],ENT_QUOTES) . "')";
        if (!@executeQuery($query)) {
            if(mysql_errno ()==1062)
            $_GLOBALS['message'] = "Given Test Conductor Name voilates some constraints, please try with some other name.";
            else
            $_GLOBALS['message'] = mysql_error();
        }
        else
            $_GLOBALS['message'] = "Successfully New Test Conductor is Created.";
    }
    closedb();
}
?>
<html>
    <head>
        <title>OES-Manage Test Conductors</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link rel="stylesheet" type="text/css" href="../oes.css"/>
        <script type="text/javascript" src="../validate.js" ></script>
    </head>
    <body>
<?php
if ($_GLOBALS['message']) {
    echo "<div class=\"message\">" . $_GLOBALS['message'] . "</div>";
}
?>
        <div id="container">
            <div class="header">
                <img style="margin:10px 2px 2px 10px;float:left;" height="80" width="100" src="../images/logo.png" alt="OES"/><h3 class="headtext"> &nbsp;Online Examination System </h3>
                <h4 style="color:#ffffff;text-align:center;margin:0 0 5px 5px;"><i>Dear Stress Let's Breakup!</i></h4>
            </div>
            <form name="tcmng" action="tcmng.php" method="post">
                <div class="menubar">


                    <ul id="menu">
        <?php
        if (isset($_SESSION['admname'])) {
// Navigations
        ?>
                            <li><input type="submit" value="LogOut" name="logout" class="subbtn" title="Log Out"/></li>
                            <li><input type="submit" value="DashBoard" name="dashboard" class="subbtn" title="Dash Board"/></li>

<?php
            //navigation for Add option
            if (isset($_REQUEST['add'])) {
?>
                                <li><input type="submit" value="Cancel" name="cancel" class="subbtn" title="Cancel"/></li>
                                <li><input type="submit" value="Save" name="savea" class="subbtn" onClick="validateform('usermng')" title="Save the Changes"/></li>

                        <?php
                    } else if (isset($_REQUEST['edit'])) { //navigation for Edit option
                        ?>
                        <li><input type="submit" value="Cancel" name="cancel" class="subbtn" title="Cancel"/></li>
                        <li><input type="submit" value="Save" name="savem" class="subbtn" onClick="validateform('usermng')" title="Save the changes"/></li>

                        <?php
                    } else {  //navigation for Default
                        ?>
                        <li><input type="submit" value="Delete" name="delete" class="subbtn" title="Delete"/></li>
                        <li><input type="submit" value="Add" name="add" class="subbtn" title="Add"/></li>
<?php }
                } ?>
                    </ul>

                </div>
                <div class="page">
<?php
                if (isset($_SESSION['admname'])) {
                    echo "<div class=\"pmsg\" style=\"text-align:center;\">Test Conductors Management </div>";
                    if (isset($_REQUEST['add'])) {
                        
?>
                    <table class="tablog"cellpadding="20" cellspacing="20" style="text-align:left;margin-left:25em" >
                        <tr>
                            <td>TC Name</td>
                            <td><input type="text" name="cname" value="" size="16" onKeyUp="isalphanum(this)"/></td>

                        </tr>

                        <tr>
                            <td>Password</td>
                            <td><input type="password" name="password" value="" size="16" onKeyUp="isalphanum(this)" /></td>

                        </tr>
                        <tr>
                            <td>Re-type Password</td>
                            <td><input type="password" name="repass" value="" size="16" onKeyUp="isalphanum(this)" /></td>

                        </tr>
                        <tr>
                            <td>E-mail ID</td>
                            <td><input type="text" name="email" value="" size="16" /></td>
                        </tr>
                        <tr>
                            <td>Contact No</td>
                            <td><input type="text" name="contactno" value="" size="16" onKeyUp="isnum(this)"/></td>
                        </tr>

                        <tr>
                            <td>Address</td>
                            <td><textarea name="address" cols="20" rows="3"></textarea></td>
                        </tr>
                        <tr>
                            <td>City</td>
                            <td><input type="text" name="city" value="" size="16" onKeyUp="isalpha(this)"/></td>
                        </tr>
                        <tr>
                            <td>PIN Code</td>
                            <td><input type="text" name="pin" value="" size="16" onKeyUp="isnum(this)" /></td>
                        </tr>

                    </table>

<?php
                    } else if (isset($_REQUEST['edit'])) {
                        
                        $result = executeQuery("select tcid,tcname,DECODE(tcpassword,'oespass') as tcpass ,emailid,contactno,address,city,pincode from testconductor where tcname='" . htmlspecialchars($_REQUEST['edit'],ENT_QUOTES) . "';");
                        if (mysql_num_rows($result) == 0) {
                            header('Location: tcmng.php');
                        } else if ($r = mysql_fetch_array($result)) {

                           
?>
                            <table class="tablog"cellpadding="20" cellspacing="20" style="text-align:left;margin-left:25em" >
                                <tr>
                                    <td>TC Name</td>
                                    <td><input type="text" name="cname" value="<?php echo htmlspecialchars_decode($r['tcname'],ENT_QUOTES); ?>" size="16" onKeyUp="isalphanum(this)"/></td>

                                </tr>

                                <tr>
                                    <td>Password</td>
                                    <td><input type="text" name="password" value="<?php echo htmlspecialchars_decode($r['tcpass'],ENT_QUOTES); ?>" size="16" onKeyUp="isalphanum(this)" /></td>

                                </tr>

                                <tr>
                                    <td>E-mail ID</td>
                                    <td><input type="text" name="email" value="<?php echo htmlspecialchars_decode($r['emailid'],ENT_QUOTES); ?>" size="16" /></td>
                                </tr>
                                <tr>
                                    <td>Contact No</td>
                                    <td><input type="text" name="contactno" value="<?php echo htmlspecialchars_decode($r['contactno'],ENT_QUOTES); ?>" size="16" onKeyUp="isnum(this)"/></td>
                                </tr>

                                <tr>
                                    <td>Address</td>
                                    <td><textarea name="address" cols="20" rows="3"><?php echo htmlspecialchars_decode($r['address'],ENT_QUOTES); ?></textarea></td>
                                </tr>
                                <tr>
                                    <td>City</td>
                                    <td><input type="text" name="city" value="<?php echo htmlspecialchars_decode($r['city'],ENT_QUOTES); ?>" size="16" onKeyUp="isalpha(this)"/></td>
                                </tr>
                                <tr>
                                    <td>PIN Code</td>
                                    <td><input type="hidden" name="tc" value="<?php echo htmlspecialchars_decode($r['tcid'],ENT_QUOTES); ?>"/><input type="text" name="pin" value="<?php echo htmlspecialchars_decode($r['pincode'],ENT_QUOTES); ?>" size="16" onKeyUp="isnum(this)" /></td>
                                </tr>

                            </table>
<?php
                            closedb();
                        }
                    } else {
                     
                        $result = executeQuery("select * from testconductor order by tcid;");
                        if (mysql_num_rows($result) == 0) {
                            echo "<h3 style=\"color:#0000cc;text-align:center;\">No Test Conductors Yet..!</h3>";
                        } else {
                            $i = 0;
?>
                            <table cellpadding="30" cellspacing="10" class="datatable">
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>TC Name</th>
                                    <th>Email-ID</th>
                                    <th>Contact Number</th>
                                    <th>Edit</th>
                                </tr>
                    <?php
                            while ($r = mysql_fetch_array($result)) {
                                $i = $i + 1;
                                if ($i % 2 == 0)
                                    echo "<tr class=\"alt\">";
                                else
                                    echo "<tr>";
                                echo "<td style=\"text-align:center;\"><input type=\"checkbox\" name=\"d$i\" value=\"" . $r['tcid'] . "\" /></td><td>" . htmlspecialchars_decode($r['tcname'],ENT_QUOTES)
                                . "</td><td>" . htmlspecialchars_decode($r['emailid'],ENT_QUOTES) . "</td><td>" . htmlspecialchars_decode($r['contactno'],ENT_QUOTES) . "</td>"
                                . "<td class=\"tddata\"><a title=\"Edit " . htmlspecialchars_decode($r['tcname'],ENT_QUOTES) . "\"href=\"tcmng.php?edit=" . htmlspecialchars_decode($r['tcname'],ENT_QUOTES) . "\"><img src=\"../images/edit.png\" height=\"30\" width=\"40\" alt=\"Edit\" /></a></td></tr>";
                            }
                    ?>
                            </table>
<?php
                        }
                        closedb();
                    }
                }
?>

                </div>
            </form>
            <div id="footer">
                 <p style="font-size:70%;color:#ffffff;"> Developed By-<b>Google Shouters</b><br/> </p><p>Copyright 2016 <a href="https://www.googleshout.com">Googleshout.com</a></p>   
            </div>
        </div>
    </body>
</html>

