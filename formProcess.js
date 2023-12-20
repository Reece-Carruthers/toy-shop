addEventListener('load', function(){
    "use strict";

    let totalPrice = 0; // Holds the price value

    const form = document.getElementById("orderForm"); // Gets the form element for assigning onchange event too

    function calcTotal(){ // Loops through each checkbox that contains a price value and if it is checked at it to the total price
        totalPrice = 0; // Sets the price to 0 so the correct value is fetched

        const allPrices = document.querySelectorAll("div.item input[type=checkbox][data-price], section#collection input[data-price]"); // Gets all the checkboxes/radio buttons which need to viewed to see if they are checked

        for(let a = 0; a < allPrices.length; a++){ // Loop through the checkboxes
            if(allPrices[a].checked){ // if checked add to total
                totalPrice += parseFloat(allPrices[a].dataset.price);
            }
        }

        const totalBox = document.querySelector("section#checkCost input[name='total']"); // Fetch the input field which holds the price to show to the user
        totalBox.value = totalPrice.toFixed(2); // set the price and round it to 2 decimal places
    }

    let termsToggle = false; // Sets the term toggle to false on load
    function toggleTerms(){ // toggles the style of the terms and condition and enables the submit button if the terms are accepted

        termsToggle = !termsToggle; // Swaps the toggle state
        const termsElement = document.querySelector("p#termsText"); // Gets the p element which styles the text of the terms checkbox
        const submitElement = document.querySelector("input[name='submit']"); // Gets the submit element

        if(termsToggle){ // if the terms are checked enable submit button, colour and font weight
            termsElement.style.color = "black";
            termsElement.style.fontWeight = "normal";
            submitElement.disabled = false;
        }else{ // else terms are set to red, font weight is bold and submit button is disabled
            termsElement.style.color = "#FF0000";
            termsElement.style.fontWeight = "bold";
            submitElement.disabled = true;
        }
    }

    function isToyChecked(){
        const toys = document.querySelectorAll("div.item input[type=checkbox][data-price]");
        let toyChecked = false;
        for(let c = 0; c < toys.length; c++){
            if(toys[c].checked){
                toyChecked = true;
            }
        }
        return toyChecked;
    }

    function checkForm(_evt){ // Checks that the user has entered everything they were suppose to
        alert("Checking form");
        let failed = false;

        if(!isToyChecked()){
            failed = true;
            alert("You must select at least one toy!");
        }
        if(!form.termsChkbx.checked){
            failed = true;
            alert("You have not accepted the terms and conditions!");
        }
        if(!form.forename.value){
            failed = true;
            alert("Forename must be entered!");
        }
        if(!form.surname.value){
            failed = true;
            alert("Surname must be entered!");
        }
        if(failed) _evt.preventDefault(); // Prevents form being submitted
    }

    form.addEventListener("change", calcTotal); // Add the on change event listener to the form for calculating the total price
    form.termsChkbx.addEventListener("change", toggleTerms); // add the on change event listener to the form for toggling the terms and condition styling
    calcTotal(); // This is ran here so the automatically selected delivery price is added on page load
    form.submit.addEventListener("click", checkForm);



});