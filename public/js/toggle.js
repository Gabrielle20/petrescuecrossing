var $toggleElement = document.querySelectorAll('.toggle-element');
console.log($toggleElement);

$toggleElement.forEach(function ($elt) {
    $elt.addEventListener('click', () => {
        var par = $elt.parentElement;
        // row
        var grandpar = par.parentElement;
        // table
        var ggr = grandpar.parentElement;
        var $toShow = ggr.querySelector('.to-show');
        $toShow.classList.toggle("is-visible");

        console.log($toShow.classList);
    });
});