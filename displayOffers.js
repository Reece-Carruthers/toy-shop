window.addEventListener("load", function () { // On page load do this
    "use strict";

    const offerJSON = "http://unn-w19011575.newnumyspace.co.uk/webprogramming/getOffers.php"; // Set the link to the script that retrives the JSON file

    const fetchData = function () {
        fetch(offerJSON) // Run the script and fetch the JSON
            .then(
                function (response) {
                    if (response.status === 200) { // If transfered successfully
                        return response.json(); // return the jason object
                    } else {
                        throw new Error("Invalid Response!") // else throw an error
                    }
                }
            )
            .then(
                function (json) {

                    // Select all children elements of the aside element offers
                    const offerCardElements = document.querySelectorAll("aside#offers > p, aside#offers > H4");

                    offerCardElements[0].innerHTML = json.toyName; // Set the header to the toyName
                    offerCardElements[1].innerHTML = json.catDesc; // Set the paragraph tag to the catDesc
                    offerCardElements[2].innerHTML = json.toyPrice; // Set the other paragraph tag to the price
                }
            )
            .catch(
                function (err) {
                    console.log("Something went wrong", err);
                }
            );
    }
    fetchData(); // Run AJAX code to display the initial data
    setInterval(fetchData, 5000); // Run the AJAX code to display an offer every five seconds
});
