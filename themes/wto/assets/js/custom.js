(function() {
    const selectors = {
        section: '.s-section_pd_home',
        home: '.b-home-wrap',
        list: '[data-list]',
    }

    const $section = document.querySelector(selectors.section);
    const $modal = document.querySelector(selectors.modal);
    const $home = $section.querySelector(selectors.home);
    const $list = $section.querySelector(selectors.list);
    const $items = Array.from($list.children);

    $home.addEventListener('click', function(event) {
        const $btn = event.target.closest('[data-item]')
        if ($btn) {
            const itemIndex = Number($btn.dataset.item);
            $items.forEach(function ($item, index) {
                $item.classList.toggle('active', index === itemIndex - 1);
            })
        }
    })
})()

$(document).ready(function() {
    $('.slide').last().append($('.card').clone()); Webflow.destroy(); Webflow.ready(); Webflow.require('ix2').init();
})