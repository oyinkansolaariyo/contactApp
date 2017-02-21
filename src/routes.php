<?php
// Routes
$app->get('/',\UserController::class.':getUsers');
$app->post('/register',\UserController::class.':registerUser');
$app->post('/login',\UserController::class.':loginUser');
$app->get('/{user_id}/contacts',\UserController::class.':getContacts');
$app->post('/{user_id}/addContact',\UserController::class.':addNewContact');
$app->post('/{contact_id}/update',\UserController::class.':updateAContact');
$app->post('/{contact_id}/delete',\UserController::class.':deleteAContact');
