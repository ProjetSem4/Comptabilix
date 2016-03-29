<?php
    // Clean the connection_state array
    $_SESSION['connection_state'] = array();

    // Destroy the old session
    session_destroy();

    // Then create a new one
    session_start();

    // Insert a success message
    $_SESSION['fortitudo_messages'][] = array('type' => 'success', 'content' => 'Vous avez été déconnecté avec succès.');

    // And the redirect to the login page
    header('Location: /connexion');
?>