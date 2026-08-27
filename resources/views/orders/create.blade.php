@extends('layouts.app')

@section('title', 'Nowe zamówienie')

@section('content')

<div class="page-header">
    <div>
        <h2>Nowe zamówienie</h2>
        <p>Dodaj pozycje do zamówienia.</p>
    </div>

    <a href="{{ route('orders.index') }}" class="btn btn-secondary">
        Wróć
    </a>
</div>

<form action="{{ route('orders.store') }}" method="POST" id="order-form">
    @csrf

    <div class="order-builder">

        <section class="menu-section">

            <div class="menu-toolbar">
                <input
                    type="text"
                    id="dish-search"
                    placeholder="Szukaj dania..."
                >

                <select id="category-filter">
                    <option value="all">Wszystkie kategorie</option>

                    @foreach($dishes->pluck('category')->unique('id') as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="dish-grid" id="dish-grid">

                @foreach($dishes as $dish)

                    <div
                        class="dish-card"
                        data-id="{{ $dish->id }}"
                        data-name="{{ strtolower($dish->name) }}"
                        data-category="{{ $dish->category_id }}"
                        data-price="{{ $dish->net_price }}"
                        data-vat="{{ $dish->vat_rate }}"
                    >
                        <h3>{{ $dish->name }}</h3>

                        <p>{{ $dish->category->name }}</p>

                        <strong>
                            {{ number_format(
                                $dish->net_price * (1 + $dish->vat_rate / 100),
                                2
                            ) }} zł
                        </strong>

                        <button
                            type="button"
                            class="btn btn-primary add-dish-btn"
                        >
                            Dodaj
                        </button>
                    </div>

                @endforeach

            </div>

        </section>


        <aside class="cart-section">

            <h3>Aktualne zamówienie</h3>

            <div id="cart-items">
                <p class="cart-empty">
                    Brak pozycji w zamówieniu.
                </p>
            </div>

            <div class="cart-summary">
                <span>Razem:</span>

                <strong id="cart-total">
                    0.00 zł
                </strong>
            </div>

            <button
                type="submit"
                class="btn btn-primary"
                id="submit-order"
            >
                Utwórz zamówienie
            </button>

        </aside>

    </div>

</form>

<script>
    const cart = new Map();

    const cartItems = document.getElementById('cart-items');
    const cartTotal = document.getElementById('cart-total');

    const searchInput = document.getElementById('dish-search');
    const categoryFilter = document.getElementById('category-filter');

    document.querySelectorAll('.add-dish-btn').forEach(button => {

        button.addEventListener('click', function () {

            const card = this.closest('.dish-card');

            const id = card.dataset.id;
            const name = card.querySelector('h3').textContent.trim();

            const netPrice = parseFloat(card.dataset.price);
            const vatRate = parseFloat(card.dataset.vat);

            const grossPrice = netPrice * (1 + vatRate / 100);

            if (cart.has(id)) {
                cart.get(id).quantity++;
            } else {
                cart.set(id, {
                    id: id,
                    name: name,
                    quantity: 1,
                    grossPrice: grossPrice,
                    notes: ''
                });
            }

            renderCart();
        });

    });


    function renderCart() {

        cartItems.innerHTML = '';

        if (cart.size === 0) {
            cartItems.innerHTML = `
                <p class="cart-empty">
                    Brak pozycji w zamówieniu.
                </p>
            `;

            cartTotal.textContent = '0.00 zł';

            return;
        }

        let total = 0;
        let index = 0;

        cart.forEach(item => {

            total += item.grossPrice * item.quantity;

            const wrapper = document.createElement('div');

            wrapper.classList.add('cart-item');

            wrapper.innerHTML = `
                <div class="cart-item-header">

                    <strong>${item.name}</strong>

                    <button
                        type="button"
                        class="cart-remove"
                        data-id="${item.id}"
                    >
                        Usuń
                    </button>

                </div>

                <div class="cart-quantity">

                    <button
                        type="button"
                        class="quantity-minus"
                        data-id="${item.id}"
                    >
                        -
                    </button>

                    <span>${item.quantity}</span>

                    <button
                        type="button"
                        class="quantity-plus"
                        data-id="${item.id}"
                    >
                        +
                    </button>

                </div>

                <input
                    type="text"
                    placeholder="Uwagi..."
                    value="${item.notes}"
                    class="cart-note"
                    data-id="${item.id}"
                >

                <input
                    type="hidden"
                    name="items[${index}][dish_id]"
                    value="${item.id}"
                >

                <input
                    type="hidden"
                    name="items[${index}][quantity]"
                    value="${item.quantity}"
                >

                <input
                    type="hidden"
                    name="items[${index}][notes]"
                    value="${item.notes}"
                    class="hidden-note"
                    data-id="${item.id}"
                >
            `;

            cartItems.appendChild(wrapper);

            index++;
        });

        cartTotal.textContent = total.toFixed(2) + ' zł';

        attachCartEvents();
    }


    function attachCartEvents() {

        document.querySelectorAll('.quantity-plus').forEach(button => {

            button.addEventListener('click', function () {

                const id = this.dataset.id;

                cart.get(id).quantity++;

                renderCart();
            });

        });


        document.querySelectorAll('.quantity-minus').forEach(button => {

            button.addEventListener('click', function () {

                const id = this.dataset.id;

                const item = cart.get(id);

                item.quantity--;

                if (item.quantity <= 0) {
                    cart.delete(id);
                }

                renderCart();
            });

        });


        document.querySelectorAll('.cart-remove').forEach(button => {

            button.addEventListener('click', function () {

                cart.delete(this.dataset.id);

                renderCart();
            });

        });


        document.querySelectorAll('.cart-note').forEach(input => {

            input.addEventListener('input', function () {

                const id = this.dataset.id;

                cart.get(id).notes = this.value;

                const hiddenInput = document.querySelector(
                    `.hidden-note[data-id="${id}"]`
                );

                hiddenInput.value = this.value;
            });

        });

    }


    function filterDishes() {

        const search = searchInput.value.toLowerCase();

        const selectedCategory = categoryFilter.value;

        document.querySelectorAll('.dish-card').forEach(card => {

            const name = card.dataset.name;
            const category = card.dataset.category;

            const matchesSearch = name.includes(search);

            const matchesCategory =
                selectedCategory === 'all'
                || category === selectedCategory;

            card.style.display =
                matchesSearch && matchesCategory
                ? 'block'
                : 'none';
        });
    }


    searchInput.addEventListener('input', filterDishes);

    categoryFilter.addEventListener('change', filterDishes);


    document
        .getElementById('order-form')
        .addEventListener('submit', function (event) {

            if (cart.size === 0) {

                event.preventDefault();

                alert('Dodaj przynajmniej jedną pozycję do zamówienia.');
            }

        });
</script>

@endsection
