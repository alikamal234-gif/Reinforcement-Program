let arrs = [
    {
        name: 'ali',
        orders: [
            { product: 'phone', price: 100, quantity: 2 },
            { product: 'headphones', price: 80, quantity: 1 }
        ]
    },
    {
        name: 'sara',
        orders: [
            { product: 'laptop', price: 900, quantity: 1 },
            { product: 'mouse', price: 25, quantity: 2 },
            { product: 'phone', price: 100, quantity: 1 }
        ]
    },
    {
        name: 'youssef',
        orders: [
            { product: 'tablet', price: 300, quantity: 1 },
            { product: 'headphones', price: 80, quantity: 2 },
            { product: 'mouse', price: 25, quantity: 1 }
        ]
    },
    {
        name: 'amina',
        orders: [
            { product: 'camera', price: 500, quantity: 1 },
            { product: 'phone', price: 100, quantity: 1 },
            { product: 'keyboard', price: 45, quantity: 2 }
        ]
    },
    {
        name: 'omar',
        orders: [
            { product: 'keyboard', price: 45, quantity: 1 },
            { product: 'monitor', price: 200, quantity: 1 },
            { product: 'headphones', price: 80, quantity: 1 }
        ]
    },
    {
        name: 'fatima',
        orders: [
            { product: 'laptop', price: 900, quantity: 1 },
            { product: 'mouse', price: 25, quantity: 3 },
            { product: 'keyboard', price: 45, quantity: 1 }
        ]
    },
    {
        name: 'hassan',
        orders: [
            { product: 'tablet', price: 300, quantity: 2 },
            { product: 'phone', price: 100, quantity: 1 },
            { product: 'monitor', price: 200, quantity: 2 }
        ]
    }
]

function x(arrs) {
    let resultats = {}
    arrs.forEach(arr => {
        let orders = arr.orders
        orders.forEach(order => {
            if (!resultats[order.product]) {
                resultats[order.product] = {
                    price: 0,
                    quantity: 0
                }
            }
            resultats[order.product].price += order.price
            resultats[order.product].quantity += order.quantity
        })
    })
    return resultats
}
console.log(x(arrs))