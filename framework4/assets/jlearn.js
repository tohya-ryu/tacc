/* initiate form handling */
document.querySelectorAll(".framework-form-submit").forEach((btn) => {
  btn.addEventListener("click", framework.form.submit);
});

var jlearn = {};

jlearn.lookup_vocab = function(event)
{
  let str = event.target.value;
  if (str.length >= 1) {
    const promise = fetch(framework.util.base_uri(
      `fetch/vocab/${encodeURI(encodeURI(encodeURIComponent(str)))}`),
      { credentials: "include", method: "get" });
    promise
      .then((response) => {
        if (!response.ok)
          throw new Error(`HTTP error: ${response.status}`);
        return response.text();
      }).then((data) => {
        document.getElementById("keyword_found_container").innerHTML = data;
      }).catch((error) => {
        console.log(error);
      });
  }
}

jlearn.lookup_kanji = function(event)
{
  let str = event.target.value;
  if (str.length >= 1) {
    const promise = fetch(framework.util.base_uri(
      `fetch/kanji/${encodeURI(encodeURI(encodeURIComponent(str)))}`),
      { credentials: "include", method: "get" });
    promise
      .then((response) => {
        if (!response.ok)
          throw new Error(`HTTP error: ${response.status}`);
        return response.text();
      }).then((data) => {
        document.getElementById("keyword_found_container").innerHTML = data;
      }).catch((error) => {
        console.log(error);
      });
  }
}

let kanji_input = document.querySelector("#kanji")
if (kanji_input) {
  switch (kanji_input.dataset.type) {
  case "vocab":
    kanji_input.addEventListener("keyup", jlearn.lookup_vocab);
    break;
  case "kanji":
    kanji_input.addEventListener("keyup", jlearn.lookup_kanji);
    break;
  }
}

