<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Saleswherewhen :: Merchant SignUp</title>

        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/frontend/css/bootstrap-theme.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/frontend/css/bootstrap.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/frontend/css/style.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/frontend/fonts/stylesheet.css" />

        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/frontend/css/mgmenu.css" type="text/css" media="screen" />


        <script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/bootstrap.js"></script>

        <script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/scripts.js"></script><!-- Mega Menu Script -->
        <script>
            $(document).ready(function ($) {
                $('#mgmenu1').universalMegaMenu({
                    menu_effect: 'hover_fade',
                    menu_speed_show: 300,
                    menu_speed_hide: 200,
                    menu_speed_delay: 200,
                    menu_click_outside: false,
                    menubar_trigger: false,
                    menubar_hide: false,
                    menu_responsive: true
                });
                megaMenuContactForm();
            });
        </script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/mootools-core.js"></script>
    </head>
    <body>
        <div class="container-fluid">
            <div class="container less_pad">
                <?php $this->load->view('front_include/header.php'); ?>                

                <?php $this->load->view('front_include/megaMenu.php'); ?>      

                <div class="col-lg-12">
                    <ol class="breadcrumb">
                        <li><a href="<?php echo base_url(); ?>">Home</a></li>
                        <li class="active">Merchant Signup</li>
                    </ol>
                </div>

                <div class="col-lg-12">
                    <div class="profile_wrap">

                        <!--Profile-->

                        <div class="col-lg-12">
                            <div class="profile_right_wrap">
                                <div id="profile" style="">
                                    <h2>Merchant Signup</h2>
                                    <form action="<?php echo base_url();?>signup/merchant" method="post" enctype="multipart/form-data">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td width="50%">
                                                <table>
                                                    <tr>
                                                        <td align="left" valign="top" width="20%">First Name:<span class="red">*</span></td>
                                                        <td align="left" valign="top" width="80%"><input type="text" size="50" class="profile_input" name="first_name" required placeholder="Enter First Name.."/></td>
                                            
                                                    </tr>
                                                    <tr>
                                                        <td align="left" valign="top" width="20%">Email:<span class="red">*</span></td>
                                                        <td align="left" valign="top" width="80%"><input type="text" size="50" class="profile_input" name="email_id" required  placeholder="Enter Email.."/></td>
                                            
                                                    </tr>
                                                    <tr>
                                                        <td align="left" valign="top" width="20%">Contact:<span class="red">*</span></td>
                                                        <td align="left" valign="top" width="80%"><input type="text" size="50" class="profile_input" name="contact_no" required  placeholder="Enter Contact.."/></td>
                                            
                                                    </tr>
                                                    <tr>
                                                        <td align="left" valign="top" width="20%">City:<span class="red">*</span></td>
                                                        <td align="left" valign="top" width="80%">
                                                            
                                                            
                                                            <select name="city" required >
                                                                <option value="0">--Select City--</option>
                                                                <?php foreach ($city_list as $city) { ?>
                                                                    <option value="<?php echo $city->city_id;?>"><?php echo $city->city_name;?></option>
                                                                <?php } ?>

                                                            </select>
                                                        </td>
                                            
                                                    </tr>
                                                </table>    
                                            </td>
                                             <td width="50%">
                                                <table>
                                                    <tr>
                                                        <td align="left" valign="top" width="20%">Last Name:</td>
                                                        <td align="left" valign="top" width="80%"><input type="text" size="50" class="profile_input" name="last_name" placeholder="Enter Last Name.."/></td>
                                            
                                                    </tr>
                                                    <tr>
                                                        <td align="left" valign="top" width="20%">Password:<span class="red">*</span></td>
                                                        <td align="left" valign="top" width="80%"><input type="password" size="50" class="profile_input" name="password" required  placeholder="Enter Password.."/></td>
                                            
                                                    </tr>
                                                    <tr>
                                                        <td align="left" valign="top" width="20%">Address:</td>
                                                        <td align="left" valign="top" width="80%"><textarea size="50" class="profile_input" name="address" placeholder="Enter Address.."></textarea></td>
                                            
                                                    </tr>
                                                </table>    
                                            </td>                                            
                                        </tr>
                                        <?php 
                                        if(isset($succ_regis)){
                                            echo '<p style="color:green">Check Your mail for Confirm Registration.</p>';
                                        }
                                        
                                        ?>
                                         <tr>
                                                
                                             <td align="right" valign="top" colspan="2"><input type="submit" class="btn btn-primary" style="float:right;  margin-right: 11.5% !important;" value="Submit"></td>
                                            </tr>
                                    </table>                                 
                                    </form>
                                    <div class="clearfix"></div>
                                </div>                              
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
        <?php $this->load->view('front_include/footer.php'); ?>
