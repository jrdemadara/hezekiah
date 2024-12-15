// stores/bagStore.js
import { defineStore } from 'pinia';

export const useBagStore = defineStore('bag', {
    state: () => ({
        items: [], // Array to store shopping bag items
    }),

    getters: {
        // Total count of items in the bag
        bagCount(state) {
            return state.items.reduce(
                (total, item) => total + item.quantity,
                0,
            );
        },

        getQuantityById: (state) => (id) => {
            const item = state.items.find((item) => item.id === id);
            return item ? item.quantity : 0; // Return 0 if the item is not in the bag
        },

        subtotal: (state) => {
            const total = state.items.reduce(
                (total, item) => total + item.quantity * item.price,
                0,
            );
            return new Intl.NumberFormat().format(total);
        },
    },

    actions: {
        // Add an item to the bag
        addToBag(product) {
            const existingItem = this.items.find(
                (item) => item.id === product.id,
            );
            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                this.items.push({
                    ...product,
                    quantity: product.quantity || 1,
                });
            }
        },

        incrementQuantity(productId) {
            const item = this.items.find((item) => item.id === productId);
            if (item) {
                item.quantity += 1;
            }
        },

        decrementQuantity(productId) {
            const item = this.items.find((item) => item.id === productId);
            if (item) {
                item.quantity = Math.max(1, item.quantity - 1); // Prevent quantity from going below 1
            }
        },

        // Remove an item from the bag
        removeFromBag(productId) {
            console.log(productId);
            this.items = this.items.filter((item) => item.id !== productId);
        },

        // Load items from localStorage (optional if using persistence plugin)
        loadBag() {
            const savedItems = JSON.parse(localStorage.getItem('shopping_bag'));
            if (savedItems) {
                this.items = savedItems;
            }
        },

        clearBag() {
            this.items = [];
        },
    },

    // Enable persistence
    persist: {
        key: 'shopping_bag', // Custom key for localStorage
        storage: localStorage, // Use localStorage to persist state
    },
});
