<?php
    $response->write('<h1>Bonjour le monde</h1><p>Nothing to see here...</p>');   
    $templacat->set_variable('PAGE_TITLE', 'Homepage');

    return $response;
?>