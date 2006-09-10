<?php
/**
* @version $Id$
* @package Joomla
* @subpackage Users
* @copyright Copyright (C) 2005 - 2006 Open Source Matters. All rights reserved.
* @license GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/*
 * This is our main control structure for the component
 *
 * Each view is determined by the $task variable
 */
switch( JRequest::getVar( 'task' ) )
{
	case 'upload':
		UserController::upload( $uid, $userfile, $userfile_name, $type, $existingImage );
		break;

	case 'edit':
		UserController::edit( );
		break;

	case 'save':
		UserController::save();
		break;

	case 'checkin':
		UserController::checkin();
		break;

	case 'cancel':
		$mainframe->redirect( 'index.php' );
		break;

	default:
		UserController::display();
		break;
}

/**
 * Static class to hold controller functions for the User component
 *
 * @static
 * @author		Louis Landry <johan.janssens@joomla.org>
 * @package		Joomla
 * @subpackage	User
 * @since		1.5
 */
class UserController
{
	function display()
	{
		global $mainframe;
		
		$user =& JFactory::getUser();

		require_once (JPATH_COMPONENT.DS.'views'.DS.'user'.DS.'view.php');
		$view = new UserViewUser();

		$view->assignRef('user'   , $user);
		
		$view->setPath('template', JPATH_COMPONENT.DS.'views'.DS.'user'.DS.'tmpl');
		$view->setLayout('table');
		$view->display();
	}

	function edit()
	{
		global $mainframe, $Itemid, $option;

		$db      =& JFactory::getDBO();
		$user	 =& JFactory::getUser();

		if ( $user->get('id') == 0 ) {
			JError::raiseError( 403, JText::_('Access Forbidden') );
			return;
		}

		// Get the paramaters of the active menu item
		$menus  = &JMenu::getInstance();
		$menu   = $menus->getItem($Itemid);

		// Set page title
		$mainframe->setPageTitle( $menu->name );
		
		// check to see if Frontend User Params have been enabled
		$check = $mainframe->getCfg('frontend_userparams');
		if ($check == '1' || $check == 1 || $check == NULL) {
			$params = $user->getParameters();
			$params->loadSetupFile(JPATH_ADMINISTRATOR . '/components/com_users/users.xml');
		}
		
		require_once (JPATH_COMPONENT.DS.'views'.DS.'user'.DS.'view.php');
		$view = new UserViewUser();
		
		$view->assignRef('user'  , $user);
		$view->assignRef('params', $params);

		$view->setPath('template', JPATH_COMPONENT.DS.'views'.DS.'user'.DS.'tmpl');
		$view->setLayout('form');
		$view->display();
	}

	function save( )
	{
		global $mainframe, $option;

		$user =& JFactory::getUser();

		// Protect against simple spoofing attacks
		if (!JUtility::spoofCheck()) {
			JError::raiseWarning( 403, JText::_( 'E_SESSION_TIMEOUT' ) );
			return;
		}

		$db 	=& JFactory::getDBO();
		$user_id = JRequest::getVar( 'id', 0, 'post', 'int' );

		// do some security checks
		if ($user->get('id') == 0 || $user_id == 0 || $user_id <> $user->get('id')) {
			JError::raiseError( 403, JText::_('Access Forbidden') );
			return;
		}

		$post = JRequest::get( 'post' );

		$post['password'] = JRequest::getVar('password', '', 'post', 'string');
		$post['verify']   = JRequest::getVar('verify', '', 'post', 'string');

		// do a password safety check
		if($password != "") {
			if($verify == $password) {
				echo "<script> alert(\"". JText::_( 'Passwords do not match', true ) ."\"); window.history.go(-1); </script>\n";
				exit();
			}
		}

		$user = JUser::getInstance($user_id);
		$orig_username = $user->get('username');

		if (!$user->bind( $post )) {
			echo "<script> alert('".$user->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}

		if (!$user->save()) {
			echo "<script> alert('".$user->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}

		// check if username has been changed
		if ( $orig_username != $user->get('username') )
		{
			// change username value in session table
			$query = "UPDATE #__session"
				. "\n SET username = '$user->get('username')"
				. "\n WHERE username = '$orig_username'"
				. "\n AND userid = $user->get('id')"
				. "\n AND gid = $user->get('gid')"
				. "\n AND guest = 0"
				;
			$db->setQuery( $query );
			$db->query();

			JSession::set('username', $user->get('username'));
		}

		$link = $_SERVER['HTTP_REFERER'];
		$mainframe->redirect( $link, JText::_( 'Your settings have been saved.' ) );
	}

