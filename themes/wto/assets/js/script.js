(function () {
  const selectors = {
    slider: ".b-slider.w-slider", // общий контейнер слайдов и кнопок
    originalPrev: ".w-slider-arrow-left", // кнопка Назад внутри контейнера
    originalNext: ".w-slider-arrow-right", // кнопка Вперед внутри контейнера

    prev: ".arrow-left",
    next: ".arrow-right",
  };

  function fakeClick($btn) {
    if ($btn) $btn.click();
  }

  function init() {
    const $slider = document.querySelector(selectors.slider);
    if (!$slider) return;
    const $originalPrev = $slider.querySelector(selectors.originalPrev);
    const $originalNext = $slider.querySelector(selectors.originalNext);

    document.body.addEventListener("click", function (e) {
      if (e.target.closest(selectors.prev)) {
        fakeClick($originalPrev);
      } else if (e.target.closest(selectors.next)) {
        fakeClick($originalNext);
      }
    });
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();