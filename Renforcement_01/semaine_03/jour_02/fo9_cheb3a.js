/**
 * CHALLENGE: Cinema Revenue Tracker
 * 
 * You are given a 'cinema' object that contains data about different halls.
 * Each hall has a list of 'showtimes'.
 * Each showtime is an object where the KEY is the Movie Name and the VALUE is an array 
 * containing [tickets_sold, ticket_price].
 * 
 * YOUR TASK:
 * Create a 'revenueSummary' object that calculates the TOTAL REVENUE for each movie.
 * Revenue = tickets_sold * ticket_price
 */

const cinema = {
    hall_A: {
        morning: { "Inception": [50, 10], "Interstellar": [30, 12] },
        evening: { "Inception": [100, 15], "The Dark Knight": [80, 15] }
    },
    hall_B: {
        morning: { "Interstellar": [20, 10], "The Dark Knight": [40, 12] },
        evening: { "Inception": [60, 15], "Interstellar": [90, 15] }
    }
};

let revenueSummary = {};


// --- YOUR CODE STARTS HERE ---

for (const hall in cinema) {
    const hallData = cinema[hall];

    for (const showtime in hallData) {
        const movies = hallData[showtime];

        for (const movie in movies) {
            const [tickets, price] = movies[movie];
            const revenue = tickets * price;

            if (!revenueSummary[movie]) {
                revenueSummary[movie] = 0;
            }

            revenueSummary[movie] += revenue;
        }
    }
}




// --- YOUR CODE ENDS HERE ---

console.log(revenueSummary);

/* 
EXPECTED OUTPUT:
{
  "Inception": 2900,
  "Interstellar": 1910,
  "The Dark Knight": 1680
}
*/

// if (true) {
//     var y = 5
// }
// console.log(typeof y)

// let x = 10
// function test() {
//     console.log(x)
// }

const array = [1, 2, 3, 4, 6]
const array2 = [1, 2, 3, 4, 6]

// const newArray =Math.max(...array)
// console.log([...array,array2])
// x < 5
// true
// false

let personne = {
    "nom": "youssef",
    "age": 20
}

console.log(Object.keys(personne))
