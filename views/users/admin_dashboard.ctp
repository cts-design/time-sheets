<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 */
?>
<?php echo $this->Html->script(array('jquery.cookie', 'jquery.jstree', 'users/dashboard'), array('inline' => false)) ?>
<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Dashboard', true), 'reset', 'unique') ; ?>
</div>

<div id="adminDashboard" class="admin">
    <p><strong><?php __('Welcome to the administration dashboard.') ?></strong>
    </p>

    <div id="administration" class="left">
        <div>
	    <h3><?php echo $html->image('icons/user_suit.png')?> Administration</h3>
	    <p class="expand-wrap"><?php echo $this->Html->link(__('Expand All', true), '', array('id' => 'expand')) ?></p>
	    <div id="dashboardAdminTree" style="background-color: transparent">
		<ul>
		    <?php if($this->Session->read('Auth.User.role_id') == 2 || $this->Session->read('Auth.User.role_id') == 3 )  { ?>
                <?php echo $this->Nav->buildAdminNavigation('website') ?>
                <?php echo $this->Nav->buildAdminNavigation('settings') ?>
		    <?php }?>
            <?php echo $this->Nav->buildAdminNavigation('alerts') ?>
            <?php echo $this->Nav->buildAdminNavigation('users') ?>
            <?php echo $this->Nav->buildAdminNavigation('selfSign') ?>
            <?php echo $this->Nav->buildAdminNavigation('storage') ?>
            <?php echo $this->Nav->buildAdminNavigation('programs') ?>
            <?php echo $this->Nav->buildAdminNavigation('audits') ?>
            <?php echo $this->Nav->buildAdminNavigation('tools') ?>
		</ul>
	    </div>
    </div>
    </div>
    <div id="information" class="left">
	    <div id='help'>
            <h3><?php echo $html->image('icons/help.png')?> <?php __('Help') ?></h3>
            <?php if($this->Session->read('Auth.User.role_id') <= 3) : ?>
		    	<p>
					<a href="/admin/help_desk_tickets">
						<?php echo $html->image('icons/email.png')?> 
						<?php __('Create a support ticket') ?>
					</a> 
		    	</p>
		    	<p><?php echo $html->image('icons/telephone.png')?> 352-666-0333</p>
	    	<?php else : ?>
	    		<p><?php echo $html->image('icons/bug.png')?> Please report issues with ATLAS to your supervisor.</p>
	    	<?php endif ?> 
	    	<p>
	    		<?php echo $html->image('icons/application_xp_terminal.png')?>
	    		<?php echo $html->link('Atlas 3.5.3', array('controller' => 'release_notes', 'admin' => true))?>
	    	</p>    	
	    </div>
    </div>
    <div class="clear"></div>
</div>

