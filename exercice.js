const orders = [
    {
        id: 101, customer: "Abderrahman", status: "delivered", items: [
            { name: "Keyboard", price: 30 },
            { name: "Mouse", price: 20 }
        ]
    },
    {
        id: 102, customer: "Sehrane", status: "pending", items: [
            { name: "Monitor", price: 200 }
        ]
    },
    {
        id: 103, customer: "Ali", status: "delivered", items: [
            { name: "USB-C Cable", price: 15 },
            { name: "Mouse", price: 20 },
            { name: "Webcam", price: 45 }
        ]
    }
];


function x(orders) {
    let resultat = {}
    orders.forEach(order => {
        let items = order.items
        let status = order.status
        if (status == "delivered") {
        items.forEach(item => {
            let name = item.name
            if (!resultat['uniqueProduct']) {
                resultat['uniqueProduct'] = []
            }
            if (resultat['uniqueProduct'] && !resultat['uniqueProduct'].includes(name)) {
                resultat['uniqueProduct'].push(name)
            }
                if (!resultat['price']) {
                    resultat['price'] = 0
                }
                    resultat['price'] += item.price
        })
        }
    });
    return resultat
}
console.log(x(orders))