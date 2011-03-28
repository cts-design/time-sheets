<?php
/** 
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <!-- meta -->
        <?php echo $this->Html->charset(); ?>
        <meta name="description" content="" />
        <meta name="KEYWORDS" content="" />
        <meta name="Copyright" content="" />
        <meta name="Language" content="English" />

        <!-- css -->
        <?php
		    echo $this->Html->css('ui-darkness/jquery-ui-1.8.5.custom');
			echo $this->Html->css('style');
			echo $this->Html->css('superfish');
			echo $this->Html->script('jquery');
			echo $this->Html->script('jquery-ui-1.8.5.custom.min');
			echo $this->Html->script('superfish/hoverIntent.js');
			echo $this->Html->script('superfish/superfish.js');
			echo $scripts_for_layout;
		?>

        <!-- [if IE]>
            <link rel="stylesheet" type="text/css" href="css/ie.css" />
        <![endif]-->

        <!-- js -->   
        <?php echo $this->Html->scriptBlock(
			"$(document).ready(function(){
				$('.message').fadeOut(10000);
				if($('.actions ul').text() == '') {
				    $('div.actions').hide();
				}
				
				$('#search_field').focus(function() {
                    if ($(this).val() == 'KEYWORD SEARCH') {   
                        $(this).val('');
                    }
                    $(this).removeClass('field_blur').addClass('field_focus');
                }).blur(function() {
                   if ($(this).val() == '') {
                       $(this).val('KEYWORD SEARCH');
                   }
                   $(this).removeClass('field_focus').addClass('field_blur');
                });
		    });"
	    )?>

        <!-- favicon -->
		<?php echo $this->Html->meta('icon'); ?>

        <!-- title -->
		<title>
		    <?php __('Tampa Bay Workforce Alliance'); ?>
		    <?php echo $title_for_layout; ?>
		</title>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h1><a href="/">Tampa Bay Workforce Alliance</a></h1>
                <ul>
                    <li style="visibility: hidden"><a href="#">User Login</a></li>
                    <li style="visibility: hidden"><a href="#">Register</a></li>
                </ul>
                <form action="#" method="post">
                    <p>
                        <label for="search_field">Search</label>
                        <input type="text" class="field_blur" id="search_field" name="search_field" value="KEYWORD SEARCH" />
                    </p>
                    <p><input type="submit" id="search_submit" value="Go" /></p>
                </form>
            </div> <!-- end .header -->
            <div class="clear"></div>
            
            <div class="content">
                <div class="topNav">
                	<?php echo $this->Nav->links('Top') ?>
                	<!-- <ul class="sf-menu">
                		<li class="current"><a href="#a">Sunfishes</a>
                			<ul>
                				<li><a href="#aa">Blackbanded Sunfish</a></li>
                				<li><a href="#ab">Shadow Bass</a></li>
                				<li><a href="#ac">Ozark Bass</a></li>
                				<li><a href="#ad">White Crappie</a></li>
                			</ul>
                		</li>
                		<li><a href="#b">Grunts</a>
                			<ul>
                 				<li><a href="#ba">Smallmouth Grunt</a></li>
                				<li><a href="#bb">Burrito</a></li>
                				<li><a href="#bc">Pigfish</a></li>               				
                			</ul>	
                		</li>
                		<li><a href="#c">Remoras</a>
                			<ul>
                				<li><a href="#ca">Whalesucker</a></li>
                				<li><a href="#cb">Marlinsucker</a></li>
                				<li><a href="#cc">Ceylonese remora</a></li>
                				<li><a href="#cd">Spearfish remora</a></li>
                				<li><a href="#ce">Slender suckerfish</a></li>
                			</ul>
                		</li>
                	</ul> -->
                </div> <!-- end .topNav -->
                <div class="clear"></div>
                
                <div class="leftNav">
                	<?php echo $this->Nav->links('Left') ?>
                </div> <!-- end .leftNav -->
            	<?php if ($this->params['action'] == 'display' && $this->params['controller'] == 'pages'): ?>
                <div>
            	<?php else: ?>
                <div class="sub_content">
	            <?php endif; ?>
	            	<h2><?php echo $title_for_layout ?></h2>
	            	<?php echo $this->Session->flash(); ?>
					<?php echo $session->flash('auth'); ?>
	            	<?php echo $content_for_layout; ?>
                </div>
            </div> <!-- end .content -->

            <div class="footer">
                <div class="first">
                    <h4>Main Services</h4>
					<?php echo $this->Nav->links('bottom') ?>
                </div> <!-- end .first -->

                <div>
                    <h4>Useful Links</h4>
                    <ul>
                        <li><a href="#">Main Services</a></li>
                        <li><a href="#">Main Services</a></li>
                        <li><a href="#">Main Services</a></li>
                        <li><a href="#">Main Services</a></li>
                    </ul>
                </div>

                <div class="last">
                    <h4>OneStop Locations</h4>
                    <ul>
                        <li><a href="#">Workforce Brandon</a></li>
                        <li><a href="#">Workforce Plant City</a></li>
                        <li><a href="#">Workforce Tampa</a></li>
                    </ul>
                </div> <!-- end .last -->
            </div> <!-- end .footer -->
        </div> <!-- end .container -->
    </body>
</html>
