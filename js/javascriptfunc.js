function myFunction(x)
{
    if (x == 1)
    {
        var element = document.getElementById("add-op");
        element.style.display = "block";
    }
    else if (x == 2)
    {
        var element = document.getElementById("add-op");
        element.style.display = "none";
    }
    else if (x == 3)
    {
        var element = document.getElementById("add-account");
        element.style.display = "block"; 
    }
    else if (x == 4)
    {
        var element = document.getElementById("add-account");
        element.style.display = "none"; 
    }
}