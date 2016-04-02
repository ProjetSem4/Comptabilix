// Unselect the dropdowns (if nothing is selected by default)
var found = false;
$('#id_client option').each(function()
{
    // If one is selected
    if($(this).attr('selected'))
    {
        // Filter the MOA select, without reseting it
        filter_moa($(this).val(), true);
        found = true;
    }
});

// If none has been selected, just reset the two selects
if(!found)
{
    $('#id_client').val('none');
    $('#id_moa').val('none');   
}

// Filter the MOA select based on the client selected
function filter_moa(id_client, init = false)
{
    // Reset the select selection
    if(!init)
        $('#id_moa').val('none');

    // For each option
    $('#id_moa option').each(function()
    {
        // If it is associated with the good client
        if($(this).attr('class') == 'client_' + id_client)
            $(this).removeAttr('disabled'); // Then enable it
        else
            $(this).attr('disabled', 'disabled'); // Otherwise disable it
    });
}