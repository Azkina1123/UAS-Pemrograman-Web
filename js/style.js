function navActive() {
  var element =  document.getElementsByClassName("menu")[0];

  element.classList.toggle("nav-active");

}

function comingSoon() {
  alert("Coming soon! \nPage not available yet.");
}

function updateAmount(input, price) {
  var paid = document.querySelector("input:checked + input[name='paid']");
  paid.value = input.value * price;

  updatePaid();
}

function updatePaid() {
  var totalPrice = document.getElementsByClassName("total-price")[0];
  var paids = document.querySelectorAll("input:checked + input[name='paid']");
  var total = 0;

  paids.forEach(element => {
    total += parseInt(element.value);
  });

  totalPrice.innerHTML = total;
}