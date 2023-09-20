wto_forms_init();

document.querySelectorAll('select[v-model]').forEach(function (select) {

  const for_value = select.getAttribute('v-for');

  const option = select.lastChild;
  const option_value = option.getAttribute('value');
  const option_text = option.innerHTML;

  option.setAttribute('v-for', for_value);
  option.setAttribute('v-bind:value', option_value);
  option.innerHTML = '{{ ' + option_text + ' }}';

  select.removeAttribute('v-for');
  option.removeAttribute('value');

  select.querySelectorAll('option:not(:nth-child(1),:nth-child(2))').forEach(el => el.remove())

});

var api_url = location.origin;

const cart = Vue.createApp({

  setup() {

    const items_data = JSON.parse(localStorage.getItem('cart_data')) || [];

    const items = Vue.ref(items_data);

    const order = Vue.ref({}); //name: 'Антон', phone: '+79264631106', email: 'amristar@ya.ru', politic: true

    const orderComplete = Vue.ref(false);

    function removeCart(index) {
      items.value.splice(index, 1);
      saveCart();
    }

    function clearCart(e) {

      e.preventDefault();
      items.value.splice(0);

      saveCart();

    }

    function changeQty(qty, id) {

      items.value.forEach((item) => {
        if (item.id === id) {

          item.qty += qty;
          if (item.qty < 1) item.qty = 1;
          item.total = item.price * item.qty;

        }
      })

      saveCart();

    }

    function saveCart() {
      const storageData = JSON.stringify(items.value);
      localStorage.setItem('cart_data', storageData);
    }

    function addCart(product) {

      let in_cart = false;

      items.value.forEach((item) => {
        if (item.id === product.id) {

          item.qty += 1;
          item.total = item.price * item.qty;
          in_cart = true;

        }
      })

      if (!in_cart) {
        items.value.push(product);
      }

      saveCart();

    }

    function cartCount() {
      return items.value.length;
    }

    function cartTotal() {

      order.content = '';

      let cartTotal = 0;
      items.value.forEach((item) => {
        item.total = item.price * item.qty;
        cartTotal += item.price * item.qty;
        order.content += item.title + ' - ' + item.price + ' руб. | ' + item.qty + ' шт. | ' + item.total + " руб.  \n";
        // order.items.push({ title: item.title, price: item.price, qty: item.qty, total: item.total });
      })

      document.querySelector('.cart-count').innerHTML = cartCount();

      return cartTotal;

    }

    function orderSend(e) {

      e.preventDefault();

      oc.ajax('onCreateOrder', {
        data: {
          name: order.value.name,
          email: order.value.email,
          phone: order.value.phone,
          politic: order.value.politic ? 'Да' : 'Нет',
          content: order.content,
          total: cartTotal() + ' руб',
        },
        success: (result) => {

          console.log(result);

          orderComplete.value = true;

          items.value.splice(0);
          saveCart();
        }
      });

    }

    function initCart() {
      setTimeout(() => {
        items.value.splice(0);
        cart.orderComplete = false;
        saveCart();
      }, 1000)

    }

    return { items, removeCart, clearCart, addCart, changeQty, cartCount, cartTotal, orderComplete, orderSend, order, initCart }
  }
}).mount('#cart_form');

const cartClose = document.querySelector('[data-bind="cart_close"]');

const addCartEl = document.querySelectorAll('[data-bind=addCart]');

addCartEl.forEach(el => {
  el.addEventListener('click', function (e) {

    e.preventDefault();

    const parent = el.closest('[data-prod-id]');

    const product = {
      id: parent.getAttribute('data-prod-id'),
      title: parent.querySelector('[data-bind="product.title"]').innerText,
      desc: parent.querySelector('[data-bind="product.short_desc"]').innerText,
      price: parseInt(parent.querySelector('[data-bind="product.price"]').innerText),
      image: parent.querySelector('[data-bind="product.image"]').getAttribute('src'),
      qty: 1,
    }

    cart.addCart(product);
  })
})

const cartCloseEl = document.querySelectorAll('[data-modal="cart"] [data-modal="close"]');

if (cartCloseEl.length) {
  cartCloseEl.forEach(el => {
    el.addEventListener('click', function (e) {
      if (cart.orderComplete) {
        cart.initCart();
      }
    })
  })
}

function wto_forms_init() {
  const forms = document.querySelectorAll('form');
  if (forms.length) {
    forms.forEach(el => {
      if (el.getAttribute('action') === null) {
        el.setAttribute('action', '/');
        el.querySelectorAll('[data-name]').forEach(el => {
          el.setAttribute('name', el.getAttribute('data-name'));
        })
        el.addEventListener('submit', function (e) {
          e.preventDefault();
          wto_forms.forEach((form, index) => {
            let form_select = this.matches(form.selector);
            if (form_select) {
              wto_form_send(this, form.form_id);
            }
          });
        })
      }
    })
  }
}

function wto_form_send(el, form_id) {

  let form_config = wto_forms[form_id];

  oc.request(el, 'onFormSend',
    {
      data: { wto_form_id: form_id },
      files: true,
      success: function (result) {

        console.log(result);

        el.reset();

        let succes_el = el.parentElement.querySelector('.w-form-done');

        if (form_config.success_message !== '') {
          succes_el.querySelector('div').innerHTML = form_config.success_message;
        }

        if (form_config.redirect !== '') {
          if (form_config.new_window === '1') {
            window.open(form_config.redirect);
          } else {
            location.href = form_config.redirect;
          }
          return;
        }

        succes_el.style.display = 'block';

        if (form_config.hide_form === '1') {
          el.style.display = 'none';
        }

        if (form_config.hide_delay !== '' && form_config.hide_delay !== '0') {

          const delay = parseInt(form_config.hide_delay);

          setTimeout(() => {
            succes_el.style.display = 'none';
            el.style.display = 'block';

            if (form_config.hide_lbox === '1') {
              lbox_el = document.querySelector(form_config.lbox_selector);
              if (lbox_el !== undefined) {
                lbox_el.style.display = 'none';
              }
            }

          }, delay)

        }


      },
      error: function (result) {
        let error_el = el.parentElement.querySelector('.w-form-fail');
        error_el.style.display = 'block';

        if (form_config.error_message !== '') {
          error_el.querySelector('div').innerHTML = form_config.error_message;
        }
      }
    });
}