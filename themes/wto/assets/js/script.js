(function () {
    const selectors = {
        sliderContainer: ".b-slider", // Common class for all slider containers
        prev: ".arrow-left",
        next: ".arrow-right",
    };

    function fakeClick($btn) {
        if ($btn) $btn.click();
    }

    function setupSlider($sliderContainer) {
        $sliderContainer.addEventListener("click", function (e) {
            const $slider = e.target.closest(selectors.sliderContainer);
            if (!$slider) return;

            const $originalPrev = $slider.querySelector(selectors.prev);
            const $originalNext = $slider.querySelector(selectors.next);

            if (e.target.closest(selectors.prev)) {
                fakeClick($originalPrev);
            } else if (e.target.closest(selectors.next)) {
                fakeClick($originalNext);
            }
        });
    }

    function init() {
        const $sliderContainers = document.querySelectorAll(selectors.sliderContainer);
        $sliderContainers.forEach((slider) => setupSlider(slider));
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", init);
    } else {
        init();
    }
})();
