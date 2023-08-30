<?php

/**

 * @package     Joomla.Site

 * @subpackage  com_wrapper

 *

 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.

 * @license     GNU General Public License version 2 or later; see LICENSE.txt

 */



defined('_JEXEC') or die;



JHtml::_('script', 'com_wrapper/iframe-height.min.js', array('version' => 'auto', 'relative' => true));

$user = JFactory::getUser();
$groups = JAccess::getGroupsByUser( $user->get('id') );

// $_SESSION["s_username"] = $user->get('username');
// $_SESSION["s_group"] = $groups[1];

// print_r($_SESSION);
?>

<div class="contentpane<?php echo $this->pageclass_sfx; ?>">

	<?php if ($this->params->get('show_page_heading')) : ?>

		<div class="page-header">

			<h1>

				<?php if ($this->escape($this->params->get('page_heading'))) : ?>

					<?php echo $this->escape($this->params->get('page_heading')); ?>

				<?php else : ?>

					<?php echo $this->escape($this->params->get('page_title')); ?>

				<?php endif; ?>

			</h1>

		</div>

	<?php endif; ?>

	<form target="iframe" action="<?php echo $this->escape($this->wrapper->url);?>" id="frm_iframe" method="post">
	    <input type="hidden" value="<?php echo $user->get('username'); ?>" name="ruksaername" />
	    <input type="hidden" value="<?php echo $groups[1];?>" name="rgkraoupid" />
	</form>

	<iframe <?php echo $this->wrapper->load;?>
		id="blockrandom"

		name="iframe"

		width="<?php echo $this->escape($this->params->get('width')); ?>"

		height="<?php echo $this->escape($this->params->get('height')); ?>"

		scrolling="<?php echo $this->escape($this->params->get('scrolling')); ?>"

		frameborder="<?php echo $this->escape($this->params->get('frameborder', 1)); ?>"

		<?php if ($this->escape($this->params->get('page_heading'))) : ?>

			title="<?php echo $this->escape($this->params->get('page_heading')); ?>"

		<?php else : ?>

			title="<?php echo $this->escape($this->params->get('page_title')); ?>"

		<?php endif; ?>

		class="wrapper<?php echo $this->pageclass_sfx; ?>">

		<?php echo JText::_('COM_WRAPPER_NO_IFRAMES'); ?>

	</iframe>

	<script type="text/javascript">

		document.getElementById("frm_iframe").submit();

	</script>

</div>

