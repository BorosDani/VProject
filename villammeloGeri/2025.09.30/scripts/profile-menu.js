// Profil menü funkcionalitása
document.addEventListener('DOMContentLoaded', function() 
{
    // Profil menü elemek
    const profileButton = document.querySelector('.menu_p button');
    const profileDropdown = document.getElementById('menu_p_DropDown');
    
    // Menü megnyitása/bezárása
    if (profileButton && profileDropdown) 
    {
        profileButton.addEventListener('click', function(e) 
        {
            e.stopPropagation();
            profileDropdown.classList.toggle('show');
        });
    }
    
    // Kattintás menün kívül - menü bezárása
    document.addEventListener('click', function(e) 
    {
        if (profileDropdown && profileDropdown.classList.contains('show')) 
        {
            if (!profileDropdown.contains(e.target) && !profileButton.contains(e.target)) 
            {
                profileDropdown.classList.remove('show');
            }
        }
    });
});