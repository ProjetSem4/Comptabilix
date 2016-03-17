<?php
    $response->write('<div class="panel panel-default contenu-page"><h1>Bonjour le monde</h1><p>Nothing to see here...</p></div>');   
    $templacat->set_variable('PAGE_TITLE', 'Homepage');

    return $response;
?>