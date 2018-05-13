<?php defined('BLUDIT') or die('Bludit CMS.');

// ============================================================================
// Functions
// ============================================================================

// ============================================================================
// Main before POST
// ============================================================================

// ============================================================================
// POST Method
// ============================================================================

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	// Prevent non-administrators to change other users
	if ($Login->role()!=='admin') {
		$_POST['username'] = $Login->username();
		unset($_POST['role']);
	}

	if (isset($_POST['deleteUserAndDeleteContent'])) {
		$_POST['deleteContent'] = true;
		deleteUser($_POST);
	} elseif (isset($_POST['deleteUserAndKeepContent'])) {
		$_POST['deleteContent'] = false;
		deleteUser($_POST);
	} elseif (isset($_POST['disableUser'])) {
		disableUser(array('username'=>$_POST['username']));
	} else {
		editUser($_POST);
	}

	Alert::set($Language->g('The changes have been saved'));
	Redirect::page('users');
}

// ============================================================================
// Main after POST
// ============================================================================

// Prevent non-administrators to change other users
if ($Login->role()!=='admin') {
	$layout['parameters'] = $Login->username();
}

// Get the user to edit
$user = $dbUsers->get($layout['parameters']);
if ($user===false) {
	Redirect::page('users');
}

// Title of the page
$layout['title'] = $Language->g('Edit user').' - '.$layout['title'];