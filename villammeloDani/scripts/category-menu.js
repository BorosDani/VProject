// Kategória menü funkcionalitása
document.addEventListener('DOMContentLoaded', function() 
{
    // Kategória menü elemek
    const categoryButton = document.querySelector('.category-btn');
    const categoryDropdown = document.getElementById('menu_category_DropDown');
    
    // Ellenőrizzük, hogy az elemek léteznek
    if (!categoryButton || !categoryDropdown) {
        return;
    }
    
    // Menü megnyitása/bezárása
    categoryButton.addEventListener('click', function(e) 
    {
        e.stopPropagation();
        categoryDropdown.classList.toggle('show');
    });
    
    // Kattintás menün kívül - menü bezárása
    document.addEventListener('click', function(e) 
    {
        if (categoryDropdown.classList.contains('show')) 
        {
            if (!categoryDropdown.contains(e.target) && !categoryButton.contains(e.target)) 
            {
                categoryDropdown.classList.remove('show');
            }
        }
    });
    
    // ESC billentyű - menü bezárása
    document.addEventListener('keydown', function(e) 
    {
        if (e.key === 'Escape' && categoryDropdown.classList.contains('show')) 
        {
            categoryDropdown.classList.remove('show');
        }
    });
    
    // Linkre kattintás - menü bezárása
    const categoryLinks = categoryDropdown.querySelectorAll('a');
    categoryLinks.forEach(link => {
        link.addEventListener('click', function() {
            categoryDropdown.classList.remove('show');
        });
    });
});