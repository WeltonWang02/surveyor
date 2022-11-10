/*
 * Listen to radio clicks and update parent element in order to reflect visual changes
 */ 
let form = document.querySelector( "form" );

form.addEventListener( "change", ( evt ) => {
  let trg = evt.target,
      trg_par = trg.parentElement;
  
  if ( trg.type === "radio" && trg_par &&
       trg_par.tagName.toLowerCase() === "label" ) {
    
    let prior = form.querySelector( 'label.checked input[name="' +
                                    trg.name + '"]' );
    
    if ( prior ) {
      prior.parentElement.classList.remove( "checked" );
    }
    
    trg_par.classList.add( "checked" );
    
  }
}, false );

/*
 * Submit the form once the user has selected the last radio button and clicked next
 */ 
var nbs = document.getElementsByClassName("question__next");
nbs[0].addEventListener("click", function() {
    document.getElementById("form").submit();
});

/*
 * Updates group number
 */ 
const urlParams = new URLSearchParams(window.location.search);
const group = urlParams.get('grp');
document.getElementById("group").value = group;