	function upload( $uid, $userfile, $userfile_name, $type, $existingImage )
	{
		global $mainframe, $option;

		$dbprefix = $mainframe->getCfg( 'dbprefix' );

		// Protect against simple spoofing attacks
		if (!JUtility::spoofCheck()) {
			JError::raiseWarning( 403, JText::_( 'E_SESSION_TIMEOUT' ) );
			return;
		}

		// Initialize some variables
		$db = & JFactory::getDBO();

		if ($uid == 0) {
			JError::raiseError( 403, JText::_('Access Forbidden') );
			return;
		}

		$base_Dir 	= 'images/stories/';
		$checksize	= filesize($userfile);

		if ($checksize > 50000)
		{
			echo "<script> alert(\"". JText::_( 'UP_SIZE' ) ."\"); window.history.go(-1); </script>\n";
		}
		else
		{
			if (file_exists($base_Dir.$userfile_name)) {
				$message= JText::_( 'UP_EXISTS', true );
				eval ("\$message = \"$message\";");
				print "<script> alert('$message'); window.history.go(-1);</script>\n";
			}
			else
			{
				if ((!strcasecmp(substr($userfile_name,-4),".gif")) || (!strcasecmp(substr($userfile_name,-4),".jpg")))
				{
					if (!move_uploaded_file($userfile, $base_Dir.$userfile_name))
					{
						echo JText::_( 'Failed to copy' ) ." $userfile_name";
					}
					else
					{
						echo "<script>window.opener.focus;</script>";
						if ($type=="news") {
							$op="UserNews";
						} elseif ($type=="articles") {
							$op="UserArticle";
						}

						if ($existingImage!="") {
							if (file_exists($base_Dir.$existingImage)) {
								//delete the exisiting file
								unlink($base_Dir.$existingImage);
							}
						}
						echo "<script>window.opener.document.adminForm.ImageName.value='$userfile_name';</script>";
						echo "<script>window.opener.document.adminForm.ImageName2.value='$userfile_name';</script>";
						echo "<script>window.opener.document.adminForm.imagelib.src=null;</script>";
						echo "<script>window.opener.document.adminForm.imagelib.src='images/stories/$userfile_name';</script>";
						echo "<script>window.close(); </script>";
					}
				}
				else
				{
					echo "<script> alert(\"". JText::_( 'You may only upload a gif, or jpg image.', true ) ."\"); window.history.go(-1); </script>\n";
				}
			}
		}
	}

	function checkin( )
	{
		global $mainframe, $option;

		$db      =& JFactory::getDBO();
		$user    =& JFactory::getUser();
		$userid  = $user->get('id');

		// Editor usertype check
		$canEdit    = $user->authorize( 'action', 'edit', 'content', 'all' );
		$canEditOwn = $user->authorize( 'action', 'edit', 'content', 'own' );

		$nullDate = $db->getNullDate();
		if (!($canEdit || $canEditOwn || $userid > 0)) {
			JError::raiseError( 403, JText::_('Access Forbidden') );
			return;
		}

		// security check to see if link exists in a menu
		$link = 'index.php?option=com_user&task=CheckIn';
		$query = "SELECT id"
			. "\n FROM #__menu"
			. "\n WHERE link LIKE '%$link%'"
			. "\n AND published = 1"
		;
		$db->setQuery( $query );
		$exists = $db->loadResult();
		if ( !$exists ) {
			JError::raiseError( 403, JText::_('Access Forbidden') );
			return;
		}

		$lt = mysql_list_tables($mainframe->getCfg('db'));
		$k = 0;
		echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">";
		while (list($tn) = mysql_fetch_array($lt)) {
			// only check in the jos_* tables
			if (strpos( $tn, $db->_table_prefix ) !== 0) {
				continue;
			}
			$lf = mysql_list_fields($mainframe->getCfg('db'), "$tn");
			$nf = mysql_num_fields($lf);

			$checked_out = false;
			$editor = false;

			for ($i = 0; $i < $nf; $i++) {
				$fname = mysql_field_name($lf, $i);
				if ( $fname == "checked_out") {
					$checked_out = true;
				} else if ( $fname == "editor") {
					$editor = true;
				}
			}

			if ($checked_out)
			{
				if ($editor) {
					$query = "SELECT checked_out, editor"
					. "\n FROM $tn"
					. "\n WHERE checked_out > 0"
					. "\n AND checked_out = $userid"
					;
					$db->setQuery( $query );
				} else {
					$query = "SELECT checked_out"
					. "\n FROM $tn"
					. "\n WHERE checked_out > 0"
					. "\n AND checked_out = $userid"
					;
					$db->setQuery( $query );
				}
				$res = $db->query();
				$num = $db->getNumRows( $res );

				if ($editor) {
					$query = "UPDATE $tn"
					. "\n SET checked_out = 0, checked_out_time = '$nullDate', editor = NULL"
					. "\n WHERE checked_out > 0"
					;
					$db->setQuery( $query );
				} else {
					$query = "UPDATE $tn"
					. "\n SET checked_out = 0, checked_out_time = '$nullDate'"
					. "\n WHERE checked_out > 0"
					;
					$db->setQuery( $query );
				}
				$res = $db->query();

				if ($res == 1) {

					if ($num > 0) {
						echo "\n<tr class=\"row$k\">";
						echo "\n	<td width=\"250\">";
						echo JText::_( 'Checking table' );
						echo " - $tn</td>";
						echo "\n	<td>";
						echo JText::_( 'Checked in' ) ." <b>". $num ."</b> ". JText::_( 'items' );
						echo "</td>";
						echo "\n</tr>";
					}
					$k = 1 - $k;
				}
			}
		}
		?>
		<tr>
			<td colspan="2">
				<b><?php echo JText::_( 'CONF_CHECKED_IN' ); ?></b>
			</td>
		</tr>
		</table>
		<?php
	}
}
?>